<?php
namespace App\Controllers;

use Core\Auth;

class ApiController extends \Core\Controller
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Kiểm tra đăng nhập cho tất cả API
        Auth::requireAuth();
        
        // Đặt header JSON
        header('Content-Type: application/json');
    }
    
    /**
     * Lấy form đánh giá theo phòng ban
     */
    public function getDepartmentForm($departmentId)
    {
        // Kiểm tra nếu departmentId hợp lệ
        if (!$departmentId || !is_numeric($departmentId)) {
            echo json_encode([
                'success' => false,
                'message' => 'ID phòng ban không hợp lệ'
            ]);
            return;
        }
        
        // Lấy form_type từ query parameter, mặc định là 'nhan_vien'
        $formType = $_GET['form_type'] ?? 'nhan_vien';
        
        // Kiểm tra quyền truy cập của người dùng đối với phòng ban
        $departmentModel = $this->model('Department');
        $userInDepartment = $departmentModel->isUserInDepartment(Auth::user()['id'], $departmentId);
        
        if (!$userInDepartment && !Auth::hasRole(['admin', 'giam_doc'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Bạn không có quyền truy cập form đánh giá của phòng ban này'
            ]);
            return;
        }
        
        // Lấy form đánh giá cho phòng ban với loại form được chỉ định
        $evaluationModel = $this->model('Evaluation');
        $form = $evaluationModel->getFormForDepartment($departmentId, $formType);
        
        if (!$form) {
            echo json_encode([
                'success' => false,
                'message' => 'Không tìm thấy form đánh giá cho phòng ban này'
            ]);
            return;
        }
        
        // Parse nội dung form từ JSON
        $formContent = null;
        if ($form && isset($form['content']) && !empty($form['content'])) {
            $formContent = json_decode($form['content'], true);
        }
        
        echo json_encode([
            'success' => true,
            'form' => [
                'id' => $form['id'] ?? null,
                'name' => $form['name'] ?? null,
                'department_id' => $form['department_id'] ?? null,
                'department_name' => $form['department_name'] ?? null,
                'content' => $formContent
            ]
        ]);
    }
} 