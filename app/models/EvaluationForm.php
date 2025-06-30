<?php
namespace App\Models;

class EvaluationForm extends \Core\Model
{
    /**
     * Lấy tất cả form đánh giá
     */
    public function getAllForms()
    {
        $stmt = $this->db->query(
            "SELECT ef.*, d.name as department_name, 
            CASE 
                WHEN ef.form_type = 'lanh_dao' THEN 'Lãnh đạo'
                WHEN ef.form_type = 'nhan_vien' THEN 'Chuyên viên'
                ELSE '' 
            END as form_type_name
            FROM evaluation_forms ef
            LEFT JOIN departments d ON ef.department_id = d.id
            ORDER BY ef.created_at DESC"
        );
        return $stmt->fetchAll();
    }
    
    /**
     * Lấy form đánh giá theo ID
     */
    public function getFormById($id)
    {
        $stmt = $this->db->prepare(
            "SELECT ef.*, d.name as department_name
            FROM evaluation_forms ef
            LEFT JOIN departments d ON ef.department_id = d.id
            WHERE ef.id = :id"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Lấy form đánh giá theo phòng ban
     */
    public function getFormByDepartmentId($departmentId, $formType = null)
    {
        $sql = "SELECT ef.*, d.name as department_name
                FROM evaluation_forms ef
                LEFT JOIN departments d ON ef.department_id = d.id
                WHERE ef.department_id = :department_id";
        
        $params = ['department_id' => $departmentId];
        
        if ($formType) {
            $sql .= " AND ef.form_type = :form_type";
            $params['form_type'] = $formType;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        if ($formType) {
            return $stmt->fetch();
        } else {
            return $stmt->fetchAll();
        }
    }
    
    /**
     * Tạo form đánh giá mới
     */
    public function createForm($name, $departmentId, $content, $formType)
    {
        // Convert empty string to NULL for department_id
        if ($departmentId === '') {
            $departmentId = null;
        }
        
        $stmt = $this->db->prepare(
            "INSERT INTO evaluation_forms (name, department_id, content, form_type, created_at)
            VALUES (:name, :department_id, :content, :form_type, NOW())"
        );
        $success = $stmt->execute([
            'name' => $name,
            'department_id' => $departmentId,
            'content' => $content,
            'form_type' => $formType
        ]);
        
        if ($success) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Cập nhật form đánh giá
     */
    public function updateForm($id, $name, $departmentId, $content, $formType = null)
    {
        // Convert empty string to NULL for department_id
        if ($departmentId === '') {
            $departmentId = null;
        }
        
        // Nếu form_type không được truyền vào, chỉ cập nhật các trường khác
        if ($formType === null) {
            $stmt = $this->db->prepare(
                "UPDATE evaluation_forms
                SET name = :name, department_id = :department_id, content = :content, updated_at = NOW()
                WHERE id = :id"
            );
            return $stmt->execute([
                'id' => $id,
                'name' => $name,
                'department_id' => $departmentId,
                'content' => $content
            ]);
        } else {
            $stmt = $this->db->prepare(
                "UPDATE evaluation_forms
                SET name = :name, department_id = :department_id, content = :content, form_type = :form_type, updated_at = NOW()
                WHERE id = :id"
            );
            return $stmt->execute([
                'id' => $id,
                'name' => $name,
                'department_id' => $departmentId,
                'content' => $content,
                'form_type' => $formType
            ]);
        }
    }
    
    /**
     * Xóa form đánh giá
     */
    public function deleteForm($id)
    {
        $stmt = $this->db->prepare("DELETE FROM evaluation_forms WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Kiểm tra phòng ban đã có form đánh giá cho loại cụ thể (lãnh đạo hoặc chuyên viên)
     */
    public function departmentHasFormType($departmentId, $formType)
    {
        $stmt = $this->db->prepare(
            "SELECT id, COUNT(*) as count FROM evaluation_forms 
            WHERE department_id = :department_id AND form_type = :form_type
            GROUP BY id"
        );
        $stmt->execute([
            'department_id' => $departmentId,
            'form_type' => $formType
        ]);
        $result = $stmt->fetch();
        
        if (!$result) {
            return false;
        }
        
        return [
            'exists' => (int)$result['count'] > 0,
            'form_id' => $result['id']
        ];
    }
    
    /**
     * Lấy form đánh giá mặc định cho loại cụ thể (lãnh đạo hoặc chuyên viên)
     */
    public function getDefaultForm($formType)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM evaluation_forms 
            WHERE (department_id IS NULL OR department_id = 0) AND form_type = :form_type
            LIMIT 1"
        );
        $stmt->execute(['form_type' => $formType]);
        return $stmt->fetch();
    }
    
    /**
     * Lấy tất cả các form mẫu
     */
    public function getTemplates()
    {
        $stmt = $this->db->query(
            "SELECT * FROM evaluation_form_templates 
            ORDER BY name ASC"
        );
        return $stmt->fetchAll();
    }
    
    /**
     * Lấy form mẫu theo ID
     */
    public function getTemplateById($id)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM evaluation_form_templates WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Lưu form mẫu mới
     */
    public function saveTemplate($name, $content, $formType)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO evaluation_form_templates (name, content, form_type, created_at)
            VALUES (:name, :content, :form_type, NOW())"
        );
        $success = $stmt->execute([
            'name' => $name,
            'content' => $content,
            'form_type' => $formType
        ]);
        
        if ($success) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }
} 