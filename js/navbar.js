// Dynamic Navbar Scroll Effect
window.addEventListener('scroll', () => {
    const navbar = document.getElementById('mainNavbar');
    if (window.scrollY > 10) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

// iOS Glass Hover Sliding Pill Animation
document.addEventListener("DOMContentLoaded", () => {
    const navMenu = document.getElementById('navMenu');
    
    if (navMenu) {
        // Create the glass pill element
        const slider = document.createElement('div');
        slider.id = 'navSlider';
        // Tailwind classes for a smooth glass pill
        slider.className = 'absolute h-full bg-gray-200/50 backdrop-blur-md rounded-full transition-all duration-300 ease-out opacity-0 pointer-events-none shadow-sm z-0';
        slider.style.top = '0';
        slider.style.left = '0';
        navMenu.appendChild(slider);

        const items = navMenu.querySelectorAll('li a');
        
        items.forEach(item => {
            item.addEventListener('mouseenter', (e) => {
                // Don't show the slider if hovering over the active item (which is already highlighted in pink)
                if (!item.classList.contains('bg-pink-main')) {
                    const rect = item.getBoundingClientRect();
                    const menuRect = navMenu.getBoundingClientRect();
                    
                    // Set width and transform to slide seamlessly
                    slider.style.width = `${rect.width}px`;
                    slider.style.transform = `translateX(${rect.left - menuRect.left}px)`;
                    slider.style.opacity = '1';
                } else {
                    slider.style.opacity = '0'; // Hide if hovering the active page link
                }
            });
        });
        
        // Hide the slider when the mouse leaves the navigation menu entirely
        navMenu.addEventListener('mouseleave', () => {
            slider.style.opacity = '0';
        });
    }
});
