<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireLogin();

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bio = mysqli_real_escape_string($connect, $_POST['bio']);
    
    $profile_picture = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/profiles/';
        $file_name = time() . '_' . basename($_FILES['profile_picture']['name']);
        $target_file = $upload_dir . $file_name;
        
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'png', 'jpeg', 'gif'];
        
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                $profile_picture = $target_file;
            } else {
                $error = "Error uploading file.";
            }
        } else {
            $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
        }
    }
    
    if (!$error) {
        if ($profile_picture) {
            $sql = "UPDATE users SET bio='$bio', profile_picture='$profile_picture' WHERE id=$user_id";
            $_SESSION['profile_picture'] = $profile_picture;
        } else {
            $sql = "UPDATE users SET bio='$bio' WHERE id=$user_id";
        }
        
        if (mysqli_query($connect, $sql)) {
            $success = "Profile updated successfully!";
        } else {
            $error = "Database error. Please try again.";
        }
    }
}

$query = mysqli_query($connect, "SELECT * FROM users WHERE id=$user_id");
$user = mysqli_fetch_assoc($query);

if (!isset($_SESSION['profile_picture'])) {
    $_SESSION['profile_picture'] = $user['profile_picture'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile — Eventix</title>
    <?php include 'includes/header_scripts.php'; ?>
</head>
<body class="text-text font-sans antialiased min-h-screen">

<?php include 'includes/navbar.php'; ?>

<div class="max-w-[1200px] mx-auto px-6 py-10 mt-24">
    <div class="mb-10" data-aos="fade-down">
        <h1 class="font-[Playfair_Display] text-4xl text-pink-dark mb-2">My Profile</h1>
        <p class="text-text-muted text-sm">Manage your public profile and details</p>
    </div>

    <div class="flex flex-col md:flex-row gap-10 max-w-[900px] mx-auto items-start">
        <div class="w-full md:w-60 text-center shrink-0" data-aos="fade-right" data-aos-delay="100">
            <div class="w-44 h-44 rounded-full bg-pink-light text-pink-dark mx-auto mb-6 overflow-hidden flex items-center justify-center text-5xl font-semibold shadow-inner border-4 border-white">
                <?php if ($user['profile_picture']): ?>
                    <img src="/eventix/<?= htmlspecialchars($user['profile_picture']) ?>" alt="Avatar" class="w-full h-full object-cover">
                <?php else: ?>
                    <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
                <?php endif; ?>
            </div>
            <h3 class="font-sans text-xl font-semibold text-text mb-1"><?= htmlspecialchars($user['full_name']) ?></h3>
            <p class="text-pink-main text-xs uppercase tracking-widest font-bold"><?= htmlspecialchars($user['role']) ?></p>
        </div>
        
        <div class="flex-1 bg-white border border-gray-100 rounded-2xl  p-8 shadow-soft" data-aos="fade-left" data-aos-delay="200">
            <h2 class="font-[Playfair_Display] text-2xl text-pink-dark mb-6">Edit Details</h2>
            
            <?php if ($error): ?><div class="bg-red-50 text-red-600 px-4 py-3 rounded-lg text-sm mb-6"><?= $error ?></div><?php endif; ?>
            <?php if ($success): ?><div class="bg-green-50 text-green-700 px-4 py-3 rounded-lg text-sm mb-6"><?= $success ?></div><?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-5">
                    <label class="block text-[11px] font-semibold tracking-wider uppercase text-text-muted mb-2">Profile Picture</label>
                    <input type="file" name="profile_picture" accept="image/*" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm font-sans text-text transition-colors focus:border-pink-main focus:bg-pink-50/30 outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-pink-light file:text-pink-dark hover:file:bg-pink-main hover:file:text-white cursor-pointer">
                    <small class="text-text-muted block mt-2 text-xs">Leave blank if you don't want to change it.</small>
                </div>
                
                <div class="mb-5">
                    <label class="block text-[11px] font-semibold tracking-wider uppercase text-text-muted mb-2">Bio</label>
                    <textarea name="bio" rows="5" placeholder="Tell us a little about yourself..." class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-sans text-text transition-colors focus:border-pink-main focus:bg-pink-50/30 outline-none resize-y"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                </div>
                
                <div class="mb-8">
                    <label class="block text-[11px] font-semibold tracking-wider uppercase text-text-muted mb-2">Email Address</label>
                    <input type="text" value="<?= htmlspecialchars($user['email']) ?>" disabled class="w-full px-4 py-3 border-[1.5px] border-gray-200 bg-gray-50 rounded-xl text-sm font-sans text-text-muted cursor-not-allowed">
                </div>
                
                <button type="submit" class="bg-pink-main text-white px-8 py-3 rounded-full font-semibold text-sm hover:bg-pink-dark transition-all transform hover:-translate-y-px active:scale-95 shadow-md hover:shadow-lg active:scale-95 transition-all">Save Changes</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer_scripts.php'; ?>
</body>
</html>
