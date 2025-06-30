<?php
namespace App\Controllers;

use Core\Auth;

class ProfileController extends \Core\Controller
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Require authentication for all profile actions
        Auth::requireAuth();
    }
    
    /**
     * Show user profile
     */
    public function show()
    {
        $userId = Auth::user()['id'];
        $userModel = $this->model('User');
        $user = $userModel->getUserById($userId);
        
        $data = [
            'title' => 'Thông tin cá nhân',
            'user' => $user
        ];
        
        $this->view('templates/header', $data);
        $this->view('profile/show', $data);
    }
    
    /**
     * Show edit profile form
     */
    public function edit()
    {
        $userId = Auth::user()['id'];
        $userModel = $this->model('User');
        $user = $userModel->getUserById($userId);
        
        $data = [
            'title' => 'Chỉnh sửa thông tin cá nhân',
            'user' => $user
        ];
        
        $this->view('templates/header', $data);
        $this->view('profile/edit', $data);
    }
    
    /**
     * Update user profile
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = Auth::user()['id'];
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            
            if (empty($name) || empty($email)) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'profile/edit');
                exit;
            }
            
            $userModel = $this->model('User');
            $currentUser = $userModel->getUserById($userId);
            
            // Check if email exists and belongs to another user
            $existingUser = $userModel->getUserByEmail($email);
            if ($existingUser && $existingUser['id'] != $userId) {
                $_SESSION['error'] = 'Email đã tồn tại trong hệ thống';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'profile/edit');
                exit;
            }
            
            // Update user basic info (without changing role)
            $success = $userModel->updateUser($userId, $name, $email);
            
            if ($success) {
                // Update session data
                $_SESSION['user']['name'] = $name;
                $_SESSION['user']['email'] = $email;
                
                $_SESSION['success'] = 'Cập nhật thông tin thành công';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'profile');
                exit;
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'profile/edit');
                exit;
            }
        }
        
        header('Location: ' . $GLOBALS['config']['base_url'] . 'profile/edit');
        exit;
    }
    
    /**
     * Show change password form
     */
    public function changePasswordForm()
    {
        $data = [
            'title' => 'Đổi mật khẩu'
        ];
        
        $this->view('templates/header', $data);
        $this->view('profile/change_password', $data);
    }
    
    /**
     * Process change password request
     */
    public function changePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = Auth::user()['id'];
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'profile/change-password');
                exit;
            }
            
            if ($newPassword !== $confirmPassword) {
                $_SESSION['error'] = 'Mật khẩu mới không khớp';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'profile/change-password');
                exit;
            }
            
            $userModel = $this->model('User');
            $user = $userModel->getUserById($userId);
            
            // Verify current password
            if (!$userModel->verifyPassword($currentPassword, $user['password'])) {
                $_SESSION['error'] = 'Mật khẩu hiện tại không đúng';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'profile/change-password');
                exit;
            }
            
            // Update password
            $success = $userModel->updatePassword($userId, $newPassword);
            
            if ($success) {
                $_SESSION['success'] = 'Đổi mật khẩu thành công';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'profile');
                exit;
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'profile/change-password');
                exit;
            }
        }
        
        header('Location: ' . $GLOBALS['config']['base_url'] . 'profile/change-password');
        exit;
    }
} 