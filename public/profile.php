<?php
$projectRoot = dirname(__DIR__);
set_include_path($projectRoot . PATH_SEPARATOR . get_include_path());

session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/validation.php';
requireLogin();

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullNameResult = validate_name($_POST['full_name'] ?? '');
    $emailResult = validate_email($_POST['email'] ?? '');
    $phoneResult = validate_phone($_POST['phone'] ?? '');
    $bio = sanitize_input($_POST['bio'] ?? '');
    $newPassword = trim($_POST['new_password'] ?? '');

    if (!$fullNameResult['valid']) {
        $error = $fullNameResult['message'];
    } elseif (!$emailResult['valid']) {
        $error = $emailResult['message'];
    } elseif (!$phoneResult['valid']) {
        $error = $phoneResult['message'];
    } elseif ($newPassword !== '' && mb_strlen($newPassword) < 8) {
        $error = 'New password must be at least 8 characters.';
    } else {
        $full_name = $fullNameResult['value'];
        $email = $emailResult['value'];
        $phone = $phoneResult['value'];

        $updates = [
            "full_name = '" . mysqli_real_escape_string($connect, $full_name) . "'",
            "email = '" . mysqli_real_escape_string($connect, $email) . "'",
            "phone = '" . mysqli_real_escape_string($connect, $phone) . "'",
            "bio = '" . mysqli_real_escape_string($connect, $bio) . "'",
        ];

        if ($newPassword !== '') {
            $updates[] = "password = '" . mysqli_real_escape_string($connect, password_hash($newPassword, PASSWORD_DEFAULT)) . "'";
        }

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
                    $updates[] = "profile_picture = '" . mysqli_real_escape_string($connect, $profile_picture) . "'";
                    $_SESSION['profile_picture'] = $profile_picture;
                } else {
                    $error = "Error uploading file.";
                }
            } else {
                $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
            }
        }

        if (!$error) {
            $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id=$user_id";
            if (mysqli_query($connect, $sql)) {
                $_SESSION['name'] = $full_name;
                $success = "Profile updated successfully!";
            } else {
                $error = "Database error. Please try again.";
            }
        }
    }
}

$query = mysqli_query($connect, "SELECT * FROM users WHERE id=$user_id");
$user = mysqli_fetch_assoc($query);

if (!isset($_SESSION['profile_picture'])) {
    $_SESSION['profile_picture'] = $user['profile_picture'] ?? null;
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
        <p class="text-text-muted text-sm">Manage your account details and profile preferences</p>
    </div>

    <div class="flex flex-col md:flex-row gap-10 max-w-[1000px] mx-auto items-start">
        <div class="w-full md:w-72 text-center shrink-0" data-aos="fade-right" data-aos-delay="100">
            <div class="profile-card rounded-3xl p-6">
                <div class="w-36 h-36 rounded-full bg-pink-light text-pink-dark mx-auto mb-6 overflow-hidden flex items-center justify-center text-5xl font-semibold shadow-inner border-4 border-white">
                    <?php if (!empty($user['profile_picture'])): ?>
                        <img src="/eventix/<?= htmlspecialchars($user['profile_picture']) ?>" alt="Avatar" class="w-full h-full object-cover">
                    <?php else: ?>
                        <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
                    <?php endif; ?>
                </div>
                <h3 class="font-sans text-xl font-semibold text-text mb-2"><?= htmlspecialchars($user['full_name']) ?></h3>
                <p class="text-text-muted text-sm mb-4"><?= htmlspecialchars($user['bio'] ?? 'Add a short bio so people can know more about you.') ?></p>
                <div class="space-y-3 text-left text-sm text-text-muted">
                    <div><strong class="text-text">Email:</strong> <?= htmlspecialchars($user['email']) ?></div>
                    <div><strong class="text-text">Phone:</strong> <?= htmlspecialchars($user['phone'] ?? 'Not added') ?></div>
                </div>
            </div>
        </div>

        <div class="flex-1 form-card rounded-3xl p-8" data-aos="fade-left" data-aos-delay="200">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-[Playfair_Display] text-2xl text-pink-dark">Edit Details</h2>
                <div class="text-sm text-text-muted">Update your profile</div>
            </div>

            <?php if ($error): ?><div class="bg-red-500/10 text-red-400 px-4 py-3 rounded-xl text-sm mb-6"><?= $error ?></div><?php endif; ?>
            <?php if ($success): ?><div class="bg-emerald-500/10 text-emerald-400 px-4 py-3 rounded-xl text-sm mb-6"><?= $success ?></div><?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="space-y-5">
                <div>
                    <label class="block text-[11px] font-semibold tracking-wider uppercase field-label mb-2">Profile Picture</label>
                    <input type="file" name="profile_picture" accept="image/*" class="form-input w-full px-4 py-2 rounded-xl text-sm cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-pink-light file:text-pink-dark hover:file:bg-pink-main hover:file:text-white">
                    <small class="text-text-muted block mt-2 text-xs">Leave blank if you do not want to change it.</small>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold tracking-wider uppercase field-label mb-2">Full Name</label>
                    <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required class="form-input w-full px-4 py-3 rounded-xl text-sm outline-none focus:border-pink-main focus:ring-2 focus:ring-pink-main/10">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold tracking-wider uppercase field-label mb-2">Email Address</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required class="form-input w-full px-4 py-3 rounded-xl text-sm outline-none focus:border-pink-main focus:ring-2 focus:ring-pink-main/10">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold tracking-wider uppercase field-label mb-2">Phone Number</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" class="form-input w-full px-4 py-3 rounded-xl text-sm outline-none focus:border-pink-main focus:ring-2 focus:ring-pink-main/10">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold tracking-wider uppercase field-label mb-2">New Password</label>
                    <input type="password" name="new_password" placeholder="Leave blank to keep current password" class="form-input w-full px-4 py-3 rounded-xl text-sm outline-none focus:border-pink-main focus:ring-2 focus:ring-pink-main/10">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold tracking-wider uppercase field-label mb-2">Bio</label>
                    <textarea name="bio" rows="5" placeholder="Tell us a little about yourself..." class="form-textarea w-full px-4 py-3 rounded-xl text-sm resize-y outline-none focus:border-pink-main focus:ring-2 focus:ring-pink-main/10"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                </div>

                <button type="submit" class="bg-pink-main text-white px-8 py-3 rounded-full font-semibold text-sm hover:bg-pink-dark transition-all transform hover:-translate-y-px active:scale-95 shadow-md hover:shadow-lg flex items-center gap-2">
                    <svg viewBox="0 0 24 24" class="w-4 h-4 fill-current"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                    <span>Save Changes</span>
                </button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer_scripts.php'; ?>
</body>
</html>