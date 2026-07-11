<?php
$projectRoot = dirname(__DIR__);
set_include_path($projectRoot . PATH_SEPARATOR . get_include_path());

require_once 'includes/auth.php';
startSecureSession();
require_once 'includes/db.php';

$id = (int)($_GET['id'] ?? 0);
$role = userRole();

$venueStmt = $connect->prepare("SELECT v.*, COALESCE(AVG(r.rating),0) AS avg_rating, COUNT(DISTINCT r.id) AS review_count
    FROM venues v
    LEFT JOIN ratings r ON v.id=r.venue_id
    WHERE v.id=? AND v.status='active'
    GROUP BY v.id");
$venueStmt->bind_param('i', $id);
$venueStmt->execute();
$venue = $venueStmt->get_result()->fetch_assoc();

if (!$venue) { header("Location: index.php"); exit(); }

$imagesStmt = $connect->prepare("SELECT * FROM venue_images WHERE venue_id=? ORDER BY is_thumbnail DESC, sort_order ASC");
$imagesStmt->bind_param('i', $id);
$imagesStmt->execute();
$imagesResult = $imagesStmt->get_result();
$imgs = [];
while ($img = $imagesResult->fetch_assoc()) $imgs[] = $img;

$reviewsStmt = $connect->prepare("SELECT r.*, u.full_name FROM ratings r
    JOIN users u ON r.user_id=u.id
    WHERE r.venue_id=? ORDER BY r.created_at DESC");
$reviewsStmt->bind_param('i', $id);
$reviewsStmt->execute();
$reviewsResult = $reviewsStmt->get_result();
$review_rows = [];
while ($r = $reviewsResult->fetch_assoc()) $review_rows[] = $r;

$addonsStmt = $connect->prepare("SELECT * FROM addons ORDER BY id");
$addonsStmt->execute();
$addonsResult = $addonsStmt->get_result();
$addon_rows = [];
while ($a = $addonsResult->fetch_assoc()) $addon_rows[] = $a;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($venue['name']) ?> — Eventix</title>
    <?php include 'includes/header_scripts.php'; ?>
</head>
<body class="text-text font-sans min-h-screen">

<?php include 'includes/navbar.php'; ?>

<div class="max-w-[1100px] mx-auto px-6 py-10 mt-24">
    <div class="mb-8" data-aos="fade-right">
        <a href="index.php" class="inline-flex items-center gap-2 text-pink-main font-medium border-2 border-pink-main px-4 py-2 rounded-full hover:bg-pink-main hover:text-white transition-colors text-sm">
            <span>←</span> Back to Venues
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-10 items-start">
        <!-- LEFT -->
        <div data-aos="fade-up">
            <!-- Carousel -->
            <div class="relative rounded-2xl overflow-hidden bg-accent-light aspect-[4/3] group" id="carousel">
                <?php if (empty($imgs)): ?>
                    <div class="flex h-full">
                        <div class="min-w-full h-full flex items-center justify-center text-6xl bg-gradient-to-br from-pink-light to-pink-mid">🏛️</div>
                    </div>
                <?php else: ?>
                    <div class="flex h-full transition-transform duration-500 ease-out" id="slides">
                        <?php foreach ($imgs as $img): ?>
                        <div class="min-w-full h-full">
                            <img src="/eventix/<?= htmlspecialchars($img['image_path']) ?>" alt="venue" class="w-full h-full object-cover">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if (count($imgs) > 1): ?>
                    <button class="absolute top-1/2 -translate-y-1/2 left-3 bg-white/80 w-10 h-10 rounded-full flex items-center justify-center text-xl shadow-md opacity-0 group-hover:opacity-100 transition-all hover:bg-white cursor-pointer" onclick="changeSlide(-1)">‹</button>
                    <button class="absolute top-1/2 -translate-y-1/2 right-3 bg-white/80 w-10 h-10 rounded-full flex items-center justify-center text-xl shadow-md opacity-0 group-hover:opacity-100 transition-all hover:bg-white cursor-pointer" onclick="changeSlide(1)">›</button>
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2" id="dots">
                        <?php foreach ($imgs as $i => $img): ?>
                        <button class="carousel-dot cursor-pointer border-none" onclick="goToSlide(<?= $i ?>)" aria-label="Go to slide <?= $i + 1 ?>"></button>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <!-- Info -->
            <div class="mt-8">
                <h1 class="font-[Playfair_Display] text-4xl text-accent-dark mb-4">
                    <?= htmlspecialchars($venue['name']) ?>
                </h1>
                <div class="flex items-center gap-2 text-text-muted text-sm mb-2.5">📍 <?= htmlspecialchars($venue['location']) ?></div>
                <div class="flex items-center gap-2 text-text-muted text-sm mb-2.5">👥 Up to <?= $venue['capacity'] ?> guests</div>
                <div class="flex items-center gap-2 text-text-muted text-sm mb-2.5">⭐ <?= number_format($venue['avg_rating'],1) ?> / 5 &nbsp;·&nbsp; <?= $venue['review_count'] ?> reviews</div>

                <?php $description = trim((string)($venue['description'] ?? '')); ?>
                <?php if ($description !== ''): ?>
                    <div class="mt-6 rounded-2xl border border-pink-light bg-pink-50/40 p-5">
                        <div id="description-wrapper" class="overflow-hidden transition-all duration-300">
                            <p id="description-text" class="text-text text-[15px] leading-relaxed line-clamp-5">
                                <?= nl2br(htmlspecialchars($description)) ?>
                            </p>
                        </div>
                        <button type="button" id="toggle-description" class="mt-3 text-sm font-semibold text-accent hover:text-accent-dark transition-colors">
                            Read more
                        </button>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Reviews -->
            <?php if (!empty($review_rows)): ?>
            <div class="mt-12">
                <h2 class="font-[Playfair_Display] text-2xl text-text font-bold mb-4">Customer Reviews</h2>
                <div class="divide-y divide-pink-light">
                    <?php foreach ($review_rows as $r): ?>
                    <div class="py-5">
                        <div class="flex justify-between mb-2">
                            <strong class="text-sm"><?= htmlspecialchars($r['full_name']) ?></strong>
                            <span class="text-[#f4a261] text-sm"><?= str_repeat('⭐', $r['rating']) ?></span>
                        </div>
                        <p class="text-sm text-text-muted"><?= htmlspecialchars($r['review'] ?? '') ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- RIGHT: Sticky booking card -->
        <div class="space-y-4 lg:sticky lg:top-[100px] self-start" data-aos="fade-left">
            <div class="bg-surface border border-surface rounded-2xl p-7 shadow-soft">
                <div class="text-3xl font-bold text-accent mb-1">
                    RM<?= number_format($venue['price_per_day'], 2) ?>
                    <span class="text-sm font-normal text-text-muted">/ day</span>
                </div>
                <p class="text-sm text-text-muted mb-6">
                    ⭐ <?= number_format($venue['avg_rating'],1) ?> &nbsp;·&nbsp; <?= $venue['review_count'] ?> reviews
                </p>

                <div class="rounded-xl p-5 mb-6">
                    <div class="flex justify-between text-sm text-text-muted mb-2">
                        <span>Venue</span>
                        <span>RM<?= number_format($venue['price_per_day'], 2) ?></span>
                    </div>
                    <div id="addon-summary" class="text-sm text-text-muted space-y-2 mb-3"></div>
                    <div class="flex justify-between text-base font-bold text-text pt-3 border-t border-pink-light">
                        <span>Total</span>
                        <span id="grand-total">RM<?= number_format($venue['price_per_day'], 2) ?></span>
                    </div>
                </div>

                <?php if ($role === 'customer'): ?>
                    <form method="GET" action="customer/book.php">
                        <input type="hidden" name="id" value="<?= $venue['id'] ?>">
                        <input type="hidden" name="addons" id="selected-addons-input" value="">
                        <button type="submit" class="w-full bg-pink-main text-white py-3 rounded-full font-semibold hover:bg-pink-dark transition-colors shadow-md hover:shadow-lg">Book Now →</button>
                    </form>
                <?php elseif ($role): ?>
                    <button type="button" class="w-full bg-transparent border-2 border-pink-light text-pink-main py-3 rounded-full font-semibold opacity-50 cursor-not-allowed">Log in as customer to book</button>
                <?php else: ?>
                    <button type="button" class="w-full bg-pink-main text-white py-3 rounded-full font-semibold hover:bg-pink-dark transition-colors shadow-md hover:shadow-lg" onclick="openAuthModal(<?= $venue['id'] ?>)">Log in to Book →</button>
                <?php endif; ?>
                <p class="text-center text-xs text-text-muted mt-4">You won't be charged yet</p>
            </div>

            <div class="bg-surface border border-surface rounded-2xl p-6 shadow-soft">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="font-[Playfair_Display] text-xl text-text font-bold">Add-ons</h2>
                        <p class="text-text-muted text-sm">Enhance your event with these extras</p>
                    </div>
                    <span class="text-xs uppercase tracking-widest text-pink-main font-semibold">Optional</span>
                </div>

                <div class="space-y-3">
                    <?php foreach ($addon_rows as $addon): ?>
                    <div class="addon-card border border-accent-light rounded-xl p-4 bg-surface cursor-pointer select-none transition-all hover:border-accent hover:-translate-y-1 [&.selected]:border-accent [&.selected]:bg-accent-light/60" id="addon-<?= $addon['id'] ?>" onclick="toggleAddon(<?= $addon['id'] ?>, <?= $addon['price'] ?>, '<?= htmlspecialchars($addon['name']) ?>')">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-lg"><?= $addon['icon'] ?></span>
                                    <div class="font-semibold text-sm text-text"><?= htmlspecialchars($addon['name']) ?></div>
                                </div>
                                <div class="text-xs text-text-muted leading-snug"><?= htmlspecialchars($addon['description']) ?></div>
                            </div>
                            <div class="text-sm font-bold text-pink-main whitespace-nowrap">+RM<?= number_format($addon['price'], 0) ?></div>
                        </div>
                        <div class="check mt-3 flex h-5 w-5 items-center justify-center rounded-full bg-pink-light text-[10px] text-transparent transition-colors [[id=addon-<?= $addon['id'] ?>].selected_&]:bg-pink-main [[id=addon-<?= $addon['id'] ?>].selected_&]:text-white">✓</div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/eventix/js/venue.js"></script>
<script>
window._venuePrice = <?= $venue['price_per_day'] ?>;
initCarousel(<?= max(count($imgs), 1) ?>);

const descriptionButton = document.getElementById('toggle-description');
const descriptionText = document.getElementById('description-text');
const descriptionWrapper = document.getElementById('description-wrapper');

if (descriptionButton && descriptionText) {
    let expanded = false;
    descriptionButton.addEventListener('click', () => {
        expanded = !expanded;
        descriptionText.classList.toggle('line-clamp-5', !expanded);
        descriptionButton.textContent = expanded ? 'Show less' : 'Read more';
        descriptionWrapper.classList.toggle('max-h-[300px]', !expanded);
    });
}
</script>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/footer_scripts.php'; ?>
</body>
</html>
