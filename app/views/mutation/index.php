<?php include __DIR__ . '/../layouts/header.php'; ?>

<h1>Asset Mutations</h1>
<p>Track asset movements between locations</p>

<?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] ?>">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
<?php endif; ?>

<div class="section">
    <h2>Record New Mutation</h2>
    <form method="POST" action="<?= BASEURL ?>mutation/add">
        <div class="form-group">
            <label for="asset_id">Asset</label>
            <select id="asset_id" name="asset_id" required onchange="updateCurrentLocation(this.value)">
                <option value="">Select Asset</option>
                <?php foreach ($assets as $asset): ?>
                    <option value="<?= $asset['id'] ?>" data-location="<?= $asset['lokasi_id'] ?>">
                        <?= htmlspecialchars($asset['kode_aset'] . ' - ' . $asset['nama_aset']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="lokasi_asal_id">Current Location (From)</label>
            <select id="lokasi_asal_id" name="lokasi_asal_id" required>
                <option value="">Select Current Location</option>
                <?php foreach ($locations as $location): ?>
                    <option value="<?= $location['id'] ?>">
                        <?= htmlspecialchars($location['nama_lokasi']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="lokasi_tujuan_id">New Location (To)</label>
            <select id="lokasi_tujuan_id" name="lokasi_tujuan_id" required>
                <option value="">Select New Location</option>
                <?php foreach ($locations as $location): ?>
                    <option value="<?= $location['id'] ?>">
                        <?= htmlspecialchars($location['nama_lokasi']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="tanggal_mutasi">Mutation Date</label>
            <input type="date" id="tanggal_mutasi" name="tanggal_mutasi" value="<?= date('Y-m-d') ?>" required>
        </div>

        <div class="form-group">
            <label for="keterangan">Notes (Optional)</label>
            <textarea id="keterangan" name="keterangan" rows="3"></textarea>
        </div>

        <button type="submit" class="btn">Record Mutation</button>
    </form>
</div>

<div class="section">
    <h2>Mutation History</h2>
    <?php if (count($mutations) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Asset Code</th>
                    <th>Asset Name</th>
                    <th>From Location</th>
                    <th>To Location</th>
                    <th>Date</th>
                    <th>Notes</th>
                    <th>Recorded By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mutations as $mutation): ?>
                    <tr>
                        <td><?= $mutation['id'] ?></td>
                        <td><?= htmlspecialchars($mutation['kode_aset']) ?></td>
                        <td><?= htmlspecialchars($mutation['nama_aset']) ?></td>
                        <td><?= htmlspecialchars($mutation['lokasi_asal']) ?></td>
                        <td><?= htmlspecialchars($mutation['lokasi_tujuan']) ?></td>
                        <td><?= date('Y-m-d', strtotime($mutation['tanggal_mutasi'])) ?></td>
                        <td><?= htmlspecialchars($mutation['keterangan'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($mutation['user_name']) ?></td>
                        <td>
                            <form method="POST" action="<?= BASEURL ?>mutation/delete" style="display:inline;"
                                onsubmit="return confirm('Are you sure you want to delete this mutation record?');">
                                <input type="hidden" name="id" value="<?= $mutation['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-small">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No mutations recorded yet.</p>
    <?php endif; ?>
</div>

<script>
    function updateCurrentLocation(assetId) {
        if (!assetId) {
            document.getElementById('lokasi_asal_id').value = '';
            return;
        }

        const select = document.getElementById('asset_id');
        const selectedOption = select.options[select.selectedIndex];
        const locationId = selectedOption.getAttribute('data-location');

        if (locationId) {
            document.getElementById('lokasi_asal_id').value = locationId;
        }
    }
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>