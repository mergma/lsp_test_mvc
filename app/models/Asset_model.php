<?php
require_once 'Database.php';

class Asset_model extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllAssets()
    {
        $stmt = $this->con->query("
            SELECT a.*, k.nama_kategori as category_name, l.nama_lokasi as location_name
            FROM asset a
            LEFT JOIN kategori_aset k ON a.kategori_id = k.id
            LEFT JOIN lokasi l ON a.lokasi_id = l.id
            ORDER BY a.kode_aset
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAssetById($id)
    {
        $stmt = $this->con->prepare("
            SELECT a.*, k.nama_kategori as category_name, l.nama_lokasi as location_name
            FROM asset a
            LEFT JOIN kategori_aset k ON a.kategori_id = k.id
            LEFT JOIN lokasi l ON a.lokasi_id = l.id
            WHERE a.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function generateAssetCode()
    {
        $stmt = $this->con->query("SELECT kode_aset FROM asset ORDER BY id DESC LIMIT 1");
        $last_asset = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($last_asset) {
            $last_code = $last_asset['kode_aset'];
            if (preg_match('/AST-(\d+)/', $last_code, $matches)) {
                $number = intval($matches[1]) + 1;
            } else {
                $number = 1;
            }
        } else {
            $number = 1;
        }

        return 'AST-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    public function createAsset($data)
    {
        $kode_aset = $this->generateAssetCode();
        $stmt = $this->con->prepare("
            INSERT INTO asset (kode_aset, nama_aset, kategori_id, lokasi_id, kondisi, jumlah) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $kode_aset,
            $data['nama_aset'],
            $data['kategori_id'],
            $data['lokasi_id'],
            $data['kondisi'],
            $data['jumlah']
        ]);
    }

    public function updateAsset($id, $data)
    {
        $stmt = $this->con->prepare("
            UPDATE asset 
            SET nama_aset = ?, kategori_id = ?, lokasi_id = ?, kondisi = ?, jumlah = ? 
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['nama_aset'],
            $data['kategori_id'],
            $data['lokasi_id'],
            $data['kondisi'],
            $data['jumlah'],
            $id
        ]);
    }

    public function deleteAsset($id)
    {
        $stmt = $this->con->prepare("DELETE FROM asset WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getTotalCount()
    {
        $stmt = $this->con->query("SELECT COUNT(*) FROM asset");
        return $stmt->fetchColumn();
    }

    public function updateAssetLocation($asset_id, $location_id)
    {
        $stmt = $this->con->prepare("UPDATE asset SET lokasi_id = ? WHERE id = ?");
        return $stmt->execute([$location_id, $asset_id]);
    }
}
