<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id'];
    mysqli_query($connect, "DELETE FROM venues WHERE id = $id");
}

$venues = mysqli_query($connect, "
    SELECT v.*, u.full_name AS manager_name
    FROM venues v
    LEFT JOIN users u ON v.manager_id = u.id
    ORDER BY v.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Venues — Eventix</title>
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
            <li><a href="users.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-text-muted hover:bg-gray-50 hover:text-text">Users</a></li>
            <li><a href="venues.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all bg-pink-main/10 text-pink-main font-semibold">Venues</a></li>
            <li><a href="bookings.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-text-muted hover:bg-gray-50 hover:text-text">Bookings</a></li>
            <li><a href="payments.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-text-muted hover:bg-gray-50 hover:text-text">Payments</a></li>
            <li><a href="reports.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-text-muted hover:bg-gray-50 hover:text-text">Reports</a></li>
        </ul>
    </aside>

    <main class="flex-1 p-10 overflow-y-auto">
        <div class="mb-10" data-aos="fade-down">
            <h1 class="font-[Playfair_Display] text-4xl text-pink-dark mb-2">All Venues</h1>
            <p>Venues listed across the platform</p>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl  p-6 shadow-soft" data-aos="fade-up">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8" data-aos="fade-up">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 border-b border-gray-100 text-pink-dark text-xs uppercase tracking-wider font-semibold bg-gray-50">#</th>
                            <th class="px-6 py-4 border-b border-gray-100 text-pink-dark text-xs uppercase tracking-wider font-semibold bg-gray-50">Venue Name</th>
                            <th class="px-6 py-4 border-b border-gray-100 text-pink-dark text-xs uppercase tracking-wider font-semibold bg-gray-50">Location</th>
                            <th class="px-6 py-4 border-b border-gray-100 text-pink-dark text-xs uppercase tracking-wider font-semibold bg-gray-50">Capacity</th>
                            <th class="px-6 py-4 border-b border-gray-100 text-pink-dark text-xs uppercase tracking-wider font-semibold bg-gray-50">Price/Day</th>
                            <th class="px-6 py-4 border-b border-gray-100 text-pink-dark text-xs uppercase tracking-wider font-semibold bg-gray-50">Manager</th>
                            <th class="px-6 py-4 border-b border-gray-100 text-pink-dark text-xs uppercase tracking-wider font-semibold bg-gray-50">Status</th>
                            <th class="px-6 py-4 border-b border-gray-100 text-pink-dark text-xs uppercase tracking-wider font-semibold bg-gray-50">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; while ($row = mysqli_fetch_assoc($venues)): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 border-b border-gray-100"><?= $i++ ?></td>
                            <td class="px-6 py-4 border-b border-gray-100"><?= htmlspecialchars($row['name']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-100"><?= htmlspecialchars($row['location']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-100"><?= $row['capacity'] ?> pax</td>
                            <td class="px-6 py-4 border-b border-gray-100">RM<?= number_format($row['price_per_day'], 2) ?></td>
                            <td class="px-6 py-4 border-b border-gray-100"><?= htmlspecialchars($row['manager_name'] ?? '—') ?></td>
                            <td class="px-6 py-4 border-b border-gray-100">
                                <span class="badge badge-<?= $row['status'] === 'active' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($row['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 border-b border-gray-100">
                                <form method="POST" onsubmit="return confirm('Delete this venue?')">
                                    <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="bg-red-50 text-red-600 px-4 py-2 rounded-full font-semibold text-xs hover:bg-red-100 transition-colors inline-block text-center">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer_scripts.php'; ?>
</body>
</html>
