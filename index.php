<?php
require_once __DIR__ . '/config/config.php';

$page_title = SITE_NAME . ' - ' . SITE_TAGLINE;
$meta_description = 'We design, build, and manage digital experiences that grow brands and empower people. Your complete digital partner for web development, digital marketing, IT consultancy, and training.';

$db = new Database();
$conn = $db->getConnection();

// Get home content
$stmt = $conn->query("SELECT * FROM home_content WHERE status = 1 ORDER BY `order` ASC");
$home_sections = $stmt->fetchAll();

// Get services
$stmt = $conn->query("SELECT * FROM services WHERE status = 1 ORDER BY `order` ASC LIMIT 6");
$services = $stmt->fetchAll();

// Get partners
$stmt = $conn->query("SELECT * FROM partners WHERE status = 1 ORDER BY `order` ASC");
$partners = $stmt->fetchAll();

// Get recent blog posts
$stmt = $conn->query("SELECT * FROM blog_posts WHERE status = 1 AND published_at IS NOT NULL AND published_at <= NOW() ORDER BY published_at DESC LIMIT 3");
$blog_posts = $stmt->fetchAll();

include 'includes/header.php';
?>

<!-- Hero Carousel Section -->
<section class="relative h-[85vh] md:h-[90vh] min-h-[600px] overflow-hidden">
    <!-- Carousel Container -->
    <div class="hero-carousel relative h-full">
        <!-- Slide 1 - Background Image with Overlay -->
        <div class="hero-slide active absolute inset-0 flex items-center justify-center bg-cover bg-center bg-no-repeat" style="background-image: url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=1920&h=1080&fit=crop');">
            <div class="absolute inset-0 bg-gradient-to-br from-primary/90 via-brandBlue/85 to-blue-900/90"></div>
            <div class="container mx-auto px-4 relative z-10 text-center text-white">
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6 animate-fade-in-up drop-shadow-lg"><?php echo SITE_TAGLINE; ?></h1>
                <p class="text-xl md:text-2xl text-blue-100 mb-8 max-w-3xl mx-auto animate-fade-in-up-delay drop-shadow-md">We design, build, and manage digital experiences that grow brands and empower people.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up-delay-2">
                    <a href="/contact" class="bg-brandYellow text-primary px-8 py-4 rounded-lg font-semibold hover:bg-yellow-300 transition transform hover:scale-105 shadow-xl">Get Started</a>
                    <a href="/services" class="bg-white/10 backdrop-blur-sm border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-primary transition transform hover:scale-105 shadow-xl">View Our Services</a>
                </div>
            </div>
        </div>
        
        <!-- Slide 2 - Background Image with Overlay -->
        <div class="hero-slide absolute inset-0 flex items-center justify-center bg-cover bg-center bg-no-repeat" style="background-image: url('https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=1920&h=1080&fit=crop');">
            <div class="absolute inset-0 bg-gradient-to-br from-brandYellow/85 via-yellow-400/80 to-yellow-500/85"></div>
            <div class="container mx-auto px-4 relative z-10 text-center">
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6 text-primary drop-shadow-lg">Digital Innovation at Your Fingertips</h1>
                <p class="text-xl md:text-2xl text-gray-900 mb-8 max-w-3xl mx-auto drop-shadow-md font-medium">Transform your business with cutting-edge technology and expert solutions.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/portfolio" class="bg-primary text-white px-8 py-4 rounded-lg font-semibold hover:bg-brandBlue transition transform hover:scale-105 shadow-xl">View Portfolio</a>
                    <a href="/training" class="bg-white/90 backdrop-blur-sm border-2 border-primary text-primary px-8 py-4 rounded-lg font-semibold hover:bg-primary hover:text-white transition transform hover:scale-105 shadow-xl">Our Training</a>
                </div>
            </div>
        </div>
        
        <!-- Slide 3 - Background Image with Overlay -->
        <div class="hero-slide absolute inset-0 flex items-center justify-center bg-cover bg-center bg-no-repeat" style="background-image: url('https://images.unsplash.com/photo-1551434678-e076c223a692?w=1920&h=1080&fit=crop');">
            <div class="absolute inset-0 bg-gradient-to-br from-primary/90 via-brandBlue/85 to-blue-900/90"></div>
            <div class="container mx-auto px-4 relative z-10 text-center text-white">
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6 drop-shadow-lg">Empowering Success Through Technology</h1>
                <p class="text-xl md:text-2xl text-blue-100 mb-8 max-w-3xl mx-auto drop-shadow-md">Your trusted partner for web development, digital marketing, and IT solutions.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/about" class="bg-brandYellow text-primary px-8 py-4 rounded-lg font-semibold hover:bg-yellow-300 transition transform hover:scale-105 shadow-xl">Learn More</a>
                    <a href="/contact" class="bg-white/10 backdrop-blur-sm border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-primary transition transform hover:scale-105 shadow-xl">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Carousel Navigation -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20 flex space-x-3">
        <button class="carousel-dot active w-3 h-3 rounded-full bg-white opacity-75 hover:opacity-100 transition" data-slide="0"></button>
        <button class="carousel-dot w-3 h-3 rounded-full bg-white opacity-75 hover:opacity-100 transition" data-slide="1"></button>
        <button class="carousel-dot w-3 h-3 rounded-full bg-white opacity-75 hover:opacity-100 transition" data-slide="2"></button>
    </div>
    
    <!-- Previous/Next Buttons -->
    <button class="carousel-prev absolute left-4 top-1/2 transform -translate-y-1/2 z-20 bg-white bg-opacity-20 hover:bg-opacity-30 text-white p-3 rounded-full transition" aria-label="Previous slide">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
    </button>
    <button class="carousel-next absolute right-4 top-1/2 transform -translate-y-1/2 z-20 bg-white bg-opacity-20 hover:bg-opacity-30 text-white p-3 rounded-full transition" aria-label="Next slide">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </button>
    
    <!-- Wave Separator -->
    <div class="absolute bottom-0 left-0 right-0 z-10">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white"/>
        </svg>
    </div>
