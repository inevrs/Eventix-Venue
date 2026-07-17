<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin('manager');

$manager_id = $_SESSION['user_id'];

$total = mysqli_fetch_row(mysqli_query($connect, "
    SELECT COALESCE(SUM(p.amount),0) FROM payments p
    JOIN bookings b ON p.booking_id=b.id
    JOIN venues v ON b.venue_id=v.id
    WHERE v.manager_id=$manager_id AND p.status='paid'
"))[0];

$avg_rating = mysqli_fetch_row(mysqli_query($connect, "
    SELECT COALESCE(AVG(r.rating),0) FROM ratings r
    JOIN venues v ON r.venue_id=v.id
    WHERE v.manager_id=$manager_id
"))[0];

$payments = mysqli_query($connect, "
    SELECT p.*, u.full_name, v.name AS venue_name
    FROM payments p
    JOIN bookings b ON p.booking_id=b.id
    JOIN users u ON b.user_id=u.id
    JOIN venues v ON b.venue_id=v.id
    WHERE v.manager_id=$manager_id
    ORDER BY p.paid_at DESC
");

$ratings = mysqli_query($connect, "
    SELECT r.*, u.full_name, v.name AS venue_name
    FROM ratings r
    JOIN users u ON r.user_id=u.id
    JOIN venues v ON r.venue_id=v.id
    WHERE v.manager_id=$manager_id
    ORDER BY r.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Earnings — Eventix</title>
    <?php include '../includes/header_scripts.php'; ?>
    <style>
        #printSummary { display: none; }
        @media print {
            body { visibility: hidden; }
            #printSummary, #printSummary * { visibility: visible; }
            #printSummary {
                display: block !important;
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<div class="flex min-h-screen pt-24">
    <aside class="w-64 bg-white border-r border-gray-100 shrink-0 py-8 shadow-sm  z-10">
        <p class="text-[10px] tracking-widest text-text-muted font-bold uppercase mb-3 px-8">Overview</p>
        <ul class="list-none p-0 m-0 mb-8 flex flex-col gap-1 px-4">
            <li><a href="dashboard.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-text-muted hover:bg-gray-50 hover:text-text">Dashboard</a></li>
        </ul>
        <p class="text-[10px] tracking-widest text-text-muted font-bold uppercase mb-3 px-8">My Business</p>
        <ul class="list-none p-0 m-0 mb-8 flex flex-col gap-1 px-4">
            <li><a href="venues.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-text-muted hover:bg-gray-50 hover:text-text">My Venues</a></li>
            <li><a href="bookings.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-text-muted hover:bg-gray-50 hover:text-text">Bookings</a></li>
            <li><a href="earnings.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all bg-pink-main/10 text-pink-main font-semibold">Earnings</a></li>
        </ul>
    </aside>

    <main class="flex-1 p-10 overflow-y-auto">
        <div class="flex justify-between items-center mb-10" data-aos="fade-down">
            <div>
                <h1 class="font-[Playfair_Display] text-4xl text-pink-dark mb-2">Earnings & Ratings</h1>
                <p class="text-text-muted text-sm no-print">Review your business performance and customer reviews</p>
            </div>
            <button onclick="window.print()" class="bg-pink-main text-white px-5 py-2.5 rounded-full font-semibold text-xs hover:bg-pink-dark transition-all transform hover:-translate-y-px active:scale-95 shadow-md no-print">
                🖨️ Print Report
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8" data-aos="fade-up">
            <div class="bg-white border border-gray-100 rounded-2xl  p-6 shadow-soft hover:shadow-hover transition-shadow">
                <div class="text-xs tracking-wider text-text-muted font-bold uppercase mb-2">Total Earnings</div>
                <div class="font-[Playfair_Display] text-4xl text-pink-dark">RM<?= number_format($total, 0) ?></div>
                <div class="text-xs text-text-muted mt-2">Confirmed payments</div>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl  p-6 shadow-soft hover:shadow-hover transition-shadow">
                <div class="text-xs tracking-wider text-text-muted font-bold uppercase mb-2">Avg Rating</div>
                <div class="font-[Playfair_Display] text-4xl text-pink-dark"><?= number_format($avg_rating, 1) ?></div>
                <div class="text-xs text-text-muted mt-2">⭐ across all venues</div>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl  p-8 shadow-soft mb-8" style="margin-bottom:28px">
            <h2 class="font-[Playfair_Display] text-2xl text-pink-dark mb-6">Payment History</h2>
            <div class="overflow-x-auto rounded-2xl border border-gray-100 shadow-sm">
                <table class="w-full text-sm text-left border-collapse min-w-[800px]">
                    <thead class="bg-gray-50 text-pink-dark text-xs uppercase tracking-wider font-semibold">
                        <tr><th class="px-6 py-4 border-b border-gray-100">Customer</th><th class="px-6 py-4 border-b border-gray-100">Venue</th><th class="px-6 py-4 border-b border-gray-100">Amount</th><th class="px-6 py-4 border-b border-gray-100">Method</th><th class="px-6 py-4 border-b border-gray-100">Status</th><th class="px-6 py-4 border-b border-gray-100">Date</th></tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($payments)): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 border-b border-gray-100 text-text"><?= htmlspecialchars($row['full_name']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-100 text-text"><?= htmlspecialchars($row['venue_name']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-100 text-text">RM<?= number_format($row['amount'], 2) ?></td>
                            <td class="px-6 py-4 border-b border-gray-100 text-text"><?= htmlspecialchars($row['method']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-100 text-text"><span class="badge badge-<?= $row['status']==='paid'?'success':'warning' ?>"><?= ucfirst($row['status']) ?></span></td>
                            <td class="px-6 py-4 border-b border-gray-100 text-text"><?= date('d M Y', strtotime($row['paid_at'])) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl  p-8 shadow-soft mb-8">
            <h2 class="font-[Playfair_Display] text-2xl text-pink-dark mb-6">Customer Reviews</h2>
            <div class="overflow-x-auto rounded-2xl border border-gray-100 shadow-sm">
                <table class="w-full text-sm text-left border-collapse min-w-[800px]">
                    <thead class="bg-gray-50 text-pink-dark text-xs uppercase tracking-wider font-semibold">
                        <tr><th class="px-6 py-4 border-b border-gray-100">Customer</th><th class="px-6 py-4 border-b border-gray-100">Venue</th><th class="px-6 py-4 border-b border-gray-100">Rating</th><th class="px-6 py-4 border-b border-gray-100">Review</th><th class="px-6 py-4 border-b border-gray-100">Date</th></tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($ratings)): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 border-b border-gray-100 text-text"><?= htmlspecialchars($row['full_name']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-100 text-text"><?= htmlspecialchars($row['venue_name']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-100 text-text">⭐ <?= $row['rating'] ?>/5</td>
                            <td class="px-6 py-4 border-b border-gray-100 text-text"><?= htmlspecialchars($row['review'] ?? '—') ?></td>
                            <td class="px-6 py-4 border-b border-gray-100 text-text"><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Print-Only Summary -->
<div id="printSummary">
    <div style="text-align: center; border-bottom: 2px solid #db2777; padding-bottom: 15px; margin-bottom: 25px;">
        <h1 style="font-family: 'Playfair Display', serif; font-size: 28px; color: #db2777; margin: 0 0 5px 0;">Eventix Business Report</h1>
        <p style="font-size: 14px; color: #6b7280; margin: 0;">Generated on <?= date('d M Y, h:i A') ?></p>
    </div>
    
    <div style="margin-bottom: 25px;">
        <h2 style="font-size: 18px; color: #111827; margin: 0 0 10px 0;">Manager Summary</h2>
        <p style="font-size: 14px; margin: 3px 0;"><strong>Manager Name:</strong> <?= htmlspecialchars($_SESSION['name']) ?></p>
        <p style="font-size: 14px; margin: 3px 0;"><strong>Role:</strong> Venue Manager</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px;">
        <div style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 15px; text-align: center;">
            <div style="font-size: 12px; color: #6b7280; text-transform: uppercase; font-weight: bold; margin-bottom: 5px;">Total Earnings</div>
            <div style="font-size: 24px; color: #db2777; font-weight: bold;">RM<?= number_format($total, 2) ?></div>
        </div>
        <div style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 15px; text-align: center;">
            <div style="font-size: 12px; color: #6b7280; text-transform: uppercase; font-weight: bold; margin-bottom: 5px;">Average Rating</div>
            <div style="font-size: 24px; color: #db2777; font-weight: bold;"><?= number_format($avg_rating, 1) ?> / 5.0 ⭐</div>
        </div>
    </div>
    
    <div style="border-top: 1px dashed #e5e7eb; padding-top: 15px; text-align: center; font-size: 12px; color: #9ca3af;">
        Eventix System — Secure Coursework Verification Report
    </div>
</div>

<?php include '../includes/footer_scripts.php'; ?>
</body>
</html>
