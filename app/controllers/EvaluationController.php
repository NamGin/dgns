<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet; //xuất excel
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; //xuất excel
use Core\Auth;

require_once __DIR__ . '/../../vendor/autoload.php'; //xuất excel
class EvaluationController extends \Core\Controller
{
    public function index()
    {
        echo "EvaluationController index action";
        // Hoặc render view mặc định nếu bạn muốn
    }
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    // xuất file excel 
    public function review($id)
    {
        $evaluationModel = $this->model('Evaluation');
        $evaluation = $evaluationModel->getEvaluationById($id);

        if (!$evaluation) {
            $_SESSION['error'] = 'Không tìm thấy bản đánh giá';
            header('Location: ' . $GLOBALS['config']['base_url'] . 'dashboard');
            exit;
        }

        // Truyền $evaluation vào view
        $this->view('manager-review', ['evaluation' => $evaluation]);
    }
    // xuất file excel 
    public function exportExcel($id)
    {
        if (!Auth::check()) {
            header('Location: ' . $GLOBALS['config']['base_url'] . 'login');
            exit;
        }

        $evaluationModel = $this->model('Evaluation');
        $evaluation = $evaluationModel->getEvaluationById($id);

        if (!$evaluation) {
            $_SESSION['error'] = 'Không tìm thấy bản đánh giá';
            header('Location: ' . $GLOBALS['config']['base_url'] . 'dashboard');
            exit;
        }

        // Include data.php để lấy $dataRate
        require_once __DIR__ . '/../../data.php';

        // Xác định $userData dựa trên loại đánh giá
        $evaluationData = json_decode($evaluation['content'], true);
        $isManager = isset($evaluationData['part3_level_1']);
        $userData = $isManager ? $dataRate['lanh_dao'] : $dataRate['nhan_vien'];

        if (empty($userData)) {
            $_SESSION['error'] = 'Dữ liệu userData không hợp lệ';
            header('Location: ' . $GLOBALS['config']['base_url'] . 'dashboard');
            exit;
        }

        // Tạo file Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Tiêu đề
        $sheet->setCellValue('A1', 'Chi tiết đánh giá');
        $sheet->setCellValue('A2', 'Người đánh giá: ' . $evaluation['employee_name']);
        $sheet->setCellValue('A3', 'Email: ' . $evaluation['employee_email']);
        $sheet->setCellValue('A4', 'Ngày tạo: ' . date('d/m/Y H:i', strtotime($evaluation['created_at'])));
        $sheet->setCellValue('A5', 'Trạng thái: ' . $evaluation['status']);
        $sheet->setCellValue('A6', 'Tổng điểm: ' . ($evaluationData['total_score'] ?? 'N/A') . '/100');

        // Tiêu đề bảng
        $row = 8;
        $sheet->setCellValue('A' . $row, 'Phần');
        $sheet->setCellValue('B' . $row, 'Tiêu chí');
        $sheet->setCellValue('C' . $row, 'Điểm tự chấm');
        $sheet->setCellValue('D' . $row, 'Điểm tối đa');

        // Dữ liệu Part 1
        $row++;
        $sheet->setCellValue('A' . $row, 'Phần I: ' . $userData['part1']['title']);
        $row++;
        foreach ($userData['part1']['criteria'] as $key => $criterion) {
            $score = isset($evaluationData['criteria'][$key]['score']) ? $evaluationData['criteria'][$key]['score'] : 'N/A';
            $sheet->setCellValue('A' . $row, '1.' . $key);
            $sheet->setCellValue('B' . $row, $criterion['text']);
            $sheet->setCellValue('C' . $row, $score);
            $sheet->setCellValue('D' . $row, $criterion['max_score']);
            $row++;
        }
        $sheet->setCellValue('A' . $row, 'Tổng điểm Phần I');
        $section1Total = array_sum(array_column($evaluationData['criteria'] ?? [], 'score') ?? [0, 0, 0, 0, 0]);
        $sheet->setCellValue('C' . $row, $section1Total);
        $sheet->setCellValue('D' . $row, $userData['part1']['total_max']);
        $row++;

        // Dữ liệu Part 2
        $sheet->setCellValue('A' . $row, 'Phần II: ' . $userData['part2']['title']);
        $row++;
        foreach ($userData['part2']['criteria'] as $key => $criterion) {
            $score = isset($evaluationData['criteria'][$key]['score']) ? $evaluationData['criteria'][$key]['score'] : 'N/A';
            $sheet->setCellValue('A' . $row, ($key - 5) . '.');
            $sheet->setCellValue('B' . $row, $criterion['text']);
            $sheet->setCellValue('C' . $row, $score);
            $sheet->setCellValue('D' . $row, $criterion['max_score']);
            $row++;
        }
        $sheet->setCellValue('A' . $row, 'Tổng điểm Phần II');
        $section2Total = array_sum(array_slice(array_column($evaluationData['criteria'] ?? [], 'score') ?? [], 5, 6));
        $sheet->setCellValue('C' . $row, $section2Total);
        $sheet->setCellValue('D' . $row, $userData['part2']['total_max']);
        $row++;

        // Dữ liệu Part 3
        $sheet->setCellValue('A' . $row, 'Phần III: ' . $userData['part3']['title']);
        $row++;
        if ($isManager) {
            $sheet->setCellValue('A' . $row, '1. ' . $userData['part3']['criteria']['level1']['text']);
            $sheet->setCellValue('C' . $row, $evaluationData['part3_level_1'] ?? 'N/A');
            $sheet->setCellValue('D' . $row, $userData['part3']['criteria']['level1']['max_score']);
            $row++;
            $sheet->setCellValue('A' . $row, '2. ' . $userData['part3']['criteria']['level2']['text']);
            $sheet->setCellValue('C' . $row, $evaluationData['part3_level_2'] ?? 'N/A');
            $sheet->setCellValue('D' . $row, $userData['part3']['criteria']['level2']['max_score']);
            $row++;
            $sheet->setCellValue('A' . $row, 'Tổng điểm Phần III');
            $sheet->setCellValue('C' . $row, ((int)($evaluationData['part3_level_1'] ?? 0) + (int)($evaluationData['part3_level_2'] ?? 0)));
        } else {
                $sheet->setCellValue('A' . $row, 'Tổng điểm Phần III');
            $sheet->setCellValue('C' . $row, isset($evaluationData['total_score']) ? $evaluationData['total_score'] : 0);
        }
        $sheet->setCellValue('D' . $row, $userData['part3']['total_max']);
        $row++;

        // Ghi chú và nhận xét
        if (!empty($evaluationData['notes'])) {
            $sheet->setCellValue('A' . $row, 'Ghi chú');
            $sheet->setCellValue('B' . $row, $evaluationData['notes']);
            $row++;
        }
        if (!empty($evaluation['manager_comment'])) {
            $sheet->setCellValue('A' . $row, 'Nhận xét của lãnh đạo');
            $sheet->setCellValue('B' . $row, $evaluation['manager_comment']);
            $row++;
        }
        if (!empty($evaluation['director_comment'])) {
            $sheet->setCellValue('A' . $row, 'Nhận xét của giám đốc');
            $sheet->setCellValue('B' . $row, $evaluation['director_comment']);
            $row++;
        }

        // Xuất file
        $filename = 'danh_gia_' . $id . '_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function createForm()
    {
        Auth::requireRole('nhan_vien');
        $evaluationModel = $this->model('Evaluation');
        $userEvaluations = $evaluationModel->getEvaluationsByEmployeeId(Auth::user()['id']);

        // Lấy danh sách phòng ban của người dùng
        $departmentModel = $this->model('Department');
        $userDepartments = $departmentModel->getUserDepartments(Auth::user()['id']);
        // Lấy form đánh giá phù hợp với phòng ban
        $formData = null;
        $departmentId = null;
        
        if (!empty($userDepartments)) {
            $departmentId = $userDepartments[0]['id'];

            // Lấy form đánh giá chuyên viên cho phòng ban này
            $formModel = $this->model('EvaluationForm');
            $form = $formModel->getFormByDepartmentId($departmentId, 'nhan_vien');
            if ($form) {
                $formData = json_decode($form['content'], true);
            } else {
                // Nếu không có form riêng cho phòng ban, dùng form mặc định
                $defaultForm = $formModel->getDefaultForm('nhan_vien');
                if ($defaultForm) {
                    $formData = json_decode($defaultForm['content'], true);
                } else {
                    // Nếu không tìm thấy cả form mặc định
                    require_once __DIR__ . '/../../data.php';
                    $formData = $dataRate['nhan_vien'];
                }
            }
        }
        
        // Nếu không tìm thấy form nào, dùng dữ liệu cố định từ data.php
        if (empty($formData)) {
            require_once __DIR__ . '/../../data.php';
            $formData = $dataRate['nhan_vien'];
        }

        // Tạo một đối tượng evaluation trống để sử dụng trong template
        $evaluation = [
            'employee_name' => Auth::user()['name'],
            'employee_email' => Auth::user()['email'],
            'department_id' => $departmentId,
            'department_name' => !empty($userDepartments) ? $userDepartments[0]['name'] : 'Chưa xác định'
        ];

        $data = [
            'title' => 'Tự đánh giá',
            'evaluations' => $userEvaluations,
            'userDepartments' => $userDepartments,
            'formData' => $formData,
            'evaluation' => $evaluation,
            'config' => $GLOBALS['config'],
            'isNewForm' => true // Đánh dấu đây là form tạo mới để template có thể hiển thị nút submit
        ];
        $this->view('templates/header', $data);
        
        // Sử dụng template mới nếu form data có cấu trúc mới
        if (isset($formData['sections'])) {
            $this->view('evaluation/evaluation-form-template', $data);
        } else {
            $this->view('evaluation/create', $data);
        }
    }

    public function managerCreateForm()
    {
        Auth::requireRole('lanh_dao');

        $evaluationModel = $this->model('Evaluation');
        $evaluations = $evaluationModel->getEvaluationsByEmployeeId(Auth::user()['id']);
        
        // Lấy danh sách phòng ban của người dùng
        $departmentModel = $this->model('Department');
        $userDepartments = $departmentModel->getUserDepartments(Auth::user()['id']);
        
        // Lấy form đánh giá phù hợp với phòng ban
        $formData = null;
        $departmentId = null;
        
        if (!empty($userDepartments)) {
            $departmentId = $userDepartments[0]['id'];
            
            // Lấy form đánh giá lãnh đạo cho phòng ban này
            $formModel = $this->model('EvaluationForm');
            $form = $formModel->getFormByDepartmentId($departmentId, 'lanh_dao');
            
            if ($form) {
                // Nếu tìm thấy form phòng ban
                $formData = json_decode($form['content'], true);
            } else {
                // Nếu không có form riêng cho phòng ban, dùng form mặc định
                $defaultForm = $formModel->getDefaultForm('lanh_dao');
                if ($defaultForm) {
                    $formData = json_decode($defaultForm['content'], true);
                } else {
                    // Nếu không tìm thấy cả form mặc định
                    require_once __DIR__ . '/../../data.php';
                    $formData = $dataRate['lanh_dao'];
                }
            }
        }
        
        // Nếu không tìm thấy form nào, dùng dữ liệu cố định từ data.php
        if (empty($formData)) {
            require_once __DIR__ . '/../../data.php';
            $formData = $dataRate['lanh_dao'];
        }

        // Tạo một đối tượng evaluation trống để sử dụng trong template
        $evaluation = [
            'employee_name' => Auth::user()['name'],
            'employee_email' => Auth::user()['email'],
            'department_id' => $departmentId,
            'department_name' => !empty($userDepartments) ? $userDepartments[0]['name'] : 'Chưa xác định'
        ];

        $data = [
            'title' => 'Tự đánh giá lãnh đạo',
            'evaluations' => $evaluations,
            'userDepartments' => $userDepartments,
            'formData' => $formData,
            'evaluation' => $evaluation,
            'config' => $GLOBALS['config'],
            'isNewForm' => true // Đánh dấu đây là form tạo mới để template có thể hiển thị nút submit
        ];

        $this->view('templates/header', $data);
        
        // Sử dụng template mới nếu form data có cấu trúc mới
        if (isset($formData['sections'])) {
            $this->view('evaluation/evaluation-form-template', $data);
        } else {
            $this->view('evaluation/manager-create', $data);
        }
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $GLOBALS['config']['base_url'] . 'form-danh-gia');
            exit;
        }
        
