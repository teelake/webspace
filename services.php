<?php
require_once __DIR__ . '/config/config.php';

$page_title = 'Our Services - ' . SITE_NAME;
$meta_description = 'Comprehensive digital services including web development, mobile apps, digital marketing, IT consultancy, training, and more.';

$db = new Database();
$conn = $db->getConnection();

// Get all services
$stmt = $conn->query("SELECT * FROM services WHERE status = 1 ORDER BY `order` ASC");
$services = $stmt->fetchAll();

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-primary to-blue-600 text-white py-16">
    <div class="container mx-auto px-4" data-aos="fade-up">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Our Services</h1>
        <p class="text-xl text-blue-100">Comprehensive digital solutions for your business</p>
    </div>
</section>

<!-- Services Grid -->
<section class="py-20">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($services as $index => $service): ?>
            <div id="<?php echo $service['slug']; ?>" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                <?php if ($service['image']): ?>
                    <img src="<?php echo UPLOAD_URL . $service['image']; ?>" alt="<?php echo htmlspecialchars($service['title']); ?>" class="w-full h-48 object-cover">
                <?php else: ?>
                    <div class="w-full h-48 bg-gradient-to-br from-primary to-accent"></div>
                <?php endif; ?>
                <div class="p-6">
                    <h3 class="text-2xl font-semibold mb-3"><?php echo htmlspecialchars($service['title']); ?></h3>
                    <p class="text-gray-600 mb-4"><?php echo nl2br(htmlspecialchars($service['description'])); ?></p>
                    
                    <?php if ($service['features']): ?>
                        <div class="mb-4">
                            <h4 class="font-semibold mb-2">Key Features:</h4>
                            <ul class="list-disc list-inside text-gray-600 space-y-1">
                                <?php 
                                $features = explode("\n", $service['features']);
                                foreach ($features as $feature):
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
                    
                    <a href="/contact" class="text-primary font-semibold hover:underline inline-block">Get Started →</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Service Process -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Our Process</h2>
            <p class="text-gray-600">How we deliver exceptional results</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center" data-aos="fade-up" data-aos-delay="0">
                <div class="bg-primary text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">1</div>
                <h3 class="font-semibold mb-2">Discovery</h3>
                <p class="text-gray-600 text-sm">We understand your needs and goals</p>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                <div class="bg-primary text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">2</div>
                <h3 class="font-semibold mb-2">Planning</h3>
                <p class="text-gray-600 text-sm">We create a tailored strategy</p>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                <div class="bg-primary text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">3</div>
                <h3 class="font-semibold mb-2">Execution</h3>
                <p class="text-gray-600 text-sm">We build and implement solutions</p>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                <div class="bg-primary text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">4</div>
                <h3 class="font-semibold mb-2">Support</h3>
                <p class="text-gray-600 text-sm">We provide ongoing maintenance</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-primary to-blue-600 text-white">
    <div class="container mx-auto px-4 text-center" data-aos="fade-up">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to Get Started?</h2>
        <p class="text-xl text-blue-100 mb-8">Let's discuss how we can help your business grow</p>
        <a href="/contact" class="bg-white text-primary px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition inline-block">Contact Us Today</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

