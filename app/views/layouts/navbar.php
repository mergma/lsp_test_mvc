<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="<?= BASEURL ?>img/Breabm 2.0.png" alt="Breabm Logo">
    </div>
    <nav class="sidebar-nav">
        <ul>
            <li><a href="<?= BASEURL ?>">Dashboard</a></li>
            <li><a href="<?= BASEURL ?>asset">Assets</a></li>
            <li><a href="<?= BASEURL ?>category">Categories</a></li>
            <li><a href="<?= BASEURL ?>location">Locations</a></li>
            <li><a href="<?= BASEURL ?>mutation">Mutations</a></li>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                <li><a href="<?= BASEURL ?>user">Users</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="sidebar-footer">
        <div class="user-info"><?= htmlspecialchars($_SESSION['name'] ?? 'User') ?></div>
        <a href="<?= BASEURL ?>auth/logout" class="logout-btn">Logout</a>
    </div>
</aside>