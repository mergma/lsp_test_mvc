<?php include '../layouts/header.php'; ?>
<?php include '../layouts/navbar.php'; ?>

<h1>Dashboard</h1>
<p>Welcome, <?= htmlspecialchars($_SESSION['name']) ?>!</p>

<?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] ?>">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
<?php endif; ?>

<div class="stats-grid">
    <div class="stat-card">
        <h3><?= $total_assets ?></h3>
        <p>Total Assets</p>
    </div>
    <div class="stat-card">
        <h3><?= $total_categories ?></h3>
        <p>Categories</p>
    </div>
    <div class="stat-card">
        <h3><?= $total_locations ?></h3>
        <p>Locations</p>
    </div>
    <div class="stat-card">
        <h3><?= $total_mutations ?></h3>
        <p>Total Mutations</p>
    </div>
</div>

<div class="section">
    <h2>Recent Asset Mutations</h2>
    <?php if (count($recent_mutations) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Asset Code</th>
                    <th>Asset Name</th>
                    <th>From Location</th>
                    <th>To Location</th>
                    <th>Date</th>
                    <th>Recorded By</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_mutations as $mutation): ?>
                    <tr>
                        <td><?= htmlspecialchars($mutation['kode_aset']) ?></td>
                        <td><?= htmlspecialchars($mutation['nama_aset']) ?></td>
                        <td><?= htmlspecialchars($mutation['lokasi_asal']) ?></td>
                        <td><?= htmlspecialchars($mutation['lokasi_tujuan']) ?></td>
                        <td><?= date('Y-m-d', strtotime($mutation['tanggal_mutasi'])) ?></td>
                        <td><?= htmlspecialchars($mutation['user_name']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No mutations recorded yet.</p>
    <?php endif; ?>
</div>

<?php include '../layouts/footer.php'; ?>