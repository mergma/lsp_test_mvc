<?php include '../app/views/templates/header.php'; ?>

<h1>Edit Location</h1>

<?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] ?>">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
<?php endif; ?>

<div class="section">
    <form method="POST" action="<?= BASEURL ?>location/update">
        <input type="hidden" name="id" value="<?= $location['id'] ?>">

        <div class="form-group">
            <label for="nama_lokasi">Location Name</label>
            <input type="text" id="nama_lokasi" name="nama_lokasi" value="<?= htmlspecialchars($location['nama_lokasi']) ?>" required>
        </div>

        <div class="form-group">
            <label for="keterangan">Description (Optional)</label>
            <textarea id="keterangan" name="keterangan" rows="3"><?= htmlspecialchars($location['keterangan'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn">Update Location</button>
        <a href="<?= BASEURL ?>location" class="btn btn-danger">Cancel</a>
    </form>
</div>

<?php include '../app/views/templates/footer.php'; ?>

