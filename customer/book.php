<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin('customer');

$id    = (int)($_GET['id'] ?? 0);
$venue = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM venues WHERE id=$id AND status='active'"));
if (!$venue) { header("Location: venues.php"); exit(); }

// Parse addons from URL
$addon_ids  = [];
$addon_rows = [];
$addons_total = 0;

if (!empty($_GET['addons'])) {
    $raw = array_filter(array_map('intval', explode(',', $_GET['addons'])));
    if (!empty($raw)) {
        $in = implode(',', $raw);
        $res = mysqli_query($connect, "SELECT * FROM addons WHERE id IN ($in)");
        while ($a = mysqli_fetch_assoc($res)) {
            $addon_rows[] = $a;
            $addon_ids[]  = $a['id'];
            $addons_total += $a['price'];
        }
    }
}

$grand_total = $venue['price_per_day'] + $addons_total;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id    = $_SESSION['user_id'];
    $start_date = mysqli_real_escape_string($connect, $_POST['start_date']);
    $end_date   = mysqli_real_escape_string($connect, $_POST['end_date']);
    $guests     = (int)$_POST['guest_count'];
    $notes      = mysqli_real_escape_string($connect, $_POST['notes'] ?? '');
    $post_addons = array_filter(array_map('intval', explode(',', $_POST['addon_ids'] ?? '')));

    if (strtotime($end_date) < strtotime($start_date)) {
        $error = "End date cannot be before start date.";
    } elseif ($guests > $venue['capacity']) {
        $error = "Guest count exceeds venue capacity of {$venue['capacity']}.";
    } else {
        $sql = "INSERT INTO bookings (user_id, venue_id, start_date, end_date, guest_count, notes, status)
                VALUES ($user_id, $id, '$start_date', '$end_date', $guests, '$notes', 'pending')";
        if (mysqli_query($connect, $sql)) {
            $booking_id = mysqli_insert_id($connect);

            // Save addons
            foreach ($post_addons as $aid) {
                $aprice = mysqli_fetch_row(mysqli_query($connect, "SELECT price FROM addons WHERE id=$aid"))[0] ?? 0;
                mysqli_query($connect, "INSERT INTO booking_addons (booking_id, addon_id, price) VALUES ($booking_id, $aid, $aprice)");
            }

            header("Location: payment.php?booking_id=$booking_id");
            exit();
        } else {
            $error = "Booking failed. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Venue — Eventix</title>
    <?php include '../includes/header_scripts.php'; ?>
    <style>
        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            padding: 7px 0;
            border-bottom: 1px solid var(--pink-light);
        }

        .summary-row:last-child { border-bottom: none; }
        .summary-row.total { font-weight: 700; font-size: 16px; color: var(--pink-main); padding-top: 12px; }

        .addon-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--pink-light);
            color: var(--pink-dark);
            border-radius: 20px;
            padding: 5px 12px;
            font-size: 12px;
            font-weight: 500;
            margin: 4px 4px 4px 0;
        }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<div class="max-w-[860px] mx-auto px-6 py-10 mt-24"  style="max-width:860px" data-aos="fade-up">
    <div class="mb-10" data-aos="fade-down">
        <h1 class="font-[Playfair_Display] text-4xl text-pink-dark mb-2">Complete Your Booking</h1>
        <div style="margin-bottom: 24px;">
            <a href="venue_detail.php?id=<?= $id ?>" class="border-2 border-pink-light text-pink-main px-4 py-1.5 rounded-full font-semibold text-xs hover:border-pink-main hover:bg-pink-50 transition-colors inline-block" style="display: inline-flex; align-items: center; gap: 8px;">
                <span>←</span> Back to Venue
            </a>
        </div>
    </div>

    <?php if ($error): ?><div class="bg-red-50 text-red-600 px-4 py-3 rounded-lg text-sm mb-6"><?= $error ?></div><?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_340px] gap-8 items-start">

        <div class="bg-white border border-gray-100 rounded-2xl  p-6 shadow-soft" data-aos="fade-up">
            <h2 style="font-family:'Playfair Display',serif;color:var(--pink-dark);margin-bottom:24px">Your Details</h2>
            <form method="POST" onsubmit="return validateBooking()">
                <input type="hidden" name="addon_ids" value="<?= implode(',', $addon_ids) ?>">
                <div style="display: flex; gap: 16px;">
                    <div class="mb-5" style="flex: 1;">
                        <label>Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="w-full px-5 py-3.5 border border-gray-200 rounded-xl text-sm font-sans focus:border-pink-main focus:ring-2 focus:ring-pink-main/10 outline-none transition-all" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                    </div>
                    <div class="mb-5" style="flex: 1;">
                        <label>End Date</label>
                        <input type="date" name="end_date" id="end_date" class="w-full px-5 py-3.5 border border-gray-200 rounded-xl text-sm font-sans focus:border-pink-main focus:ring-2 focus:ring-pink-main/10 outline-none transition-all" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                    </div>
                </div>
                <div class="mb-5">
                    <label>Number of Guests</label>
                    <input type="number" name="guest_count" class="w-full px-5 py-3.5 border border-gray-200 rounded-xl text-sm font-sans focus:border-pink-main focus:ring-2 focus:ring-pink-main/10 outline-none transition-all" min="1" max="<?= $venue['capacity'] ?>" placeholder="Max <?= $venue['capacity'] ?>" required>
                </div>
                <div class="mb-5">
                    <label>Additional Notes</label>
                    <textarea name="notes" class="w-full px-5 py-3.5 border border-gray-200 rounded-xl text-sm font-sans focus:border-pink-main focus:ring-2 focus:ring-pink-main/10 outline-none transition-all" rows="3" placeholder="Any special requirements..."></textarea>
                </div>
                <button type="submit" class="bg-pink-main text-white px-6 py-2.5 rounded-full hover:bg-pink-dark transition-all hover:-translate-y-px active:scale-95 shadow-md hover:shadow-lg" style="width:100%">Proceed to Payment →</button>
            </form>
        </div>

        <!-- Order Summary -->
        <div class="bg-white border border-gray-100 rounded-2xl  p-6 shadow-soft" style="position:sticky;top:84px" data-aos="fade-up">
            <h2 style="font-family:'Playfair Display',serif;color:var(--pink-dark);margin-bottom:20px">Order Summary</h2>

            <div style="margin-bottom:16px">
                <div style="font-weight:600;font-size:15px;color:var(--text);margin-bottom:4px"><?= htmlspecialchars($venue['name']) ?></div>
                <div style="font-size:13px;color:var(--text-muted)">📍 <?= htmlspecialchars($venue['location']) ?></div>
            </div>

            <div class="summary-row">
                <span id="venue-price-label">Venue (1 day)</span>
                <span id="venue-price-display">RM<?= number_format($venue['price_per_day'], 2) ?></span>
            </div>

            <?php foreach ($addon_rows as $a): ?>
            <div class="summary-row">
                <span><?= $a['icon'] ?> <?= htmlspecialchars($a['name']) ?></span>
                <span>+RM<?= number_format($a['price'], 2) ?></span>
            </div>
            <?php endforeach; ?>

            <div class="summary-row total">
                <span>Total</span>
                <span id="grand-total-display">RM<?= number_format($grand_total, 2) ?></span>
            </div>

            <?php if (!empty($addon_rows)): ?>
            <div style="margin-top:16px">
                <p style="font-size:11px;letter-spacing:1px;text-transform:uppercase;color:var(--text-muted);margin-bottom:8px">Selected Add-ons</p>
                <?php foreach ($addon_rows as $a): ?>
                <span class="addon-pill"><?= $a['icon'] ?> <?= htmlspecialchars($a['name']) ?></span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function validateBooking() {
    const start = document.getElementById('start_date').value;
    const end = document.getElementById('end_date').value;
    const guests = document.querySelector('input[name="guest_count"]').value;
    let errors = [];

    if (!start) errors.push('Please select a start date.');
    if (!end) errors.push('Please select an end date.');
    if (start && end && new Date(end) < new Date(start)) errors.push('End date cannot be before start date.');
    if (!guests || guests < 1) errors.push('Please enter the number of guests.');

    if (errors.length > 0) {
        let existing = document.getElementById('js-error');
        if (existing) existing.remove();
        let div = document.createElement('div');
        div.id = 'js-error';
        div.className = 'bg-red-50 text-red-600 px-4 py-3 rounded-lg text-sm mb-6';
        div.innerHTML = errors.join('<br>');
        document.querySelector('form').prepend(div);
        return false;
    }
    return true;
}
</script>
<script src="/eventix/js/booking.js"></script>
<script>initBookingCalc(<?= $venue['price_per_day'] ?>, <?= $addons_total ?>);</script>
<?php include '../includes/footer_scripts.php'; ?>
</body>
</html>
