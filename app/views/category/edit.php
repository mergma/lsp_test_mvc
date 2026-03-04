<?php include __DIR__ . '/../layouts/header.php'; ?>

<h1>Edit Category</h1>

<?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] ?>">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
<?php endif; ?>

<div class="section">
    <form method="POST" action="<?= BASEURL ?>category/update">
        <input type="hidden" name="id" value="<?= $category['id'] ?>">

        <div class="form-group">
            <label for="nama_kategori">Category Name</label>
            <input type="text" id="nama_kategori" name="nama_kategori" value="<?= htmlspecialchars($category['nama_kategori']) ?>" required>
        </div>

        <button type="submit" class="btn">Update Category</button>
        <a href="<?= BASEURL ?>category" class="btn btn-danger">Cancel</a>
    </form>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>