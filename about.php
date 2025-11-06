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
<section class="relative bg-gradient-to-r from-primary to-blue-600 text-white py-20 overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=1920&h=600&fit=crop" alt="About Us" class="w-full h-full object-cover">
    </div>
    <div class="container mx-auto px-4 relative z-10" data-aos="fade-up">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">About Us</h1>
        <p class="text-xl text-blue-100">Your Complete Digital Partner</p>
    </div>
</section>

<!-- About Content -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="prose prose-lg max-w-none" data-aos="fade-up">
                <h2 class="text-3xl font-bold mb-6">Who We Are</h2>
                <p class="text-gray-600 mb-6">
                    Webspace Innovation Hub Limited is a leading digital solutions provider committed to empowering businesses and individuals through innovative technology and comprehensive training programs. 
                </p>
                <div class="flex items-center space-x-2 text-gray-600 mb-6">
                    <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l1.09 3.27L16.27 6l-3.18 1.09L12 10.36l-1.09-3.27L7.73 6l3.18-1.09L12 2z" fill="currentColor"/>
                        <path d="M12 22l-1.09-3.27L7.73 18l3.18-1.09L12 13.64l1.09 3.27L16.27 18l-3.18 1.09L12 22z" fill="currentColor"/>
                        <path d="M22 12l-3.27-1.09L18 7.73l-1.09 3.18L13.64 12l3.27 1.09L18 16.27l1.09-3.18L22 12z" fill="currentColor"/>
                        <path d="M2 12l3.27 1.09L6 16.27l1.09-3.18L10.36 12l-3.27-1.09L6 7.73l-1.09 3.18L2 12z" fill="currentColor"/>
                        <path d="M19.07 4.93l-2.83 2.83-2.12-.71.71-2.12 2.83-2.83 2.12.71-.71 2.12z" fill="currentColor"/>
                        <path d="M4.93 19.07l2.83-2.83 2.12.71-.71 2.12-2.83 2.83-2.12-.71.71-2.12z" fill="currentColor"/>
                        <path d="M19.07 19.07l-2.83-2.83-.71 2.12 2.12.71 2.83 2.83.71-2.12-2.12-.71z" fill="currentColor"/>
                        <path d="M4.93 4.93l2.83 2.83.71-2.12-2.12-.71-2.83-2.83-.71 2.12 2.12.71z" fill="currentColor"/>
                    </svg>
                    <span>We operate with registration number <strong>RC: <?php echo REGISTRATION_NO; ?></strong> as a trusted partner in the digital transformation journey.</span>
                </div>
                <p class="text-gray-600 mb-6">
                    Our mission is to design, build, and manage digital experiences that grow brands and empower people. We combine technical expertise with creative solutions to deliver results that matter.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-16 bg-gray-50">
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
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">What We Do</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Comprehensive digital solutions tailored to your needs</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <div class="text-center" data-aos="fade-up" data-aos-delay="0">
                <div class="bg-primary bg-opacity-10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                    </svg>
                </div>
                <h3 class="font-semibold mb-2">Web Design & Development</h3>
                <p class="text-gray-600 text-sm">Custom websites and web applications</p>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="50">
                <div class="bg-accent bg-opacity-10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold mb-2">Mobile App Development</h3>
                <p class="text-gray-600 text-sm">Native and cross-platform solutions</p>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                <div class="bg-primary bg-opacity-10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold mb-2">Digital Marketing</h3>
                <p class="text-gray-600 text-sm">Comprehensive marketing strategies</p>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="150">
                <div class="bg-accent bg-opacity-10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold mb-2">SEO Services</h3>
                <p class="text-gray-600 text-sm">Search engine optimization</p>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                <div class="bg-primary bg-opacity-10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold mb-2">IT Consultancy & Support</h3>
                <p class="text-gray-600 text-sm">Expert IT consulting services</p>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="250">
                <div class="bg-accent bg-opacity-10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold mb-2">Cloud Computing</h3>
                <p class="text-gray-600 text-sm">DevSecOps & cloud infrastructure</p>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                <div class="bg-primary bg-opacity-10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                    </svg>
                </div>
                <h3 class="font-semibold mb-2">Graphics Design & Branding</h3>
                <p class="text-gray-600 text-sm">Creative design services</p>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="350">
                <div class="bg-accent bg-opacity-10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <h3 class="font-semibold mb-2">Software Testing</h3>
                <p class="text-gray-600 text-sm">Quality assurance services</p>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="400">
                <div class="bg-primary bg-opacity-10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold mb-2">Data Analysis</h3>
                <p class="text-gray-600 text-sm">Business intelligence & analytics</p>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="450">
                <div class="bg-accent bg-opacity-10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold mb-2">Survey & Data Management</h3>
                <p class="text-gray-600 text-sm">Data collection solutions</p>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="500">
                <div class="bg-primary bg-opacity-10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                    </svg>
                </div>
                <h3 class="font-semibold mb-2">Domain & Web Hosting</h3>
                <p class="text-gray-600 text-sm">Domain registration & hosting</p>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="550">
                <div class="bg-accent bg-opacity-10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="font-semibold mb-2">Digital Skills Training</h3>
                <p class="text-gray-600 text-sm">Comprehensive training programs</p>
            </div>
        </div>
    </div>
</section>

<!-- Partners Section -->
<?php if (!empty($partners)): ?>
<section class="py-16 bg-gray-50">
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

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-primary to-blue-600 text-white">
    <div class="container mx-auto px-4 text-center" data-aos="fade-up">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Let's Work Together</h2>
        <p class="text-xl text-blue-100 mb-8">Ready to start your digital transformation journey?</p>
        <a href="/contact" class="bg-brandYellow text-primary px-8 py-3 rounded-lg font-semibold hover:bg-yellow-300 transition inline-block shadow-lg">Get In Touch</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

