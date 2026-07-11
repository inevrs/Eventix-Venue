<?php
$projectRoot = dirname(__DIR__);
set_include_path($projectRoot . PATH_SEPARATOR . get_include_path());

require_once 'includes/auth.php';
startSecureSession();
require_once 'includes/db.php';
require_once 'includes/lang.php';
require_once 'includes/icons.php';

$lang = getCurrentLanguage();

$search = trim($_GET['search'] ?? '');
$searchTerm = '%' . $search . '%';

$categories = [
    ['label' => 'Wedding', 'query' => 'wedding', 'icon' => getIcon('wedding')],
    ['label' => 'Corporate', 'query' => 'corporate', 'icon' => getIcon('corporate')],
    ['label' => 'Outdoor', 'query' => 'outdoor', 'icon' => getIcon('outdoor')],
    ['label' => 'Party', 'query' => 'party', 'icon' => getIcon('party')],
    ['label' => 'Studio', 'query' => 'studio', 'icon' => getIcon('studio')],
];

$where = "WHERE v.status='active'";
$params = [];
$types = '';

if ($search !== '') {
    $where .= " AND (v.name LIKE ? OR v.location LIKE ?)";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= 'ss';
}

$bestQuery = "
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
";

$bestStmt = $connect->prepare($bestQuery);
if ($bestStmt && $params) {
    $bestStmt->bind_param($types, ...$params);
}
$bestStmt->execute();
$best_picks = $bestStmt->get_result();

$popularQuery = "
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
";

$popularStmt = $connect->prepare($popularQuery);
if ($popularStmt && $params) {
    $popularStmt->bind_param($types, ...$params);
}
$popularStmt->execute();
$popular_venues = $popularStmt->get_result();

$total_venues = (int)mysqli_fetch_row(mysqli_query($connect, "SELECT COUNT(*) FROM venues WHERE status='active'"))[0];
$total_reviews = (int)mysqli_fetch_row(mysqli_query($connect, "SELECT COUNT(*) FROM ratings"))[0];
$overall_rating = (float)mysqli_fetch_row(mysqli_query($connect, "SELECT COALESCE(AVG(rating),0) FROM ratings"))[0];
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
            <p class="text-accent text-xs tracking-widest uppercase mb-4 font-semibold">— <?= translate('discover_book', $lang) ?></p>
            <h1 class="font-[Playfair_Display] text-6xl leading-tight text-accent mb-4">
                Event<span class="text-accent-dark">ix</span>
            </h1>
            <p class="text-text-muted text-lg leading-relaxed mb-8">
                <?= translate('hero_subtitle', $lang) ?>
            </p>
            <div class="flex flex-col gap-4 max-w-2xl">
                <form method="GET" action="index.php" class="flex flex-col sm:flex-row items-stretch gap-3">
                    <input type="text" name="search" placeholder="<?= translate('search_prompt', $lang) ?>" value="<?= htmlspecialchars($search) ?>" class="flex-1 px-6 py-4 border border-gray-200 rounded-full text-sm font-sans focus:border-accent focus:ring-4 focus:ring-accent/15 outline-none transition-all bg-surface text-text">
                    <button type="submit" class="bg-accent text-white px-8 py-4 rounded-full font-semibold text-sm hover:bg-[#c72d6d] active:scale-95 transition-all shadow-md">Search</button>
                </form>
                <div class="flex flex-wrap gap-3">
                    <?php foreach ($categories as $category): ?>
                        <a href="index.php?search=<?= urlencode($category['query']) ?>" class="inline-flex items-center gap-2 rounded-full border border-surface bg-surface px-4 py-3 text-sm text-text hover:border-accent hover:text-accent transition">
                            <span><?= $category['icon'] ?></span>
                            <?= translate(strtolower($category['label']), $lang) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="hidden md:flex flex-col gap-8 text-right pt-10" data-aos="fade-left" data-aos-delay="200">
            <div>
                <div class="font-[Playfair_Display] text-5xl text-pink-dark"><?= $total_venues ?>+</div>
                <div class="text-[11px] tracking-widest uppercase text-text-muted mt-1 font-semibold"><?= translate('venues', $lang) ?></div>
            </div>
            <div>
                <div class="font-[Playfair_Display] text-5xl text-pink-dark"><?= $total_reviews ?></div>
                <div class="text-[11px] tracking-widest uppercase text-text-muted mt-1 font-semibold"><?= translate('reviews', $lang) ?></div>
            </div>
            <div>
                <div class="font-[Playfair_Display] text-5xl text-pink-dark"><?= number_format($overall_rating,1) ?></div>
                <div class="text-[11px] tracking-widest uppercase text-text-muted mt-1 font-semibold"><?= translate('avg_rating', $lang) ?></div>
            </div>
        </div>
    </div>

    <!-- Popular Venues Section -->
    <div class="mb-16" data-aos="fade-up">
        <div class="mb-6">
            <h2 class="text-2xl font-bold tracking-tight text-text mb-1"><?= translate('popular_header', $lang) ?></h2>
            <p class="text-text-muted text-sm"><?= translate('popular_subtitle', $lang) ?></p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php $p_delay = 0; $has_pop = false; while ($row = mysqli_fetch_assoc($popular_venues)): $has_pop = true; ?>
            <a href="venue.php?id=<?= $row['id'] ?>" class="group block bg-surface border border-surface rounded-2xl overflow-hidden shadow-soft hover:shadow-hover hover:-translate-y-1.5 transition-all duration-300 relative" data-aos="fade-up" data-aos-delay="<?= $p_delay ?>">
                <span class="absolute top-3 left-3 bg-accent text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full z-10 shadow-sm">
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
            <h2 class="text-2xl font-bold tracking-tight text-text mb-1"><?= translate('best_picks', $lang) ?></h2>
            <p class="text-text-muted text-sm"><?= translate('best_picks_subtitle', $lang) ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php $delay = 0; while ($row = mysqli_fetch_assoc($best_picks)): ?>
        <a href="venue.php?id=<?= $row['id'] ?>" class="group block bg-surface border border-surface rounded-3xl overflow-hidden shadow-soft hover:border-accent hover:shadow-hover hover:-translate-y-1.5 transition-all duration-300 relative" data-aos="fade-up" data-aos-delay="<?= $delay ?>">
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
