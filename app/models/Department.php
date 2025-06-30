<?php
namespace App\Models;

class Department extends \Core\Model
{
    public function getAllDepartments()
    {
        $stmt = $this->db->query("SELECT * FROM departments ORDER BY name");
        return $stmt->fetchAll();
    }
    
    public function getDepartmentById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM departments WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    public function createDepartment($name, $description = '')
    {
        $stmt = $this->db->prepare("INSERT INTO departments (name, description) VALUES (:name, :description)");
        $success = $stmt->execute([
            'name' => $name,
            'description' => $description
        ]);
        
        if ($success) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }
    
    public function updateDepartment($id, $name, $description = '')
    {
        $stmt = $this->db->prepare("UPDATE departments SET name = :name, description = :description WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'name' => $name,
            'description' => $description
        ]);
    }
    
    public function deleteDepartment($id)
    {
        $stmt = $this->db->prepare("DELETE FROM departments WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    public function getDepartmentUsers($departmentId)
    {
        $sql = "SELECT u.*, ud.is_leader 
                FROM users u
                JOIN user_departments ud ON u.id = ud.user_id
                WHERE ud.department_id = :department_id
                ORDER BY ud.is_leader DESC, u.name";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['department_id' => $departmentId]);
        return $stmt->fetchAll();
    }
    
    public function getDepartmentLeaders($departmentId)
    {
        $sql = "SELECT u.* 
                FROM users u
                JOIN user_departments ud ON u.id = ud.user_id
                WHERE ud.department_id = :department_id AND ud.is_leader = 1
                ORDER BY u.name";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['department_id' => $departmentId]);
        return $stmt->fetchAll();
    }
    
    public function addUserToDepartment($userId, $departmentId, $isLeader = 0)
    {
        // Kiểm tra xem người dùng đã thuộc phòng ban này chưa
        $checkSql = "SELECT id FROM user_departments WHERE user_id = :user_id AND department_id = :department_id";
        $checkStmt = $this->db->prepare($checkSql);
        $checkStmt->execute([
            'user_id' => $userId,
            'department_id' => $departmentId
        ]);
        
        if ($checkStmt->rowCount() > 0) {
            // Cập nhật trạng thái là lãnh đạo
            $sql = "UPDATE user_departments SET is_leader = :is_leader WHERE user_id = :user_id AND department_id = :department_id";
        } else {
            // Thêm mới quan hệ
            $sql = "INSERT INTO user_departments (user_id, department_id, is_leader) VALUES (:user_id, :department_id, :is_leader)";
        }
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'user_id' => $userId,
            'department_id' => $departmentId,
            'is_leader' => $isLeader ? 1 : 0
        ]);
    }
    
    public function removeUserFromDepartment($userId, $departmentId)
    {
        $sql = "DELETE FROM user_departments WHERE user_id = :user_id AND department_id = :department_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'user_id' => $userId,
            'department_id' => $departmentId
        ]);
    }
    
    public function getUserDepartments($userId)
    {
        $sql = "SELECT d.*, ud.is_leader 
                FROM departments d
                JOIN user_departments ud ON d.id = ud.department_id
                WHERE ud.user_id = :user_id
                ORDER BY d.name";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }
    
    public function getDepartmentMemberCount($departmentId)
    {
        $sql = "SELECT COUNT(*) as count FROM user_departments WHERE department_id = :department_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['department_id' => $departmentId]);
        $result = $stmt->fetch();
        return $result['count'];
    }
    
    public function countDepartmentsForUser($userId)
    {
        $sql = "SELECT COUNT(*) as count FROM user_departments WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch();
        return $result['count'];
    }
    
    /**
     * Kiểm tra người dùng có thuộc phòng ban hay không
     */
    public function isUserInDepartment($userId, $departmentId)
    {
        $sql = "SELECT COUNT(*) as count FROM user_departments 
                WHERE user_id = :user_id AND department_id = :department_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'department_id' => $departmentId
        ]);
        $result = $stmt->fetch();
        return (int)$result['count'] > 0;
    }
    
    /**
     * Kiểm tra người dùng có phải là lãnh đạo của phòng ban hay không
     */
    public function isUserDepartmentLeader($userId, $departmentId)
    {
        $sql = "SELECT COUNT(*) as count FROM user_departments 
                WHERE user_id = :user_id AND department_id = :department_id AND is_leader = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'department_id' => $departmentId
        ]);
        $result = $stmt->fetch();
        return (int)$result['count'] > 0;
    }
} 