        Auth::requireRole('nhan_vien');

        // Lấy dữ liệu từ form
        $criteriaScores = $_POST['criteria'] ?? [];
        $totalScore = $_POST['total_score'] ?? '';
        $notes = $_POST['notes'] ?? '';
        $departmentId = $_POST['department_id'] ?? null;

        // Nếu không có department_id, lấy phòng ban đầu tiên của người dùng
        if (!$departmentId) {
            $departmentModel = $this->model('Department');
            $userDepartments = $departmentModel->getUserDepartments(Auth::user()['id']);
            if (!empty($userDepartments)) {
                $departmentId = $userDepartments[0]['id'];
            }
        }

        // Validate dữ liệu
        $validationErrors = [];
        
        // Kiểm tra các trường điểm tiêu chí
        if (empty($criteriaScores)) {
            $validationErrors[] = 'Vui lòng điền đầy đủ điểm các tiêu chí';
        } else {
            // Kiểm tra từng tiêu chí có điểm hợp lệ không
            foreach ($criteriaScores as $criteriaKey => $criteriaData) {
                if (!isset($criteriaData['score']) || $criteriaData['score'] === '') {
                    $validationErrors[] = 'Vui lòng điền đầy đủ điểm cho tất cả các tiêu chí';
                    break;
                }
            }
        }
        
