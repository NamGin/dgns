<?php
namespace App\Controllers;

use Core\Auth;

class AdminController extends \Core\Controller
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        Auth::requireRole('admin');
    }
    
    public function userList()
    {
        $userModel = $this->model('User');
        
        $role = isset($_GET['role']) && !empty($_GET['role']) ? $_GET['role'] : null;
        $search = isset($_GET['search']) && !empty($_GET['search']) ? $_GET['search'] : null;
        $departmentId = isset($_GET['department_id']) && !empty($_GET['department_id']) ? $_GET['department_id'] : null;
        
        if ($role || $search || $departmentId) {
            $users = $userModel->getFilteredUsers($role, $search, $departmentId);
        } else {
            $users = $userModel->getAllUsers();
        }
        
        $departmentModel = $this->model('Department');
        $departments = $departmentModel->getAllDepartments();
        
        $data = [
            'title' => 'Quản lý tài khoản',
            'users' => $users,
            'departments' => $departments,
            'filter' => [
                'role' => $role,
                'search' => $search,
                'department_id' => $departmentId
            ]
        ];
        
        $this->view('templates/header', $data);
        $this->view('admin/users/index', $data);
    }
    
    public function userCreateForm()
    {
        $data = [
            'title' => 'Thêm tài khoản mới'
        ];
        
        $this->view('templates/header', $data);
        $this->view('admin/users/create', $data);
    }
    
    public function userStore()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? '';
            $departmentIds = $_POST['department_ids'] ?? [];
            
            if (empty($name) || empty($email) || empty($password) || empty($role)) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/users/create');
                exit;
            }
            
            $userModel = $this->model('User');
            
            $existingUser = $userModel->getUserByEmail($email);
            if ($existingUser) {
                $_SESSION['error'] = 'Email đã tồn tại trong hệ thống';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/users/create');
                exit;
            }
            
            $userId = $userModel->createUser($name, $email, $password, $role);
            
            if ($userId) {
                // Thêm người dùng vào các phòng ban đã chọn
                if (!empty($departmentIds)) {
                    $departmentModel = $this->model('Department');
                    foreach ($departmentIds as $deptId) {
                        // Automatically set user as leader if they have lanh_dao role
                        $isLeader = ($role === 'lanh_dao') ? 1 : 0;
                        $departmentModel->addUserToDepartment($userId, $deptId, $isLeader);
                    }
                }
                
                $_SESSION['success'] = 'Thêm tài khoản thành công';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/users');
                exit;
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/users/create');
                exit;
            }
        }
        
        header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/users/create');
        exit;
    }
    
    public function userEditForm($id)
    {
        $userModel = $this->model('User');
        $user = $userModel->getUserById($id);
        
        if (!$user) {
            $_SESSION['error'] = 'Không tìm thấy tài khoản';
            header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/users');
            exit;
        }
        
        $data = [
            'title' => 'Chỉnh sửa tài khoản',
            'user' => $user
        ];
        
        $this->view('templates/header', $data);
        $this->view('admin/users/edit', $data);
    }
    
    public function userUpdate($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $role = $_POST['role'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($name) || empty($email) || empty($role)) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/users/edit/' . $id);
                exit;
            }
            
            $userModel = $this->model('User');
            $currentUser = $userModel->getUserById($id);
            
            if (!$currentUser) {
                $_SESSION['error'] = 'Không tìm thấy tài khoản';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/users');
                exit;
            }
            
            $existingUser = $userModel->getUserByEmail($email);
            if ($existingUser && $existingUser['id'] != $id) {
                $_SESSION['error'] = 'Email đã tồn tại trong hệ thống';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/users/edit/' . $id);
                exit;
            }
            
            $success = $userModel->updateUser($id, $name, $email, $role);
            
            if (!empty($password)) {
                $userModel->updatePassword($id, $password);
            }
            
            if ($success && $currentUser['role'] !== $role) {
                $departmentModel = $this->model('Department');
                $userDepartments = $departmentModel->getUserDepartments($id);
                
                if (!empty($userDepartments)) {
                    foreach ($userDepartments as $dept) {
                        $isLeader = ($role === 'lanh_dao') ? 1 : 0;
                        $departmentModel->addUserToDepartment($id, $dept['id'], $isLeader);
                    }
                }
            }
            
            if ($success) {
                $_SESSION['success'] = 'Cập nhật tài khoản thành công';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/users');
                exit;
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/users/edit/' . $id);
                exit;
            }
        }
        
        header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/users/edit/' . $id);
        exit;
    }
    
    public function userDelete($id)
    {
        $userModel = $this->model('User');
        $user = $userModel->getUserById($id);
        
        if (!$user) {
            $_SESSION['error'] = 'Không tìm thấy tài khoản';
            header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/users');
            exit;
        }
        
        if ($user['id'] == Auth::user()['id']) {
            $_SESSION['error'] = 'Không thể xóa tài khoản đang đăng nhập';
            header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/users');
            exit;
        }
        
        $evaluationModel = $this->model('Evaluation');
        $evaluations = $evaluationModel->getEvaluationsByEmployeeId($id);
        
        if (!empty($evaluations)) {
            $_SESSION['error'] = 'Không thể xóa tài khoản đã có đánh giá';
            header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/users');
            exit;
        }
        
        $success = $userModel->deleteUser($id);
        
        if ($success) {
            $_SESSION['success'] = 'Xóa tài khoản thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
        }
        
        header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/users');
        exit;
    }
    
    public function userDetail($id)
    {
        $userModel = $this->model('User');
        $user = $userModel->getUserWithDepartments($id);
        
        if (!$user) {
            $_SESSION['error'] = 'Không tìm thấy tài khoản';
            header('Location: ' . $GLOBALS['config']['base_url'] . 'admin/users');
            exit;
        }
        
        $data = [
            'title' => 'Chi tiết tài khoản: ' . $user['name'],
            'user' => $user
        ];
        
        $this->view('templates/header', $data);
        $this->view('admin/users/detail', $data);
    }
} 