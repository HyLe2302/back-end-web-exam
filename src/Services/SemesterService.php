<?php

namespace App\Services;

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\Semester;

class SemesterService
{

    //student functions
    public function getOpenSemesterByStudent($studentId)
    {
        return Capsule::table('students as st')
            ->join('semester as se', 'st.cohort_id', '=', 'se.cohort_id')
            ->where('st.student_id', $studentId)
            ->where('se.status', 1)
            ->select(
                'se.semester_id',
                'se.semester_name',
                'se.academic_year',
                'se.start_date',
                'se.end_date',
                'se.notice',
                'se.status',
                'se.cohort_id'
            )
            ->orderBy('se.semester_id', 'desc')
            ->first();
    }


    //admin functions

    public function getAll()
    {
        return Semester::query() 
        ->leftJoin('cohorts as c', 'semester.cohort_id', '=', 'c.id') 
        ->select( 'semester.*', 'c.cohort as cohort' ) 
        ->orderBy('semester.semester_id', 'desc') 
        ->get();
    }

    public function create(array $data)
    {
        if (empty($data['semester_name'])){
            throw new \Exception("Semester name is required");   
        }
        if (empty($data['cohort_id'])){
            throw new \Exception("Cohort is required");   
        }
        if (empty($data['start_date'])){
            throw new \Exception("Start date is required");   
        }
        if (empty($data['end_date'])){
            throw new \Exception("End date is required");   
        }

        $cohortExists = Capsule::table('cohorts') 
        ->where('id', $data['cohort_id']) 
        ->exists(); 

        if (!$cohortExists) { 
            throw new \Exception("Cohort does not exist"); 
        }

        return Semester::create([
            'cohort_id' => $data['cohort_id'],
            'semester_name' => $data['semester_name'],
            'academic_year' => $data['academic_year'],
            'start_date'    => $data['start_date'] ?? null,
            'end_date'      => $data['end_date'] ?? null,
            'notice'        => $data['notice'] ?? null,
            'status'        => $data['status']
        ]);
    }

    public function update(int $id, array $data)
    {
        $semester = Semester::find($id);

        if (!$semester) {
            throw new \Exception("Semester not found");
        }

        if (isset($data['cohort_id']) && empty($data['cohort_id'])) {
            throw new \Exception("Cohort is required");
        }

        $semester->update([
            'cohort_id'      => $data['cohort_id'] ?? $semester->cohort_id,
            'semester_name'  => $data['semester_name'] ?? $semester->semester_name,
            'academic_year'  => $data['academic_year'] ?? $semester->academic_year,
            'start_date'     => $data['start_date'] ?? $semester->start_date,
            'end_date'       => $data['end_date'] ?? $semester->end_date,
            'notice'         => $data['notice'] ?? $semester->notice,
            'status'         => $data['status'] ?? $semester->status
        ]);

        return $semester;
    }

    public function delete(int $id)
    {
        $semester = Semester::find($id);

        if (!$semester) {
            throw new \Exception("Semester not found");
        }

        $semester->delete();

        return true;
    }

    public function getInfoById(int $id)
    {
        $semester = Semester::from('semester as s') 
        ->leftJoin('cohorts as c', 's.cohort_id', '=', 'c.id') 
        ->where('s.semester_id', $id) 
        ->select( 
            's.semester_id', 
            's.cohort_id', 
            'c.cohort', 
            's.semester_name', 
            's.academic_year', 
            's.start_date', 
            's.end_date', 
            's.notice', 
            's.status' 
            ) 
            ->first();

        if (!$semester) {
            throw new \Exception("Semester not found");
        }

        return $semester;
    }

    


}
