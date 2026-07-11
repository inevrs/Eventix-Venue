<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin('manager');

$manager_id = $_SESSION['user_id'];
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name     = mysqli_real_escape_string($connect, $_POST['name']);
    $location = mysqli_real_escape_string($connect, $_POST['location']);
    $capacity = (int)$_POST['capacity'];
    $price    = (float)$_POST['price_per_day'];
    $desc     = mysqli_real_escape_string($connect, $_POST['description']);

    $sql = "INSERT INTO venues (name, location, capacity, price_per_day, description, manager_id, status)
            VALUES ('$name','$location',$capacity,$price,'$desc',$manager_id,'active')";

    if (mysqli_query($connect, $sql)) {
        $venue_id = mysqli_insert_id($connect);

        $upload_dir = '../uploads/venues/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        // Thumbnail
        if (!empty($_FILES['thumbnail']['name'])) {
            $ext      = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
            $filename = 'venue_' . $venue_id . '_thumb_' . time() . '.' . $ext;
            $dest     = $upload_dir . $filename;
            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $dest)) {
                $path = mysqli_real_escape_string($connect, 'uploads/venues/' . $filename);
                mysqli_query($connect, "INSERT INTO venue_images (venue_id, image_path, is_thumbnail, sort_order) VALUES ($venue_id, '$path', 1, 0)");
            }
        }

        // Gallery images
        if (!empty($_FILES['gallery']['name'][0])) {
            foreach ($_FILES['gallery']['tmp_name'] as $i => $tmp) {
                if ($_FILES['gallery']['error'][$i] !== 0) continue;
                $ext      = pathinfo($_FILES['gallery']['name'][$i], PATHINFO_EXTENSION);
                $filename = 'venue_' . $venue_id . '_gallery_' . time() . '_' . $i . '.' . $ext;
                $dest     = $upload_dir . $filename;
                if (move_uploaded_file($tmp, $dest)) {
                    $path = mysqli_real_escape_string($connect, 'uploads/venues/' . $filename);
                    mysqli_query($connect, "INSERT INTO venue_images (venue_id, image_path, is_thumbnail, sort_order) VALUES ($venue_id, '$path', 0, $i)");
                }
            }
        }

        $success = "Venue added successfully.";
    } else {
        $error = "Failed to add venue.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id'];
    $images = mysqli_query($connect, "SELECT image_path FROM venue_images WHERE venue_id=$id");
    while ($img = mysqli_fetch_assoc($images)) {
        $file = '../' . $img['image_path'];
        if (file_exists($file)) unlink($file);
    }
    mysqli_query($connect, "DELETE FROM venues WHERE id=$id AND manager_id=$manager_id");
    $success = "Venue deleted.";
}

