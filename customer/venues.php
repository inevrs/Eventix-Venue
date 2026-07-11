<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin('customer');

$search = mysqli_real_escape_string($connect, $_GET['search'] ?? '');
$nameFilter = mysqli_real_escape_string($connect, $_GET['name'] ?? '');
$locationFilter = mysqli_real_escape_string($connect, $_GET['location'] ?? '');
$managerFilter = mysqli_real_escape_string($connect, $_GET['manager'] ?? '');
$priceMin = (int)($_GET['price_min'] ?? 0);
$priceMax = (int)($_GET['price_max'] ?? 0);
$capacityMin = (int)($_GET['capacity_min'] ?? 0);
$capacityMax = (int)($_GET['capacity_max'] ?? 0);
$sort = $_GET['sort'] ?? 'newest';

$locationOptions = [];
$locationResult = mysqli_query($connect, "SELECT DISTINCT location FROM venues WHERE status='active' ORDER BY location ASC");
while ($row = mysqli_fetch_assoc($locationResult)) {
    $locationOptions[] = $row['location'];
}

$managerOptions = [];
$managerResult = mysqli_query($connect, "SELECT DISTINCT u.full_name FROM users u JOIN venues v ON v.manager_id=u.id WHERE v.status='active' ORDER BY u.full_name ASC");
while ($row = mysqli_fetch_assoc($managerResult)) {
    $managerOptions[] = $row['full_name'];
}

$whereClauses = ["v.status='active'"];
if ($search) {
    $whereClauses[] = "(v.name LIKE '%$search%' OR v.location LIKE '%$search%' OR m.full_name LIKE '%$search%')";
}
if ($nameFilter) {
    $whereClauses[] = "v.name LIKE '%$nameFilter%'";
}
if ($locationFilter) {
    $whereClauses[] = "v.location LIKE '%$locationFilter%'";
}
if ($managerFilter) {
    $whereClauses[] = "m.full_name LIKE '%$managerFilter%'";
}
if ($priceMin > 0) {
    $whereClauses[] = "v.price_per_day >= $priceMin";
}
if ($priceMax > 0) {
    $whereClauses[] = "v.price_per_day <= $priceMax";
}
if ($capacityMin > 0) {
    $whereClauses[] = "v.capacity >= $capacityMin";
}
if ($capacityMax > 0) {
    $whereClauses[] = "v.capacity <= $capacityMax";
}
$where = 'WHERE ' . implode(' AND ', $whereClauses);

$orderBy = 'v.created_at DESC';
switch ($sort) {
    case 'price_asc':
        $orderBy = 'v.price_per_day ASC';
        break;
    case 'price_desc':
        $orderBy = 'v.price_per_day DESC';
        break;
    case 'rating_desc':
        $orderBy = 'avg_rating DESC';
        break;
    case 'capacity_desc':
        $orderBy = 'v.capacity DESC';
        break;
}

