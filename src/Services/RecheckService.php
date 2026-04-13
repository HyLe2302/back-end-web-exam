<?php

namespace App\Services;

use App\Models\RecheckRequest;
use Illuminate\Database\Capsule\Manager as Capsule;

class RecheckService
{

    //student functions
    public function store(array $data): array
    {
        if (empty($data['student_id'])) {
            errorResponse(400, 'student_id là bắt buộc');
        }

        if (empty($data['semester_id'])) {
            errorResponse(400, 'semester_id là bắt buộc');
        }

        if (empty($data['details']) || !is_array($data['details'])) {
            errorResponse(400, 'details phải là mảng và có ít nhất 1 phần tử');
        }

        return Capsule::connection()->transaction(function () use ($data) {
            $studentId = $data['student_id'];
            $semesterId = $data['semester_id'];
            $details = $data['details'];

            $requestId = Capsule::table('recheck_request')->insertGetId([
                'student_id'   => $studentId,
                'semester_id'  => $semesterId,
                'date_request' => date('Y-m-d'),
                'status'      => '1',
                'is_read'     => 0 ,
                'is_new'      => 0
            ]);

            foreach ($details as $detail) {
                if (empty($detail['subject_id'])) {
                    throw new \Exception('subject_id trong details là bắt buộc');
                }

                if (empty($detail['reason'])) {
                    throw new \Exception('reason trong details là bắt buộc');
                }

                $subjectId = $detail['subject_id'];

                $grade = Capsule::table('grades')
                    ->where('student_id', $studentId)
                    ->where('semester_id', $semesterId)
                    ->where('subject_id', $subjectId)
                    ->first();

                if (!$grade) {
                    throw new \Exception("Không tìm thấy điểm cho subject_id = {$subjectId}");
                }

                Capsule::table('recheck_detail')->insert([
                    'request_id'  => $requestId,
                    'subject_id'  => $subjectId,
                    'final_score' => $grade->final_exam_score,
                    'reason'      => $detail['reason'],
                    'status'      => 'pending'
                ]);
            }

            return [
                'request_id' => $requestId,
                'student_id' => $studentId,
                'semester_id' => $semesterId,
                'details_count' => count($details)
            ];
        });
    }

    public function getRecheckFormData(int $requestId): array
    {
        $request = Capsule::table('recheck_request as rr')
            ->join('students as st', 'rr.student_id', '=', 'st.student_id')
            ->join('semester as se', 'rr.semester_id', '=', 'se.semester_id')
            ->where('rr.request_id', $requestId)
            ->select(
                'rr.request_id',
                'rr.date_request',
                'rr.student_id',
                'rr.semester_id',
                'st.name',
                'st.class',
                'st.level',
                'st.day_of_birth',
                'st.phone',
                'st.email',
                'se.semester_name',
                'se.academic_year'
            )
            ->first();

        if (!$request) {
            throw new \Exception('Không tìm thấy đơn phúc khảo');
        }

        $details = Capsule::table('recheck_detail as rd')
            ->join('subjects as s', 'rd.subject_id', '=', 's.subject_id')
            ->leftJoin('exams as e', function ($join) use ($request) {
                $join->on('rd.subject_id', '=', 'e.subject_id')
                    ->where('e.semester_id', '=', $request->semester_id);
            })
            ->where('rd.request_id', $requestId)
            ->select(
                'rd.subject_id',
                'rd.final_score',
                'rd.reason',
                's.subject_name',
                's.teacher_name',
                'e.date_exam',
                'e.venue_exam'
            )
            ->get();

        return [
            'student' => [
                'student_id' => $request->student_id,
                'name' => $request->name,
                'class' => $request->class,
                'level' => $request->level,
                'day_of_birth' => $request->day_of_birth,
                'phone' => $request->phone,
                'email' => $request->email,
            ],
            'semester' => [
                'semester_id' => $request->semester_id,
                'semester_name' => $request->semester_name,
                'academic_year' => $request->academic_year,
            ],
            'request' => [
                'request_id' => $request->request_id,
                'date_request' => $request->date_request,
            ],
            'details' => collect($details)->map(function (object $item) {
                return [
                    'subject_name' => $item->subject_name,
                    'teacher_name' => $item->teacher_name,
                    'date_exam' => $item->date_exam,
                    'venue_exam' => $item->venue_exam,
                    'final_score' => $this->formatScore($item->final_score),
                    'reason' => $item->reason,
                ];
            })->toArray()
        ];
    }

