<?php


$router->get('', 'Auth', 'loginForm');
$router->get('login', 'Auth', 'loginForm');
$router->post('login', 'Auth', 'login');
$router->get('logout', 'Auth', 'logout');


$router->get('form-danh-gia', 'Evaluation', 'createForm');
$router->post('form-danh-gia', 'Evaluation', 'store');

// Route for employee to view their own evaluation details
$router->get('nhan-vien-review/{id}', 'Evaluation', 'employeeReviewForm');

$router->get('lanh-dao-danh-gia', 'Evaluation', 'managerCreateForm');
$router->post('lanh-dao-danh-gia', 'Evaluation', 'managerStore');

// Route for manager to view their own evaluation details
$router->get('lanh-dao-view/{id}', 'Evaluation', 'managerViewForm');

$router->get('lanh-dao-review', 'Evaluation', 'managerReviewList');
$router->get('lanh-dao-review/{id}', 'Evaluation', 'managerReviewForm');
$router->post('lanh-dao-review/{id}', 'Evaluation', 'managerApprove');


$router->get('giam-doc-xem', 'Evaluation', 'directorList');
$router->get('giam-doc-xem/{id}', 'Evaluation', 'directorReviewForm');
$router->post('giam-doc-xem/{id}', 'Evaluation', 'directorSaveComment');

$router->get('admin/users', 'Admin', 'userList');
$router->get('admin/users/create', 'Admin', 'userCreateForm');
$router->post('admin/users/create', 'Admin', 'userStore');
$router->get('admin/users/edit/{id}', 'Admin', 'userEditForm');
$router->post('admin/users/edit/{id}', 'Admin', 'userUpdate');
$router->get('admin/users/delete/{id}', 'Admin', 'userDelete');
$router->get('admin/users/detail/{id}', 'Admin', 'userDetail');

// Routes for evaluation review from template form
$router->post('admin/evaluations/save-review/{id}', 'AdminEvaluations', 'saveReview');

$router->get('profile', 'Profile', 'show');
$router->get('profile/edit', 'Profile', 'edit');
$router->post('profile/update', 'Profile', 'update');
$router->get('profile/change-password', 'Profile', 'changePasswordForm');
$router->post('profile/change-password', 'Profile', 'changePassword');

// Routes cho quản lý phòng ban
$router->get('departments', 'Department', 'index');
$router->get('departments/create', 'Department', 'create');
$router->post('departments/store', 'Department', 'store');
$router->get('departments/edit/{id}', 'Department', 'edit');
$router->post('departments/update/{id}', 'Department', 'update');
$router->get('departments/delete/{id}', 'Department', 'delete');
$router->get('departments/members/{id}', 'Department', 'members');
$router->post('departments/add-member/{id}', 'Department', 'addMember');
$router->post('departments/update-member/{id}', 'Department', 'updateMember');
$router->get('departments/remove-member/{id}/{userId}', 'Department', 'removeMember');

// router để xuất file excel
$router->get('evaluation/export/{id}', 'Evaluation', 'exportExcel');
// $router->get('evaluation/export/{id}', 'Evaluation', 'exportExcel');
// $router->get('lanh-dao-review/{id}', 'Evaluation', 'review');
// $router->get('evaluations/view-details', ['controller' => 'Evaluations', 'action' => 'viewDetails']);

// Routes cho quản lý form đánh giá
$router->get('admin/evaluation-forms', 'EvaluationForm', 'index');
$router->get('admin/evaluation-forms/create', 'EvaluationForm', 'create');
$router->post('admin/evaluation-forms/store', 'EvaluationForm', 'store');
$router->get('admin/evaluation-forms/edit/{id}', 'EvaluationForm', 'edit');
$router->post('admin/evaluation-forms/update/{id}', 'EvaluationForm', 'update');
$router->get('admin/evaluation-forms/delete/{id}', 'EvaluationForm', 'delete');
$router->get('admin/evaluation-forms/preview/{id}', 'EvaluationForm', 'preview');
$router->get('admin/evaluation-forms/check-existing', 'EvaluationForm', 'checkExisting');

// API Routes
$router->get('api/get-department-form/{id}', 'Api', 'getDepartmentForm');

// Routes cho quản lý phòng ban

// Route for deputy director to view evaluation list
$router->get('pho-giam-doc-xem', 'Evaluation', 'deputyDirectorList');
$router->get('pho-giam-doc-xem/{id}', 'Evaluation', 'deputyDirectorReviewForm');
$router->post('pho-giam-doc-xem/submit/{id}', 'Evaluation', 'deputyDirectorSaveComment');
// Route cập nhật điểm bị trừ bổ sung cho đánh giá
$router->post('evaluation/update-extra-deduction', 'Evaluation', 'updateExtraDeduction');
$router->get('evaluation/view-details/{id}', 'Evaluation', 'viewDetails');
