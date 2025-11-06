    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-20">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div data-aos="fade-up">
                    <img src="<?php echo LOGO_URL; ?>webspace-logo.png" alt="<?php echo SITE_NAME; ?>" class="h-12 w-auto mb-4">
                    <p class="text-gray-400 mb-4"><?php echo SITE_TAGLINE; ?></p>
                    <p class="text-sm text-gray-500">Registration No: <?php echo REGISTRATION_NO; ?></p>
                </div>
                
                <!-- Quick Links -->
                <div data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="/" class="text-gray-400 hover:text-white transition">Home</a></li>
                        <li><a href="/about" class="text-gray-400 hover:text-white transition">About Us</a></li>
                        <li><a href="/services" class="text-gray-400 hover:text-white transition">Services</a></li>
                        <li><a href="/training" class="text-gray-400 hover:text-white transition">Training</a></li>
                        <li><a href="/portfolio" class="text-gray-400 hover:text-white transition">Portfolio</a></li>
                        <li><a href="/blog" class="text-gray-400 hover:text-white transition">Blog</a></li>
                    </ul>
                </div>
                
                <!-- Services -->
                <div data-aos="fade-up" data-aos-delay="200">
                    <h3 class="text-lg font-semibold mb-4">Services</h3>
                    <ul class="space-y-2">
                        <li><a href="/services" class="text-gray-400 hover:text-white transition">Web Development</a></li>
                        <li><a href="/services" class="text-gray-400 hover:text-white transition">Digital Marketing</a></li>
                        <li><a href="/services" class="text-gray-400 hover:text-white transition">IT Consultancy</a></li>
                        <li><a href="/services" class="text-gray-400 hover:text-white transition">Training Programs</a></li>
                    </ul>
                </div>
                
                <!-- Contact -->
                <div data-aos="fade-up" data-aos-delay="300">
                    <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <a href="mailto:<?php echo SITE_EMAIL; ?>" class="hover:text-white transition"><?php echo SITE_EMAIL; ?></a>
                        </li>
                        <li class="flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <a href="tel:<?php echo SITE_PHONE_1; ?>" class="hover:text-white transition"><?php echo SITE_PHONE_1; ?></a>
                        </li>
                        <li class="flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <a href="tel:<?php echo SITE_PHONE_2; ?>" class="hover:text-white transition"><?php echo SITE_PHONE_2; ?></a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });
        
        // Mobile Menu Toggle
        document.getElementById('mobileMenuBtn')?.addEventListener('click', function() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        });
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#' && href.length > 1) {
                    const target = document.querySelector(href);
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });
        
        // Add scroll effect to navbar
        let lastScroll = 0;
        const navbar = document.querySelector('nav');
        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;
            if (currentScroll > 100) {
                navbar.classList.add('shadow-lg');
            } else {
                navbar.classList.remove('shadow-lg');
            }
            lastScroll = currentScroll;
        });
        
        // Lazy load images
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                            observer.unobserve(img);
                        }
                    }
                });
            });
            
            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    </script>
</body>
</html>

