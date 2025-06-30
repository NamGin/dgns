<?php
namespace App\Models;

class User extends \Core\Model
{
    public function getAllUsers()
    {
        $stmt = $this->db->query("SELECT * FROM users ORDER BY name");
        return $stmt->fetchAll();
    }

    public function getFilteredUsers($role = null, $search = null, $departmentId = null)
    {
        if ($departmentId) {
            $sql = "SELECT DISTINCT u.* FROM users u 
                    JOIN user_departments ud ON u.id = ud.user_id 
                    WHERE ud.department_id = :department_id";
            $params = ['department_id' => $departmentId];
        } else {
            $sql = "SELECT * FROM users WHERE 1=1";
            $params = [];
        }
        
        if (!empty($role)) {
            $sql .= " AND role = :role";
            $params['role'] = $role;
        }
        
        if (!empty($search)) {
            $sql .= " AND (name LIKE :search_name OR email LIKE :search_email)";
            $params['search_name'] = '%' . $search . '%';
            $params['search_email'] = '%' . $search . '%';
        }
        
        $sql .= " ORDER BY name";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }
    
    public function getUserById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    public function getUserByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }
    
    public function getUsersByRole($role)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE role = :role ORDER BY name");
        $stmt->execute(['role' => $role]);
        return $stmt->fetchAll();
    }

    public function getUsersNotInDepartment($departmentId)
    {
        $sql = "SELECT * FROM users u 
                WHERE u.id NOT IN (
                    SELECT user_id FROM user_departments WHERE department_id = :department_id
                )
                ORDER BY u.name";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['department_id' => $departmentId]);
        return $stmt->fetchAll();
    }
    
    public function createUser($name, $email, $password, $role = 'nhan_vien')
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)");
        $success = $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => $role
        ]);

        if ($success) {
            return $this->db->lastInsertId();
        }

        return false;
    }
    
    public function updateUser($id, $name, $email, $role = null)
    {
        $params = [
            'id' => $id,
            'name' => $name,
            'email' => $email
        ];
        
        $sql = "UPDATE users SET name = :name, email = :email";
        
        if ($role !== null) {
            $sql .= ", role = :role";
            $params['role'] = $role;
        }
        
        $sql .= " WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    
    public function updatePassword($id, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->db->prepare("UPDATE users SET password = :password WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'password' => $hashedPassword
        ]);
    }
    
    public function deleteUser($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Verify if a plain text password matches the hashed password in the database
     *
     * @param string $password Plain text password
     * @param string $hashedPassword Hashed password from database
     * @return bool
     */
    public function verifyPassword($password, $hashedPassword)
    {
        return password_verify($password, $hashedPassword);
    }

    public function getUserWithDepartments($id)
    {
        // Lấy thông tin người dùng
        $user = $this->getUserById($id);
        
        if (!$user) {
            return null;
        }
        
        // Lấy danh sách phòng ban của người dùng
        $sql = "SELECT d.*, ud.is_leader 
                FROM departments d
                JOIN user_departments ud ON d.id = ud.department_id
                WHERE ud.user_id = :user_id
                ORDER BY d.name";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $id]);
        $departments = $stmt->fetchAll();
        
        $user['departments'] = $departments;
        
        return $user;
    }
} 