</section>

<style>
.hero-slide {
    opacity: 0;
    transition: opacity 1s ease-in-out;
    transform: translateX(0);
}
.hero-slide.active {
    opacity: 1;
    z-index: 1;
}
.hero-slide.prev {
    transform: translateX(-100%);
}
.hero-slide.next {
    transform: translateX(100%);
}
@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-fade-in-up {
    animation: fade-in-up 0.8s ease-out;
}
.animate-fade-in-up-delay {
    animation: fade-in-up 0.8s ease-out 0.2s both;
}
.animate-fade-in-up-delay-2 {
    animation: fade-in-up 0.8s ease-out 0.4s both;
}
</style>

<script>
// Hero Carousel Functionality
(function() {
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.carousel-dot');
    const prevBtn = document.querySelector('.carousel-prev');
    const nextBtn = document.querySelector('.carousel-next');
    let currentSlide = 0;
    let autoSlideInterval;
    
    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.remove('active');
            if (i === index) {
                slide.classList.add('active');
            }
        });
        
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
        
        currentSlide = index;
    }
    
    function nextSlide() {
        const next = (currentSlide + 1) % slides.length;
        showSlide(next);
    }
    
    function prevSlide() {
        const prev = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(prev);
    }
    
    function startAutoSlide() {
        autoSlideInterval = setInterval(nextSlide, 6000); // Change slide every 6 seconds
    }
    
    function stopAutoSlide() {
        clearInterval(autoSlideInterval);
    }
    
    // Event Listeners
    nextBtn?.addEventListener('click', () => {
        nextSlide();
        stopAutoSlide();
        startAutoSlide();
    });
    
    prevBtn?.addEventListener('click', () => {
        prevSlide();
        stopAutoSlide();
        startAutoSlide();
    });
    
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            showSlide(index);
            stopAutoSlide();
            startAutoSlide();
        });
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') prevSlide();
        if (e.key === 'ArrowRight') nextSlide();
    });
    
    // Pause on hover
    const carousel = document.querySelector('.hero-carousel');
    carousel?.addEventListener('mouseenter', stopAutoSlide);
    carousel?.addEventListener('mouseleave', startAutoSlide);
    
    // Start auto-slide
    startAutoSlide();
})();
</script>

