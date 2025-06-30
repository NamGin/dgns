<?php
namespace App\Controllers;

class AuthController extends \Core\Controller
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function loginForm()
    {
        if (isset($_SESSION['user'])) {
            $this->redirectBasedOnRole();
            exit;
        }
        
        $data = [
            'title' => 'Đăng nhập'
        ];
        
        $this->view('templates/header', $data);
        $this->view('auth/login', $data);
    }
    
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            if (empty($email) || empty($password)) {
                $_SESSION['error'] = 'Vui lòng nhập email và mật khẩu';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'login');
                exit;
            }
            
            $userModel = $this->model('User');
            $user = $userModel->getUserByEmail($email);
            
            if ($user && $userModel->verifyPassword($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ];
                
                $this->redirectBasedOnRole();
                exit;
            } else {
                $_SESSION['error'] = 'Email hoặc mật khẩu không đúng';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'login');
                exit;
            }
        }
        
        header('Location: ' . $GLOBALS['config']['base_url'] . 'login');
        exit;
    }
    
    /**
     * Logout user
     */
    public function logout()
    {
        session_destroy();
        
        header('Location: ' . $GLOBALS['config']['base_url'] . 'login');
        exit;
    }
    
    /**
     * Redirect based on user role
     */
    private function redirectBasedOnRole()
    {
        $baseUrl = $GLOBALS['config']['base_url'];
        
        switch ($_SESSION['user']['role']) {
            case 'nhan_vien':
                header('Location: ' . $baseUrl . 'form-danh-gia');
                break;
            case 'lanh_dao':
                header('Location: ' . $baseUrl . 'lanh-dao-review');
                break;
            case 'pho_giam_doc':
                header('Location: ' . $baseUrl . 'pho-giam-doc-xem');
                break;
            case 'giam_doc':
                header('Location: ' . $baseUrl . 'giam-doc-xem');
                break;
            case 'admin':
                header('Location: ' . $baseUrl . 'admin/users');
                break;
            default:
                header('Location: ' . $baseUrl);
                break;
        }
    }
}
