<?php include __DIR__ . '/../layouts/header.php'; ?>

<h1>User Management</h1>

<?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] ?>">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
<?php endif; ?>

<div class="section">
    <h2>Add New User</h2>
    <form method="POST" action="<?= BASEURL ?>user/add">
        <div class="form-row">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Enter full name" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter email address" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Min. 6 characters" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn">Add User</button>
    </form>
</div>

<div class="section">
    <h2>All Users</h2>
    <?php if (count($users) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>User Code</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['kode_user'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <?php $role = $user['role']; ?>
                            <span class="badge badge-<?= $role === 'admin' ? 'admin' : 'user' ?>">
                                <?= ucfirst($role === 'petugas' ? 'user' : $role) ?>
                            </span>
                        </td>
                        <td><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                        <td class="action-cell">
                            <a href="<?= BASEURL ?>user/edit/<?= $user['id'] ?>" class="btn btn-small">Edit</a>
                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <form method="POST" action="<?= BASEURL ?>user/delete" style="display:inline;"
                                    onsubmit="return confirm('Delete <?= htmlspecialchars($user['name']) ?>? This cannot be undone.');">
                                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-small">Delete</button>
                                </form>
                            <?php else: ?>
                                <span class="current-user-tag">You</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="color:#888;">No users found.</p>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>