<!-- Services Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-12">
            <div data-aos="fade-right">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Our Services</h2>
                <p class="text-gray-600 mb-6">Comprehensive digital solutions to help your business thrive in the digital age</p>
                <a href="/services" class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-brandBlue transition inline-block shadow-md hover:shadow-lg">View All Services</a>
            </div>
            <div data-aos="fade-left" class="hidden lg:block">
                <img src="https://undraw.co/api/illustrations/undraw_web_devices_re_m8sc?primary=%231D4ED8&secondary=%23FCD34D" alt="Services" class="w-full h-auto" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&h=600&fit=crop';">
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($services as $index => $service): ?>
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                <div class="w-12 h-12 bg-gradient-to-br from-primary to-brandYellow rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($service['title']); ?></h3>
                <p class="text-gray-600 mb-4"><?php echo htmlspecialchars(truncate($service['description'], 100)); ?></p>
                <a href="/services#<?php echo $service['slug']; ?>" class="text-primary font-semibold hover:underline inline-flex items-center">
                    Learn More 
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center" data-aos="fade-up" data-aos-delay="0">
                <div class="text-4xl md:text-5xl font-bold text-primary mb-2">12+</div>
                <div class="text-gray-600 font-medium">Services</div>
            </div>
            <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                <div class="text-4xl md:text-5xl font-bold text-primary mb-2">100+</div>
                <div class="text-gray-600 font-medium">Projects</div>
            </div>
            <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                <div class="text-4xl md:text-5xl font-bold text-primary mb-2">500+</div>
                <div class="text-gray-600 font-medium">Trained</div>
            </div>
            <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                <div class="text-4xl md:text-5xl font-bold text-primary mb-2">12+</div>
                <div class="text-gray-600 font-medium">Clients & Partners</div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div data-aos="fade-right" class="order-2 md:order-1">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">About Webspace Innovation Hub</h2>
                <p class="text-gray-600 mb-4">We are a leading digital solutions provider committed to empowering businesses and individuals through innovative technology and comprehensive training programs.</p>
                <p class="text-gray-600 mb-6">With years of experience in web development, digital marketing, IT consultancy, and skills training, we help our clients achieve their digital transformation goals.</p>
                <a href="/about" class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-brandBlue transition inline-block shadow-md hover:shadow-lg">Learn More About Us</a>
            </div>
            <div data-aos="fade-left" class="order-1 md:order-2">
                <div class="relative">
                    <img src="https://undraw.co/api/illustrations/undraw_team_collaboration_re_ow29?primary=%231D4ED8&secondary=%23FCD34D" alt="Team Collaboration" class="w-full h-auto rounded-lg shadow-xl" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&h=600&fit=crop';">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-brandYellow/5 rounded-lg pointer-events-none"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Why Choose Us</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">We combine expertise, innovation, and dedication to deliver exceptional results</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center" data-aos="fade-up" data-aos-delay="0">
                <div class="w-16 h-16 bg-gradient-to-br from-primary to-brandYellow rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Proven Track Record</h3>
                <p class="text-gray-600">Years of experience delivering successful projects for clients across various industries</p>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                <div class="w-16 h-16 bg-gradient-to-br from-primary to-brandYellow rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Fast & Efficient</h3>
                <p class="text-gray-600">Agile methodologies ensure quick turnaround times without compromising quality</p>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                <div class="w-16 h-16 bg-gradient-to-br from-primary to-brandYellow rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Competitive Pricing</h3>
                <p class="text-gray-600">Transparent pricing with no hidden costs. Value-driven solutions that fit your budget</p>
            </div>
        </div>
    </div>
</section>

