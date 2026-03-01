<?php
require_once 'Database.php';

class Mutation_model extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllMutations()
    {
        $stmt = $this->con->query("
            SELECT m.*, a.kode_aset, a.nama_aset,
                   la.nama_lokasi as lokasi_asal,
                   lt.nama_lokasi as lokasi_tujuan,
                   u.name as user_name
            FROM mutasi_aset m
            LEFT JOIN asset a ON m.asset_id = a.id
            LEFT JOIN lokasi la ON m.lokasi_asal_id = la.id
            LEFT JOIN lokasi lt ON m.lokasi_tujuan_id = lt.id
            LEFT JOIN users u ON m.user_id = u.id
            ORDER BY m.tanggal_mutasi DESC, m.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecentMutations($limit = 5)
    {
        // Cast to integer to prevent SQL injection
        $limit = (int)$limit;
        $stmt = $this->con->prepare("
            SELECT m.*, a.kode_aset, a.nama_aset,
                   la.nama_lokasi as lokasi_asal,
                   lt.nama_lokasi as lokasi_tujuan,
                   u.name as user_name
            FROM mutasi_aset m
            JOIN asset a ON m.asset_id = a.id
            JOIN lokasi la ON m.lokasi_asal_id = la.id
            JOIN lokasi lt ON m.lokasi_tujuan_id = lt.id
            JOIN users u ON m.user_id = u.id
            ORDER BY m.tanggal_mutasi DESC, m.created_at DESC
            LIMIT " . $limit . "
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMutationById($id)
    {
        $stmt = $this->con->prepare("
            SELECT m.*, a.kode_aset, a.nama_aset,
                   la.nama_lokasi as lokasi_asal,
                   lt.nama_lokasi as lokasi_tujuan,
                   u.name as user_name
            FROM mutasi_aset m
            LEFT JOIN asset a ON m.asset_id = a.id
            LEFT JOIN lokasi la ON m.lokasi_asal_id = la.id
            LEFT JOIN lokasi lt ON m.lokasi_tujuan_id = lt.id
            LEFT JOIN users u ON m.user_id = u.id
            WHERE m.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createMutation($data)
    {
        $stmt = $this->con->prepare("
            INSERT INTO mutasi_aset (asset_id, lokasi_asal_id, lokasi_tujuan_id, tanggal_mutasi, keterangan, user_id) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['asset_id'],
            $data['lokasi_asal_id'],
            $data['lokasi_tujuan_id'],
            $data['tanggal_mutasi'],
            $data['keterangan'] ?? '',
            $data['user_id']
        ]);
    }

    public function deleteMutation($id)
    {
        $stmt = $this->con->prepare("DELETE FROM mutasi_aset WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getTotalCount()
    {
        $stmt = $this->con->query("SELECT COUNT(*) FROM mutasi_aset");
        return $stmt->fetchColumn();
    }
}