        // Kiểm tra tổng điểm
        if (empty($totalScore)) {
            $validationErrors[] = 'Vui lòng điền tổng điểm đánh giá';
        }
        
        // Nếu có lỗi, chuyển hướng về trang form với thông báo lỗi
        if (!empty($validationErrors)) {
            $_SESSION['error'] = implode('. ', $validationErrors);
            header('Location: ' . $GLOBALS['config']['base_url'] . 'form-danh-gia');
            exit;
        }

        // Tạo JSON data để lưu vào DB
        $contentData = [
            'criteria' => $criteriaScores,
            'total_score' => $totalScore,
            'notes' => $notes
        ];
        
        $content = json_encode($contentData, JSON_UNESCAPED_UNICODE);

        // Lưu vào DB sử dụng form đánh giá theo phòng ban
        $evaluationModel = $this->model('Evaluation');
        $success = $evaluationModel->createEvaluationWithForm(Auth::user()['id'], $content, $departmentId);

        if ($success) {
            $_SESSION['success'] = 'Đã gửi đánh giá thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi gửi đánh giá';
        }

        header('Location: ' . $GLOBALS['config']['base_url'] . 'form-danh-gia');
        exit;
    }

    public function managerStore()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $GLOBALS['config']['base_url'] . 'lanh-dao-danh-gia');
            exit;
        }
        
        Auth::requireRole('lanh_dao');

        // Lấy dữ liệu từ form
        $criteriaScores = $_POST['criteria'] ?? [];
        $part3Level1 = $_POST['part3_level_1'] ?? '';
        $part3Level2 = $_POST['part3_level_2'] ?? '';
        $totalScore = $_POST['total_score'] ?? '';
        $notes = $_POST['notes'] ?? '';
        $departmentId = $_POST['department_id'] ?? null;

        // Nếu không có department_id, lấy phòng ban đầu tiên của người dùng
        if (!$departmentId) {
            $departmentModel = $this->model('Department');
            $userDepartments = $departmentModel->getUserDepartments(Auth::user()['id']);
            if (!empty($userDepartments)) {
                $departmentId = $userDepartments[0]['id'];
            }
        }

        // Validate dữ liệu
        $validationErrors = [];
        
        // Kiểm tra các trường điểm tiêu chí
        if (empty($criteriaScores)) {
            $validationErrors[] = 'Vui lòng điền đầy đủ điểm các tiêu chí';
        } else {
            // Kiểm tra từng tiêu chí có điểm hợp lệ không
            foreach ($criteriaScores as $criteriaKey => $criteriaData) {
                if (!isset($criteriaData['score']) || $criteriaData['score'] === '') {
                    $validationErrors[] = 'Vui lòng điền đầy đủ điểm cho tất cả các tiêu chí';
                    break;
                }
            }
        }
        
        // Kiểm tra phần 3 cho lãnh đạo
        if (empty($part3Level1)) {
            $validationErrors[] = 'Vui lòng chọn mức độ đánh giá cá nhân';
        }
        
        if (empty($part3Level2)) {
            $validationErrors[] = 'Vui lòng chọn mức độ đánh giá đơn vị';
        }
        
        // Kiểm tra tổng điểm
        if (empty($totalScore)) {
            $validationErrors[] = 'Vui lòng điền tổng điểm đánh giá';
        }
        
        // Nếu có lỗi, chuyển hướng về trang form với thông báo lỗi
        if (!empty($validationErrors)) {
            $_SESSION['error'] = implode('. ', $validationErrors);
            header('Location: ' . $GLOBALS['config']['base_url'] . 'lanh-dao-danh-gia');
            exit;
        }

        // Tạo JSON data để lưu vào DB
        $contentData = [
            'criteria' => $criteriaScores,
            'part3_level_1' => $part3Level1,
            'part3_level_2' => $part3Level2,
            'total_score' => $totalScore,
            'notes' => $notes
        ];
        
        $content = json_encode($contentData, JSON_UNESCAPED_UNICODE);

        // Lưu vào DB sử dụng form đánh giá theo phòng ban
        $evaluationModel = $this->model('Evaluation');
        $success = $evaluationModel->createManagerEvaluationWithForm(Auth::user()['id'], $content, $departmentId);

        if ($success) {
            $_SESSION['success'] = 'Đã gửi đánh giá thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi gửi đánh giá';
        }

        header('Location: ' . $GLOBALS['config']['base_url'] . 'lanh-dao-danh-gia');
        exit;
    }

    public function managerReviewList()
    {
        Auth::requireRole('lanh_dao');

        $evaluationModel = $this->model('Evaluation');
        $userId = Auth::user()['id'];
        $pendingEvaluations = $evaluationModel->getEvaluationsByStatusAndDepartment('sent', $userId);
        $reviewedEvaluations = $evaluationModel->getEvaluationsByStatusAndDepartment('reviewed', $userId);

        $data = [
            'title' => 'Duyệt đánh giá',
            'pendingEvaluations' => $pendingEvaluations,
            'reviewedEvaluations' => $reviewedEvaluations
        ];

        $this->view('templates/header', $data);
        $this->view('evaluation/manager-list', $data);
    }
    public function managerReviewForm($id)
    {
        Auth::requireRole('lanh_dao');
        
        $evaluationModel = $this->model('Evaluation');
        $evaluation = $evaluationModel->getEvaluationById($id);
        $userId = Auth::user()['id'];
        // dd($evaluation);
        // Kiểm tra xem đánh giá có tồn tại không
        if (!$evaluation) {
            $_SESSION['error'] = 'Không tìm thấy bản đánh giá';
            header('Location: ' . $GLOBALS['config']['base_url'] . 'lanh-dao-review');
            exit;
        }
        
        // Kiểm tra xem lãnh đạo có quyền quản lý phòng ban của người được đánh giá không
        $departmentModel = $this->model('Department');
        $userDepartments = $departmentModel->getUserDepartments($userId);
        $hasDepartmentAccess = false;
        
        foreach ($userDepartments as $department) {
            if ($department['is_leader'] && $department['id'] == $evaluation['department_id']) {
                $hasDepartmentAccess = true;
                break;
            }
        }
        
        if (!$hasDepartmentAccess) {
            $_SESSION['error'] = 'Bạn không có quyền xem đánh giá này';
            header('Location: ' . $GLOBALS['config']['base_url'] . 'lanh-dao-duyet');
            exit;
        }
        
        // Lấy mẫu form đánh giá phù hợp
        $formModel = $this->model('EvaluationForm');
        $formData = null;
        
        // Xác định loại form (lãnh đạo hoặc chuyên viên)
        $evaluationContent = json_decode($evaluation['content'], true);
        $formType = isset($evaluationContent['part3_level_1']) ? 'lanh_dao' : 'nhan_vien';
        
        if (!empty($evaluation['department_id'])) {
            // Lấy form đánh giá của phòng ban
            $form = $formModel->getFormByDepartmentId($evaluation['department_id'], $formType);
            if ($form) {
                $formData = json_decode($form['content'], true);
            }
        }
        
        // Nếu không có form riêng của phòng ban, sử dụng form mặc định
        if (!$formData) {
            $defaultForm = $formModel->getDefaultForm($formType);
            if ($defaultForm) {
                $formData = json_decode($defaultForm['content'], true);
            }
        }
        
        $data = [
            'title' => 'Đánh giá bản tự đánh giá của: ' . $evaluation['employee_name'],
            'evaluation' => $evaluation,
            'formData' => $formData
        ];
        $this->view('templates/header', $data);
        $this->view('evaluation/manager-review', $data);
    }

    public function managerApprove($id)
    {
        Auth::requireRole('lanh_dao');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $comment = $_POST['manager_comment'] ?? '';
            $extraDeduction = $_POST['extra_deduction'] ?? 0;
            $rescore = $_POST['rescore'] ?? [];
            
            $evaluationModel = $this->model('Evaluation');
            $evaluation = $evaluationModel->getEvaluationById($id);
            $userId = Auth::user()['id'];

            if (!$evaluation) {
                $_SESSION['error'] = 'Không tìm thấy bản đánh giá';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'lanh-dao-review');
                exit;
            }
            if ($action === 'approve') {
            // Lưu điểm chấm lại vào DB
            $evaluationModel->updateLeaderRescore($id, $rescore);
            // Lưu điểm bị trừ bổ sung vào DB
            $evaluationModel->updateExtraDeduction($id, $extraDeduction);

            $success = $evaluationModel->updateStatusAndManagerComment($id, 'reviewed', $comment);

            if ($success) {
                $_SESSION['success'] = 'Đã duyệt đánh giá thành công';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
            }
            } elseif ($action === 'update') {
                // Chỉ cập nhật comment mà không thay đổi trạng thái
                $success = $evaluationModel->updateManagerComment($id, $comment);
                
                if ($success) {
                    $_SESSION['success'] = 'Đã cập nhật nhận xét thành công';
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
                }
            } elseif ($action === 'cancel') {
                // Kiểm tra xem có thể hoàn tác hay không (phó giám đốc và giám đốc chưa duyệt)
                if (empty($evaluation['deputy_director_comment']) && empty($evaluation['director_comment'])) {
                    // Đổi trạng thái về 'sent' và giữ nguyên comment
                    $success = $evaluationModel->updateStatus($id, 'sent');
                    
                    if ($success) {
                        $_SESSION['success'] = 'Đã hoàn tác phê duyệt thành công';
                    } else {
                        $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
                    }
                } else {
                    $_SESSION['error'] = 'Không thể hoàn tác phê duyệt khi đã có nhận xét từ cấp cao hơn';
                }
            }

            header('Location: ' . $GLOBALS['config']['base_url'] . 'lanh-dao-review');
            exit;
        }

        header('Location: ' . $GLOBALS['config']['base_url'] . 'lanh-dao-review');
        exit;
    }

    public function directorList()
    {
        Auth::requireRole('giam_doc');
        
        $evaluationModel = $this->model('Evaluation');
        
        // Get all evaluations but prioritize those with deputy director reviews
        $allEvaluations = $evaluationModel->getAllEvaluations();
        
        // Split evaluations into priority (deputy_reviewed) and others
        $priorityEvaluations = [];
        $otherEvaluations = [];
        
        foreach ($allEvaluations as $evaluation) {
            if ($evaluation['status'] === 'deputy_reviewed') {
                $priorityEvaluations[] = $evaluation;
            } else {
                $otherEvaluations[] = $evaluation;
            }
        }
        
        // Combine with priority first
        $evaluations = array_merge($priorityEvaluations, $otherEvaluations);
        
        $data = [
            'title' => 'Danh sách đánh giá',
            'evaluations' => $evaluations
        ];
        
        $this->view('templates/header', $data);
        $this->view('evaluation/director-list', $data);
    }

    public function directorReviewForm($id)
    {
        Auth::requireRole('giam_doc');
        
        $evaluationModel = $this->model('Evaluation');
        $evaluation = $evaluationModel->getEvaluationById($id);
        
        if (!$evaluation || ($evaluation['status'] != 'reviewed' && $evaluation['status'] != 'deputy_reviewed' && $evaluation['status'] != 'approved')) {
            $_SESSION['error'] = 'Không tìm thấy bản đánh giá hoặc bản đánh giá không ở trạng thái hợp lệ';
            header('Location: ' . $GLOBALS['config']['base_url'] . 'giam-doc-xem');
            exit;
        }

        // Lấy mẫu form đánh giá phù hợp
        $formModel = $this->model('EvaluationForm');
        $formData = null;
        
        // Xác định loại form (lãnh đạo hoặc chuyên viên)
        $evaluationContent = json_decode($evaluation['content'], true);
        $formType = isset($evaluationContent['part3_level_1']) ? 'lanh_dao' : 'nhan_vien';
        
        if (!empty($evaluation['department_id'])) {
            // Lấy form đánh giá của phòng ban
            $form = $formModel->getFormByDepartmentId($evaluation['department_id'], $formType);
            if ($form) {
                $formData = json_decode($form['content'], true);
            }
        }
        
        // Nếu không có form riêng của phòng ban, sử dụng form mặc định
        if (!$formData) {
            $defaultForm = $formModel->getDefaultForm($formType);
            if ($defaultForm) {
                $formData = json_decode($defaultForm['content'], true);
            }
        }
        
        $data = [
            'title' => 'Duyệt đánh giá: ' . $evaluation['employee_name'],
            'evaluation' => $evaluation,
            'formData' => $formData
        ];
        
        $this->view('templates/header', $data);
        
        // Sử dụng template mới nếu form data hợp lệ
        if ($formData && isset($formData['sections'])) {
            $this->view('evaluation/director-review', $data);
        } else {
            $this->view('evaluation/director-review', $data);
        }
    }

    public function directorSaveComment($id)
    {
        Auth::requireRole('giam_doc');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $comment = $_POST['director_comment'] ?? '';
            $action = $_POST['action'] ?? '';
            $rescore = $_POST['rescore'] ?? [];

            $evaluationModel = $this->model('Evaluation');
            $evaluation = $evaluationModel->getEvaluationById($id);

            if (!$evaluation) {
                $_SESSION['error'] = 'Không tìm thấy bản đánh giá';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'giam-doc-xem');
                exit;
            }

            if ($action === 'approve') {
                $success = $evaluationModel->updateDirectorCommentAndApprove($id, $comment);
                // Lưu điểm chấm lại vào DB
                $evaluationModel->updateDirectorRescore($id, $rescore);
                if ($success) {
                    $_SESSION['success'] = 'Đã phê duyệt và lưu nhận xét thành công';
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
                }
            }

            header('Location: ' . $GLOBALS['config']['base_url'] . 'giam-doc-xem');
            exit;
        }

        header('Location: ' . $GLOBALS['config']['base_url'] . 'giam-doc-xem');
        exit;
    }

    public function viewDetails($id)
    {
        // Require authentication (any role can view)
        if (!Auth::check()) {
            header('Location: ' . $GLOBALS['config']['base_url'] . 'login');
            exit;
        }

        $evaluationModel = $this->model('Evaluation');
        $evaluation = $evaluationModel->getEvaluationById($id);

        if (!$evaluation) {
            $_SESSION['error'] = 'Không tìm thấy bản đánh giá';

            // Redirect based on user role
            $redirect = 'dashboard';
            if (Auth::hasRole('nhan_vien')) {
                $redirect = 'form-danh-gia';
            } elseif (Auth::hasRole('lanh_dao')) {
                $redirect = 'lanh-dao-danh-gia';
            } elseif (Auth::hasRole('pho_giam_doc')) {
                $redirect = 'pho-giam-doc-xem';
            } elseif (Auth::hasRole('giam_doc')) {
                $redirect = 'giam-doc-xem';
            }

            header('Location: ' . $GLOBALS['config']['base_url'] . $redirect);
            exit;
        }

        // Check if user has permission to view this evaluation
        $currentUser = Auth::user();
        if (
            $evaluation['employee_id'] != $currentUser['id'] &&
            !Auth::hasRole('lanh_dao') &&
            !Auth::hasRole('pho_giam_doc') &&
            !Auth::hasRole('giam_doc') &&
            !Auth::hasRole('admin')
        ) {

            $_SESSION['error'] = 'Bạn không có quyền xem bản đánh giá này';
            header('Location: ' . $GLOBALS['config']['base_url'] . 'dashboard');
            exit;
        }

        // Add redirect destination for pho_giam_doc role
        $redirect = 'dashboard';
        if (Auth::hasRole('nhan_vien')) {
            $redirect = 'form-danh-gia';
        } elseif (Auth::hasRole('lanh_dao')) {
            $redirect = 'lanh-dao-danh-gia';
        } elseif (Auth::hasRole('pho_giam_doc')) {
            $redirect = 'pho-giam-doc-xem';
        } elseif (Auth::hasRole('giam_doc')) {
            $redirect = 'giam-doc-xem';
        }
        
        // Lấy mẫu form đánh giá phù hợp từ EvaluationForm model
        $formModel = $this->model('EvaluationForm');
        $formData = null;
        
        // Xác định loại form (lãnh đạo hoặc chuyên viên)
        $evaluationContent = json_decode($evaluation['content'], true);
        $formType = isset($evaluationContent['part3_level_1']) ? 'lanh_dao' : 'nhan_vien';
        
        if (!empty($evaluation['department_id'])) {
            // Lấy form đánh giá của phòng ban
            $form = $formModel->getFormByDepartmentId($evaluation['department_id'], $formType);
            if ($form) {
                $formData = json_decode($form['content'], true);
            }
        }
        
        // Nếu không có form riêng của phòng ban, sử dụng form mặc định
        if (!$formData) {
            $defaultForm = $formModel->getDefaultForm($formType);
            if ($defaultForm) {
                $formData = json_decode($defaultForm['content'], true);
            }
        }

        $data = [
            'title' => 'Chi tiết đánh giá',
            'evaluation' => $evaluation,
            'formData' => $formData,
            'config' => $GLOBALS['config']
        ];

        $this->view('templates/header', $data);
        
        // Sử dụng template mới nếu form data hợp lệ, ngược lại sử dụng template cũ
        if ($formData && isset($formData['sections'])) {
            $this->view('evaluation/evaluation-form-template', $data);
        } else {
            // Fallback to old template if new form structure is not available
            $this->view('evaluation/view-details', $data);
        }
    }

    public function employeeReviewForm($id)
    {
        Auth::requireRole('nhan_vien');

        $evaluationModel = $this->model('Evaluation');
        $evaluation = $evaluationModel->getEvaluationById($id);

        if (!$evaluation) {
            $_SESSION['error'] = 'Không tìm thấy bản đánh giá';
            header('Location: ' . $GLOBALS['config']['base_url'] . 'form-danh-gia');
            exit;
        }

        // Verify that the employee is viewing their own evaluation
        $currentUser = Auth::user();
        if ($evaluation['employee_id'] != $currentUser['id']) {
            $_SESSION['error'] = 'Bạn không có quyền xem bản đánh giá này';
            header('Location: ' . $GLOBALS['config']['base_url'] . 'form-danh-gia');
            exit;
        }

        // Lấy mẫu form đánh giá phù hợp
        $formModel = $this->model('EvaluationForm');
        $formData = null;
        
        // Xác định loại form (lãnh đạo hoặc chuyên viên)
        $evaluationContent = json_decode($evaluation['content'], true);
        $formType = isset($evaluationContent['part3_level_1']) ? 'lanh_dao' : 'nhan_vien';
        
        if (!empty($evaluation['department_id'])) {
            // Lấy form đánh giá của phòng ban
            $form = $formModel->getFormByDepartmentId($evaluation['department_id'], $formType);
            if ($form) {
                $formData = json_decode($form['content'], true);
            }
        }
        
        // Nếu không có form riêng của phòng ban, sử dụng form mặc định
        if (!$formData) {
            $defaultForm = $formModel->getDefaultForm($formType);
            if ($defaultForm) {
                $formData = json_decode($defaultForm['content'], true);
            }
        }

        $data = [
            'title' => 'Chi tiết đánh giá',
            'evaluation' => $evaluation,
            'formData' => $formData,
            'config' => $GLOBALS['config']
        ];

        $this->view('templates/header', $data);
        
        // Sử dụng template mới nếu form data hợp lệ
        if ($formData && isset($formData['sections'])) {
            $this->view('evaluation/view-details', $data);
        } else {
            $this->view('evaluation/view-details', $data);
        }
    }

    public function managerViewForm($id)
    {
        Auth::requireRole('lanh_dao');

        $evaluationModel = $this->model('Evaluation');
        $evaluation = $evaluationModel->getEvaluationById($id);

        if (!$evaluation) {
            $_SESSION['error'] = 'Không tìm thấy bản đánh giá';
            header('Location: ' . $GLOBALS['config']['base_url'] . 'lanh-dao-danh-gia');
            exit;
        }

        // Verify that the manager is viewing their own evaluation
        $currentUser = Auth::user();
        if ($evaluation['employee_id'] != $currentUser['id']) {
            $_SESSION['error'] = 'Bạn không có quyền xem bản đánh giá này';
            header('Location: ' . $GLOBALS['config']['base_url'] . 'lanh-dao-danh-gia');
            exit;
        }

        // Lấy mẫu form đánh giá phù hợp
        $formModel = $this->model('EvaluationForm');
        $formData = null;
        
        // Xác định loại form (lãnh đạo hoặc chuyên viên)
        $evaluationContent = json_decode($evaluation['content'], true);
        $formType = isset($evaluationContent['part3_level_1']) ? 'lanh_dao' : 'nhan_vien';
        
        if (!empty($evaluation['department_id'])) {
            // Lấy form đánh giá của phòng ban
            $form = $formModel->getFormByDepartmentId($evaluation['department_id'], $formType);
            if ($form) {
                $formData = json_decode($form['content'], true);
            }
        }
        
        // Nếu không có form riêng của phòng ban, sử dụng form mặc định
        if (!$formData) {
            $defaultForm = $formModel->getDefaultForm($formType);
            if ($defaultForm) {
                $formData = json_decode($defaultForm['content'], true);
            }
        }

        $data = [
            'title' => 'Chi tiết đánh giá',
            'evaluation' => $evaluation,
            'formData' => $formData,
            'config' => $GLOBALS['config']
        ];

        $this->view('templates/header', $data);
        
        // Sử dụng template mới nếu form data hợp lệ
        if ($formData && isset($formData['sections'])) {
            $this->view('evaluation/evaluation-form-template', $data);
        } else {
            $this->view('evaluation/view-details', $data);
        }
    }

    /**
     * Deputy Director Review List
     */
    public function deputyDirectorList()
    {
        Auth::requireRole('pho_giam_doc');
        
        $evaluationModel = $this->model('Evaluation');
        $evaluations = $evaluationModel->getEvaluationsForDeputyDirector();
        
        $data = [
            'title' => 'Danh sách đánh giá cần phê duyệt',
            'evaluations' => $evaluations
        ];
        
        $this->view('templates/header', $data);
        $this->view('evaluation/deputy-director-list', $data);
    }
    
    /**
     * Deputy Director Review Form
     */
    public function deputyDirectorReviewForm($id)
    {
        Auth::requireRole('pho_giam_doc');
        
        $evaluationModel = $this->model('Evaluation');
        $evaluation = $evaluationModel->getEvaluationById($id);
        
        if (!$evaluation || ($evaluation['status'] != 'reviewed' && $evaluation['status'] != 'deputy_reviewed')) {
            $_SESSION['error'] = 'Không tìm thấy bản đánh giá hoặc bản đánh giá không ở trạng thái chờ phê duyệt';
            header('Location: ' . $GLOBALS['config']['base_url'] . 'pho-giam-doc-xem');
            exit;
        }
        
        // Lấy mẫu form đánh giá phù hợp
        $formModel = $this->model('EvaluationForm');
        $formData = null;
        
        // Xác định loại form (lãnh đạo hoặc chuyên viên)
        $evaluationContent = json_decode($evaluation['content'], true);
        $formType = isset($evaluationContent['part3_level_1']) ? 'lanh_dao' : 'nhan_vien';
        
        if (!empty($evaluation['department_id'])) {
            // Lấy form đánh giá của phòng ban
            $form = $formModel->getFormByDepartmentId($evaluation['department_id'], $formType);
            if ($form) {
                $formData = json_decode($form['content'], true);
            }
        }
        
        // Nếu không có form riêng của phòng ban, sử dụng form mặc định
        if (!$formData) {
            $defaultForm = $formModel->getDefaultForm($formType);
            if ($defaultForm) {
                $formData = json_decode($defaultForm['content'], true);
            }
        }
        
        $data = [
            'title' => 'Phê duyệt đánh giá: ' . $evaluation['employee_name'],
            'evaluation' => $evaluation,
            'formData' => $formData
        ];
        
        $this->view('templates/header', $data);
        
        // Sử dụng template mới nếu form data hợp lệ
        if ($formData && isset($formData['sections'])) {
            $this->view('evaluation/deputy-director-review', $data);
        } else {
            $this->view('evaluation/deputy-director-review', $data);
        }
    }
    
    /**
     * Deputy Director Save Comment
     */
    public function deputyDirectorSaveComment($id)
    {
        Auth::requireRole('pho_giam_doc');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $comment = $_POST['comment'] ?? '';
            $evaluationModel = $this->model('Evaluation');
            $rescore = $_POST['rescore'] ?? [];
            $evaluationModel->updateDeputyDirectorRescore($id, $rescore);
            $evaluation = $evaluationModel->getEvaluationById($id);
            
            if (!$evaluation || ($evaluation['status'] != 'reviewed' && $evaluation['status'] != 'deputy_reviewed')) {
                $_SESSION['error'] = 'Không tìm thấy bản đánh giá hoặc bản đánh giá không ở trạng thái chờ phê duyệt';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'pho-giam-doc-xem');
                exit;
            }
            
            // Nếu đã ở trạng thái deputy_reviewed, chỉ cập nhật comment
            if ($evaluation['status'] === 'deputy_reviewed') {
                $success = $evaluationModel->updateDeputyDirectorComment($id, $comment);
            } else {
                $success = $evaluationModel->updateStatusAndDeputyDirectorComment($id, 'deputy_reviewed', $comment);
            }
            
            if ($success) {
                $_SESSION['success'] = 'Đã lưu nhận xét và chuyển cho giám đốc';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'pho-giam-doc-xem');
                exit;
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'pho-giam-doc-xem/' . $id);
                exit;
            }
        }
        
        header('Location: ' . $GLOBALS['config']['base_url'] . 'pho-giam-doc-xem/' . $id);
        exit;
    }
    public function updateExtraDeduction()
    {
    Auth::requireRole(['nhan_vien', 'lanh_dao', 'pho_giam_doc', 'giam_doc']);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $evaluationId = $_POST['evaluation_id'] ?? null;
        $extraDeduction = $_POST['extra_deduction'] ?? 0;
        $evaluationModel = $this->model('Evaluation');
        $evaluationModel->updateExtraDeduction($evaluationId, $extraDeduction);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
    }
}