<!-- How We Work Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">How We Work</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Our proven process ensures successful project delivery</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center" data-aos="fade-up" data-aos-delay="0">
                <div class="w-20 h-20 bg-primary text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">1</div>
                <h3 class="text-lg font-semibold mb-2">Discovery</h3>
                <p class="text-gray-600 text-sm">We understand your goals, challenges, and requirements</p>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                <div class="w-20 h-20 bg-primary text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">2</div>
                <h3 class="text-lg font-semibold mb-2">Planning</h3>
                <p class="text-gray-600 text-sm">We create a detailed roadmap and strategy</p>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                <div class="w-20 h-20 bg-primary text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">3</div>
                <h3 class="text-lg font-semibold mb-2">Development</h3>
                <p class="text-gray-600 text-sm">We build and implement with regular updates</p>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                <div class="w-20 h-20 bg-primary text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">4</div>
                <h3 class="text-lg font-semibold mb-2">Launch & Support</h3>
                <p class="text-gray-600 text-sm">We launch and provide ongoing maintenance</p>
            </div>
        </div>
    </div>
</section>

<!-- Partners Section -->
<?php if (!empty($partners)): ?>
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Clients & Partners</h2>
            <p class="text-gray-600">Trusted by leading organizations and businesses</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-8" data-aos="fade-up">
            <?php foreach ($partners as $partner): ?>
            <div class="bg-white p-4 rounded-lg shadow-md flex items-center justify-center h-24 hover:shadow-lg transition">
                <?php if ($partner['logo']): ?>
                    <img src="<?php echo UPLOAD_URL . $partner['logo']; ?>" alt="<?php echo htmlspecialchars($partner['name']); ?>" class="max-h-16 max-w-full">
                <?php else: ?>
                    <span class="text-gray-600 text-sm text-center"><?php echo htmlspecialchars($partner['name']); ?></span>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Testimonials Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">What Our Clients Say</h2>
            <p class="text-gray-600">Trusted by businesses across various industries</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="0">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary to-brandYellow rounded-full flex items-center justify-center text-white font-bold">JD</div>
                    <div class="ml-4">
                        <h4 class="font-semibold">John Doe</h4>
                        <p class="text-sm text-gray-500">CEO, Tech Solutions</p>
                    </div>
                </div>
                <div class="flex text-brandYellow mb-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                </div>
                <p class="text-gray-600">"Webspace delivered exactly what we needed. Their team is professional, responsive, and truly understands digital transformation."</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary to-brandYellow rounded-full flex items-center justify-center text-white font-bold">SM</div>
                    <div class="ml-4">
                        <h4 class="font-semibold">Sarah Miller</h4>
                        <p class="text-sm text-gray-500">Marketing Director</p>
                    </div>
                </div>
                <div class="flex text-brandYellow mb-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                </div>
                <p class="text-gray-600">"The digital marketing strategies they implemented increased our online presence significantly. Highly recommended!"</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="200">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary to-brandYellow rounded-full flex items-center justify-center text-white font-bold">RW</div>
                    <div class="ml-4">
                        <h4 class="font-semibold">Robert Williams</h4>
                        <p class="text-sm text-gray-500">Business Owner</p>
                    </div>
                </div>
                <div class="flex text-brandYellow mb-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                </div>
                <p class="text-gray-600">"Their training programs are excellent. Our team gained valuable skills that transformed our operations."</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section - After Testimonials (High Engagement Point) -->
<section class="py-16 bg-gradient-to-r from-primary to-blue-600 text-white">
    <div class="container mx-auto px-4 text-center" data-aos="fade-up">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to Get Started?</h2>
        <p class="text-xl text-blue-100 mb-8">Let's discuss how we can help transform your digital presence</p>
        <a href="/contact" class="bg-brandYellow text-primary px-8 py-3 rounded-lg font-semibold hover:bg-yellow-300 transition inline-block shadow-lg">Contact Us Today</a>
    </div>
</section>

