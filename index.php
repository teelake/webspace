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
        <!-- Slide 1 -->
        <div class="hero-slide active absolute inset-0 bg-gradient-to-br from-primary via-blue-600 to-blue-800 flex items-center justify-center">
            <div class="absolute inset-0 bg-black bg-opacity-30"></div>
            <div class="container mx-auto px-4 relative z-10 text-center text-white">
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6 animate-fade-in-up"><?php echo SITE_TAGLINE; ?></h1>
                <p class="text-xl md:text-2xl text-blue-100 mb-8 max-w-3xl mx-auto animate-fade-in-up-delay">We design, build, and manage digital experiences that grow brands and empower people.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up-delay-2">
                    <a href="/contact" class="bg-white text-primary px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition transform hover:scale-105 shadow-lg">Get Started</a>
                    <a href="/services" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-primary transition transform hover:scale-105">View Our Services</a>
                </div>
            </div>
        </div>
        
        <!-- Slide 2 -->
        <div class="hero-slide absolute inset-0 bg-gradient-to-br from-accent via-green-500 to-green-600 flex items-center justify-center">
            <div class="absolute inset-0 bg-black bg-opacity-30"></div>
            <div class="container mx-auto px-4 relative z-10 text-center text-white">
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6">Digital Innovation at Your Fingertips</h1>
                <p class="text-xl md:text-2xl text-green-100 mb-8 max-w-3xl mx-auto">Transform your business with cutting-edge technology and expert solutions.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/portfolio" class="bg-white text-accent px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition transform hover:scale-105 shadow-lg">View Portfolio</a>
                    <a href="/training" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-accent transition transform hover:scale-105">Our Training</a>
                </div>
            </div>
        </div>
        
        <!-- Slide 3 -->
        <div class="hero-slide absolute inset-0 bg-gradient-to-br from-purple-600 via-purple-700 to-indigo-800 flex items-center justify-center">
            <div class="absolute inset-0 bg-black bg-opacity-30"></div>
            <div class="container mx-auto px-4 relative z-10 text-center text-white">
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6">Empowering Success Through Technology</h1>
                <p class="text-xl md:text-2xl text-purple-100 mb-8 max-w-3xl mx-auto">Your trusted partner for web development, digital marketing, and IT solutions.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/about" class="bg-white text-purple-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition transform hover:scale-105 shadow-lg">Learn More</a>
                    <a href="/contact" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-purple-600 transition transform hover:scale-105">Contact Us</a>
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
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Our Services</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Comprehensive digital solutions to help your business thrive in the digital age</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($services as $index => $service): ?>
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                <div class="w-12 h-12 bg-primary bg-opacity-10 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($service['title']); ?></h3>
                <p class="text-gray-600"><?php echo htmlspecialchars(truncate($service['description'], 100)); ?></p>
                <a href="/services#<?php echo $service['slug']; ?>" class="text-primary font-semibold mt-4 inline-block hover:underline">Learn More →</a>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-12" data-aos="fade-up">
            <a href="/services" class="bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition inline-block">View All Services</a>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-20">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div data-aos="fade-right">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">About Webspace Innovation Hub</h2>
                <p class="text-gray-600 mb-4">We are a leading digital solutions provider committed to empowering businesses and individuals through innovative technology and comprehensive training programs.</p>
                <p class="text-gray-600 mb-6">With years of experience in web development, digital marketing, IT consultancy, and skills training, we help our clients achieve their digital transformation goals.</p>
                <a href="/about" class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition inline-block">Learn More About Us</a>
            </div>
            <div data-aos="fade-left">
                <div class="bg-gradient-to-br from-primary to-accent rounded-lg p-8 text-white">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <div class="text-4xl font-bold mb-2">10+</div>
                            <div class="text-blue-100">Services</div>
                        </div>
                        <div>
                            <div class="text-4xl font-bold mb-2">12+</div>
                            <div class="text-blue-100">Partners</div>
                        </div>
                        <div>
                            <div class="text-4xl font-bold mb-2">100+</div>
                            <div class="text-blue-100">Projects</div>
                        </div>
                        <div>
                            <div class="text-4xl font-bold mb-2">500+</div>
                            <div class="text-blue-100">Trained</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Partners Section -->
<?php if (!empty($partners)): ?>
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Our Partners</h2>
            <p class="text-gray-600">Trusted by leading organizations</p>
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

<!-- Blog Section -->
<?php if (!empty($blog_posts)): ?>
<section class="py-20">
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

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-primary to-blue-600 text-white">
    <div class="container mx-auto px-4 text-center" data-aos="fade-up">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to Get Started?</h2>
        <p class="text-xl text-blue-100 mb-8">Let's discuss how we can help transform your digital presence</p>
        <a href="/contact" class="bg-white text-primary px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition inline-block">Contact Us Today</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

