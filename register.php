<?php
session_start();
require_once 'includes/db.php';

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = mysqli_real_escape_string($connect, $_POST['full_name']);
    $email    = mysqli_real_escape_string($connect, $_POST['email']);
    $phone    = mysqli_real_escape_string($connect, $_POST['phone']);
    $role     = mysqli_real_escape_string($connect, $_POST['role']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = mysqli_query($connect, "SELECT id FROM users WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Email already registered.";
    } else {
        $sql = "INSERT INTO users (full_name, email, phone, password, role) VALUES ('$name','$email','$phone','$password','$role')";
        if (mysqli_query($connect, $sql)) {
            $success = "Account created! <a href='/eventix/login.php' class='underline font-bold'>Sign in here</a>.";
        } else {
            $error = "Something went wrong. Try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — Eventix</title>
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
            <h1 class="font-[Playfair_Display] text-6xl text-pink-dark leading-[1.1] mb-6">Join us<br>today.</h1>
            <p class="text-text-muted text-base max-w-[280px] leading-relaxed">Create your account and start discovering extraordinary event venues.</p>
        </div>
        <div class="relative z-10 flex gap-2">
            <span class="w-2 h-2 rounded-full bg-pink-main"></span>
            <span class="w-2 h-2 rounded-full bg-pink-main/30"></span>
            <span class="w-2 h-2 rounded-full bg-pink-main/30"></span>
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="w-full lg:w-7/12 bg-white flex flex-col justify-center items-center p-8 overflow-y-auto" data-aos="fade-left" data-aos-duration="1000">
        <div class="w-full max-w-[440px] py-10">
            <h1 class="font-[Playfair_Display] text-5xl text-pink-dark leading-[1.1] mb-4">Create<br>Account</h1>
            <p class="text-text-muted text-sm mb-10">Fill in your details to get started.</p>

            <?php if ($error): ?>
                <div class="bg-red-50 text-red-600 px-4 py-3 rounded-lg text-sm mb-6"><?= $error ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="bg-green-50 text-green-700 px-4 py-3 rounded-lg text-sm mb-6"><?= $success ?></div>
            <?php endif; ?>

            <form method="POST" onsubmit="return validateRegister()">
                <div class="mb-5">
                    <label class="block text-[11px] font-semibold tracking-wider uppercase text-text-muted mb-2">Full Name</label>
                    <input type="text" name="full_name" placeholder="Your full name" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-sans text-text transition-colors focus:border-pink-main focus:bg-pink-50/30 outline-none">
                </div>
                <div class="mb-5">
                    <label class="block text-[11px] font-semibold tracking-wider uppercase text-text-muted mb-2">Email Address</label>
                    <input type="email" name="email" placeholder="your@email.com" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-sans text-text transition-colors focus:border-pink-main focus:bg-pink-50/30 outline-none">
                </div>
                <div class="mb-5">
                    <label class="block text-[11px] font-semibold tracking-wider uppercase text-text-muted mb-2">Phone Number</label>
                    <input type="text" name="phone" placeholder="+60 12-345 6789" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-sans text-text transition-colors focus:border-pink-main focus:bg-pink-50/30 outline-none">
                </div>
                <div class="mb-5">
                    <label class="block text-[11px] font-semibold tracking-wider uppercase text-text-muted mb-2">Password</label>
                    <input type="password" name="password" placeholder="Min. 8 characters" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-sans text-text transition-colors focus:border-pink-main focus:bg-pink-50/30 outline-none">
                </div>
                <div class="mb-8">
                    <label class="block text-[11px] font-semibold tracking-wider uppercase text-text-muted mb-2">Register As</label>
                    <select name="role" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-sans text-text focus:border-pink-main focus:ring-2 focus:ring-pink-main/10 outline-none transition-all cursor-pointer pr-10">
                        <option value="customer">Customer</option>
                        <option value="manager">Venue Manager</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-pink-main text-white py-3.5 rounded-full font-semibold text-sm hover:bg-pink-dark transition-all transform hover:-translate-y-px active:scale-95 shadow-md hover:shadow-lg active:scale-95 transition-all">Create Account</button>
            </form>

            <p class="text-center mt-8 text-sm text-text-muted">
                Already have an account? <a href="/eventix/login.php" class="text-pink-main font-semibold hover:underline">Sign in</a>
            </p>
        </div>
    </div>
</div>

<script>
function validateRegister() {
    const name = document.querySelector('input[name="full_name"]').value.trim();
    const email = document.querySelector('input[name="email"]').value.trim();
    const phone = document.querySelector('input[name="phone"]').value.trim();
    const pass = document.querySelector('input[name="password"]').value;
    let errors = [];

    if (name.length < 3) errors.push('Full name must be at least 3 characters.');
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) errors.push('Please enter a valid email address.');
    if (phone && !/^[\d\s\-\+]{8,15}$/.test(phone)) errors.push('Please enter a valid phone number.');
    if (pass.length < 8) errors.push('Password must be at least 8 characters.');

    if (errors.length > 0) {
        // Remove existing JS error
        let existing = document.getElementById('js-error');
        if (existing) existing.remove();
        // Create error div
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
<?php include 'includes/footer_scripts.php'; ?>
</body>
</html>