<!-- Blog Section -->
<?php if (!empty($blog_posts)): ?>
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Latest from Our Blog</h2>
            <p class="text-gray-600">Insights, tips, and updates from our team</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ($blog_posts as $index => $post): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                <?php if ($post['featured_image']): ?>
                    <img src="<?php echo UPLOAD_URL . $post['featured_image']; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="w-full h-48 object-cover">
                <?php else: ?>
                    <div class="w-full h-48 bg-gradient-to-br from-primary to-accent"></div>
                <?php endif; ?>
                <div class="p-6">
                    <div class="text-sm text-gray-500 mb-2"><?php echo formatDate($post['published_at']); ?></div>
                    <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($post['title']); ?></h3>
                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars(truncate($post['excerpt'] ?: $post['content'], 100)); ?></p>
                    <a href="/blog/<?php echo $post['slug']; ?>" class="text-primary font-semibold hover:underline">Read More →</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-12" data-aos="fade-up">
            <a href="/blog" class="bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition inline-block">View All Posts</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- FAQs Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Frequently Asked Questions</h2>
            <p class="text-gray-600">Find answers to common questions about our services</p>
        </div>
        
        <div class="max-w-3xl mx-auto space-y-4">
            <div class="bg-white rounded-lg shadow-md overflow-hidden" data-aos="fade-up" data-aos-delay="0">
                <button class="faq-toggle w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition" onclick="toggleFaq(this)">
                    <span class="font-semibold text-gray-900">What services do you offer?</span>
                    <svg class="w-5 h-5 text-primary transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="faq-content hidden px-6 pb-4 text-gray-600">
                    We offer comprehensive digital solutions including web design & development, mobile app development, digital marketing, SEO, IT consultancy, cloud computing, graphics design, data analysis, and skills training programs.
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                <button class="faq-toggle w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition" onclick="toggleFaq(this)">
                    <span class="font-semibold text-gray-900">How long does a typical project take?</span>
                    <svg class="w-5 h-5 text-primary transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="faq-content hidden px-6 pb-4 text-gray-600">
                    Project timelines vary based on scope and complexity. A simple website typically takes 2-4 weeks, while complex web applications may take 2-6 months. We provide detailed timelines during the planning phase.
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden" data-aos="fade-up" data-aos-delay="200">
                <button class="faq-toggle w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition" onclick="toggleFaq(this)">
                    <span class="font-semibold text-gray-900">Do you provide ongoing support?</span>
                    <svg class="w-5 h-5 text-primary transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="faq-content hidden px-6 pb-4 text-gray-600">
                    Yes, we offer comprehensive maintenance and support packages to ensure your digital solutions continue to perform optimally. This includes updates, security patches, and technical support.
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden" data-aos="fade-up" data-aos-delay="300">
                <button class="faq-toggle w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition" onclick="toggleFaq(this)">
                    <span class="font-semibold text-gray-900">Can you work with our existing systems?</span>
                    <svg class="w-5 h-5 text-primary transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="faq-content hidden px-6 pb-4 text-gray-600">
                    Absolutely! We specialize in integrating new solutions with existing systems. Our team will assess your current infrastructure and ensure seamless integration.
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section - Before Footer (Standard Practice) -->
<section class="py-16 bg-gradient-to-r from-primary to-brandBlue text-white">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto text-center" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Stay Updated</h2>
            <p class="text-blue-100 mb-8">Subscribe to our newsletter for the latest insights, tips, and updates</p>
            <form class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto" onsubmit="handleNewsletter(event)">
                <input type="email" name="email" placeholder="Enter your email" required class="flex-1 px-4 py-3 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-brandYellow">
                <button type="submit" class="bg-brandYellow text-primary px-8 py-3 rounded-lg font-semibold hover:bg-yellow-300 transition shadow-lg">Subscribe</button>
            </form>
            <p class="text-sm text-blue-200 mt-4">We respect your privacy. Unsubscribe at any time.</p>
        </div>
    </div>
</section>

<script>
function toggleFaq(button) {
    const content = button.nextElementSibling;
    const icon = button.querySelector('svg');
    const isOpen = !content.classList.contains('hidden');
    
    // Close all FAQs
    document.querySelectorAll('.faq-content').forEach(item => item.classList.add('hidden'));
    document.querySelectorAll('.faq-toggle svg').forEach(item => item.classList.remove('rotate-180'));
    
    // Toggle current FAQ
    if (isOpen) {
        content.classList.add('hidden');
        icon.classList.remove('rotate-180');
    } else {
        content.classList.remove('hidden');
        icon.classList.add('rotate-180');
    }
}

function handleNewsletter(event) {
    event.preventDefault();
    const email = event.target.email.value;
    // In production, this would send to your backend
    alert('Thank you for subscribing! We\'ll keep you updated.');
    event.target.reset();
}
</script>

<?php include 'includes/footer.php'; ?>

