<?php
require_once 'Database.php';

class Location_model extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllLocations()
    {
        $stmt = $this->con->query("SELECT * FROM lokasi ORDER BY nama_lokasi");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLocationById($id)
    {
        $stmt = $this->con->prepare("SELECT * FROM lokasi WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function generateLocationCode()
    {
        $stmt = $this->con->query("SELECT kode_lokasi FROM lokasi WHERE kode_lokasi IS NOT NULL ORDER BY id DESC LIMIT 1");
        $last = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($last && preg_match('/LK-(\d+)/', $last['kode_lokasi'], $matches)) {
            $number = intval($matches[1]) + 1;
        } else {
            $number = 1;
        }

        return 'LK-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    public function createLocation($data)
    {
        $kode_lokasi = $this->generateLocationCode();
        $stmt = $this->con->prepare("INSERT INTO lokasi (kode_lokasi, nama_lokasi, keterangan) VALUES (?, ?, ?)");
        return $stmt->execute([$kode_lokasi, $data['nama_lokasi'], $data['keterangan'] ?? '']);
    }

    public function updateLocation($id, $data)
    {
        $stmt = $this->con->prepare("UPDATE lokasi SET nama_lokasi = ?, keterangan = ? WHERE id = ?");
        return $stmt->execute([$data['nama_lokasi'], $data['keterangan'] ?? '', $id]);
    }

    public function deleteLocation($id)
    {
        $stmt = $this->con->prepare("DELETE FROM lokasi WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getTotalCount()
    {
        $stmt = $this->con->query("SELECT COUNT(*) FROM lokasi");
        return $stmt->fetchColumn();
    }
}
