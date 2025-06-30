<?php
namespace App\Controllers;

use Core\Auth;

class EvaluationFormController extends \Core\Controller
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Chỉ Admin mới có quyền quản lý form đánh giá
        Auth::requireRole('admin');
    }
    
    public function index()
    {
        $formModel = $this->model('EvaluationForm');
        $forms = $formModel->getAllForms();
        
        // Lấy danh sách phòng ban cho bộ lọc
        $departmentModel = $this->model('Department');
        $departments = $departmentModel->getAllDepartments();
        
        $data = [
            'title' => 'Quản lý form đánh giá',
            'forms' => $forms,
            'departments' => $departments
        ];
        
        $this->view('templates/header', $data);
        $this->view('admin/evaluation_forms/index', $data);
    }
    
    public function create()
    {
        $departmentModel = $this->model('Department');
        $departments = $departmentModel->getAllDepartments();
        
        $data = [
            'title' => 'Tạo form đánh giá mới',
            'departments' => $departments
        ];
        
        $this->view('templates/header', $data);
        $this->view('admin/evaluation_forms/create', $data);
    }
    
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $departmentId = $_POST['department_id'] ?? null;
            $content = $_POST['content'] ?? '';
            
            // Decode content to get form_type
            $contentData = json_decode($content, true);
            $formType = $contentData['form_type'] ?? '';
            
            if (empty($name)) {
                $_SESSION['error'] = 'Vui lòng nhập tên form đánh giá';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/evaluation-forms/create');
                exit;
            }
            
            if (empty($formType)) {
                $_SESSION['error'] = 'Vui lòng chọn loại form đánh giá';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/evaluation-forms/create');
                exit;
            }
            
            // Kiểm tra hợp lệ của các tiêu chí đánh giá
            if (!$this->validateEvaluationContent($contentData)) {
                $_SESSION['error'] = 'Cấu trúc form đánh giá không hợp lệ. Vui lòng kiểm tra lại các tiêu chí và trọng số.';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/evaluation-forms/create');
                exit;
            }
            
            // Kiểm tra nếu phòng ban đã có form cho loại đã chọn
            if (!empty($departmentId)) {
                $formModel = $this->model('EvaluationForm');
                $existingForm = $formModel->departmentHasFormType($departmentId, $formType);
                
                if ($existingForm && $existingForm['exists']) {
                    $_SESSION['error'] = 'Phòng ban này đã có form đánh giá cho ' . 
                        ($formType == 'lanh_dao' ? 'lãnh đạo' : 'chuyên viên') . 
                        '. Vui lòng chỉnh sửa form hiện có.';
                    header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/evaluation-forms/create');
                    exit;
                }
            }
            
            // Tạo form mới
            $formModel = $this->model('EvaluationForm');
            $formId = $formModel->createForm($name, $departmentId, $content, $formType);
            
            if ($formId) {
                $_SESSION['success'] = 'Tạo form đánh giá thành công';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/evaluation-forms');
                exit;
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/evaluation-forms/create');
                exit;
            }
        }
        
        header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/evaluation-forms/create');
        exit;
    }
    
    // Phương thức kiểm tra hợp lệ của nội dung form đánh giá
    private function validateEvaluationContent($contentData) {
        // Kiểm tra cấu trúc cơ bản
        if (!isset($contentData['form_type']) || !isset($contentData['sections'])) {
            return false;
        }
        
        // Tổng trọng số các phần phải bằng 100
        $totalWeight = 0;
        foreach ($contentData['sections'] as $section) {
            if (!isset($section['weight']) || !is_numeric($section['weight'])) {
                return false;
            }
            $totalWeight += (int)$section['weight'];
            
            // Kiểm tra các tiêu chí trong mỗi phần
            if (!isset($section['criteria']) || !is_array($section['criteria']) || empty($section['criteria'])) {
                return false;
            }
            
            // Tổng điểm tối đa trong mỗi phần phải bằng trọng số của phần đó
            $totalMaxScore = 0;
            foreach ($section['criteria'] as $criterion) {
                if (!isset($criterion['max_score']) || !is_numeric($criterion['max_score'])) {
                    return false;
                }
                $totalMaxScore += (int)$criterion['max_score'];
            }
            
            if ($totalMaxScore != $section['weight']) {
                return false;
            }
        }
        
        return $totalWeight == 100;
    }
    
    public function checkExisting()
    {
        $departmentId = $_GET['department_id'] ?? null;
        $formType = $_GET['form_type'] ?? null;
        
        if (empty($departmentId) || empty($formType)) {
            echo json_encode([
                'exists' => false
            ]);
            exit;
        }
        
        $formModel = $this->model('EvaluationForm');
        $existingForm = $formModel->departmentHasFormType($departmentId, $formType);
        
        if ($existingForm && $existingForm['exists']) {
            echo json_encode([
                'exists' => true,
                'form_id' => $existingForm['form_id']
            ]);
        } else {
            echo json_encode([
                'exists' => false
            ]);
        }
        exit;
    }
    
    public function edit($id)
    {
        $formModel = $this->model('EvaluationForm');
        $form = $formModel->getFormById($id);
        
        if (!$form) {
            $_SESSION['error'] = 'Không tìm thấy form đánh giá';
            header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/evaluation-forms');
            exit;
        }
        
        $departmentModel = $this->model('Department');
        $departments = $departmentModel->getAllDepartments();
        
        $data = [
            'title' => 'Chỉnh sửa form đánh giá',
            'form' => $form,
            'departments' => $departments
        ];
        
        $this->view('templates/header', $data);
        $this->view('admin/evaluation_forms/edit', $data);
    }
    
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $departmentId = $_POST['department_id'] ?? null;
            $content = $_POST['content'] ?? '';
            
            // Decode content to get form_type
            $contentData = json_decode($content, true);
            $formType = $contentData['form_type'] ?? '';
            
            if (empty($name)) {
                $_SESSION['error'] = 'Vui lòng nhập tên form đánh giá';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/evaluation-forms/edit/' . $id);
                exit;
            }
            
            if (empty($formType)) {
                $_SESSION['error'] = 'Vui lòng chọn loại form đánh giá';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/evaluation-forms/edit/' . $id);
                exit;
            }
            
            // Kiểm tra hợp lệ của các tiêu chí đánh giá
            if (!$this->validateEvaluationContent($contentData)) {
                $_SESSION['error'] = 'Cấu trúc form đánh giá không hợp lệ. Vui lòng kiểm tra lại các tiêu chí và trọng số.';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/evaluation-forms/edit/' . $id);
                exit;
            }
            
            // Kiểm tra form tồn tại
            $formModel = $this->model('EvaluationForm');
            $form = $formModel->getFormById($id);
            
            if (!$form) {
                $_SESSION['error'] = 'Không tìm thấy form đánh giá';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/evaluation-forms');
                exit;
            }
            
            // Kiểm tra nếu đổi phòng ban và phòng ban mới đã có form cho loại form này
            if (!empty($departmentId) && ($departmentId != $form['department_id'] || $formType != $form['form_type'])) {
                $existingForm = $formModel->departmentHasFormType($departmentId, $formType);
                if ($existingForm && $existingForm['exists'] && $existingForm['form_id'] != $id) {
                    $_SESSION['error'] = 'Phòng ban này đã có form đánh giá cho ' . 
                        ($formType == 'lanh_dao' ? 'lãnh đạo' : 'chuyên viên') . 
                        '. Vui lòng chọn phòng ban khác hoặc loại form khác.';
                    header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/evaluation-forms/edit/' . $id);
                    exit;
                }
            }
            
            // Cập nhật form
            $success = $formModel->updateForm($id, $name, $departmentId, $content, $formType);
            
            if ($success) {
                $_SESSION['success'] = 'Cập nhật form đánh giá thành công';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/evaluation-forms');
                exit;
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/evaluation-forms/edit/' . $id);
                exit;
            }
        }
        
        header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/evaluation-forms/edit/' . $id);
        exit;
    }
    
    public function delete($id)
    {
        $formModel = $this->model('EvaluationForm');
        $form = $formModel->getFormById($id);
        
        if (!$form) {
            $_SESSION['error'] = 'Không tìm thấy form đánh giá';
            header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/evaluation-forms');
            exit;
        }
        
        $success = $formModel->deleteForm($id);
        
        if ($success) {
            $_SESSION['success'] = 'Xóa form đánh giá thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
        }
        
        header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/evaluation-forms');
        exit;
    }
    
    public function preview($id)
    {
        $formModel = $this->model('EvaluationForm');
        $form = $formModel->getFormById($id);
        
        if (!$form) {
            $_SESSION['error'] = 'Không tìm thấy form đánh giá';
            header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/evaluation-forms');
            exit;
        }
        
        $data = [
            'title' => 'Xem trước form đánh giá',
            'form' => $form
        ];
        
        $this->view('templates/header', $data);
        $this->view('admin/evaluation_forms/preview', $data);
    }
} 