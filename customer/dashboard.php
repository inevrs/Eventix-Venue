<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin('customer');

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'] ?? 'Customer';

$summaryQuery = mysqli_query($connect, "SELECT
    COUNT(*) AS total_bookings,
    SUM(CASE WHEN status='confirmed' THEN 1 ELSE 0 END) AS confirmed_bookings,
    SUM(CASE WHEN status='cancelled' THEN 1 ELSE 0 END) AS cancelled_bookings
    FROM bookings WHERE user_id=$user_id");
$summary = mysqli_fetch_assoc($summaryQuery) ?: ['total_bookings' => 0, 'confirmed_bookings' => 0, 'cancelled_bookings' => 0];
$reviews_written = (int)mysqli_fetch_row(mysqli_query($connect, "SELECT COUNT(*) FROM ratings WHERE user_id=$user_id"))[0];

$favoriteVenuesResult = mysqli_query($connect, "
    SELECT v.id, v.name, v.location, COUNT(*) AS booking_count
    FROM bookings b
    JOIN venues v ON b.venue_id=v.id
    WHERE b.user_id=$user_id
    GROUP BY v.id
    ORDER BY booking_count DESC, MAX(b.created_at) DESC
    LIMIT 4
");
$favoriteVenues = [];
while ($venue = mysqli_fetch_assoc($favoriteVenuesResult)) {
    $favoriteVenues[] = $venue;
}

$upcomingEventsResult = mysqli_query($connect, "
    SELECT b.id, b.start_date, b.end_date, v.name
    FROM bookings b
    JOIN venues v ON b.venue_id=v.id
    WHERE b.user_id=$user_id AND b.status='confirmed' AND b.start_date >= CURDATE()
    ORDER BY b.start_date ASC
    LIMIT 3
");
$upcomingEvents = [];
while ($row = mysqli_fetch_assoc($upcomingEventsResult)) {
    $upcomingEvents[] = $row;
}

$recentActivity = [];
$bookingActivity = mysqli_query($connect, "
    SELECT b.id, b.status, b.created_at, v.name
    FROM bookings b
    JOIN venues v ON b.venue_id=v.id
    WHERE b.user_id=$user_id
    ORDER BY b.created_at DESC
    LIMIT 3
");
while ($row = mysqli_fetch_assoc($bookingActivity)) {
    $recentActivity[] = [
        'type' => 'booking',
        'title' => ucfirst($row['status']) . ' booking for ' . $row['name'],
        'time' => $row['created_at'],
        'meta' => ''
    ];
}

$reviewActivity = mysqli_query($connect, "
    SELECT r.id, r.rating, r.review, r.created_at, v.name
    FROM ratings r
    JOIN venues v ON r.venue_id=v.id
    WHERE r.user_id=$user_id
    ORDER BY r.created_at DESC
    LIMIT 2
");
while ($row = mysqli_fetch_assoc($reviewActivity)) {
    $recentActivity[] = [
        'type' => 'review',
        'title' => 'Review added for ' . $row['name'],
        'time' => $row['created_at'],
        'meta' => str_repeat('⭐', $row['rating'])
    ];
}

usort($recentActivity, function ($a, $b) {
    return strcmp($b['time'], $a['time']);
});

$favoriteCount = count($favoriteVenues);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Eventix</title>
    <?php include '../includes/header_scripts.php'; ?>
</head>
<body class="text-text font-sans antialiased min-h-screen bg-[#fffafa]">

<?php include '../includes/navbar.php'; ?>

<div class="max-w-7xl mx-auto px-6 py-10 mt-24">
    <div class="mb-10" data-aos="fade-down">
        <h1 class="font-[Playfair_Display] text-4xl text-pink-dark mb-3">Welcome back, <?= htmlspecialchars($user_name) ?></h1>
        <p class="text-text-muted text-sm">Here’s everything you need to manage your bookings, reviews, and profile.</p>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-4">
        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-soft">
            <p class="text-xs uppercase font-semibold tracking-[0.3em] text-text-muted mb-4">Total bookings</p>
            <div class="text-4xl font-bold text-pink-main"><?= number_format($summary['total_bookings']) ?></div>
            <p class="text-sm text-text-muted mt-2">All booking requests created by you.</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-soft">
            <p class="text-xs uppercase font-semibold tracking-[0.3em] text-text-muted mb-4">Completed bookings</p>
            <div class="text-4xl font-bold text-pink-main"><?= number_format($summary['confirmed_bookings']) ?></div>
            <p class="text-sm text-text-muted mt-2">Confirmed bookings that are ready to enjoy.</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-soft">
            <p class="text-xs uppercase font-semibold tracking-[0.3em] text-text-muted mb-4">Reviews written</p>
            <div class="text-4xl font-bold text-pink-main"><?= number_format($reviews_written) ?></div>
            <p class="text-sm text-text-muted mt-2">Venue impressions shared with hosts and other guests.</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-soft">
            <p class="text-xs uppercase font-semibold tracking-[0.3em] text-text-muted mb-4">Favorite venues</p>
            <div class="text-4xl font-bold text-pink-main"><?= number_format($favoriteCount) ?></div>
            <p class="text-sm text-text-muted mt-2">Most booked venues from your past reservations.</p>
        </div>
    </div>

    <div class="grid gap-8 xl:grid-cols-[1.2fr_0.9fr] mt-10">
        <section class="space-y-8">
            <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-soft" data-aos="fade-up">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
                    <div>
                        <h2 class="font-[Playfair_Display] text-2xl text-pink-dark">Notifications</h2>
                        <p class="text-sm text-text-muted">Recent booking and review activity.</p>
                    </div>
                    <a href="my_bookings.php" class="text-pink-main font-semibold text-sm hover:underline">View all bookings</a>
                </div>
                <?php if (empty($recentActivity)): ?>
                    <div class="rounded-3xl bg-pink-light/40 p-6 text-sm text-text-muted">No recent activity yet. Start by booking a venue or writing a review.</div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($recentActivity as $event): ?>
                            <div class="rounded-3xl border border-gray-100 bg-white p-5 shadow-sm">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-semibold text-text"><?= htmlspecialchars($event['title']) ?></p>
                                        <p class="text-xs text-text-muted mt-1"><?= date('j M Y, H:i', strtotime($event['time'])) ?></p>
                                    </div>
                                    <?php if ($event['meta']): ?>
                                        <span class="text-xs font-semibold text-pink-main"><?= htmlspecialchars($event['meta']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-soft" data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-center justify-between gap-4 mb-6">
                    <div>
                        <h2 class="font-[Playfair_Display] text-2xl text-pink-dark">Upcoming events</h2>
                        <p class="text-sm text-text-muted">Stay on top of your next confirmed bookings.</p>
                    </div>
                    <a href="my_bookings.php" class="text-pink-main font-semibold text-sm hover:underline">Manage bookings</a>
                </div>

                <?php if (empty($upcomingEvents)): ?>
                    <div class="rounded-3xl bg-pink-light/40 p-6 text-sm text-text-muted">No upcoming confirmed events. Browse venues to book your next gathering.</div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($upcomingEvents as $event): ?>
                        <div class="rounded-3xl border border-gray-100 bg-white p-5 shadow-sm">
                            <div class="flex items-center justify-between gap-3 mb-3">
                                <div>
                                    <p class="text-sm font-semibold text-text"><?= htmlspecialchars($event['name']) ?></p>
                                    <p class="text-xs text-text-muted">Starts <?= date('j M Y', strtotime($event['start_date'])) ?></p>
                                </div>
                                <span class="text-xs uppercase tracking-[0.25em] font-semibold text-pink-main">Upcoming</span>
                            </div>
                            <p class="text-sm text-text-muted">Dates: <?= date('j M Y', strtotime($event['start_date'])) ?> — <?= date('j M Y', strtotime($event['end_date'])) ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <aside class="space-y-8">
            <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-soft" data-aos="fade-up" data-aos-delay="200">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-full bg-pink-light text-pink-dark flex items-center justify-center text-2xl font-semibold">
                        <?= strtoupper(substr($user_name, 0, 1)) ?>
                    </div>
                    <div>
                        <p class="text-sm text-text-muted">Logged in as</p>
                        <p class="font-semibold text-text"><?= htmlspecialchars($user_name) ?></p>
                    </div>
                </div>
                <div class="grid gap-3">
                    <a href="venues.php" class="block rounded-2xl border border-gray-200 bg-pink-light/70 px-4 py-3 text-sm font-semibold text-pink-dark hover:bg-pink-light transition">Book a venue</a>
                    <a href="my_reviews.php" class="block rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-text hover:bg-gray-50 transition">Write a review</a>
                    <a href="my_bookings.php" class="block rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-text hover:bg-gray-50 transition">View previous bookings</a>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-soft" data-aos="fade-up" data-aos-delay="300">
                <div class="flex items-center justify-between gap-4 mb-6">
                    <h2 class="font-[Playfair_Display] text-2xl text-pink-dark">Favorite venues</h2>
                    <span class="text-xs uppercase tracking-[0.2em] font-semibold text-text-muted">Top picks</span>
                </div>
                <?php if (empty($favoriteVenues)): ?>
                    <p class="text-sm text-text-muted">Your favorite venues will appear here after your first bookings.</p>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($favoriteVenues as $venue): ?>
                        <div class="rounded-3xl border border-gray-100 bg-white p-4 shadow-sm">
                            <p class="font-semibold text-text"><?= htmlspecialchars($venue['name']) ?></p>
                            <p class="text-xs text-text-muted"><?= htmlspecialchars($venue['location']) ?></p>
                            <p class="text-xs text-pink-main mt-2">Booked <?= $venue['booking_count'] ?> time<?= $venue['booking_count'] > 1 ? 's' : '' ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </aside>
    </div>
</div>

<?php include '../includes/footer_scripts.php'; ?>
</body>
</html>
