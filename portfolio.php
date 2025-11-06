<?php
require_once __DIR__ . '/config/config.php';

$page_title = 'Portfolio - ' . SITE_NAME;
$meta_description = 'View our portfolio of successful web development, mobile app, and digital marketing projects.';

$db = new Database();
$conn = $db->getConnection();

// Get portfolio items
$stmt = $conn->query("SELECT * FROM portfolio WHERE status = 1 ORDER BY `order` ASC, created_at DESC");
$portfolio_items = $stmt->fetchAll();

// Get unique categories
$stmt = $conn->query("SELECT DISTINCT category FROM portfolio WHERE status = 1 AND category IS NOT NULL AND category != ''");
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-primary to-blue-600 text-white py-16">
    <div class="container mx-auto px-4" data-aos="fade-up">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Our Portfolio</h1>
        <p class="text-xl text-blue-100">Showcasing our successful projects</p>
    </div>
</section>

<!-- Portfolio Filter -->
<?php if (!empty($categories)): ?>
<section class="py-8 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap justify-center gap-4" data-aos="fade-up">
            <button onclick="filterPortfolio('all')" class="filter-btn active bg-primary text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">All</button>
            <?php foreach ($categories as $category): ?>
                <button onclick="filterPortfolio('<?php echo htmlspecialchars($category); ?>')" class="filter-btn bg-white text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-100 transition" data-category="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></button>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Portfolio Grid -->
<section class="py-20">
    <div class="container mx-auto px-4">
        <?php if (empty($portfolio_items)): ?>
            <div class="text-center py-12" data-aos="fade-up">
                <p class="text-gray-600 text-lg">Portfolio items coming soon. Please check back later.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="portfolioGrid">
                <?php foreach ($portfolio_items as $index => $item): ?>
                <div class="portfolio-item bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>" data-category="<?php echo htmlspecialchars($item['category'] ?? ''); ?>">
                    <?php if ($item['image']): ?>
                        <img src="<?php echo UPLOAD_URL . $item['image']; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="w-full h-64 object-cover">
                    <?php else: ?>
                        <div class="w-full h-64 bg-gradient-to-br from-primary to-accent"></div>
                    <?php endif; ?>
                    <div class="p-6">
                        <?php if ($item['category']): ?>
                            <span class="inline-block bg-primary bg-opacity-10 text-primary text-xs font-semibold px-3 py-1 rounded-full mb-3"><?php echo htmlspecialchars($item['category']); ?></span>
                        <?php endif; ?>
                        <h3 class="text-2xl font-semibold mb-2"><?php echo htmlspecialchars($item['title']); ?></h3>
                        <?php if ($item['client']): ?>
                            <p class="text-gray-600 text-sm mb-2"><strong>Client:</strong> <?php echo htmlspecialchars($item['client']); ?></p>
                        <?php endif; ?>
                        <p class="text-gray-600 mb-4"><?php echo nl2br(htmlspecialchars(truncate($item['description'], 120))); ?></p>
                        
                        <?php if ($item['technologies']): ?>
                            <div class="mb-4">
                                <p class="text-sm text-gray-600">
                                    <strong>Technologies:</strong> <?php echo htmlspecialchars($item['technologies']); ?>
                                </p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="flex gap-4">
                            <?php if ($item['project_url']): ?>
                                <a href="<?php echo htmlspecialchars($item['project_url']); ?>" target="_blank" rel="noopener" class="text-primary font-semibold hover:underline">View Project →</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-primary to-blue-600 text-white">
    <div class="container mx-auto px-4 text-center" data-aos="fade-up">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Have a Project in Mind?</h2>
        <p class="text-xl text-blue-100 mb-8">Let's discuss how we can bring your vision to life</p>
        <a href="/contact" class="bg-white text-primary px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition inline-block">Start Your Project</a>
    </div>
</section>

<script>
function filterPortfolio(category) {
    const items = document.querySelectorAll('.portfolio-item');
    const buttons = document.querySelectorAll('.filter-btn');
    
    // Update active button
    buttons.forEach(btn => {
        btn.classList.remove('active', 'bg-primary', 'text-white');
        btn.classList.add('bg-white', 'text-gray-700');
    });
    
    event.target.classList.add('active', 'bg-primary', 'text-white');
    event.target.classList.remove('bg-white', 'text-gray-700');
    
    // Filter items
    items.forEach(item => {
        if (category === 'all' || item.dataset.category === category) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}
</script>

<?php include 'includes/footer.php'; ?>