    private function formatScore($score)
    {
        if ($score === null) {
            return '';
        }

        $score = (float)$score;
        return ($score == (int)$score) ? (int)$score : round($score, 1);
    }

    public function getListByStudent($studentId)
    {
        return Capsule::table('recheck_request as rr')
            ->join('recheck_detail as rd', 'rr.request_id', '=', 'rd.request_id')
            ->join('subjects as s', 'rd.subject_id', '=', 's.subject_id')
            ->where('rr.student_id', $studentId)
            // ->where('rd.status', 'Pending')
            ->select(
                's.subject_id',
                's.subject_name',
                'rd.final_score as final_exam_score',
                'rr.date_request',
                'rd.reason',
                'rd.status',
                'rd.rechecked_score',
                'rd.score_updated_at'
            )
            ->orderBy('rr.date_request', 'desc')
            ->orderBy('rd.detail_id', 'desc')
            ->get();
    }







    //admin functions

    public function getUnreadRequestCount()
    {
        return Capsule::table('recheck_request')
            ->where('is_read', 0)
            ->count();
    }

    public function markAllRequestAsRead()
    {
        return Capsule::table('recheck_request')
            ->where('is_read', 0)
            ->update([
                'is_read' => 1
            ]);
    }

    public function markNewRead($requestId)
    {
        Capsule::table('recheck_request')
            ->where('request_id', $requestId)
            ->update([
                'is_new' => 1
            ]);

        successResponse([
            'request_id' => $requestId,
            'is_new' => 1
        ]);
    }

    public function getStatistics()
    {
        $total = Capsule::table('recheck_detail')->count();

        $pending = Capsule::table('recheck_detail')
            ->where('status', 'pending')
            ->count();

        $approved = Capsule::table('recheck_detail')
            ->where('status', 'approved')
            ->count();

        $rejected = Capsule::table('recheck_detail')
            ->where('status', 'rejected')
            ->count();

        $completed = Capsule::table('recheck_detail')
            ->where('status', 'completed')
            ->count();

        return [
            'total_requests' => $total,
            'pending_requests' => $pending,
            'approved_requests' => $approved,
            'rejected_requests' => $rejected,
            'completed_requests' => $completed
        ];
    }

    public function getStudentRecheckList() { 
        return Capsule::table('recheck_request as rr') 
        ->join('students as s', 'rr.student_id', '=', 's.student_id') 
        ->join('cohorts as c', 's.cohort_id', '=', 'c.id') 
        ->join('recheck_detail as rd', 'rr.request_id', '=', 'rd.request_id') 
        ->select( 
            's.student_id', 
            's.name', 's.class', 
            'c.cohort', 's.day_of_birth', 
            'rr.date_request', 
            Capsule::raw('COUNT(rd.detail_id) as total_subject_recheck'),
            'rr.status', 
            'rr.request_id',
            'rr.is_new'
            ) 
            ->groupBy( 
                's.student_id', 
                's.name', 's.class', 
                'c.cohort', 's.day_of_birth', 
                'rr.date_request', 
                'rr.status',
                'rr.request_id' 
                ) 
        ->orderBy('rr.date_request', 'desc') 
        ->orderBy('s.student_id') 
        ->get() 
        ->map(function ($item) { 
            return [ 
                'student_id' => $item->student_id, 
                'name' => $item->name, 'class' => $item->class, 
                'cohort' => $item->cohort, 'day_of_birth' => $item->day_of_birth, 
                'total_subject_recheck' => (int) $item->total_subject_recheck, 
                'status' => $item->status,
                'is_new' => $item->is_new,
                'date_request' => $item->date_request, 'request_id' => (int) $item->request_id, 
                'view_url' => "/admin/recheck-requests/students/{$item->student_id}/requests/{$item->request_id}/subjects", 
                'download_url' => "/recheck-requests/{$item->request_id}/download" 
            ]; 
        }); 
    }