$venues = mysqli_query($connect, "
    SELECT v.*, vi.image_path AS thumbnail
    FROM venues v
    LEFT JOIN venue_images vi ON v.id=vi.venue_id AND vi.is_thumbnail=1
    WHERE v.manager_id=$manager_id
    ORDER BY v.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Venues — Eventix</title>
    <?php include '../includes/header_scripts.php'; ?>
    <style>
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 50;
        }
        .modal-overlay.active {
            display: flex;
        }
        .modal {
            background: white;
            border-radius: 1.5rem;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
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
            <li><a href="venues.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all bg-pink-main/10 text-pink-main font-semibold">My Venues</a></li>
            <li><a href="bookings.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-text-muted hover:bg-gray-50 hover:text-text">Bookings</a></li>
            <li><a href="earnings.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-text-muted hover:bg-gray-50 hover:text-text">Earnings</a></li>
        </ul>
    </aside>

    <main class="flex-1 p-10 overflow-y-auto">
        <div class="mb-10" data-aos="fade-down" style="display:flex;justify-content:space-between;align-items:flex-start">
            <div>
                <h1 class="font-[Playfair_Display] text-4xl text-pink-dark mb-2">My Venues</h1>
                <p>Manage your listed spaces</p>
            </div>
            <button class="bg-pink-main text-white px-6 py-2.5 rounded-full font-semibold text-sm hover:bg-pink-dark transition-colors inline-block" onclick="document.getElementById('addModal').classList.add('active')">+ Add Venue</button>
        </div>

        <?php if ($success): ?><div class="bg-green-50 text-green-700 px-4 py-3 rounded-xl text-sm mb-6 border border-green-200"><?= $success ?></div><?php endif; ?>
        <?php if ($error):   ?><div class="bg-red-50 text-red-600 px-4 py-3 rounded-xl text-sm mb-6 border border-red-200"><?= $error ?></div><?php endif; ?>

        <div class="bg-white border border-gray-100 rounded-2xl  p-8 shadow-soft mb-8">
            <div class="overflow-x-auto rounded-2xl border border-gray-100 shadow-sm">
                <table class="w-full text-sm text-left border-collapse min-w-[800px]">
                    <thead class="bg-gray-50 text-pink-dark text-xs uppercase tracking-wider font-semibold">
                        <tr>
                            <th class="px-6 py-4 border-b border-gray-100">Thumbnail</th>
                            <th class="px-6 py-4 border-b border-gray-100">Name</th>
                            <th class="px-6 py-4 border-b border-gray-100">Location</th>
                            <th class="px-6 py-4 border-b border-gray-100">Capacity</th>
                            <th class="px-6 py-4 border-b border-gray-100">Price/Day</th>
                            <th class="px-6 py-4 border-b border-gray-100">Status</th>
                            <th class="px-6 py-4 border-b border-gray-100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($venues)): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 border-b border-gray-100 text-text">
                                <?php if ($row['thumbnail']): ?>
                                    <img src="/eventix/<?= htmlspecialchars($row['thumbnail']) ?>" style="width:60px;height:45px;object-fit:cover;border-radius:6px">
                                <?php else: ?>
                                    <div style="width:60px;height:45px;background:var(--pink-light);border-radius:6px;display:flex;align-items:center;justify-content:center">🏛️</div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 border-b border-gray-100 text-text"><?= htmlspecialchars($row['name']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-100 text-text"><?= htmlspecialchars($row['location']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-100 text-text"><?= $row['capacity'] ?> pax</td>
                            <td class="px-6 py-4 border-b border-gray-100 text-text">RM<?= number_format($row['price_per_day'], 2) ?></td>
                            <td class="px-6 py-4 border-b border-gray-100 text-text"><span class="badge badge-<?= $row['status']==='active'?'success':'warning' ?>"><?= ucfirst($row['status']) ?></span></td>
                            <td style="display:flex;gap:8px">
                                <a href="edit_venue.php?id=<?= $row['id'] ?>" class="border-2 border-pink-light text-pink-main px-4 py-1.5 rounded-full font-semibold text-xs hover:border-pink-main hover:bg-pink-50 transition-colors inline-block">Edit</a>
                                <form method="POST" onsubmit="return confirm('Delete venue?')">
                                    <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-full font-semibold text-xs hover:bg-red-600 transition-colors inline-block text-center">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Add Venue Modal -->
<div class="modal-overlay" id="addModal">
    <div class="modal" style="width:560px;max-height:90vh;overflow-y:auto">
        <h2>Add New Venue</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">
            <div class="mb-5">
                <label class="block text-[11px] font-semibold tracking-wider uppercase text-text-muted mb-2">Venue Name</label>
                <input type="text" class="w-full px-5 py-3.5 border border-gray-200 rounded-xl text-sm font-sans focus:border-pink-main focus:ring-2 focus:ring-pink-main/10 outline-none transition-all"  name="name" required>
            </div>
            <div class="mb-5">
                <label class="block text-[11px] font-semibold tracking-wider uppercase text-text-muted mb-2">Location</label>
                <input type="text" class="w-full px-5 py-3.5 border border-gray-200 rounded-xl text-sm font-sans focus:border-pink-main focus:ring-2 focus:ring-pink-main/10 outline-none transition-all"  name="location" required>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="mb-5">
                    <label class="block text-[11px] font-semibold tracking-wider uppercase text-text-muted mb-2">Capacity (pax)</label>
                    <input type="number" class="w-full px-5 py-3.5 border border-gray-200 rounded-xl text-sm font-sans focus:border-pink-main focus:ring-2 focus:ring-pink-main/10 outline-none transition-all"  name="capacity" required>
                </div>
                <div class="mb-5">
                    <label class="block text-[11px] font-semibold tracking-wider uppercase text-text-muted mb-2">Price per Day (RM)</label>
                    <input type="number" class="w-full px-5 py-3.5 border border-gray-200 rounded-xl text-sm font-sans focus:border-pink-main focus:ring-2 focus:ring-pink-main/10 outline-none transition-all"  name="price_per_day" step="0.01" required>
                </div>
            </div>
            <div class="mb-5">
                <label class="block text-[11px] font-semibold tracking-wider uppercase text-text-muted mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full px-5 py-3.5 border border-gray-200 rounded-xl text-sm font-sans focus:border-pink-main focus:ring-2 focus:ring-pink-main/10 outline-none transition-all"></textarea>
            </div>
            <div class="mb-5">
                <label class="block text-[11px] font-semibold tracking-wider uppercase text-text-muted mb-2">Thumbnail Picture</label>
                <input type="file" name="thumbnail" accept="image/*" style="padding:8px">
                <small style="color:var(--text-muted);font-size:12px">This appears as the card preview image</small>
            </div>
            <div class="mb-5">
                <label class="block text-[11px] font-semibold tracking-wider uppercase text-text-muted mb-2">Gallery Pictures</label>
                <input type="file" name="gallery[]" accept="image/*" multiple style="padding:8px">
                <small style="color:var(--text-muted);font-size:12px">Select multiple — shown in venue detail page</small>
            </div>
            <div style="display:flex;gap:12px;justify-content:flex-end">
                <button type="button" class="border border-gray-300 text-text px-5 py-2.5 rounded-full font-semibold text-sm hover:bg-gray-50 transition-colors" onclick="document.getElementById('addModal').classList.remove('active')">Cancel</button>
                <button type="submit" class="bg-pink-main text-white px-6 py-2.5 rounded-full font-semibold text-sm hover:bg-pink-dark transition-colors inline-block">Add Venue</button>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer_scripts.php'; ?>
</body>
</html>
