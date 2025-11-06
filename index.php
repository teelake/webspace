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

<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-primary via-blue-600 to-blue-800 text-white py-20 md:py-32">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center" data-aos="fade-up">
            <h1 class="text-4xl md:text-6xl font-bold mb-6"><?php echo SITE_TAGLINE; ?></h1>
            <p class="text-xl md:text-2xl text-blue-100 mb-8">We design, build, and manage digital experiences that grow brands and empower people.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/contact" class="bg-white text-primary px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition transform hover:scale-105">Get Started</a>
                <a href="/services" class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-primary transition transform hover:scale-105">View Our Services</a>
            </div>
        </div>
    </div>
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white"/>
        </svg>
    </div>
</section>

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