$venues = mysqli_query($connect, "
    SELECT v.*, 
           COALESCE(AVG(r.rating),0) AS avg_rating, 
           COUNT(DISTINCT r.id) AS review_count,
           vi.image_path AS thumbnail
    FROM venues v
    LEFT JOIN ratings r ON v.id=r.venue_id
    LEFT JOIN users m ON v.manager_id=m.id
    LEFT JOIN venue_images vi ON v.id=vi.venue_id AND vi.is_thumbnail=1
    $where
    GROUP BY v.id
    ORDER BY $orderBy
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Venues — Eventix</title>
    <?php include '../includes/header_scripts.php'; ?>
</head>
<body class="text-text font-sans antialiased min-h-screen">

<?php include '../includes/navbar.php'; ?>

<div class="max-w-7xl mx-auto px-6 py-10 mt-24">
    <div class="mb-10" data-aos="fade-down">
        <h1 class="font-[Playfair_Display] text-4xl text-pink-dark mb-2">All Venues</h1>
        <p class="text-text-muted text-sm">Find the perfect space for your event</p>
    </div>

    <form method="GET" class="space-y-4 mb-6" data-aos="fade-up" data-aos-delay="100">
        <div class="flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between">
            <div class="flex-1 min-w-0">
                <input type="text" name="search" class="w-full px-5 py-3 border border-gray-200 rounded-full text-sm font-sans focus:border-pink-main focus:ring-4 focus:ring-pink-main/15 outline-none transition-all" placeholder="Search by name, location, or manager..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="flex gap-3 flex-wrap justify-end">
                <button type="button" id="toggleFilters" class="inline-flex items-center gap-2 text-sm text-pink-main font-semibold hover:text-pink-dark transition-colors">
                    <span>Filter</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01.293.707l-6 6V19a1 1 0 01-1.447.894L10 16.618V10.707l-6-6A1 1 0 014 6V4z" />
                    </svg>
                </button>
                <button type="submit" class="bg-pink-main text-white px-7 py-3 rounded-full font-semibold text-sm hover:bg-pink-dark active:scale-95 transition-all shadow-md hover:shadow-lg active:scale-95">Search</button>
                <a href="venues.php" class="inline-flex items-center justify-center rounded-full border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-text hover:border-pink-main hover:text-pink-main transition">Reset</a>
            </div>
        </div>

        <div id="filterPanel" class="hidden rounded-3xl border border-pink-light bg-white p-6 shadow-soft">
            <div class="grid gap-4 lg:grid-cols-4">
                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-[0.24em] text-text-muted mb-2">Venue Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($nameFilter) ?>" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-pink-main focus:ring-2 focus:ring-pink-main/10 transition">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-[0.24em] text-text-muted mb-2">Location</label>
                    <input list="locationOptions" type="text" name="location" value="<?= htmlspecialchars($locationFilter) ?>" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-pink-main focus:ring-2 focus:ring-pink-main/10 transition">
                    <datalist id="locationOptions">
                        <?php foreach ($locationOptions as $location): ?>
                            <option value="<?= htmlspecialchars($location) ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-[0.24em] text-text-muted mb-2">Manager</label>
                    <input list="managerOptions" type="text" name="manager" value="<?= htmlspecialchars($managerFilter) ?>" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-pink-main focus:ring-2 focus:ring-pink-main/10 transition">
                    <datalist id="managerOptions">
                        <?php foreach ($managerOptions as $manager): ?>
                            <option value="<?= htmlspecialchars($manager) ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-[0.24em] text-text-muted mb-2">Sort by</label>
                    <select name="sort" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm outline-none focus:border-pink-main focus:ring-2 focus:ring-pink-main/10 transition">
                        <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest</option>
                        <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Price: low → high</option>
                        <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price: high → low</option>
                        <option value="rating_desc" <?= $sort === 'rating_desc' ? 'selected' : '' ?>>Rating</option>
                        <option value="capacity_desc" <?= $sort === 'capacity_desc' ? 'selected' : '' ?>>Capacity</option>
                    </select>
                </div>
            </div>

            <div class="grid gap-4 lg:grid-cols-2 mt-5">
                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-[0.24em] text-text-muted mb-2">Price range (RM)</label>
                    <div class="flex items-center gap-3">
                        <input type="number" name="price_min" min="0" value="<?= $priceMin ?>" placeholder="Min" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-pink-main focus:ring-2 focus:ring-pink-main/10 transition">
                        <input type="number" name="price_max" min="0" value="<?= $priceMax ?>" placeholder="Max" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-pink-main focus:ring-2 focus:ring-pink-main/10 transition">
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-[0.24em] text-text-muted mb-2">Capacity range</label>
                    <div class="flex items-center gap-3">
                        <input type="number" name="capacity_min" min="0" value="<?= $capacityMin ?>" placeholder="Min" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-pink-main focus:ring-2 focus:ring-pink-main/10 transition">
                        <input type="number" name="capacity_max" min="0" value="<?= $capacityMax ?>" placeholder="Max" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-pink-main focus:ring-2 focus:ring-pink-main/10 transition">
                    </div>
                </div>
            </div>
            <div class="mt-5 flex items-center justify-end gap-3">
                <a href="venues.php" class="text-sm text-text-muted hover:text-pink-main transition">Clear filters</a>
                <button type="submit" class="rounded-full bg-pink-main px-6 py-3 text-sm font-semibold text-white hover:bg-pink-dark transition">Apply filters</button>
            </div>
        </div>
    </form>

    <?php if ($search || $nameFilter || $locationFilter || $managerFilter || $priceMin > 0 || $priceMax > 0 || $capacityMin > 0 || $capacityMax > 0): ?>
        <div class="mb-6 flex flex-wrap gap-3">
            <?php if ($search): ?><span class="inline-flex items-center gap-2 rounded-full bg-pink-light px-4 py-2 text-sm font-medium text-pink-dark">Search: <?= htmlspecialchars($search) ?></span><?php endif; ?>
            <?php if ($nameFilter): ?><span class="inline-flex items-center gap-2 rounded-full bg-pink-light px-4 py-2 text-sm font-medium text-pink-dark">Name: <?= htmlspecialchars($nameFilter) ?></span><?php endif; ?>
            <?php if ($locationFilter): ?><span class="inline-flex items-center gap-2 rounded-full bg-pink-light px-4 py-2 text-sm font-medium text-pink-dark">Location: <?= htmlspecialchars($locationFilter) ?></span><?php endif; ?>
            <?php if ($managerFilter): ?><span class="inline-flex items-center gap-2 rounded-full bg-pink-light px-4 py-2 text-sm font-medium text-pink-dark">Manager: <?= htmlspecialchars($managerFilter) ?></span><?php endif; ?>
            <?php if ($priceMin > 0): ?><span class="inline-flex items-center gap-2 rounded-full bg-pink-light px-4 py-2 text-sm font-medium text-pink-dark">Min price: RM<?= number_format($priceMin) ?></span><?php endif; ?>
            <?php if ($priceMax > 0): ?><span class="inline-flex items-center gap-2 rounded-full bg-pink-light px-4 py-2 text-sm font-medium text-pink-dark">Max price: RM<?= number_format($priceMax) ?></span><?php endif; ?>
            <?php if ($capacityMin > 0): ?><span class="inline-flex items-center gap-2 rounded-full bg-pink-light px-4 py-2 text-sm font-medium text-pink-dark">Min capacity: <?= $capacityMin ?></span><?php endif; ?>
            <?php if ($capacityMax > 0): ?><span class="inline-flex items-center gap-2 rounded-full bg-pink-light px-4 py-2 text-sm font-medium text-pink-dark">Max capacity: <?= $capacityMax ?></span><?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php $delay = 0; while ($row = mysqli_fetch_assoc($venues)): ?>
        <a href="venue_detail.php?id=<?= $row['id'] ?>" class="group block bg-white border border-gray-100 rounded-2xl  overflow-hidden shadow-soft hover:shadow-hover hover:-translate-y-1.5 transition-all duration-300 relative" data-aos="fade-up" data-aos-delay="<?= $delay ?>">
            <?php if ($row['thumbnail']): ?>
                <img src="/eventix/<?= htmlspecialchars($row['thumbnail']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="w-full h-48 object-cover">
            <?php else: ?>
                <div class="w-full h-48 bg-gradient-to-br from-pink-light to-pink-mid flex items-center justify-center text-4xl">🏛️</div>
            <?php endif; ?>
            
            <div class="p-4">
                <h3 class="font-semibold text-text text-lg mb-1 truncate"><?= htmlspecialchars($row['name']) ?></h3>
                <p class="text-sm text-text-muted mb-2 truncate">📍 <?= htmlspecialchars($row['location']) ?> &nbsp;·&nbsp; <?= $row['capacity'] ?> pax</p>
                <p class="text-xs text-text-muted mb-4 leading-relaxed line-clamp-2"><?= htmlspecialchars($row['description'] ?? '') ?></p>
                
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

<?php include '../includes/footer.php'; ?>
<script>
const toggleFilters = document.getElementById('toggleFilters');
const filterPanel = document.getElementById('filterPanel');
if (toggleFilters && filterPanel) {
    toggleFilters.addEventListener('click', function() {
        filterPanel.classList.toggle('hidden');
    });
}
</script>
<?php include '../includes/footer_scripts.php'; ?>
</body>
</html>
document.getElementById('toggleFilters').addEventListener('click', function() {
    const panel = document.getElementById('filterPanel');
    panel.classList.toggle('hidden');
});
</script>
<?php include '../includes/footer_scripts.php'; ?>
</body>
</html>
