<nav class="navbar">
    <div class="nav-brand">
        <a href="<?= BASEURL ?>">Asset System</a>
    </div>
    <ul class="nav-menu">
        <li><a href="<?= BASEURL ?>">Dashboard</a></li>
        <li><a href="<?= BASEURL ?>asset">Assets</a></li>
        <li><a href="<?= BASEURL ?>category">Categories</a></li>
        <li><a href="<?= BASEURL ?>location">Locations</a></li>
        <li><a href="<?= BASEURL ?>mutation">Mutations</a></li>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <li><a href="<?= BASEURL ?>user">Users</a></li>
        <?php endif; ?>
        <li><a href="<?= BASEURL ?>auth/logout">Logout (<?= htmlspecialchars($_SESSION['name'] ?? 'User') ?>)</a></li>
    </ul>
</nav>

