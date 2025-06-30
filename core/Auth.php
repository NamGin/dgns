<?php
namespace Core;

class Auth
{
    /**
     * Check if user is logged in
     *
     * @return bool
     */
    public static function check()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['user']);
    }
    
    /**
     * Get the current authenticated user
     *
     * @return array|null
     */
    public static function user()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        return $_SESSION['user'] ?? null;
    }
    
    /**
     * Check if user has specific role
     *
     * @param string|array $roles
     * @return bool
     */
    public static function hasRole($roles)
    {
        if (!self::check()) {
            return false;
        }
        
        $user = self::user();
        if (is_string($roles)) {
            return $user['role'] === $roles;
        }
        if (is_array($roles)) {
            return in_array($user['role'], $roles);
        }
        
        return false;
    }
    

    public static function requireAuth()
    {
        if (!self::check()) {
            header('Location: ' . $GLOBALS['config']['base_url'] . 'login');
            exit;
        }
    }
    

    public static function requireRole($roles, $redirect = null)
    {
        self::requireAuth();
        if (!self::hasRole($roles)) {
            if ($redirect) {
                header('Location: ' . $GLOBALS['config']['base_url'] . $redirect);
            } else {
                $user = self::user();
                $baseUrl = $GLOBALS['config']['base_url'];
                
                switch ($user['role']) {
                    case 'nhan_vien':
                        header('Location: ' . $baseUrl . 'form-danh-gia');
                        break;
                    case 'lanh_dao':
                        header('Location: ' . $baseUrl . 'lanh-dao-review');
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
            exit;
        }
    }
} 