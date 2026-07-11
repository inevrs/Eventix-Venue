<div class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[9999] opacity-0 transition-opacity duration-300 items-center justify-center hidden [&.active]:flex [&.active]:opacity-100" id="authModalOverlay">
    <div class="bg-white w-full max-w-[440px] rounded-2xl shadow-hover p-8 relative scale-95 transition-transform duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] [[id=authModalOverlay].active_&]:scale-100">
        <button class="absolute top-4 right-4 bg-transparent border-none text-2xl cursor-pointer text-text-muted hover:text-text transition-colors" onclick="closeAuthModal()">×</button>
        
        <h2 class="font-serif text-pink-dark text-3xl mb-2">Welcome Back</h2>
        <p class="text-text-muted text-sm mb-6">Log in to book your favorite venues instantly.</p>
        
        <div id="auth-error" class="hidden bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-4"></div>

        <form id="authLoginForm" onsubmit="handleAuthLogin(event)">
            <input type="hidden" id="pendingVenueId" name="venue_id" value="">
            
            <div class="mb-5">
                <label class="block text-[11px] font-semibold tracking-wider uppercase text-text-muted mb-2">Email Address</label>
                <input type="email" id="authEmail" name="email" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-sans text-text bg-white transition-colors focus:border-pink-main focus:bg-pink-50/30 outline-none">
            </div>
            
            <div class="mb-6">
                <label class="block text-[11px] font-semibold tracking-wider uppercase text-text-muted mb-2">Password</label>
                <input type="password" id="authPassword" name="password" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-sans text-text bg-white transition-colors focus:border-pink-main focus:bg-pink-50/30 outline-none">
            </div>
            
            <button type="submit" class="w-full inline-block px-7 py-3 rounded-full text-sm font-semibold bg-pink-main text-white hover:bg-pink-dark transition-all transform hover:-translate-y-px active:scale-95 text-center cursor-pointer border-none" id="authLoginBtn">Log in</button>
        </form>

        <p class="text-center mt-6 text-sm text-text-muted">
            Don't have an account? <a href="/eventix/register.php" class="text-pink-main font-semibold hover:underline">Sign up</a>
        </p>
    </div>
</div>

<script src="/eventix/js/auth_modal.js"></script>
