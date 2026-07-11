<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin('manager');

$manager_id = $_SESSION['user_id'];

$total_venues   = mysqli_fetch_row(mysqli_query($connect, "SELECT COUNT(*) FROM venues WHERE manager_id=$manager_id"))[0];
$total_bookings = mysqli_fetch_row(mysqli_query($connect, "SELECT COUNT(*) FROM bookings b JOIN venues v ON b.venue_id=v.id WHERE v.manager_id=$manager_id"))[0];
$total_earnings = mysqli_fetch_row(mysqli_query($connect, "SELECT COALESCE(SUM(p.amount),0) FROM payments p JOIN bookings b ON p.booking_id=b.id JOIN venues v ON b.venue_id=v.id WHERE v.manager_id=$manager_id AND p.status='paid'"))[0];
$avg_rating     = mysqli_fetch_row(mysqli_query($connect, "SELECT COALESCE(AVG(r.rating),0) FROM ratings r JOIN venues v ON r.venue_id=v.id WHERE v.manager_id=$manager_id"))[0];

// Fetch data for Chart (Bookings per Venue)
$chart_query = mysqli_query($connect, "SELECT v.name, COUNT(b.id) as count FROM venues v LEFT JOIN bookings b ON v.id=b.venue_id WHERE v.manager_id=$manager_id GROUP BY v.id");
$chart_labels = [];
$chart_data = [];
while ($row = mysqli_fetch_assoc($chart_query)) {
    // Truncate long venue names for chart
    $name = strlen($row['name']) > 15 ? substr($row['name'], 0, 15) . '...' : $row['name'];
    $chart_labels[] = $name;
    $chart_data[] = $row['count'];
}

$recent = mysqli_query($connect, "
    SELECT b.*, u.full_name, v.name AS venue_name
    FROM bookings b
    JOIN users u ON b.user_id=u.id
    JOIN venues v ON b.venue_id=v.id
    WHERE v.manager_id=$manager_id
    ORDER BY b.created_at DESC LIMIT 6
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manager Dashboard — Eventix</title>
    <?php include '../includes/header_scripts.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<div class="flex min-h-screen pt-24">
    <aside class="w-64 bg-white border-r border-gray-100 shrink-0 py-8 shadow-sm  z-10">
        <p class="text-[10px] tracking-widest text-text-muted font-bold uppercase mb-3 px-8">Overview</p>
        <ul class="list-none p-0 m-0 mb-8 flex flex-col gap-1 px-4">
            <li><a href="dashboard.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all bg-pink-main/10 text-pink-main font-semibold">Dashboard</a></li>
        </ul>
        <p class="text-[10px] tracking-widest text-text-muted font-bold uppercase mb-3 px-8">My Business</p>
        <ul class="list-none p-0 m-0 mb-8 flex flex-col gap-1 px-4">
            <li><a href="venues.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-text-muted hover:bg-gray-50 hover:text-text">My Venues</a></li>
            <li><a href="bookings.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-text-muted hover:bg-gray-50 hover:text-text">Bookings</a></li>
            <li><a href="earnings.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-text-muted hover:bg-gray-50 hover:text-text">Earnings</a></li>
        </ul>
    </aside>

    <main class="flex-1 p-10 overflow-y-auto">
        <div class="mb-10" data-aos="fade-down">
            <h1 class="font-[Playfair_Display] text-4xl text-pink-dark mb-2">Welcome, <?= htmlspecialchars($_SESSION['name']) ?></h1>
            <p>Here's how your venues are performing</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8" data-aos="fade-up">
            <div class="bg-white border border-gray-100 rounded-2xl  p-6 shadow-soft hover:shadow-hover transition-shadow">
                <div class="text-xs tracking-wider text-text-muted font-bold uppercase mb-2">My Venues</div>
                <div class="font-[Playfair_Display] text-4xl text-pink-dark"><?= $total_venues ?></div>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl  p-6 shadow-soft hover:shadow-hover transition-shadow">
                <div class="text-xs tracking-wider text-text-muted font-bold uppercase mb-2">Total Bookings</div>
                <div class="font-[Playfair_Display] text-4xl text-pink-dark"><?= $total_bookings ?></div>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl  p-6 shadow-soft hover:shadow-hover transition-shadow">
                <div class="text-xs tracking-wider text-text-muted font-bold uppercase mb-2">Earnings</div>
                <div class="font-[Playfair_Display] text-4xl text-pink-dark">RM<?= number_format($total_earnings, 0) ?></div>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl  p-6 shadow-soft hover:shadow-hover transition-shadow">
                <div class="text-xs tracking-wider text-text-muted font-bold uppercase mb-2">Avg Rating</div>
                <div class="font-[Playfair_Display] text-4xl text-pink-dark"><?= number_format($avg_rating, 1) ?></div>
                <div class="text-xs text-text-muted mt-2">⭐ across all venues</div>
            </div>
        </div>

        <!-- Chart Section for STA116 Integration -->
        <div class="bg-white border border-gray-100 rounded-2xl  p-8 shadow-soft mb-8" style="margin-bottom: 24px;">
            <h2 class="font-[Playfair_Display] text-2xl text-pink-dark mb-6">Bookings per Venue (STA116 Integration)</h2>
            <p style="color:var(--text-muted);font-size:14px;margin-bottom:16px;">This bar chart represents the frequency of bookings across your different venues.</p>
            <div style="height: 300px;">
                <canvas id="venueChart"></canvas>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl  p-8 shadow-soft mb-8">
            <h2 class="font-[Playfair_Display] text-2xl text-pink-dark mb-6">Recent Bookings</h2>
            <div class="overflow-x-auto rounded-2xl border border-gray-100 shadow-sm">
                <table class="w-full text-sm text-left border-collapse min-w-[800px]">
                    <thead class="bg-gray-50 text-pink-dark text-xs uppercase tracking-wider font-semibold">
                        <tr>
                            <th class="px-6 py-4 border-b border-gray-100">Customer</th>
                            <th class="px-6 py-4 border-b border-gray-100">Venue</th>
                            <th class="px-6 py-4 border-b border-gray-100">Event Date</th>
                            <th class="px-6 py-4 border-b border-gray-100">Guests</th>
                            <th class="px-6 py-4 border-b border-gray-100">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($recent)): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 border-b border-gray-100 text-text"><?= htmlspecialchars($row['full_name']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-100 text-text"><?= htmlspecialchars($row['venue_name']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-100 text-text"><?= date('d M Y', strtotime($row['start_date'])) ?> to <?= date('d M Y', strtotime($row['end_date'])) ?></td>
                            <td class="px-6 py-4 border-b border-gray-100 text-text"><?= $row['guest_count'] ?></td>
                            <td class="px-6 py-4 border-b border-gray-100 text-text">
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

<script src="/eventix/js/manager_dashboard.js"></script>
<script>
initManagerChart(<?= json_encode($chart_labels) ?>, <?= json_encode($chart_data) ?>);
</script>

<?php include '../includes/footer_scripts.php'; ?>
</body>
</html>
