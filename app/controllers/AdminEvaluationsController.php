<?php
namespace App\Controllers;

use Core\Auth;

class AdminEvaluationsController extends \Core\Controller
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Handle review submissions from all roles (manager, deputy director, director)
     */
    public function saveReview($id)
    {
        // Verify user is logged in
        Auth::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $GLOBALS['config']['base_url'] . 'dashboard');
            exit;
        }
        
        // Get evaluation data
        $evaluationModel = $this->model('Evaluation');
        $evaluation = $evaluationModel->getEvaluationById($id);
        
        if (!$evaluation) {
            $_SESSION['error'] = 'Không tìm thấy bản đánh giá';
            header('Location: ' . $GLOBALS['config']['base_url'] . 'dashboard');
            exit;
        }
        
        // Get post data
        $reviewType = $_POST['review_type'] ?? '';
        $comment = $_POST['comment'] ?? '';
        $criteriaScores = $_POST['criteria'] ?? [];
        
        // Validate required fields
        if (empty($comment) || empty($criteriaScores)) {
            $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin đánh giá và nhận xét';
            $this->redirectBasedOnRole($reviewType, $id);
        }
        
        // Process review based on user role
        $userRole = $_SESSION['user']['role'] ?? '';
        $success = false;
        $redirectUrl = 'dashboard';
        
        switch ($reviewType) {
            case 'manager':
                // Verify user is a manager
                if ($userRole !== 'manager') {
                    $_SESSION['error'] = 'Bạn không có quyền thực hiện hành động này';
                    header('Location: ' . $GLOBALS['config']['base_url'] . 'dashboard');
                    exit;
                }
                
                // Verify evaluation status
                if ($evaluation['status'] !== 'sent') {
                    $_SESSION['error'] = 'Bản đánh giá không ở trạng thái chờ duyệt';
                    header('Location: ' . $GLOBALS['config']['base_url'] . 'lanh-dao-review');
                    exit;
                }
                
                // Save manager score
                $managerScore = json_encode($criteriaScores, JSON_UNESCAPED_UNICODE);
                $success = $evaluationModel->updateStatusAndManagerScoreAndComment($id, 'reviewed', $managerScore, $comment);
                $redirectUrl = 'lanh-dao-review';
                break;
                
            case 'deputy_director':
                // Verify user is a deputy director
                if ($userRole !== 'deputy_director') {
                    $_SESSION['error'] = 'Bạn không có quyền thực hiện hành động này';
                    header('Location: ' . $GLOBALS['config']['base_url'] . 'dashboard');
                    exit;
                }
                
                // Verify evaluation status
                if ($evaluation['status'] !== 'reviewed') {
                    $_SESSION['error'] = 'Bản đánh giá không ở trạng thái chờ phó giám đốc duyệt';
                    header('Location: ' . $GLOBALS['config']['base_url'] . 'pho-giam-doc-xem');
                    exit;
                }
                
                // Save deputy director score
                $deputyDirectorScore = json_encode($criteriaScores, JSON_UNESCAPED_UNICODE);
                $success = $evaluationModel->updateStatusAndDeputyDirectorScoreAndComment($id, 'deputy_reviewed', $deputyDirectorScore, $comment);
                $redirectUrl = 'pho-giam-doc-xem';
                break;
                
            case 'director':
                // Verify user is a director
                if ($userRole !== 'director') {
                    $_SESSION['error'] = 'Bạn không có quyền thực hiện hành động này';
                    header('Location: ' . $GLOBALS['config']['base_url'] . 'dashboard');
                    exit;
                }
                
                // Verify evaluation status is either after manager or deputy director review
                if ($evaluation['status'] !== 'reviewed' && $evaluation['status'] !== 'deputy_reviewed') {
                    $_SESSION['error'] = 'Bản đánh giá không ở trạng thái chờ giám đốc duyệt';
                    header('Location: ' . $GLOBALS['config']['base_url'] . 'giam-doc-xem');
                    exit;
                }
                
                // Save director score
                $directorScore = json_encode($criteriaScores, JSON_UNESCAPED_UNICODE);
                $success = $evaluationModel->updateStatusAndDirectorScoreAndComment($id, 'approved', $directorScore, $comment);
                $redirectUrl = 'giam-doc-xem';
                break;
                
            default:
                $_SESSION['error'] = 'Loại đánh giá không hợp lệ';
                header('Location: ' . $GLOBALS['config']['base_url'] . 'dashboard');
                exit;
        }
        
        if ($success) {
            $_SESSION['success'] = 'Đã lưu đánh giá thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi lưu đánh giá';
        }
        
        header('Location: ' . $GLOBALS['config']['base_url'] . $redirectUrl);
        exit;
    }
    
    /**
     * Helper method to redirect based on role
     */
    private function redirectBasedOnRole($reviewType, $id = null)
    {
        $redirectUrls = [
            'manager' => 'lanh-dao-review',
            'deputy_director' => 'pho-giam-doc-xem',
            'director' => 'giam-doc-xem'
        ];
        
        $redirectUrl = $redirectUrls[$reviewType] ?? 'dashboard';
        $redirectUrl = $GLOBALS['config']['base_url'] . $redirectUrl;
        
        if ($id !== null) {
            $redirectUrl .= '/' . $id;
        }
        
        header('Location: ' . $redirectUrl);
        exit;
    }
} 