<?php
require_once 'Database.php';

class User_model extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllUsers()
    {
        $stmt = $this->con->query("SELECT * FROM users ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id)
    {
        $stmt = $this->con->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->con->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function generateUserCode()
    {
        $stmt = $this->con->query("SELECT kode_user FROM users WHERE kode_user IS NOT NULL ORDER BY id DESC LIMIT 1");
        $last = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($last && preg_match('/US-(\d+)/', $last['kode_user'], $matches)) {
            $number = intval($matches[1]) + 1;
        } else {
            $number = 1;
        }

        return 'US-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    public function createUser($data)
    {
        $kode_user = $this->generateUserCode();
        $stmt = $this->con->prepare("INSERT INTO users (kode_user, name, email, password, role) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $kode_user,
            $data['name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['role'] ?? 'user'
        ]);
    }

    public function updateUser($id, $data)
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $stmt = $this->con->prepare("UPDATE users SET name = ?, email = ?, password = ?, role = ? WHERE id = ?");
            return $stmt->execute([
                $data['name'],
                $data['email'],
                password_hash($data['password'], PASSWORD_DEFAULT),
                $data['role'],
                $id
            ]);
        } else {
            $stmt = $this->con->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
            return $stmt->execute([
                $data['name'],
                $data['email'],
                $data['role'],
                $id
            ]);
        }
    }

    public function deleteUser($id)
    {
        $stmt = $this->con->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function verifyPassword($email, $password)
    {
        $user = $this->getUserByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function emailExists($email, $excludeId = null)
    {
        if ($excludeId) {
            $stmt = $this->con->prepare("SELECT COUNT(*) FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$email, $excludeId]);
        } else {
            $stmt = $this->con->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $stmt->execute([$email]);
        }
        return $stmt->fetchColumn() > 0;
    }
}
