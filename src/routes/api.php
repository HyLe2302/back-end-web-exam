<?php

use Illuminate\Support\Facades\Route;
use Pecee\SimpleRouter\SimpleRouter as Router;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

Router::get('/recheck_exam', function () {
    try {
        \Illuminate\Database\Capsule\Manager::connection()->getPdo();
        return "Kết nối DB thành công!";
    } catch (\Exception $e) {
        return $e->getMessage();
    }
});

//Student routes

Router::get('/students/{studentId}/grades', 'GradeController@getGradesByStudent');

//grade detail route
Router::get('/grades/subject-detail', 'GradeController@getSubjectDetail');

//Recheck request routes
Router::post('/recheck-requests', 'RecheckController@store');
Router::get('/recheck-requests/{id}/download', 'RecheckController@download');
Router::get('/recheck-requests/list/{studentId}', 'RecheckController@getListByStudent'); 

//semester routes
Router::get('/students/{studentId}/open-semester', 'SemesterController@getOpenSemesterByStudent');



//admin routes

//Semester routes
Router::get('/admin/semesters', 'SemesterController@getAll');
Router::get('/admin/semesters/{id}', 'SemesterController@getInfoById');
Router::post('/admin/semesters', 'SemesterController@create');
Router::put('/admin/semesters/{id}', 'SemesterController@update');
Router::delete('/admin/semesters/{id}', 'SemesterController@delete');

//recheck request routes
Router::get('/admin/recheck-requests/statistics', 'RecheckController@getStatistics');
Router::get('/admin/recheck-requests/students', 'RecheckController@getStudentRecheckList');
Router::get('/admin/recheck-requests/students/{studentId}/requests/{requestId}/subjects', 'RecheckController@getSubjectsByStudent');
Router::put('/admin/recheck-requests/{requestId}/status', 'RecheckController@updateRequestStatus');

//recheck Detail routes
Router::put('/admin/recheck-details/{detailId}/status', 'RecheckController@updateDetailStatus');
Router::get('/admin/recheck-details/approved', 'RecheckController@getApprovedDetailStatus');
Router::get('/admin/recheck-details/completed', 'RecheckController@getCompletedDetailStatus');
Router::put('/admin/recheck-details/{detailId}/score', 'RecheckController@updateDetailScore');

//notification requests routes
Router::get('/admin/recheck-requests/unread-count', 'RecheckController@getUnreadRequestCount');
Router::post('/admin/recheck-requests/mark-read', 'RecheckController@markAllRequestAsRead');
Router::put('/admin/recheck-requests/{requestId}/mark-new-read', 'RecheckController@markNewRead');

// Validate và Export Excel
Router::get('/admin/recheck-details/export-validate', 'RecheckController@validateExport');
Router::get('/admin/recheck-details/export', 'RecheckController@exportExcel');

//Cohort routes
Router::get('/admin/cohorts', 'CohortController@getAll');
