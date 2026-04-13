<?php

namespace App\Services;

use Illuminate\Database\Capsule\Manager as Capsule;

class GradeService
{
    //student functions
    public function getGradesByStudent($studentId)
    {
        return $row = Capsule::table('grades as g')
            ->join('subjects as s', 'g.subject_id', '=', 's.subject_id')
            ->join('semester as se', 'g.semester_id', '=', 'se.semester_id')
            ->where('g.student_id', $studentId)
            ->select(
                'g.grade_id',
                'g.student_id',
                'g.subject_id',
                'g.semester_id',
                's.credits',
                'g.attempt_number',
                'g.attendance_score',
                'g.assignment_score',
                'g.midterm_score',
                'g.final_exam_score',
                'g.score_10_scale',
                'g.letter_grade',
                's.subject_name',
                'se.semester_name',
                'se.academic_year'
            )
            ->orderBy('g.semester_id')
            ->orderBy('g.student_id')
            ->orderBy('g.subject_id')
            ->get();
    }

    public function getSubjectDetail($studentId, $subjectId)
    {
        return Capsule::table('grades as g')
            ->join('subjects as s', 'g.subject_id', '=', 's.subject_id')
            ->leftJoin('exams as e', function ($join) {
                $join->on('g.subject_id', '=', 'e.subject_id')
                     ->on('g.semester_id', '=', 'e.semester_id');
            })
            ->where('g.student_id', $studentId)
            ->where('g.subject_id', $subjectId)
            ->select(
                'g.student_id',
                'g.subject_id',
                's.subject_name',
                's.teacher_name',
                'e.date_exam',
                'e.venue_exam',
                'g.final_exam_score'
            )
            ->first();
    }
}