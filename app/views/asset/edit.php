<?php include __DIR__ . '/../layouts/header.php'; ?>

<h1>Edit Asset</h1>

<?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] ?>">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
<?php endif; ?>

<div class="section">
    <form method="POST" action="<?= BASEURL ?>asset/update">
        <input type="hidden" name="id" value="<?= $asset['id'] ?>">

        <div class="form-group">
            <label for="kode_aset">Asset Code</label>
            <input type="text" id="kode_aset" value="<?= htmlspecialchars($asset['kode_aset']) ?>"
                readonly style="background-color: #f0f0f0; cursor: not-allowed;">
            <small style="color: #666;">Asset code is auto-generated and cannot be changed</small>
        </div>

        <div class="form-group">
            <label for="nama_aset">Asset Name</label>
            <input type="text" id="nama_aset" name="nama_aset" value="<?= htmlspecialchars($asset['nama_aset']) ?>" required>
        </div>

        <div class="form-group">
            <label for="kategori_id">Category</label>
            <select id="kategori_id" name="kategori_id" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>" <?= $asset['kategori_id'] == $category['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['nama_kategori']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="lokasi_id">Location</label>
            <select id="lokasi_id" name="lokasi_id" required>
                <option value="">Select Location</option>
                <?php foreach ($locations as $location): ?>
                    <option value="<?= $location['id'] ?>" <?= $asset['lokasi_id'] == $location['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($location['nama_lokasi']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="kondisi">Condition</label>
            <select id="kondisi" name="kondisi" required>
                <option value="baik" <?= $asset['kondisi'] == 'baik' ? 'selected' : '' ?>>Baik (Good)</option>
                <option value="rusak" <?= $asset['kondisi'] == 'rusak' ? 'selected' : '' ?>>Rusak (Damaged)</option>
            </select>
        </div>

        <div class="form-group">
            <label for="jumlah">Quantity</label>
            <input type="number" id="jumlah" name="jumlah" min="1" value="<?= htmlspecialchars($asset['jumlah']) ?>" required>
        </div>

        <button type="submit" class="btn">Update Asset</button>
        <a href="<?= BASEURL ?>asset" class="btn btn-danger">Cancel</a>
    </form>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>