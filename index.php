<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';

$search = mysqli_real_escape_string($connect, $_GET['search'] ?? '');

$where = "WHERE v.status='active'";
if ($search) $where .= " AND (v.name LIKE '%$search%' OR v.location LIKE '%$search%')";

$best_picks = mysqli_query($connect, "
    SELECT v.*, 
           COALESCE(AVG(r.rating),0) AS avg_rating, 
           COUNT(DISTINCT r.id) AS review_count,
           vi.image_path AS thumbnail
    FROM venues v
    LEFT JOIN ratings r ON v.id=r.venue_id
    LEFT JOIN venue_images vi ON v.id=vi.venue_id AND vi.is_thumbnail=1
    $where
    GROUP BY v.id
    ORDER BY avg_rating DESC LIMIT 8
");

$popular_venues = mysqli_query($connect, "
    SELECT v.*, 
           COALESCE(AVG(r.rating),0) AS avg_rating, 
           COUNT(DISTINCT r.id) AS review_count,
           COUNT(DISTINCT b.id) AS booking_count,
           vi.image_path AS thumbnail
    FROM venues v
    LEFT JOIN ratings r ON v.id=r.venue_id
    LEFT JOIN bookings b ON v.id=b.venue_id
    LEFT JOIN venue_images vi ON v.id=vi.venue_id AND vi.is_thumbnail=1
    $where
    GROUP BY v.id
    ORDER BY booking_count DESC, avg_rating DESC LIMIT 4
");

$total_venues   = mysqli_fetch_row(mysqli_query($connect, "SELECT COUNT(*) FROM venues WHERE status='active'"))[0];
$total_reviews  = mysqli_fetch_row(mysqli_query($connect, "SELECT COUNT(*) FROM ratings"))[0];
$overall_rating = mysqli_fetch_row(mysqli_query($connect, "SELECT COALESCE(AVG(rating),0) FROM ratings"))[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Eventix — Book Your Perfect Venue</title>
    <?php include 'includes/header_scripts.php'; ?>
</head>
<body class="text-text font-sans antialiased min-h-screen">

<?php include 'includes/navbar.php'; ?>

<div class="max-w-7xl mx-auto px-6 py-10 mt-24">
    <div class="flex flex-col md:flex-row justify-between items-start mb-16" data-aos="fade-up">
        <div class="max-w-xl">
            <p class="text-pink-main text-xs tracking-widest uppercase mb-4 font-semibold">— Discover & Book</p>
            <h1 class="font-[Playfair_Display] text-6xl leading-tight text-pink-dark mb-4">
                Event<span class="text-pink-main">ix</span><br>Venues
            </h1>
            <p class="text-text-muted text-lg leading-relaxed mb-8">
                Extraordinary spaces for every occasion — from intimate gatherings to grand celebrations.
            </p>
            <form method="GET" action="index.php" class="flex gap-3 max-w-lg">
                <input type="text" name="search" placeholder="Search by name or location..." value="<?= htmlspecialchars($search) ?>" class="flex-1 px-5 py-3 border border-gray-200 rounded-full text-sm font-sans focus:border-pink-main focus:ring-4 focus:ring-pink-main/15 outline-none transition-all">
                <button type="submit" class="bg-pink-main text-white px-7 py-3 rounded-full font-semibold text-sm hover:bg-pink-dark active:scale-95 transition-all">Search</button>
            </form>
        </div>

        <div class="hidden md:flex flex-col gap-8 text-right pt-10" data-aos="fade-left" data-aos-delay="200">
            <div>
                <div class="font-[Playfair_Display] text-5xl text-pink-dark"><?= $total_venues ?>+</div>
                <div class="text-[11px] tracking-widest uppercase text-text-muted mt-1 font-semibold">Venues</div>
            </div>
            <div>
                <div class="font-[Playfair_Display] text-5xl text-pink-dark"><?= $total_reviews ?></div>
                <div class="text-[11px] tracking-widest uppercase text-text-muted mt-1 font-semibold">Reviews</div>
            </div>
            <div>
                <div class="font-[Playfair_Display] text-5xl text-pink-dark"><?= number_format($overall_rating,1) ?></div>
                <div class="text-[11px] tracking-widest uppercase text-text-muted mt-1 font-semibold">Avg Rating</div>
            </div>
        </div>
    </div>

    <!-- Popular Venues Section -->
    <div class="mb-16" data-aos="fade-up">
        <div class="mb-6">
            <h2 class="text-2xl font-bold tracking-tight text-text mb-1">🔥 Most Popular</h2>
            <p class="text-text-muted text-sm">Our most booked and highly-rated spaces</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php $p_delay = 0; $has_pop = false; while ($row = mysqli_fetch_assoc($popular_venues)): $has_pop = true; ?>
            <a href="venue.php?id=<?= $row['id'] ?>" class="group block bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-soft hover:shadow-hover hover:-translate-y-1.5 transition-all duration-300 relative" data-aos="fade-up" data-aos-delay="<?= $p_delay ?>">
                <span class="absolute top-3 left-3 bg-pink-main text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full z-10 shadow-sm">
                    🔥 <?= $row['booking_count'] ?> Books
                </span>
                <?php if ($row['thumbnail']): ?>
                    <img src="/eventix/<?= htmlspecialchars($row['thumbnail']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="w-full h-48 object-cover">
                <?php else: ?>
                    <div class="w-full h-48 bg-gradient-to-br from-pink-light to-pink-mid flex items-center justify-center text-4xl">🏛️</div>
                <?php endif; ?>
                
                <div class="p-4">
                    <h3 class="font-semibold text-text text-lg mb-1 truncate"><?= htmlspecialchars($row['name']) ?></h3>
                    <p class="text-sm text-text-muted mb-3 truncate">📍 <?= htmlspecialchars($row['location']) ?> &nbsp;·&nbsp; <?= $row['capacity'] ?> pax</p>
                    
                    <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                        <span class="font-bold text-pink-main text-[15px]">RM<?= number_format($row['price_per_day'], 0) ?>/day</span>
                        <span class="text-sm text-text-muted">
                            <span class="text-[#f4a261] font-semibold">⭐ <?= number_format($row['avg_rating'],1) ?></span> 
                            <span class="opacity-70">(<?= $row['review_count'] ?>)</span>
                        </span>
                    </div>
                </div>
            </a>
            <?php $p_delay += 50; endwhile; ?>
            <?php if (!$has_pop): ?>
                <div class="col-span-full py-8 text-center text-text-muted text-sm">No popular venues yet.</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="flex justify-between items-center mb-6" data-aos="fade-up">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-text mb-1">Our Best Picks</h2>
            <p class="text-text-muted text-sm">Handpicked for your next unforgettable event</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php $delay = 0; while ($row = mysqli_fetch_assoc($best_picks)): ?>
        <a href="venue.php?id=<?= $row['id'] ?>" class="group block bg-white border border-gray-100 rounded-2xl  overflow-hidden shadow-soft hover:shadow-hover hover:-translate-y-1.5 transition-all duration-300 relative" data-aos="fade-up" data-aos-delay="<?= $delay ?>">
            <?php if ($row['thumbnail']): ?>
                <img src="/eventix/<?= htmlspecialchars($row['thumbnail']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="w-full h-48 object-cover">
            <?php else: ?>
                <div class="w-full h-48 bg-gradient-to-br from-pink-light to-pink-mid flex items-center justify-center text-4xl">🏛️</div>
            <?php endif; ?>
            
            <div class="p-4">
                <h3 class="font-semibold text-text text-lg mb-1 truncate"><?= htmlspecialchars($row['name']) ?></h3>
                <p class="text-sm text-text-muted mb-3 truncate">📍 <?= htmlspecialchars($row['location']) ?> &nbsp;·&nbsp; <?= $row['capacity'] ?> pax</p>
                
                <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                    <span class="font-bold text-pink-main text-[15px]">RM<?= number_format($row['price_per_day'], 0) ?>/day</span>
                    <span class="text-sm text-text-muted">
                        <span class="text-[#f4a261] font-semibold">⭐ <?= number_format($row['avg_rating'],1) ?></span> 
                        <span class="opacity-70">(<?= $row['review_count'] ?>)</span>
                    </span>
                </div>
            </div>
        </a>
        <?php $delay += 50; endwhile; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/footer_scripts.php'; ?>
</body>
</html>
