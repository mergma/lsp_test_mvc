<?php include '../layouts/header.php'; ?>

<h1>Locations</h1>

<?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] ?>">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
<?php endif; ?>

<div class="section">
    <h2>Add New Location</h2>
    <form method="POST" action="<?= BASEURL ?>location/add">
        <div class="form-group">
            <label for="nama_lokasi">Location Name</label>
            <input type="text" id="nama_lokasi" name="nama_lokasi" required>
        </div>

        <div class="form-group">
            <label for="keterangan">Description (Optional)</label>
            <textarea id="keterangan" name="keterangan" rows="3"></textarea>
        </div>

        <button type="submit" class="btn">Add Location</button>
    </form>
</div>

<div class="section">
    <h2>All Locations</h2>
    <?php if (count($locations) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($locations as $location): ?>
                    <tr>
                        <td><?= $location['id'] ?></td>
                        <td><?= htmlspecialchars($location['nama_lokasi']) ?></td>
                        <td><?= htmlspecialchars($location['keterangan'] ?? '-') ?></td>
                        <td><?= date('Y-m-d H:i', strtotime($location['created_at'])) ?></td>
                        <td>
                            <a href="<?= BASEURL ?>location/edit/<?= $location['id'] ?>" class="btn btn-small">Edit</a>
                            <form method="POST" action="<?= BASEURL ?>location/delete" style="display:inline;"
                                onsubmit="return confirm('Are you sure you want to delete this location?');">
                                <input type="hidden" name="id" value="<?= $location['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-small">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No locations found. Add one above!</p>
    <?php endif; ?>
</div>

<?php include '../layouts/footer.php'; ?>