    public function getSubjectsByStudent($studentId, $requestId)
    {
        $rows = Capsule::table('recheck_request as rr')
            ->join('recheck_detail as rd', 'rr.request_id', '=', 'rd.request_id')
            ->join('subjects as s', 'rd.subject_id', '=', 's.subject_id')
            ->leftJoin('grades as g', function ($join) {
                $join->on('g.subject_id', '=', 'rd.subject_id')
                    ->on('g.student_id', '=', 'rr.student_id');
            })
            ->leftJoin('exams as e', function ($join) {
                $join->on('e.subject_id', '=', 'rd.subject_id')
                    ->on('e.semester_id', '=', 'rr.semester_id');
            })
            ->where('rr.student_id', $studentId)
            ->where('rr.request_id', $requestId)
            ->select(
                'rr.request_id',
                'rr.student_id',
                'rd.detail_id',
                'rd.subject_id',
                's.subject_name',
                's.teacher_name',
                'e.date_exam',
                'e.venue_exam',
                'rd.final_score',
                'rd.reason',
                'rd.status'
            )
            ->orderBy('rr.request_id', 'desc')
            ->orderBy('rd.detail_id', 'asc')
            ->get();

        return $rows->map(function (object $item) {
            $score = $item->final_score;

            if ($score !== null) {
                if ((float)$score == (int)$score) {
                    $item->final_score = (int)$score;
                } else {
                    $item->final_score = round($score, 1);
                }
            }

            return [
                'request_id' => $item->request_id,
                'student_id' => $item->student_id,
                'detail_id' => $item->detail_id,
                'subject_id' => $item->subject_id,
                'subject_name' => $item->subject_name,
                'teacher_name' => $item->teacher_name,
                'date_exam' => $item->date_exam,
                'venue_exam' => $item->venue_exam,
                'final_score' => $item->final_score,
                'reason' => $item->reason,
                'status' => $item->status
            ];
        })->toArray();
    }

    public function updateDetailStatus($detailId, $status)
    {
        $allowedStatus = ['completed', 'approved', 'rejected'];

        if (!in_array($status, $allowedStatus)) {
            throw new \Exception('Status không hợp lệ. Chỉ chấp nhận completed, approved, rejected');
        }

        $detail = Capsule::table('recheck_detail')
            ->where('detail_id', $detailId)
            ->first();

        if (!$detail) {
            throw new \Exception('Không tìm thấy detail_id = ' . $detailId);
        }

        Capsule::table('recheck_detail')
            ->where('detail_id', $detailId)
            ->update([
                'status' => $status
            ]);

        return [
            'detail_id' => (int)$detailId,
            'status' => $status,
            'message' => 'Cập nhật trạng thái thành công'
        ];
    }

    public function updateDetailScore($detailId, $recheckedScore)
    {
        $detail = Capsule::table('recheck_detail as rd')
        ->join('recheck_request as rr', 'rd.request_id', '=', 'rr.request_id')
        ->select(
            'rd.detail_id',
            'rd.request_id',
            'rd.subject_id',
            'rr.student_id'
        )
        ->where('rd.detail_id', $detailId)
        ->first();

        if (!$detail) {
            return errorResponse(404, 'Không tìm thấy detail_id = ' . $detailId);
        }

        Capsule::connection()->transaction(function () use ($detail, $detailId, $recheckedScore) {

            // cập nhật bảng recheck_detail
            Capsule::table('recheck_detail')
                ->where('detail_id', $detailId)
                ->update([
                    'rechecked_score' => $recheckedScore,
                    'score_updated_at' => date('Y-m-d'),
                    'status' => 'completed'
                ]);

            // cập nhật điểm cuối kỳ trong bảng grades
            Capsule::table('grades')
                ->where('student_id', $detail->student_id)
                ->where('subject_id', $detail->subject_id)
                ->update([
                    'final_exam_score' => $recheckedScore
                ]);
        });

        return [
            'detail_id' => (int)$detailId,
            'rechecked_score' => $recheckedScore,
            'score_updated_at' => date('Y-m-d'),
            'status' => 'completed',
            'message' => 'Cập nhật điểm phúc khảo thành công'
        ];
    }

