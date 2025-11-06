    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-20">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-8">
                <!-- Company Info -->
                <div data-aos="fade-up" class="lg:col-span-2">
                    <img src="<?php echo LOGO_URL; ?>webspaceng-logo-renewed.png" alt="<?php echo SITE_NAME; ?>" class="h-20 md:h-24 lg:h-28 w-auto mb-4">
                    <p class="text-gray-400 mb-4"><?php echo SITE_TAGLINE; ?></p>
                    <!-- Registration Number with Icon (12-pointed gear/starburst) -->
                    <div class="flex items-center space-x-2 text-sm text-gray-400 mb-4">
                        <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <!-- 12-pointed starburst/gear icon -->
                            <path d="M12 2l1.09 3.27L16.27 6l-3.18 1.09L12 10.36l-1.09-3.27L7.73 6l3.18-1.09L12 2z" fill="currentColor"/>
                            <path d="M12 22l-1.09-3.27L7.73 18l3.18-1.09L12 13.64l1.09 3.27L16.27 18l-3.18 1.09L12 22z" fill="currentColor"/>
                            <path d="M22 12l-3.27-1.09L18 7.73l-1.09 3.18L13.64 12l3.27 1.09L18 16.27l1.09-3.18L22 12z" fill="currentColor"/>
                            <path d="M2 12l3.27 1.09L6 16.27l1.09-3.18L10.36 12l-3.27-1.09L6 7.73l-1.09 3.18L2 12z" fill="currentColor"/>
                            <path d="M19.07 4.93l-2.83 2.83-2.12-.71.71-2.12 2.83-2.83 2.12.71-.71 2.12z" fill="currentColor"/>
                            <path d="M4.93 19.07l2.83-2.83 2.12.71-.71 2.12-2.83 2.83-2.12-.71.71-2.12z" fill="currentColor"/>
                            <path d="M19.07 19.07l-2.83-2.83-.71 2.12 2.12.71 2.83 2.83.71-2.12-2.12-.71z" fill="currentColor"/>
                            <path d="M4.93 4.93l2.83 2.83.71-2.12-2.12-.71-2.83-2.83-.71 2.12 2.12.71z" fill="currentColor"/>
                        </svg>
                        <span>RC: <?php echo REGISTRATION_NO; ?></span>
                    </div>
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
                <div data-aos="fade-up" data-aos-delay="200">
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
                
                <!-- Connect With Us (Social Media) -->
                <div data-aos="fade-up" data-aos-delay="300">
                    <h3 class="text-lg font-semibold mb-4">Connect With Us</h3>
                    <p class="text-gray-400 mb-4 text-sm">Follow us on social media for updates and insights</p>
                    <div class="flex space-x-4">
                        <?php if (defined('SOCIAL_FACEBOOK') && SOCIAL_FACEBOOK): ?>
                            <a href="<?php echo SOCIAL_FACEBOOK; ?>" target="_blank" rel="noopener" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-primary transition group" aria-label="Facebook">
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                        <?php endif; ?>
                        <?php if (defined('SOCIAL_TWITTER') && SOCIAL_TWITTER): ?>
                            <a href="<?php echo SOCIAL_TWITTER; ?>" target="_blank" rel="noopener" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-primary transition group" aria-label="Twitter">
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                            </a>
                        <?php endif; ?>
                        <?php if (defined('SOCIAL_LINKEDIN') && SOCIAL_LINKEDIN): ?>
                            <a href="<?php echo SOCIAL_LINKEDIN; ?>" target="_blank" rel="noopener" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-primary transition group" aria-label="LinkedIn">
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            </a>
                        <?php endif; ?>
                        <?php if (defined('SOCIAL_INSTAGRAM') && SOCIAL_INSTAGRAM): ?>
                            <a href="<?php echo SOCIAL_INSTAGRAM; ?>" target="_blank" rel="noopener" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-primary transition group" aria-label="Instagram">
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>
                        <?php endif; ?>
                        <?php if (defined('SOCIAL_YOUTUBE') && SOCIAL_YOUTUBE): ?>
                            <a href="<?php echo SOCIAL_YOUTUBE; ?>" target="_blank" rel="noopener" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-primary transition group" aria-label="YouTube">
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            </a>
                        <?php endif; ?>
                    </div>
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
        
        // Mobile Services Dropdown Toggle
        document.getElementById('mobileServicesBtn')?.addEventListener('click', function() {
            const servicesMenu = document.getElementById('mobileServicesMenu');
            const arrow = document.getElementById('mobileServicesArrow');
            servicesMenu.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
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
            if (currentScroll > 10) {
                navbar.classList.add('show-gradient');
            } else {
                navbar.classList.remove('show-gradient');
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

