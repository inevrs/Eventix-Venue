<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin('admin');

$payments = mysqli_query($connect, "
    SELECT p.*, u.full_name, v.name AS venue_name
    FROM payments p
    JOIN bookings b ON p.booking_id = b.id
    JOIN users u ON b.user_id = u.id
    JOIN venues v ON b.venue_id = v.id
    ORDER BY p.paid_at DESC
");

$total = mysqli_fetch_row(mysqli_query($connect, "SELECT COALESCE(SUM(amount),0) FROM payments WHERE status='paid'"))[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payments — Eventix</title>
    <?php include '../includes/header_scripts.php'; ?>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<div class="flex min-h-screen pt-24">
    <aside class="w-64 bg-white border-r border-gray-100 shrink-0 py-8 shadow-sm  z-10">
        <p class="text-[10px] tracking-widest text-text-muted font-bold uppercase mb-3 px-8">Overview</p>
        <ul class="list-none p-0 m-0 mb-8 flex flex-col gap-1 px-4">
            <li><a href="dashboard.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-text-muted hover:bg-gray-50 hover:text-text">Dashboard</a></li>
        </ul>
        <p class="text-[10px] tracking-widest text-text-muted font-bold uppercase mb-3 px-8">Manage</p>
        <ul class="list-none p-0 m-0 mb-8 flex flex-col gap-1 px-4">
            <li><a href="users.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-text-muted hover:bg-gray-50 hover:text-text">Users</a></li>
            <li><a href="venues.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-text-muted hover:bg-gray-50 hover:text-text">Venues</a></li>
            <li><a href="bookings.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-text-muted hover:bg-gray-50 hover:text-text">Bookings</a></li>
            <li><a href="payments.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all bg-pink-main/10 text-pink-main font-semibold">Payments</a></li>
            <li><a href="reports.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-text-muted hover:bg-gray-50 hover:text-text">Reports</a></li>
        </ul>
    </aside>

    <main class="flex-1 p-10 overflow-y-auto">
        <div class="mb-10" data-aos="fade-down">
            <h1 class="font-[Playfair_Display] text-4xl text-pink-dark mb-2">Payments</h1>
            <p>Total confirmed revenue: <strong style="color:var(--pink-main)">RM<?= number_format($total, 2) ?></strong></p>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl  p-8 shadow-soft mb-8">
            <div class="overflow-x-auto rounded-2xl border border-gray-100 shadow-sm">
                <table class="w-full text-sm text-left border-collapse min-w-[800px]">
                    <thead class="bg-gray-50 text-pink-dark text-xs uppercase tracking-wider font-semibold">
                        <tr>
                            <th class="px-6 py-4 border-b border-gray-100">#</th>
                            <th class="px-6 py-4 border-b border-gray-100">Customer</th>
                            <th class="px-6 py-4 border-b border-gray-100">Venue</th>
                            <th class="px-6 py-4 border-b border-gray-100">Amount</th>
                            <th class="px-6 py-4 border-b border-gray-100">Method</th>
                            <th class="px-6 py-4 border-b border-gray-100">Status</th>
                            <th class="px-6 py-4 border-b border-gray-100">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; while ($row = mysqli_fetch_assoc($payments)): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 border-b border-gray-100 text-text"><?= $i++ ?></td>
                            <td class="px-6 py-4 border-b border-gray-100 text-text"><?= htmlspecialchars($row['full_name']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-100 text-text"><?= htmlspecialchars($row['venue_name']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-100 text-text">RM<?= number_format($row['amount'], 2) ?></td>
                            <td class="px-6 py-4 border-b border-gray-100 text-text"><?= htmlspecialchars($row['method']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-100 text-text">
                                <span class="badge badge-<?= $row['status'] === 'paid' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($row['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 border-b border-gray-100 text-text"><?= date('d M Y', strtotime($row['paid_at'])) ?></td>
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
