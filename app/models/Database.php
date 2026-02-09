<?php
class Database
{
    private $host = DB_HOST;
    private $dbname = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    protected $con;

    public function __construct()
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname}";
            $this->con = new PDO($dsn, $this->username, $this->password);
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->createTables();
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    private function createTables()
    {
        try {
            // Users table
            $this->con->exec("CREATE TABLE IF NOT EXISTS users (
                id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                role VARCHAR(20) NOT NULL DEFAULT 'petugas',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )");

            // Category table
            $this->con->exec("CREATE TABLE IF NOT EXISTS kategori_aset (
                id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                nama_kategori VARCHAR(100) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )");

            // Location table
            $this->con->exec("CREATE TABLE IF NOT EXISTS lokasi (
                id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                nama_lokasi VARCHAR(100) NOT NULL,
                keterangan TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )");

            // Asset table
            $this->con->exec("CREATE TABLE IF NOT EXISTS asset (
                id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                kode_aset VARCHAR(50) NOT NULL UNIQUE,
                nama_aset VARCHAR(150) NOT NULL,
                kategori_id BIGINT NOT NULL,
                lokasi_id BIGINT NOT NULL,
                kondisi VARCHAR(30) NOT NULL DEFAULT 'baik',
                jumlah INT NOT NULL DEFAULT 1,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (kategori_id) REFERENCES kategori_aset(id) ON DELETE RESTRICT,
                FOREIGN KEY (lokasi_id) REFERENCES lokasi(id) ON DELETE RESTRICT
            )");

            // Mutation table
            $this->con->exec("CREATE TABLE IF NOT EXISTS mutasi_aset (
                id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                asset_id BIGINT NOT NULL,
                lokasi_asal_id BIGINT NOT NULL,
                lokasi_tujuan_id BIGINT NOT NULL,
                tanggal_mutasi DATE NOT NULL,
                keterangan TEXT,
                user_id BIGINT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (asset_id) REFERENCES asset(id) ON DELETE RESTRICT,
                FOREIGN KEY (lokasi_asal_id) REFERENCES lokasi(id) ON DELETE RESTRICT,
                FOREIGN KEY (lokasi_tujuan_id) REFERENCES lokasi(id) ON DELETE RESTRICT,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT
            )");
        } catch (PDOException $e) {
            die("Table creation failed: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->con;
    }
}