    public function getApprovedDetailStatus()
    {
        return Capsule::table('recheck_detail as rd')
            ->join('recheck_request as r', 'rd.request_id', '=', 'r.request_id')
            ->join('students as st', 'r.student_id', '=', 'st.student_id')
            ->join('subjects as sb', 'rd.subject_id', '=', 'sb.subject_id')
            ->join('grades as g', function ($join) {
                $join->on('g.student_id', '=', 'r.student_id')
                    ->on('g.subject_id', '=', 'rd.subject_id');
            })
            ->select(
                'rd.detail_id',
                'st.student_id',
                'st.name',
                'sb.subject_name',
                'rd.final_score',
                'rd.rechecked_score',
                'rd.status'
            )
            ->where('rd.status', 'approved')
            ->orderBy('st.student_id')
            ->orderBy('sb.subject_name')
            ->get();
    }

    public function getCompletedDetailStatus()
    {
        return Capsule::table('recheck_detail as rd')
            ->join('recheck_request as r', 'rd.request_id', '=', 'r.request_id')
            ->join('students as st', 'r.student_id', '=', 'st.student_id')
            ->join('subjects as sb', 'rd.subject_id', '=', 'sb.subject_id')
            ->join('grades as g', function ($join) {
                $join->on('g.student_id', '=', 'r.student_id')
                    ->on('g.subject_id', '=', 'rd.subject_id');
            })
            ->select(
                'st.student_id',
                'st.name',
                'sb.subject_name',
                'rd.final_score',
                'rd.rechecked_score',
                'rd.score_updated_at',
                'rd.status'
            )
            ->where('rd.status', 'completed')
            ->orderByDesc('rd.score_updated_at')
            ->get();
    }

    public function updateRequestStatus(int $requestId, array $data) { 
        
        $request = RecheckRequest::find($requestId); 

        if (!$request) { 
            throw new \Exception('Recheck request not found'); 
        } 

        if (!isset($data['status'])) { 
            throw new \Exception('Status is required'); 
        }  

        $request->status = (int)$data['status']; 
        $request->save(); 

        return $request; 
    }



    public function exportRecheckData($cohort = 'all', $status = 'approved')
    {
        $allowStatus = ['approved', 'pending', 'rejected', 'completed', 'all'];

        // Validate status
        if (!in_array(strtolower($status), $allowStatus)) {
            return [
                'success' => false,
                'message' => 'Trạng thái không hợp lệ'
            ];
        }

        // Validate cohort
        if ($cohort !== 'all') {
            $cohortExists = Capsule::table('cohorts')
                ->where('id', $cohort)
                ->exists();

            if (!$cohortExists) {
                return [
                    'success' => false,
                    'message' => 'Khóa không tồn tại'
                ];
            }
        }

        $query = Capsule::table('recheck_request as rr')
            ->join('recheck_detail as rd', 'rr.request_id', '=', 'rd.request_id')
            ->join('students as st', 'rr.student_id', '=', 'st.student_id')
            ->join('cohorts as c', 'st.cohort_id', '=', 'c.id')
            ->join('subjects as sub', 'rd.subject_id', '=', 'sub.subject_id')
            ->join('semester as sem', 'rr.semester_id', '=', 'sem.semester_id')
            ->leftJoin('grades as g', function ($join) {
                $join->on('g.student_id', '=', 'st.student_id')
                    ->on('g.subject_id', '=', 'rd.subject_id')
                    ->on('g.semester_id', '=', 'rr.semester_id');
            })
            ->leftJoin('exams as ex', function ($join) {
                $join->on('ex.subject_id', '=', 'rd.subject_id')
                    ->on('ex.semester_id', '=', 'rr.semester_id');
            })
            ->select(
                'st.student_id',
                'st.name',
                'st.email',
                'st.class',
                'st.phone',
                'sub.subject_name',
                'sub.teacher_name',
                'ex.venue_exam',
                'ex.date_exam',
                'g.final_exam_score',
                'rd.reason',
                'st.faculty',
                'c.cohort',
                'rd.status',
            );

        if (!empty($cohort) && $cohort !== 'all') {
            $query->where('c.id', $cohort);
        }

        if (!empty($status) && $status !== 'all') {
            $query->where('rd.status', $status);
        }

        $data = $query
            ->orderBy('rr.request_id', 'desc')
            ->orderBy('rd.detail_id', 'desc')
            ->get();

        if ($data->isEmpty()) {
            return [
                'success' => false,
                'message' => 'Không có dữ liệu phù hợp với bộ lọc đã chọn'
            ];
        }

        return [
            'success' => true,
            'data' => $data
        ];
    }
}