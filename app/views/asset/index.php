<?php include __DIR__ . '/../layouts/header.php'; ?>

<h1>Assets</h1>

<?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] ?>">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
<?php endif; ?>

<div class="section">
    <h2>Add New Asset</h2>
    <form method="POST" action="<?= BASEURL ?>asset/add">
        <div class="form-group">
            <label>Asset Code</label>
            <input type="text" value="Auto-generated (AST-XXX)" readonly style="background-color: #f0f0f0; cursor: not-allowed;">
            <small style="color: #666;">Asset code will be automatically generated</small>
        </div>

        <div class="form-group">
            <label for="nama_aset">Asset Name</label>
            <input type="text" id="nama_aset" name="nama_aset" required>
        </div>

        <div class="form-group">
            <label for="kategori_id">Category</label>
            <select id="kategori_id" name="kategori_id" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['nama_kategori']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="lokasi_id">Location</label>
            <select id="lokasi_id" name="lokasi_id" required>
                <option value="">Select Location</option>
                <?php foreach ($locations as $location): ?>
                    <option value="<?= $location['id'] ?>"><?= htmlspecialchars($location['nama_lokasi']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="kondisi">Condition</label>
            <select id="kondisi" name="kondisi" required>
                <option value="baik">Baik (Good)</option>
                <option value="rusak">Rusak (Damaged)</option>
            </select>
        </div>

        <div class="form-group">
            <label for="jumlah">Quantity</label>
            <input type="number" id="jumlah" name="jumlah" min="1" value="1" required>
        </div>

        <button type="submit" class="btn">Add Asset</button>
    </form>
</div>

<div class="section">
    <h2>All Assets</h2>
    <?php if (count($assets) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Condition</th>
                    <th>Quantity</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assets as $asset): ?>
                    <tr>
                        <td><?= htmlspecialchars($asset['kode_aset']) ?></td>
                        <td><?= htmlspecialchars($asset['nama_aset']) ?></td>
                        <td><?= htmlspecialchars($asset['category_name']) ?></td>
                        <td><?= htmlspecialchars($asset['location_name']) ?></td>
                        <td><?= htmlspecialchars($asset['kondisi']) ?></td>
                        <td><?= htmlspecialchars($asset['jumlah']) ?></td>
                        <td><?= date('Y-m-d H:i', strtotime($asset['created_at'])) ?></td>
                        <td>
                            <a href="<?= BASEURL ?>asset/edit/<?= $asset['id'] ?>" class="btn btn-small">Edit</a>
                            <form method="POST" action="<?= BASEURL ?>asset/delete" style="display:inline;"
                                onsubmit="return confirm('Are you sure you want to delete this asset?');">
                                <input type="hidden" name="id" value="<?= $asset['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-small">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No assets found. Add one above!</p>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>