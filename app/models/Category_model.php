<?php
require_once 'Database.php';

class Category_model extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllCategories()
    {
        $stmt = $this->con->query("SELECT * FROM kategori_aset ORDER BY nama_kategori");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoryById($id)
    {
        $stmt = $this->con->prepare("SELECT * FROM kategori_aset WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createCategory($nama_kategori)
    {
        $stmt = $this->con->prepare("INSERT INTO kategori_aset (nama_kategori) VALUES (?)");
        return $stmt->execute([$nama_kategori]);
    }

    public function updateCategory($id, $nama_kategori)
    {
        $stmt = $this->con->prepare("UPDATE kategori_aset SET nama_kategori = ? WHERE id = ?");
        return $stmt->execute([$nama_kategori, $id]);
    }

    public function deleteCategory($id)
    {
        $stmt = $this->con->prepare("DELETE FROM kategori_aset WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getTotalCount()
    {
        $stmt = $this->con->query("SELECT COUNT(*) FROM kategori_aset");
        return $stmt->fetchColumn();
    }
}
