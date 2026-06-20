<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header("Location: /eventix/" . userRole() . "/dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = mysqli_real_escape_string($connect, $_POST['email']);
    $password = $_POST['password'];

    $sql    = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($connect, $sql);
    $user   = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name']    = $user['full_name'];
        $_SESSION['role']    = $user['role'];

        $_SESSION['profile_picture'] = $user['profile_picture'] ?? null; 

        $redirect = $_GET['redirect'] ?? '';
        if ($redirect && strpos($redirect, 'venue.php') === 0) {
            if ($user['role'] === 'customer') {
                $venue_id = explode('id=', $redirect)[1] ?? 0;
                header("Location: /eventix/customer/book.php?id=" . (int)$venue_id);
            } else {
                header("Location: /eventix/" . $redirect);
            }
        } else {
            if ($user['role'] === 'admin')   header("Location: /eventix/admin/dashboard.php");
            if ($user['role'] === 'manager') header("Location: /eventix/manager/dashboard.php");
            if ($user['role'] === 'customer') header("Location: /eventix/index.php");
        }
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — Eventix</title>
    <?php include 'includes/header_scripts.php'; ?>
</head>
<body class="text-text font-sans h-screen m-0 overflow-hidden">

<div class="flex h-screen">
    <!-- LEFT PANEL -->
    <div class="hidden lg:flex flex-col justify-between w-5/12 bg-pink-light p-14 relative" data-aos="fade-right" data-aos-duration="1000">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.8),transparent)] pointer-events-none"></div>
        <div class="relative z-10 flex flex-col items-start gap-3">
            <img src="/eventix/images/eventix_logo.jpg" alt="Eventix Logo" class="w-[120px] h-auto rounded-xl shadow-sm mb-2 mix-blend-multiply">
            <h2 class="font-sans text-3xl font-bold text-pink-main tracking-tight m-0">Eventix</h2>
            <span class="text-[10px] tracking-widest text-text-muted font-semibold uppercase">EVENT MANAGEMENT SYSTEM</span>
        </div>
        <div class="relative z-10">
            <h1 class="font-[Playfair_Display] text-6xl text-pink-dark leading-[1.1] mb-6">Every event<br>a memory.</h1>
            <p class="text-text-muted text-base max-w-[280px] leading-relaxed">Sign in to explore curated venues and manage your bookings with ease.</p>
        </div>
        <div class="relative z-10 flex gap-2">
            <span class="w-2 h-2 rounded-full bg-pink-main/30"></span>
            <span class="w-2 h-2 rounded-full bg-pink-main"></span>
            <span class="w-2 h-2 rounded-full bg-pink-main/30"></span>
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="w-full lg:w-7/12 bg-white flex flex-col justify-center items-center p-8 overflow-y-auto" data-aos="fade-left" data-aos-duration="1000">
        <div class="w-full max-w-[400px]">
            <h1 class="font-[Playfair_Display] text-5xl text-pink-dark leading-[1.1] mb-4">Welcome<br>Back</h1>
            <p class="text-text-muted text-sm mb-10">Sign in to your account to continue.</p>

            <?php if ($error): ?>
                <div class="bg-red-50 text-red-600 px-4 py-3 rounded-lg text-sm mb-6"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" onsubmit="return validateLogin()">
                <div class="mb-5">
                    <label class="block text-[11px] font-semibold tracking-wider uppercase text-text-muted mb-2">Email Address</label>
                    <input type="email" name="email" placeholder="your@email.com" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-sans text-text transition-colors focus:border-pink-main focus:bg-pink-50/30 outline-none">
                </div>
                <div class="mb-8">
                    <label class="block text-[11px] font-semibold tracking-wider uppercase text-text-muted mb-2">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="passInput" placeholder="••••••••" required class="w-full px-4 py-3 pr-10 border border-gray-200 rounded-xl text-sm font-sans text-text transition-colors focus:border-pink-main focus:bg-pink-50/30 outline-none">
                        <span onclick="togglePass()" class="absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer text-text-muted hover:text-pink-main text-lg select-none">👁</span>
                    </div>
                </div>
                <button type="submit" class="w-full bg-pink-main text-white py-3.5 rounded-full font-semibold text-sm hover:bg-pink-dark transition-all transform hover:-translate-y-px active:scale-95 shadow-md hover:shadow-lg active:scale-95 transition-all">Sign In →</button>
            </form>

            <p class="text-center mt-8 text-sm text-text-muted">
                Don't have an account? <a href="/eventix/register.php" class="text-pink-main font-semibold hover:underline">Register here</a>
            </p>
            <div class="text-center mt-12">
                <a href="/eventix/login.php?role=admin" class="text-[10px] tracking-widest uppercase font-semibold text-text-muted hover:text-pink-main transition-colors">ADMIN PORTAL</a>
            </div>
        </div>
    </div>
</div>

<script>
function validateLogin() {
    const email = document.querySelector('input[name="email"]').value.trim();
    let errors = [];
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        errors.push('Please enter a valid email address.');
    }
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
<script src="/eventix/js/auth.js"></script>
<?php include 'includes/footer_scripts.php'; ?>
</body>
</html>