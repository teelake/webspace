<?php
require_once __DIR__ . '/config/config.php';

$page_title = 'About Us - ' . SITE_NAME;
$meta_description = 'Learn about Webspace Innovation Hub Limited, your complete digital partner. We provide web development, digital marketing, IT consultancy, and training services.';

$db = new Database();
$conn = $db->getConnection();

// Get about content
$stmt = $conn->query("SELECT * FROM about_content WHERE status = 1 ORDER BY `order` ASC");
$about_sections = $stmt->fetchAll();

// Get partners
$stmt = $conn->query("SELECT * FROM partners WHERE status = 1 ORDER BY `order` ASC");
$partners = $stmt->fetchAll();

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-primary to-blue-600 text-white py-16">
    <div class="container mx-auto px-4" data-aos="fade-up">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">About Us</h1>
        <p class="text-xl text-blue-100">Your Complete Digital Partner</p>
    </div>
</section>

<!-- About Content -->
<section class="py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="prose prose-lg max-w-none" data-aos="fade-up">
                <h2 class="text-3xl font-bold mb-6">Who We Are</h2>
                <p class="text-gray-600 mb-6">
                    Webspace Innovation Hub Limited is a leading digital solutions provider committed to empowering businesses and individuals through innovative technology and comprehensive training programs. With our registration number <strong><?php echo REGISTRATION_NO; ?></strong>, we operate as a trusted partner in the digital transformation journey.
                </p>
                <p class="text-gray-600 mb-6">
                    Our mission is to design, build, and manage digital experiences that grow brands and empower people. We combine technical expertise with creative solutions to deliver results that matter.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Our Core Values</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="0">
                <div class="w-16 h-16 bg-primary bg-opacity-10 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Innovation</h3>
                <p class="text-gray-600">We stay ahead of the curve with cutting-edge technologies and creative solutions.</p>
            </div>
            
            <div class="bg-white p-8 rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="100">
                <div class="w-16 h-16 bg-accent bg-opacity-10 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Excellence</h3>
                <p class="text-gray-600">We deliver high-quality solutions that exceed expectations and drive results.</p>
            </div>
            
            <div class="bg-white p-8 rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="200">
                <div class="w-16 h-16 bg-primary bg-opacity-10 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Partnership</h3>
                <p class="text-gray-600">We build long-term relationships based on trust, transparency, and mutual success.</p>
            </div>
        </div>
    </div>
</section>

<!-- Services Overview -->
<section class="py-20">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">What We Do</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Comprehensive digital solutions tailored to your needs</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="text-center" data-aos="fade-up" data-aos-delay="0">
                <div class="bg-primary bg-opacity-10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                    </svg>
                </div>
                <h3 class="font-semibold mb-2">Web Development</h3>
                <p class="text-gray-600 text-sm">Custom websites and web applications</p>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                <div class="bg-accent bg-opacity-10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold mb-2">Mobile Apps</h3>
                <p class="text-gray-600 text-sm">Native and cross-platform solutions</p>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                <div class="bg-primary bg-opacity-10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold mb-2">Digital Marketing</h3>
                <p class="text-gray-600 text-sm">SEO, social media, and campaigns</p>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                <div class="bg-accent bg-opacity-10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="font-semibold mb-2">Training</h3>
                <p class="text-gray-600 text-sm">Skills development programs</p>
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

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-primary to-blue-600 text-white">
    <div class="container mx-auto px-4 text-center" data-aos="fade-up">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Let's Work Together</h2>
        <p class="text-xl text-blue-100 mb-8">Ready to start your digital transformation journey?</p>
        <a href="/contact" class="bg-white text-primary px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition inline-block">Get In Touch</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

