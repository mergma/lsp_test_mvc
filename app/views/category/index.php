<?php include __DIR__ . '/../layouts/header.php'; ?>

<h1>Asset Categories</h1>

<?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] ?>">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
<?php endif; ?>

<div class="section">
    <h2>Add New Category</h2>
    <form method="POST" action="<?= BASEURL ?>category/add">
        <div class="form-group">
            <label for="nama_kategori">Category Name</label>
            <input type="text" id="nama_kategori" name="nama_kategori" required>
        </div>

        <button type="submit" class="btn">Add Category</button>
    </form>
</div>

<div class="section">
    <h2>All Categories</h2>
    <?php if (count($categories) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?= $category['id'] ?></td>
                        <td><?= htmlspecialchars($category['nama_kategori']) ?></td>
                        <td><?= date('Y-m-d H:i', strtotime($category['created_at'])) ?></td>
                        <td>
                            <a href="<?= BASEURL ?>category/edit/<?= $category['id'] ?>" class="btn btn-small">Edit</a>
                            <form method="POST" action="<?= BASEURL ?>category/delete" style="display:inline;"
                                onsubmit="return confirm('Are you sure you want to delete this category?');">
                                <input type="hidden" name="id" value="<?= $category['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-small">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No categories found. Add one above!</p>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>