/* 
   LaraBids Elite - Core Logic
   Global Countdown Timer & UI Interactions
*/

document.addEventListener('DOMContentLoaded', () => {

    // Initializing AOS
    if (typeof AOS !== 'undefined') {
        AOS.init({
            once: true,
            duration: 1000,
            easing: 'ease-out-cubic'
        });
    }

    // --- Partials Loader (For Static HTML Consistency) ---
    async function loadPartials() {
        const headerHolder = document.getElementById('header-holder');
        const footerHolder = document.getElementById('footer-holder');

        if (headerHolder) {
            try {
                const response = await fetch('partials/header.html');
                const html = await response.text();
                headerHolder.innerHTML = html;
                setActiveLink();
            } catch (err) {
                console.error('Failed to load header:', err);
            }
        }

        if (footerHolder) {
            try {
                const response = await fetch('partials/footer.html');
                const html = await response.text();
                footerHolder.innerHTML = html;
            } catch (err) {
                console.error('Failed to load footer:', err);
            }
        }
    }

    function setActiveLink() {
        const path = window.location.pathname;
        const page = path.split("/").pop() || 'index.html';
        const links = document.querySelectorAll('.nav-link');

        links.forEach(link => {
            if (link.getAttribute('href') === page) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });

        // Dashboard specific tweaks
        const dashboardPages = ['dashboard.html', 'profile.html', 'wishlist.html', 'my-bids.html', 'winning-items.html'];
        if (dashboardPages.includes(page)) {
            const navbar = document.querySelector('.navbar-elite');
            if (navbar) {
                navbar.classList.add('bg-transparent', 'border-0');

                // Hide redundant logo on desktop as sidebar has its own
                const brand = navbar.querySelector('.navbar-brand');
                if (brand) brand.classList.add('d-lg-none');

                const authBtns = navbar.querySelector('.d-flex.gap-3');
                if (authBtns && authBtns.querySelector('.guest-only')) {
                    authBtns.querySelector('.guest-only').classList.add('d-none');
                }
            }
        }
    }

    // Sidebar Toggle Logic
    function initSidebarToggle() {
        const sidebar = document.querySelector('.sidebar-elite');
        const overlay = document.querySelector('.sidebar-overlay');
        const toggleBtn = document.querySelector('.sidebar-toggle');

        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('show');
                if (overlay) overlay.classList.toggle('show');
            });
        }

        if (overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });
        }
    }

    // Initialize
    loadPartials();
    initSidebarToggle();

    // --- Global Countdown System ---
    const timers = document.querySelectorAll('.timer-val');

    function updateCountdowns() {
        timers.forEach(timer => {
            let d = parseInt(timer.getAttribute('data-days')) || 0;
            let h = parseInt(timer.getAttribute('data-hours')) || 0;
            let m = parseInt(timer.getAttribute('data-min')) || 0;
            let s = parseInt(timer.getAttribute('data-sec')) || 0;

            if (s > 0) {
                s--;
            } else {
                if (m > 0) {
                    m--; s = 59;
                } else {
                    if (h > 0) {
                        h--; m = 59; s = 59;
                    } else {
                        if (d > 0) {
                            d--; h = 23; m = 59; s = 59;
                        }
                    }
                }
            }

            timer.setAttribute('data-days', d);
            timer.setAttribute('data-hours', h);
            timer.setAttribute('data-min', m);
            timer.setAttribute('data-sec', s);

            const dayEl = timer.querySelector('[data-days]') || (timer.hasAttribute('data-days') ? timer : null);
            const hourEl = timer.querySelector('[data-hours]') || (timer.hasAttribute('data-hours') ? timer : null);
            const minEl = timer.querySelector('[data-min]') || (timer.hasAttribute('data-min') ? timer : null);
            const secEl = timer.querySelector('[data-sec]') || (timer.hasAttribute('data-sec') ? timer : null);

            if (dayEl && dayEl !== timer) dayEl.innerText = d.toString().padStart(2, '0');
            if (hourEl && hourEl !== timer) hourEl.innerText = h.toString().padStart(2, '0');
            if (minEl && minEl !== timer) minEl.innerText = m.toString().padStart(2, '0');
            if (secEl && secEl !== timer) secEl.innerText = s.toString().padStart(2, '0');

            if (timer.classList.contains('combined-timer')) {
                timer.innerText = `${d.toString().padStart(2, '0')}:${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
            }
        });
    }

    if (timers.length > 0) {
        setInterval(updateCountdowns, 1000);
    }

    console.log('LaraBids Elite System Initialized.');
});
