<?php include __DIR__ . '/../layouts/header.php'; ?>

<h1>Locations</h1>

<?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] ?>">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
<?php endif; ?>

<!-- Add Location Modal -->
<div class="modal-overlay" id="addModal">
    <div class="modal-box">
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
</div>

<!-- Floating Action Button -->
<button class="fab" id="fabBtn" onclick="toggleModal()" title="Add New Location">+</button>

<script>
    function toggleModal() {
        const overlay = document.getElementById('addModal');
        const fab = document.getElementById('fabBtn');
        overlay.classList.toggle('active');
        fab.classList.toggle('fab-open');
        fab.textContent = fab.classList.contains('fab-open') ? '×' : '+';
    }
    document.getElementById('addModal').addEventListener('click', function(e) {
        if (e.target === this) toggleModal();
    });
</script>

<div class="section">
    <h2>All Locations</h2>
    <?php if (count($locations) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Location Code</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($locations as $location): ?>
                    <tr>
                        <td><?= htmlspecialchars($location['kode_lokasi'] ?? '-') ?></td>
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
        <p>No locations found. Click the <strong>+</strong> button to add one!</p>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>