<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin('customer');

$user_id = $_SESSION['user_id'];

$bookings = mysqli_query($connect, "
    SELECT b.*, v.name AS venue_name, v.location, v.price_per_day,
           p.status AS payment_status, p.method,
           r.rating, r.review
    FROM bookings b
    JOIN venues v ON b.venue_id=v.id
    LEFT JOIN payments p ON p.booking_id=b.id
    LEFT JOIN ratings r ON r.venue_id=v.id AND r.user_id=$user_id
    WHERE b.user_id=$user_id
    ORDER BY b.created_at DESC
");

$success = $error = '';

// Submit rating
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['venue_id'], $_POST['rating'])) {
    $venue_id = (int)$_POST['venue_id'];
    $rating   = (int)$_POST['rating'];
    $review   = mysqli_real_escape_string($connect, $_POST['review'] ?? '');

    $check = mysqli_fetch_row(mysqli_query($connect, "SELECT id FROM ratings WHERE user_id=$user_id AND venue_id=$venue_id"));
    if ($check) {
        mysqli_query($connect, "UPDATE ratings SET rating=$rating, review='$review' WHERE user_id=$user_id AND venue_id=$venue_id");
    } else {
        mysqli_query($connect, "INSERT INTO ratings (user_id, venue_id, rating, review) VALUES ($user_id, $venue_id, $rating, '$review')");
    }
    $success = "Rating submitted!";
    header("Location: my_bookings.php?rated=1");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings — Eventix</title>
    <?php include '../includes/header_scripts.php'; ?>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<div class="max-w-[860px] mx-auto px-6 py-10 mt-24"  data-aos="fade-up">
    <div class="mb-10" data-aos="fade-down">
        <h1 class="font-[Playfair_Display] text-4xl text-pink-dark mb-2">My Bookings</h1>
        <p>Track and manage your venue reservations</p>
    </div>

    <?php if (isset($_GET['rated'])): ?>
        <div class="bg-green-50 text-green-700 px-4 py-3 rounded-lg text-sm mb-6">Rating submitted successfully!</div>
    <?php endif; ?>

    <?php if (isset($_GET['booked'])): ?>
        <div class="bg-green-50 text-green-700 px-4 py-3 rounded-lg text-sm mb-6">Booking submitted successfully! Our manager will review your request. Once approved, you can complete your payment.</div>
    <?php endif; ?>

    <?php
    $rows = [];
    while ($row = mysqli_fetch_assoc($bookings)) $rows[] = $row;

    if (empty($rows)): ?>
        <div class="bg-white border border-gray-100 rounded-2xl  p-6 shadow-soft" style="text-align:center;padding:60px" data-aos="fade-up">
            <p style="font-size:40px;margin-bottom:16px">🏛️</p>
            <h2 style="font-family:'Playfair Display',serif;color:var(--pink-dark);margin-bottom:8px">No bookings yet</h2>
            <p style="color:var(--text-muted);margin-bottom:24px">Discover amazing venues and make your first booking.</p>
            <a href="venues.php" class="bg-pink-main text-white px-6 py-2.5 rounded-full hover:bg-pink-dark transition-all hover:-translate-y-px active:scale-95 shadow-md hover:shadow-lg">Browse Venues</a>
        </div>
    <?php else: ?>

    <div style="display:flex;flex-direction:column;gap:20px">
        <?php foreach ($rows as $row): ?>
        <div class="bg-white border border-gray-100 rounded-2xl  p-6 shadow-soft" data-aos="fade-up">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:16px">
                <div>
                    <h3 style="font-family:'Playfair Display',serif;font-size:20px;color:var(--pink-dark);margin-bottom:6px">
                        <?= htmlspecialchars($row['venue_name']) ?>
                    </h3>
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px">📍 <?= htmlspecialchars($row['location']) ?></p>
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px">📅 <?= date('d M Y', strtotime($row['start_date'])) ?> to <?= date('d M Y', strtotime($row['end_date'])) ?> &nbsp;·&nbsp; 👥 <?= $row['guest_count'] ?> guests</p>
                    <p style="color:var(--text-muted);font-size:13px">💳 <?= htmlspecialchars($row['method'] ?? 'Not paid') ?> &nbsp;·&nbsp; RM<?= number_format($row['price_per_day'], 2) ?></p>
                </div>
                <div style="text-align:right" class="flex flex-col items-end gap-2">
                    <span class="inline-block px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-full bg-<?= $row['status']==='confirmed'?'green-100 text-green-700':($row['status']==='pending'?'yellow-100 text-yellow-700':'red-100 text-red-700') ?>">
                        <?= ucfirst($row['status']) ?>
                    </span>
                    
                    <?php if ($row['payment_status'] === 'paid'): ?>
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Paid</span>
                        <a href="payment.php?booking_id=<?= $row['id'] ?>" class="text-xs text-pink-main font-semibold hover:underline mt-1">Print Receipt</a>
                    <?php else: ?>
                        <a href="payment.php?booking_id=<?= $row['id'] ?>" class="bg-pink-main text-white px-5 py-2 rounded-full font-semibold text-xs hover:bg-pink-dark transition-all transform hover:-translate-y-px active:scale-95 shadow-md">Pay Now</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Booking Status Timeline -->
            <div class="mt-6 border-t border-gray-100 pt-4">
                <div class="flex items-center justify-between text-[11px] font-semibold text-text-muted">
                    <!-- Step 1: Booked -->
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-green-500 text-white flex items-center justify-center text-[10px]">✓</span>
                        <span class="text-green-600">Booked</span>
                    </div>
                    <!-- Line 1 to Step 2: Payment -->
                    <div class="flex-1 h-[2px] mx-4 bg-<?= ($row['payment_status'] === 'paid') ? 'green-500' : 'gray-200' ?>"></div>
                    <!-- Step 2: Payment -->
                    <div class="flex items-center gap-2">
                        <?php if ($row['payment_status'] === 'paid'): ?>
                            <span class="w-5 h-5 rounded-full bg-green-500 text-white flex items-center justify-center text-[10px]">✓</span>
                            <span class="text-green-600 font-bold">Paid</span>
                        <?php else: ?>
                            <span class="w-5 h-5 rounded-full border-2 border-gray-300 bg-white flex items-center justify-center text-[10px]"></span>
                            <span class="text-gray-400">Payment</span>
                        <?php endif; ?>
                    </div>
                    <!-- Line 2 to Step 3: Approval -->
                    <div class="flex-1 h-[2px] mx-4 bg-<?= ($row['status'] === 'confirmed') ? 'green-500' : 'gray-200' ?>"></div>
                    <!-- Step 3: Approval -->
                    <div class="flex items-center gap-2">
                        <?php if ($row['status'] === 'confirmed'): ?>
                            <span class="w-5 h-5 rounded-full bg-green-500 text-white flex items-center justify-center text-[10px]">✓</span>
                            <span class="text-green-600">Approved</span>
                        <?php elseif ($row['status'] === 'cancelled'): ?>
                            <span class="w-5 h-5 rounded-full bg-red-500 text-white flex items-center justify-center text-[10px]">✗</span>
                            <span class="text-red-600">Rejected</span>
                        <?php else: ?>
                            <?php if ($row['payment_status'] === 'paid'): ?>
                                <span class="w-5 h-5 rounded-full border-2 border-pink-main bg-white text-pink-main flex items-center justify-center text-[10px] animate-pulse">●</span>
                                <span class="text-pink-main font-semibold">Awaiting Approval</span>
                            <?php else: ?>
                                <span class="w-5 h-5 rounded-full border-2 border-gray-300 bg-white flex items-center justify-center text-[10px]"></span>
                                <span class="text-gray-400">Approval</span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if ($row['status'] === 'confirmed'): ?>
            <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--pink-light)">
                <p style="font-size:13px;font-weight:600;color:var(--text-muted);margin-bottom:12px">YOUR REVIEW</p>
                <form method="POST" style="display:flex;gap:12px;align-items:flex-start;flex-wrap:wrap">
                    <input type="hidden" name="venue_id" value="<?= $row['venue_id'] ?>">
                    <select name="rating" style="padding:8px 12px;border:1.5px solid var(--pink-light);border-radius:8px;font-size:14px;">
                        <?php for ($i=5;$i>=1;$i--): ?>
                        <option value="<?= $i ?>" <?= $row['rating']==$i?'selected':'' ?>>⭐ <?= $i ?>/5</option>
                        <?php endfor; ?>
                    </select>
                    <input type="text" name="review" value="<?= htmlspecialchars($row['review'] ?? '') ?>" placeholder="Leave a comment..." class="w-full px-5 py-3.5 border border-gray-200 rounded-xl text-sm font-sans focus:border-pink-main focus:ring-2 focus:ring-pink-main/10 outline-none transition-all" style="flex:1;padding:8px 14px;font-size:14px;min-width:200px;">
                    <button type="submit" class="border-2 border-pink-light text-pink-main px-4 py-1.5 rounded-full font-semibold text-xs hover:border-pink-main hover:bg-pink-50 transition-colors inline-block"><?= $row['rating'] ? 'Update' : 'Submit' ?> Review</button>
                </form>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <?php endif; ?>
</div>

<?php include '../includes/footer_scripts.php'; ?>
</body>
</html>
