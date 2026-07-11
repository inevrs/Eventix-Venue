<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin('admin');

$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id'];
    if (mysqli_query($connect, "DELETE FROM users WHERE id = $id AND role != 'admin'")) {
        $success = 'User deleted successfully!';
    }
}

$search = mysqli_real_escape_string($connect, $_GET['search'] ?? '');
$filter = mysqli_real_escape_string($connect, $_GET['role'] ?? '');

$where = "WHERE role != 'admin'";
if ($search) $where .= " AND (full_name LIKE '%$search%' OR email LIKE '%$search%')";
if ($filter) $where .= " AND role = '$filter'";

$users = mysqli_query($connect, "SELECT * FROM users $where ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users — Eventix</title>
    <?php include '../includes/header_scripts.php'; ?>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<div class="flex min-h-screen pt-24">
    <aside class="w-64 bg-white border-r border-gray-100 shrink-0 py-8 shadow-sm  z-10">
        <p class="text-[10px] tracking-widest text-text-muted font-bold uppercase mb-3 px-8 mt-6">Overview</p>
        <ul class="list-none p-0 m-0 flex flex-col gap-1 px-4">
            <li><a href="dashboard.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-text-muted hover:bg-gray-50 hover:text-text">Dashboard</a></li>
        </ul>
        <p class="text-[10px] tracking-widest text-text-muted font-bold uppercase mb-3 px-8 mt-6">Manage</p>
        <ul class="list-none p-0 m-0 flex flex-col gap-1 px-4">
            <li><a href="users.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all bg-pink-main/10 text-pink-main font-semibold">Users</a></li>
            <li><a href="venues.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-text-muted hover:bg-gray-50 hover:text-text">Venues</a></li>
            <li><a href="bookings.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-text-muted hover:bg-gray-50 hover:text-text">Bookings</a></li>
            <li><a href="payments.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-text-muted hover:bg-gray-50 hover:text-text">Payments</a></li>
            <li><a href="reports.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-text-muted hover:bg-gray-50 hover:text-text">Reports</a></li>
        </ul>
    </aside>

    <main class="flex-1 p-10 overflow-y-auto">
        <div class="mb-10" data-aos="fade-down">
            <h1 class="font-[Playfair_Display] text-4xl text-pink-dark mb-2">Manage Users</h1>
            <p class="text-text-muted text-sm">View and remove platform users</p>
        </div>

        <form method="GET" class="flex gap-3 max-w-xl mb-12" data-aos="fade-up">
            <input type="text" name="search" placeholder="Search name or email..." class="flex-1 px-5 py-3 border border-gray-200 rounded-full text-sm font-sans focus:border-pink-main focus:ring-4 focus:ring-pink-main/15 outline-none transition-all" value="<?= htmlspecialchars($search) ?>">
            <select name="role" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-sans text-text focus:border-pink-main focus:ring-2 focus:ring-pink-main/10 outline-none transition-all cursor-pointer pr-10">
                <option value="">All Roles</option>
                <option value="customer" <?= $filter === 'customer' ? 'selected' : '' ?>>Customer</option>
                <option value="manager"  <?= $filter === 'manager'  ? 'selected' : '' ?>>Manager</option>
            </select>
            <button type="submit" class="bg-pink-main text-white px-7 py-3 rounded-full font-semibold text-sm hover:bg-pink-dark active:scale-95 transition-all shadow-md hover:shadow-lg active:scale-95 transition-all">Search</button>
        </form>

        <?php if ($success): ?>
            <div class="bg-green-50 text-green-700 px-4 py-3 rounded-lg text-sm mb-6"><?= $success ?></div>
        <?php endif; ?>

        <div class="bg-white border border-gray-100 rounded-2xl  p-6 shadow-soft" data-aos="fade-up">
            <div class="overflow-x-auto rounded-2xl border border-gray-100 shadow-sm">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 border-b border-gray-100 text-pink-dark text-xs uppercase tracking-wider font-semibold bg-gray-50">#</th>
                            <th class="px-6 py-4 border-b border-gray-100 text-pink-dark text-xs uppercase tracking-wider font-semibold bg-gray-50">Name</th>
                            <th class="px-6 py-4 border-b border-gray-100 text-pink-dark text-xs uppercase tracking-wider font-semibold bg-gray-50">Email</th>
                            <th class="px-6 py-4 border-b border-gray-100 text-pink-dark text-xs uppercase tracking-wider font-semibold bg-gray-50">Phone</th>
                            <th class="px-6 py-4 border-b border-gray-100 text-pink-dark text-xs uppercase tracking-wider font-semibold bg-gray-50">Role</th>
                            <th class="px-6 py-4 border-b border-gray-100 text-pink-dark text-xs uppercase tracking-wider font-semibold bg-gray-50">Joined</th>
                            <th class="px-6 py-4 border-b border-gray-100 text-pink-dark text-xs uppercase tracking-wider font-semibold bg-gray-50">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $rows = [];
                        while ($row = mysqli_fetch_assoc($users)) $rows[] = $row;
                        if (empty($rows)): ?>
                        <tr><td colspan="7" class="px-6 py-12 text-center text-text-muted">
                            <p style="font-size:32px;margin-bottom:8px">👥</p>
                            <p class="font-semibold mb-1">No users found</p>
                            <p class="text-sm">There are no users matching your criteria.</p>
                        </td></tr>
                        <?php else: ?>
                        <?php $i = 1; foreach ($rows as $row): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 border-b border-gray-100"><?= $i++ ?></td>
                            <td class="px-6 py-4 border-b border-gray-100"><?= htmlspecialchars($row['full_name']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-100"><?= htmlspecialchars($row['email']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-100"><?= htmlspecialchars($row['phone']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-100"><span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider"><?= ucfirst($row['role']) ?></span></td>
                            <td class="px-6 py-4 border-b border-gray-100"><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                            <td class="px-6 py-4 border-b border-gray-100">
                                <form method="POST" onsubmit="return confirm('Delete this user?')">
                                    <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="bg-red-50 text-red-600 px-4 py-2 rounded-full font-semibold text-xs hover:bg-red-100 transition-colors inline-block text-center">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer_scripts.php'; ?>
</body>
</html>
