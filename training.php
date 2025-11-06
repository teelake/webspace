<?php
require_once __DIR__ . '/config/config.php';

$page_title = 'Training Programs - ' . SITE_NAME;
$meta_description = 'Comprehensive digital skills training programs for remote and physical learning. Enhance your career with our expert-led courses.';

$db = new Database();
$conn = $db->getConnection();

// Get training programs
$stmt = $conn->query("SELECT * FROM training_programs WHERE status = 1 ORDER BY `order` ASC");
$programs = $stmt->fetchAll();

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-primary to-blue-600 text-white py-16">
    <div class="container mx-auto px-4" data-aos="fade-up">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Training Programs</h1>
        <p class="text-xl text-blue-100">Empower yourself with digital skills</p>
    </div>
</section>

<!-- Training Programs -->
<section class="py-20">
    <div class="container mx-auto px-4">
        <?php if (empty($programs)): ?>
            <div class="text-center py-12" data-aos="fade-up">
                <p class="text-gray-600 text-lg">Training programs coming soon. Please check back later.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($programs as $index => $program): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                    <?php if ($program['image']): ?>
                        <img src="<?php echo UPLOAD_URL . $program['image']; ?>" alt="<?php echo htmlspecialchars($program['title']); ?>" class="w-full h-48 object-cover">
                    <?php else: ?>
                        <div class="w-full h-48 bg-gradient-to-br from-primary to-accent"></div>
                    <?php endif; ?>
                    <div class="p-6">
                        <h3 class="text-2xl font-semibold mb-3"><?php echo htmlspecialchars($program['title']); ?></h3>
                        <p class="text-gray-600 mb-4"><?php echo nl2br(htmlspecialchars($program['description'])); ?></p>
                        
                        <div class="flex items-center justify-between mb-4 text-sm text-gray-600">
                            <?php if ($program['duration']): ?>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <?php echo htmlspecialchars($program['duration']); ?>
                                </span>
                            <?php endif; ?>
                            
                            <?php if ($program['price']): ?>
                                <span class="font-semibold text-primary">₦<?php echo number_format($program['price'], 2); ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($program['instructor']): ?>
                            <p class="text-sm text-gray-600 mb-4">
                                <strong>Instructor:</strong> <?php echo htmlspecialchars($program['instructor']); ?>
                            </p>
                        <?php endif; ?>
                        
                        <?php if ($program['features']): ?>
                            <div class="mb-4">
                                <h4 class="font-semibold mb-2 text-sm">What You'll Learn:</h4>
                                <ul class="list-disc list-inside text-gray-600 space-y-1 text-sm">
                                    <?php 
                                    $features = explode("\n", $program['features']);
                                    foreach (array_slice($features, 0, 3) as $feature):
                                        if (trim($feature)):
                                    ?>
                                        <li><?php echo htmlspecialchars(trim($feature)); ?></li>
                                    <?php 
                                        endif;
                                    endforeach; 
                                    ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <a href="/contact?subject=Training: <?php echo urlencode($program['title']); ?>" class="block w-full bg-primary text-white text-center px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">Enroll Now</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Why Choose Our Training -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Why Choose Our Training?</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="0">
                <div class="w-16 h-16 bg-primary bg-opacity-10 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Expert Instructors</h3>
                <p class="text-gray-600">Learn from industry professionals with years of real-world experience.</p>
            </div>
            
            <div class="bg-white p-8 rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="100">
                <div class="w-16 h-16 bg-accent bg-opacity-10 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Flexible Learning</h3>
                <p class="text-gray-600">Choose between remote and physical training options to fit your schedule.</p>
            </div>
            
            <div class="bg-white p-8 rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="200">
                <div class="w-16 h-16 bg-primary bg-opacity-10 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Certification</h3>
                <p class="text-gray-600">Receive recognized certificates upon completion of our programs.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-primary to-blue-600 text-white">
    <div class="container mx-auto px-4 text-center" data-aos="fade-up">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to Start Learning?</h2>
        <p class="text-xl text-blue-100 mb-8">Contact us to learn more about our training programs</p>
        <a href="/contact" class="bg-white text-primary px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition inline-block">Get In Touch</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

