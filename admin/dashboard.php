<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin('admin');

$total_users    = mysqli_fetch_row(mysqli_query($connect, "SELECT COUNT(*) FROM users WHERE role != 'admin'"))[0];
$total_venues   = mysqli_fetch_row(mysqli_query($connect, "SELECT COUNT(*) FROM venues"))[0];
$total_bookings = mysqli_fetch_row(mysqli_query($connect, "SELECT COUNT(*) FROM bookings"))[0];
$total_revenue  = mysqli_fetch_row(mysqli_query($connect, "SELECT COALESCE(SUM(amount),0) FROM payments WHERE status='paid'"))[0];

// Fetch data for Chart (Booking Status Distribution)
$chart_query = mysqli_query($connect, "SELECT status, COUNT(*) as count FROM bookings GROUP BY status");
$chart_labels = [];
$chart_data = [];
while ($row = mysqli_fetch_assoc($chart_query)) {
    $chart_labels[] = ucfirst($row['status']);
    $chart_data[] = $row['count'];
}

$recent_bookings = mysqli_query($connect, "
    SELECT b.*, u.full_name, v.name AS venue_name
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    JOIN venues v ON b.venue_id = v.id
    ORDER BY b.created_at DESC LIMIT 8
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — Eventix</title>
    <?php include '../includes/header_scripts.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<div class="flex min-h-screen pt-24">
    <aside class="w-64 bg-white border-r border-gray-100 shrink-0 py-8 shadow-sm  z-10">
        <p class="text-[10px] tracking-widest text-text-muted font-bold uppercase mb-3 px-8 mt-6">Overview</p>
        <ul class="list-none p-0 m-0 flex flex-col gap-1 px-4">
            <li><a href="dashboard.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all bg-pink-main/10 text-pink-main font-semibold">Dashboard</a></li>
        </ul>
        <p class="text-[10px] tracking-widest text-text-muted font-bold uppercase mb-3 px-8 mt-6">Manage</p>
        <ul class="list-none p-0 m-0 flex flex-col gap-1 px-4">
            <li><a href="users.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-text-muted hover:bg-gray-50 hover:text-text">Users</a></li>
            <li><a href="venues.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-text-muted hover:bg-gray-50 hover:text-text">Venues</a></li>
            <li><a href="bookings.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-text-muted hover:bg-gray-50 hover:text-text">Bookings</a></li>
            <li><a href="payments.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-text-muted hover:bg-gray-50 hover:text-text">Payments</a></li>
            <li><a href="reports.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-text-muted hover:bg-gray-50 hover:text-text">Reports</a></li>
        </ul>
    </aside>

    <main class="flex-1 p-10 overflow-y-auto">
        <div class="mb-10" data-aos="fade-down">
            <h1 class="font-[Playfair_Display] text-4xl text-pink-dark mb-2">Dashboard</h1>
            <p class="text-text-muted text-sm">Overview of the Eventix platform</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8" data-aos="fade-up">
            <div class="bg-white border border-gray-100 rounded-2xl  p-6 shadow-soft hover:shadow-hover transition-shadow">
                <div class="text-xs tracking-wider text-text-muted font-bold uppercase mb-2">Total Users</div>
                <div class="font-[Playfair_Display] text-4xl text-pink-dark"><?= $total_users ?></div>
                <div class="text-xs text-text-muted mt-2">Customers & managers</div>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl  p-6 shadow-soft hover:shadow-hover transition-shadow">
                <div class="text-xs tracking-wider text-text-muted font-bold uppercase mb-2">Venues</div>
                <div class="font-[Playfair_Display] text-4xl text-pink-dark"><?= $total_venues ?></div>
                <div class="text-xs text-text-muted mt-2">Listed on platform</div>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl  p-6 shadow-soft hover:shadow-hover transition-shadow">
                <div class="text-xs tracking-wider text-text-muted font-bold uppercase mb-2">Bookings</div>
                <div class="font-[Playfair_Display] text-4xl text-pink-dark"><?= $total_bookings ?></div>
                <div class="text-xs text-text-muted mt-2">All time</div>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl  p-6 shadow-soft hover:shadow-hover transition-shadow">
                <div class="text-xs tracking-wider text-text-muted font-bold uppercase mb-2">Revenue</div>
                <div class="font-[Playfair_Display] text-4xl text-pink-dark">RM<?= number_format($total_revenue, 0) ?></div>
                <div class="text-xs text-text-muted mt-2">Confirmed payments</div>
            </div>
        </div>

        <!-- Chart Section for STA116 Integration -->
        <div class="bg-white border border-gray-100 rounded-2xl  p-8 shadow-soft mb-8" style="margin-bottom: 24px; max-width: 600px;" data-aos="fade-up">
            <h2 class="font-[Playfair_Display] text-2xl text-pink-dark mb-6">Booking Status Distribution (STA116 Integration)</h2>
            <p style="color:var(--text-muted);font-size:14px;margin-bottom:16px;">This chart visualizes the frequency distribution of booking statuses.</p>
            <canvas id="statusChart"></canvas>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl  p-8 shadow-soft mb-8" data-aos="fade-up">
            <h2 class="font-[Playfair_Display] text-2xl text-pink-dark mb-6">Recent Bookings</h2>
            <div class="overflow-x-auto rounded-2xl border border-gray-100 shadow-sm">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 border-b border-gray-100 text-pink-dark text-xs uppercase tracking-wider font-semibold bg-gray-50">Customer</th>
                            <th class="px-6 py-4 border-b border-gray-100 text-pink-dark text-xs uppercase tracking-wider font-semibold bg-gray-50">Venue</th>
                            <th class="px-6 py-4 border-b border-gray-100 text-pink-dark text-xs uppercase tracking-wider font-semibold bg-gray-50">Date</th>
                            <th class="px-6 py-4 border-b border-gray-100 text-pink-dark text-xs uppercase tracking-wider font-semibold bg-gray-50">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($recent_bookings)): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 border-b border-gray-100"><?= htmlspecialchars($row['full_name']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-100"><?= htmlspecialchars($row['venue_name']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-100"><?= date('d M Y', strtotime($row['start_date'])) ?> to <?= date('d M Y', strtotime($row['end_date'])) ?></td>
                            <td class="px-6 py-4 border-b border-gray-100">
                                <span class="<?= $row['status']==='confirmed' ? 'bg-green-100 text-green-700' : ($row['status']==='pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') ?> px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider">
                                    <?= ucfirst($row['status']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script src="/eventix/js/admin_dashboard.js"></script>
<script>
initAdminChart(<?= json_encode($chart_labels) ?>, <?= json_encode($chart_data) ?>);
</script>

<?php include '../includes/footer_scripts.php'; ?>
</body>
</html>
