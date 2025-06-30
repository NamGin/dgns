<?php
namespace App\Controllers;

use Core\Auth;

class DepartmentController extends \Core\Controller
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        Auth::requireRole(['admin']);
    }
    
    public function index()
    {
        $departmentModel = $this->model('Department');
        $departments = $departmentModel->getAllDepartments();
        
        // Thêm số lượng thành viên cho mỗi phòng ban
        foreach ($departments as &$department) {
            $department['member_count'] = $departmentModel->getDepartmentMemberCount($department['id']);
        }
        
        $data = [
            'title' => 'Quản lý phòng ban',
            'departments' => $departments
        ];
        
        $this->view('templates/header', $data);
        $this->view('admin/departments/index', $data);
    }
    
    public function create()
    {
        $data = [
            'title' => 'Thêm phòng ban mới'
        ];
        
        $this->view('templates/header', $data);
        $this->view('admin/departments/create', $data);
    }
    
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            
            if (empty($name)) {
                $_SESSION['error'] = 'Vui lòng nhập tên phòng ban';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'departments/create');
                exit;
            }
            
            $departmentModel = $this->model('Department');
            $departmentId = $departmentModel->createDepartment($name, $description);
            
            if ($departmentId) {
                $_SESSION['success'] = 'Thêm phòng ban thành công';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'departments');
                exit;
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'departments/create');
                exit;
            }
        }
        
        header('Location: ' . $GLOBALS['config']['base_url'] . 'departments/create');
        exit;
    }
    
    public function edit($id)
    {
        $departmentModel = $this->model('Department');
        $department = $departmentModel->getDepartmentById($id);
        
        if (!$department) {
            $_SESSION['error'] = 'Không tìm thấy phòng ban';
            header('Location: ' . $GLOBALS['config']['base_url'] . 'departments');
            exit;
        }
        
        $data = [
            'title' => 'Chỉnh sửa phòng ban',
            'department' => $department
        ];
        
        $this->view('templates/header', $data);
        $this->view('admin/departments/edit', $data);
    }
    
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            
            if (empty($name)) {
                $_SESSION['error'] = 'Vui lòng nhập tên phòng ban';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'departments/edit/' . $id);
                exit;
            }
            
            $departmentModel = $this->model('Department');
            $department = $departmentModel->getDepartmentById($id);
            
            if (!$department) {
                $_SESSION['error'] = 'Không tìm thấy phòng ban';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'departments');
                exit;
            }
            
            $success = $departmentModel->updateDepartment($id, $name, $description);
            
            if ($success) {
                $_SESSION['success'] = 'Cập nhật phòng ban thành công';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'departments');
                exit;
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'departments/edit/' . $id);
                exit;
            }
        }
        
        header('Location: ' . $GLOBALS['config']['base_url'] . 'departments/edit/' . $id);
        exit;
    }
    
    public function delete($id)
    {
        $departmentModel = $this->model('Department');
        $department = $departmentModel->getDepartmentById($id);
        
        if (!$department) {
            $_SESSION['error'] = 'Không tìm thấy phòng ban';
            header('Location: ' . $GLOBALS['config']['base_url'] . 'departments');
            exit;
        }
        
        $success = $departmentModel->deleteDepartment($id);
        
        if ($success) {
            $_SESSION['success'] = 'Xóa phòng ban thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
        }
        
        header('Location: ' . $GLOBALS['config']['base_url'] . 'departments');
        exit;
    }
    
    public function members($id)
    {
        $departmentModel = $this->model('Department');
        $department = $departmentModel->getDepartmentById($id);
        
        if (!$department) {
            $_SESSION['error'] = 'Không tìm thấy phòng ban';
            header('Location: ' . $GLOBALS['config']['base_url'] . 'departments');
            exit;
        }
        
        $members = $departmentModel->getDepartmentUsers($id);
        $userModel = $this->model('User');
        $availableUsers = $userModel->getUsersNotInDepartment($id);
        
        $data = [
            'title' => 'Quản lý thành viên phòng ban: ' . $department['name'],
            'department' => $department,
            'members' => $members,
            'availableUsers' => $availableUsers
        ];
        
        $this->view('templates/header', $data);
        $this->view('admin/departments/members', $data);
    }
    
    public function addMember($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'] ?? '';
            $autoLeader = isset($_POST['auto_leader']) ? $_POST['auto_leader'] : 0;
            
            if (empty($userId)) {
                $_SESSION['error'] = 'Vui lòng chọn người dùng';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'departments/members/' . $id);
                exit;
            }
            
            $departmentModel = $this->model('Department');
            $department = $departmentModel->getDepartmentById($id);
            
            if (!$department) {
                $_SESSION['error'] = 'Không tìm thấy phòng ban';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'departments');
                exit;
            }
            
            // Check if user is lãnh đạo to automatically set as leader
            $userModel = $this->model('User');
            $user = $userModel->getUserById($userId);
            
            // If user is lãnh đạo or auto_leader is set, they will be leader
            $isLeader = ($user && $user['role'] === 'lanh_dao') || $autoLeader == 1 ? 1 : 0;
            
            $success = $departmentModel->addUserToDepartment($userId, $id, $isLeader);
            
            if ($success) {
                $_SESSION['success'] = 'Thêm thành viên vào phòng ban thành công';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
            }
            
            header('Location: ' . $GLOBALS['config']['base_url'] . 'departments/members/' . $id);
            exit;
        }
        
        header('Location: ' . $GLOBALS['config']['base_url'] . 'departments/members/' . $id);
        exit;
    }
    
    public function updateMember($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'] ?? '';
            
            if (empty($userId)) {
                $_SESSION['error'] = 'Thông tin không hợp lệ';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'departments/members/' . $id);
                exit;
            }
            
            $departmentModel = $this->model('Department');
            $department = $departmentModel->getDepartmentById($id);
            
            if (!$department) {
                $_SESSION['error'] = 'Không tìm thấy phòng ban';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'departments');
                exit;
            }
            
            $userModel = $this->model('User');
            $user = $userModel->getUserById($userId);
            
            $isLeader = ($user && $user['role'] === 'lanh_dao') ? 1 : 0;
            
            $success = $departmentModel->addUserToDepartment($userId, $id, $isLeader);
            
            if ($success) {
                $_SESSION['success'] = 'Cập nhật thành viên thành công';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
            }
            
            header('Location: ' . $GLOBALS['config']['base_url'] . 'departments/members/' . $id);
            exit;
        }
        
        header('Location: ' . $GLOBALS['config']['base_url'] . 'departments/members/' . $id);
        exit;
    }
    
    public function removeMember($id, $userId)
    {
        $departmentModel = $this->model('Department');
        $department = $departmentModel->getDepartmentById($id);
        
        if (!$department) {
            $_SESSION['error'] = 'Không tìm thấy phòng ban';
            header('Location: ' . $GLOBALS['config']['base_url'] . 'departments');
            exit;
        }
        
        $userModel = $this->model('User');
        $user = $userModel->getUserById($userId);
        
        if (!$user) {
            $_SESSION['error'] = 'Không tìm thấy người dùng';
            header('Location: ' . $GLOBALS['config']['base_url'] . 'departments/members/' . $id);
            exit;
        }
        
        $success = $departmentModel->removeUserFromDepartment($userId, $id);
        
        if ($success) {
            $_SESSION['success'] = 'Xóa thành viên khỏi phòng ban thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
        }
        
        header('Location: ' . $GLOBALS['config']['base_url'] . 'departments/members/' . $id);
        exit;
    }
} 