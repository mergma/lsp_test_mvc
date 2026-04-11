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
                role VARCHAR(20) NOT NULL DEFAULT 'user',
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
            $this->addColumnIfNotExists('users',       'kode_user',   "VARCHAR(20) DEFAULT NULL");
            $this->addColumnIfNotExists('lokasi',       'kode_lokasi', "VARCHAR(20) DEFAULT NULL");
            $this->addColumnIfNotExists('mutasi_aset',  'kode_mutasi', "VARCHAR(20) DEFAULT NULL");
            $this->seedDefaultAdmin();
            $this->backfillCodes();
        } catch (PDOException $e) {
            die("Table creation failed: " . $e->getMessage());
        }
    }

    private function addColumnIfNotExists(string $table, string $column, string $definition): void
    {
        $stmt = $this->con->prepare(
            "SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?"
        );
        $stmt->execute([$table, $column]);
        if ((int)$stmt->fetchColumn() === 0) {
            $this->con->exec("ALTER TABLE `{$table}` ADD COLUMN `{$column}` {$definition}");
        }
    }

    private function backfillCodes(): void
    {
        // Users without a code
        $stmt = $this->con->query("SELECT MAX(CAST(SUBSTRING(kode_user, 4) AS UNSIGNED)) FROM users WHERE kode_user IS NOT NULL");
        $max = (int)$stmt->fetchColumn();
        $rows = $this->con->query("SELECT id FROM users WHERE kode_user IS NULL ORDER BY id")->fetchAll(PDO::FETCH_COLUMN);
        foreach ($rows as $id) {
            $max++;
            $this->con->prepare("UPDATE users SET kode_user = ? WHERE id = ?")
                ->execute(['US-' . str_pad($max, 3, '0', STR_PAD_LEFT), $id]);
        }

        // Locations without a code
        $stmt = $this->con->query("SELECT MAX(CAST(SUBSTRING(kode_lokasi, 4) AS UNSIGNED)) FROM lokasi WHERE kode_lokasi IS NOT NULL");
        $max = (int)$stmt->fetchColumn();
        $rows = $this->con->query("SELECT id FROM lokasi WHERE kode_lokasi IS NULL ORDER BY id")->fetchAll(PDO::FETCH_COLUMN);
        foreach ($rows as $id) {
            $max++;
            $this->con->prepare("UPDATE lokasi SET kode_lokasi = ? WHERE id = ?")
                ->execute(['LK-' . str_pad($max, 3, '0', STR_PAD_LEFT), $id]);
        }

        // Mutations without a code
        $stmt = $this->con->query("SELECT MAX(CAST(SUBSTRING(kode_mutasi, 4) AS UNSIGNED)) FROM mutasi_aset WHERE kode_mutasi IS NOT NULL");
        $max = (int)$stmt->fetchColumn();
        $rows = $this->con->query("SELECT id FROM mutasi_aset WHERE kode_mutasi IS NULL ORDER BY id")->fetchAll(PDO::FETCH_COLUMN);
        foreach ($rows as $id) {
            $max++;
            $this->con->prepare("UPDATE mutasi_aset SET kode_mutasi = ? WHERE id = ?")
                ->execute(['MT-' . str_pad($max, 3, '0', STR_PAD_LEFT), $id]);
        }
    }

    private function seedDefaultAdmin()
    {
        // Only insert if no admin account exists yet
        $stmt = $this->con->query("SELECT COUNT(*) FROM users WHERE role = 'admin'");
        if ($stmt->fetchColumn() > 0) {
            return;
        }

        $stmt = $this->con->prepare(
            "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')"
        );
        $stmt->execute([
            'Administrator',
            'admin@admin.com',
            password_hash('admin123', PASSWORD_DEFAULT)
        ]);
    }

    public function getConnection()
    {
        return $this->con;
    }
}
