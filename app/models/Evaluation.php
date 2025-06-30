<?php
namespace App\Models;

class Evaluation extends \Core\Model
{
    /**
     * Get all evaluations
     */
    public function getAllEvaluations()
    {
        $stmt = $this->db->query(
            "SELECT e.*, u.name as employee_name, u.email as employee_email, d.name as department_name
            FROM evaluations e
            JOIN users u ON e.employee_id = u.id
            LEFT JOIN departments d ON e.department_id = d.id
            ORDER BY e.created_at DESC"
        );
        return $stmt->fetchAll();
    }
    
    /**
     * Get evaluation by ID
     */
    public function getEvaluationById($id)
    {
        $stmt = $this->db->prepare(
            "SELECT e.*, u.name as employee_name, u.email as employee_email, d.name as department_name
            FROM evaluations e
            JOIN users u ON e.employee_id = u.id
            LEFT JOIN departments d ON e.department_id = d.id
            WHERE e.id = :id"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Get evaluations by employee ID
     */
    public function getEvaluationsByEmployeeId($employeeId)
    {
        $stmt = $this->db->prepare(
            "SELECT e.*, d.name as department_name
            FROM evaluations e
            LEFT JOIN departments d ON e.department_id = d.id
            WHERE e.employee_id = :employee_id
            ORDER BY e.created_at DESC"
        );
        $stmt->execute(['employee_id' => $employeeId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get evaluations by status
     */
    public function getEvaluationsByStatus($status)
    {
        $stmt = $this->db->prepare(
            "SELECT e.*, u.name as employee_name, d.name as department_name
            FROM evaluations e
            JOIN users u ON e.employee_id = u.id
            LEFT JOIN departments d ON e.department_id = d.id
            WHERE e.status = :status
            ORDER BY e.created_at DESC"
        );
        $stmt->execute(['status' => $status]);
        return $stmt->fetchAll();
    }

    /**
     * Get evaluations for department leader
     */
    public function getEvaluationsForDepartmentLeader($userId)
    {
        $stmt = $this->db->prepare(
            "SELECT e.*, u.name as employee_name, d.name as department_name
            FROM evaluations e
            JOIN users u ON e.employee_id = u.id
            JOIN departments d ON e.department_id = d.id
            JOIN user_departments ud ON d.id = ud.department_id AND ud.user_id = :user_id AND ud.is_leader = 1
            WHERE e.status = 'sent'
            ORDER BY e.created_at DESC"
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Create a new evaluation
     */
    public function createEvaluation($employeeId, $content, $departmentId = null)
    {
        $contentData = json_decode($content, true);
        $data = json_encode($contentData['criteria'] ?? [], JSON_UNESCAPED_UNICODE);
        
        $stmt = $this->db->prepare(
            "INSERT INTO evaluations (employee_id, department_id, content, data, status)
            VALUES (:employee_id, :department_id, :content, :data, 'sent')"
        );
        return $stmt->execute([
            'employee_id' => $employeeId,
            'department_id' => $departmentId,
            'content' => $content,
            'data' => $data
        ]);
    }
    
    /**
     * Create a new evaluation for manager that is auto-approved
     */
    public function createManagerEvaluation($employeeId, $content, $departmentId = null)
    {
        $contentData = json_decode($content, true);
        $data = json_encode($contentData['criteria'] ?? [], JSON_UNESCAPED_UNICODE);
        
        $stmt = $this->db->prepare(
            "INSERT INTO evaluations (employee_id, department_id, content, data, status)
            VALUES (:employee_id, :department_id, :content, :data, 'reviewed')"
        );
        return $stmt->execute([
            'employee_id' => $employeeId,
            'department_id' => $departmentId,
            'content' => $content,
            'data' => $data
        ]);
    }
    
    /**
     * Update evaluation status
     */
    public function updateStatus($id, $status)
    {
        $stmt = $this->db->prepare(
            "UPDATE evaluations
            SET status = :status
            WHERE id = :id"
        );
        return $stmt->execute([
            'id' => $id,
            'status' => $status
        ]);
    }
    
    /**
     * Update manager comment
     */
    public function updateManagerComment($id, $comment)
    {
        $stmt = $this->db->prepare(
            "UPDATE evaluations
            SET manager_comment = :comment
            WHERE id = :id"
        );
        return $stmt->execute([
            'id' => $id,
            'comment' => $comment
        ]);
    }
    
    /**
     * Update director comment
     */
    public function updateDirectorComment($id, $comment)
    {
        $stmt = $this->db->prepare(
            "UPDATE evaluations
            SET director_comment = :comment
            WHERE id = :id"
        );
        return $stmt->execute([
            'id' => $id,
            'comment' => $comment
        ]);
    }

    /**
     * Update status and manager comment
     */
    public function updateStatusAndManagerComment($id, $status, $comment)
    {
        $stmt = $this->db->prepare(
            "UPDATE evaluations
            SET status = :status, manager_comment = :comment
            WHERE id = :id"
        );
        return $stmt->execute([
            'id' => $id,
            'status' => $status,
            'comment' => $comment
        ]);
    }
    
    /**
     * Get count of evaluations by status
     */
    public function getCountByStatus($status)
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as count
            FROM evaluations
            WHERE status = :status"
        );
        $stmt->execute(['status' => $status]);
        $result = $stmt->fetch();
        return $result['count'];
    }

    /**
     * Update director comment and approve evaluation
     */
    public function updateDirectorCommentAndApprove($id, $comment)
    {
        $stmt = $this->db->prepare(
            "UPDATE evaluations
            SET director_comment = :comment, status = 'approved'
            WHERE id = :id"
        );
        return $stmt->execute([
            'id' => $id,
            'comment' => $comment
        ]);
    }

    /**
     * Get evaluations by department ID
     */
    public function getEvaluationsByDepartmentId($departmentId)
    {
        $stmt = $this->db->prepare(
            "SELECT e.*, u.name as employee_name, u.email as employee_email
            FROM evaluations e
            JOIN users u ON e.employee_id = u.id
            WHERE e.department_id = :department_id
            ORDER BY e.created_at DESC"
        );
        $stmt->execute(['department_id' => $departmentId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get form for department
     */
    public function getFormForDepartment($departmentId, $formType = null)
    {
        // Đầu tiên tìm form riêng cho phòng ban
        $formModel = new \App\Models\EvaluationForm();
        $form = null;
        
        if ($formType) {
            // Nếu có formType, tìm form cụ thể cho phòng ban và loại form
            $form = $formModel->getFormByDepartmentId($departmentId, $formType);
            
            // Nếu không có form riêng, sử dụng form mặc định cho loại form đó
            if (!$form) {
                $form = $formModel->getDefaultForm($formType);
                
                // Nếu vẫn không tìm thấy form mặc định, lấy từ data.php
                if (!$form) {
                    require_once __DIR__ . '/../../data.php';
                    if ($formType === 'nhan_vien' && isset($dataRate['nhan_vien'])) {
                        // Tạo một đối tượng form giả với nội dung từ $dataRate
                        $form = [
                            'id' => null,
                            'content' => json_encode($dataRate['nhan_vien'], JSON_UNESCAPED_UNICODE)
                        ];
                    } elseif ($formType === 'lanh_dao' && isset($dataRate['lanh_dao'])) {
                        $form = [
                            'id' => null,
                            'content' => json_encode($dataRate['lanh_dao'], JSON_UNESCAPED_UNICODE)
                        ];
                    }
                }
            }
        } else {
            // Nếu không có formType, lấy form đầu tiên cho phòng ban
            $form = $formModel->getFormByDepartmentId($departmentId);
            
            // Nếu không có form riêng, sử dụng form mặc định 'nhan_vien'
            if (!$form) {
                $form = $formModel->getDefaultForm('nhan_vien');
                
                // Nếu vẫn không tìm thấy form mặc định, lấy từ data.php
                if (!$form) {
                    require_once __DIR__ . '/../../data.php';
                    if (isset($dataRate['nhan_vien'])) {
                        // Tạo một đối tượng form giả với nội dung từ $dataRate
                        $form = [
                            'id' => null,
                            'content' => json_encode($dataRate['nhan_vien'], JSON_UNESCAPED_UNICODE)
                        ];
                    }
                }
            }
        }
        
        return $form;
    }
    
    /**
     * Cập nhật evaluationFormId khi tạo đánh giá mới
     */
    public function createEvaluationWithForm($employeeId, $content, $departmentId = null)
    {
        $contentData = json_decode($content, true);
        $data = json_encode($contentData['criteria'] ?? [], JSON_UNESCAPED_UNICODE);
        
        // Lấy form đánh giá cho phòng ban
        $evaluationFormId = null;
        if ($departmentId) {
            $form = $this->getFormForDepartment($departmentId, 'nhan_vien');
            if ($form) {
                $evaluationFormId = $form['id'];
            }
        }
        
        $stmt = $this->db->prepare(
            "INSERT INTO evaluations (employee_id, department_id, evaluation_form_id, content, data, status)
            VALUES (:employee_id, :department_id, :evaluation_form_id, :content, :data, 'sent')"
        );
        return $stmt->execute([
            'employee_id' => $employeeId,
            'department_id' => $departmentId,
            'evaluation_form_id' => $evaluationFormId,
            'content' => $content,
            'data' => $data
        ]);
    }
    
    /**
     * Tạo đánh giá cho lãnh đạo với form theo phòng ban
     */
    public function createManagerEvaluationWithForm($employeeId, $content, $departmentId = null)
    {
        $contentData = json_decode($content, true);
        $data = json_encode($contentData['criteria'] ?? [], JSON_UNESCAPED_UNICODE);
        
        // Lấy form đánh giá cho phòng ban
        $evaluationFormId = null;
        if ($departmentId) {
            $form = $this->getFormForDepartment($departmentId, 'lanh_dao');
            if ($form) {
                $evaluationFormId = $form['id'];
            }
        }
        
        $stmt = $this->db->prepare(
            "INSERT INTO evaluations (employee_id, department_id, evaluation_form_id, content, data, status)
            VALUES (:employee_id, :department_id, :evaluation_form_id, :content, :data, 'reviewed')"
        );
        return $stmt->execute([
            'employee_id' => $employeeId,
            'department_id' => $departmentId,
            'evaluation_form_id' => $evaluationFormId,
            'content' => $content,
            'data' => $data
        ]);
    }

    /**
     * Update deputy director comment
     */
    public function updateDeputyDirectorComment($id, $comment)
    {
        $stmt = $this->db->prepare(
            "UPDATE evaluations
            SET deputy_director_comment = :comment
            WHERE id = :id"
        );
        return $stmt->execute([
            'id' => $id,
            'comment' => $comment
        ]);
    }

    /**
     * Update status and deputy director comment
     */
    public function updateStatusAndDeputyDirectorComment($id, $status, $comment)
    {
        $stmt = $this->db->prepare(
            "UPDATE evaluations
            SET status = :status, deputy_director_comment = :comment
            WHERE id = :id"
        );
        return $stmt->execute([
            'id' => $id,
            'status' => $status,
            'comment' => $comment
        ]);
    }
    
    /**
     * Get evaluations for deputy director review
     */
    public function getEvaluationsForDeputyDirector()
    {
        $stmt = $this->db->prepare(
            "SELECT e.*, u.name as employee_name, d.name as department_name
            FROM evaluations e
            JOIN users u ON e.employee_id = u.id
            LEFT JOIN departments d ON e.department_id = d.id
            WHERE e.status IN ('reviewed', 'deputy_reviewed')
            ORDER BY e.status ASC, e.created_at DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Update status and manager comment with score
     */
    public function updateStatusAndManagerScoreAndComment($id, $status, $score, $comment)
    {
        $stmt = $this->db->prepare(
            "UPDATE evaluations
            SET status = :status, manager_score = :score, manager_comment = :comment
            WHERE id = :id"
        );
        return $stmt->execute([
            'id' => $id,
            'status' => $status,
            'score' => $score,
            'comment' => $comment
        ]);
    }
    
    /**
     * Update status and deputy director score and comment
     */
    public function updateStatusAndDeputyDirectorScoreAndComment($id, $status, $score, $comment)
    {
        $stmt = $this->db->prepare(
            "UPDATE evaluations
            SET status = :status, deputy_director_score = :score, deputy_director_comment = :comment
            WHERE id = :id"
        );
        return $stmt->execute([
            'id' => $id,
            'status' => $status,
            'score' => $score,
            'comment' => $comment
        ]);
    }

    /**
     * Update status and director score and comment
     */
    public function updateStatusAndDirectorScoreAndComment($id, $status, $score, $comment)
    {
        $stmt = $this->db->prepare(
            "UPDATE evaluations
            SET status = :status, director_score = :score, director_comment = :comment
            WHERE id = :id"
        );
        return $stmt->execute([
            'id' => $id,
            'status' => $status,
            'score' => $score,
            'comment' => $comment
        ]);
    }

    /**
     * Get evaluations by status and department for manager
     */
    public function getEvaluationsByStatusAndDepartment($status, $userId)
    {
        $stmt = $this->db->prepare(
            "SELECT e.*, u.name as employee_name, d.name as department_name
            FROM evaluations e
            JOIN users u ON e.employee_id = u.id
            JOIN departments d ON e.department_id = d.id
            JOIN user_departments ud ON d.id = ud.department_id AND ud.user_id = :user_id AND ud.is_leader = 1
            WHERE e.status = :status
            ORDER BY e.created_at DESC"
        );
        $stmt->execute([
            'status' => $status,
            'user_id' => $userId
        ]);
        return $stmt->fetchAll();
    }

    public function updateExtraDeduction($id, $extraDeduction)
    {
        $stmt = $this->db->prepare(
            "UPDATE evaluations SET extra_deduction = :extra_deduction WHERE id = :id"
        );
        return $stmt->execute([
            'id' => $id,
            'extra_deduction' => $extraDeduction
        ]);
    }
    // Thay thế 3 hàm update*Rescore như sau:

public function updateLeaderRescore($id, $rescore)
{
    $total = array_sum(array_map('floatval', $rescore));
    // Lấy điểm bị trừ bổ sung
    $stmt = $this->db->prepare("SELECT extra_deduction FROM evaluations WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $extraDeduction = $stmt->fetchColumn() ?: 0;
    $final = $total - floatval($extraDeduction);

    $stmt = $this->db->prepare(
        "UPDATE evaluations
        SET leader_rescore = :rescore,
            leader_rescore_total = :total,
            leader_rescore_final = :final
        WHERE id = :id"
    );
    return $stmt->execute([
        'id' => $id,
        'rescore' => json_encode($rescore, JSON_UNESCAPED_UNICODE),
        'total' => $total,
        'final' => $final
    ]);
}

public function updateDeputyDirectorRescore($id, $rescore)
{
    $total = array_sum(array_map('floatval', $rescore));
    $stmt = $this->db->prepare("SELECT extra_deduction FROM evaluations WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $extraDeduction = $stmt->fetchColumn() ?: 0;
    $final = $total - floatval($extraDeduction);

    $stmt = $this->db->prepare(
        "UPDATE evaluations
        SET deputy_director_rescore = :rescore,
            deputy_director_rescore_total = :total,
            deputy_director_rescore_final = :final
        WHERE id = :id"
    );
    return $stmt->execute([
        'id' => $id,
        'rescore' => json_encode($rescore, JSON_UNESCAPED_UNICODE),
        'total' => $total,
        'final' => $final
    ]);
}

public function updateDirectorRescore($id, $rescore)
{
    $total = array_sum(array_map('floatval', $rescore));
    $stmt = $this->db->prepare("SELECT extra_deduction FROM evaluations WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $extraDeduction = $stmt->fetchColumn() ?: 0;
    $final = $total - floatval($extraDeduction);

    $stmt = $this->db->prepare(
        "UPDATE evaluations
        SET director_rescore = :rescore,
            director_rescore_total = :total,
            director_rescore_final = :final
        WHERE id = :id"
    );
    return $stmt->execute([
        'id' => $id,
        'rescore' => json_encode($rescore, JSON_UNESCAPED_UNICODE),
        'total' => $total,
        'final' => $final
    ]);
}
}