<?php
require_once __DIR__ . '/config/config.php';

$db = new Database();
$conn = $db->getConnection();

// Check if single post
$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';

if ($slug) {
    // Single post view
    $stmt = $conn->prepare("SELECT bp.*, bc.name as category_name, bc.slug as category_slug 
                            FROM blog_posts bp 
                            LEFT JOIN blog_categories bc ON bp.category_id = bc.id 
                            WHERE bp.slug = ? AND bp.status = 1 AND bp.published_at IS NOT NULL AND bp.published_at <= NOW()");
    $stmt->execute([$slug]);
    $post = $stmt->fetch();
    
    if (!$post) {
        header('HTTP/1.0 404 Not Found');
        include '404.php';
        exit;
    }
    
    // Update views
    $conn->prepare("UPDATE blog_posts SET views = views + 1 WHERE id = ?")->execute([$post['id']]);
    
    $page_title = $post['meta_title'] ?: $post['title'] . ' - ' . SITE_NAME;
    $meta_description = $post['meta_description'] ?: truncate(strip_tags($post['content']), 160);
    
    include 'includes/header.php';
    ?>
    
    <!-- Single Post -->
    <article class="py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <!-- Breadcrumb -->
                <nav class="mb-8 text-sm text-gray-600" data-aos="fade-up">
                    <a href="/" class="hover:text-primary">Home</a> / 
                    <a href="/blog" class="hover:text-primary">Blog</a> / 
                    <span class="text-gray-900"><?php echo htmlspecialchars($post['title']); ?></span>
                </nav>
                
                <!-- Post Header -->
                <header class="mb-8" data-aos="fade-up">
                    <?php if ($post['category_name']): ?>
                        <a href="/blog?category=<?php echo $post['category_slug']; ?>" class="inline-block bg-primary bg-opacity-10 text-primary text-sm font-semibold px-4 py-2 rounded-full mb-4 hover:bg-opacity-20 transition">
                            <?php echo htmlspecialchars($post['category_name']); ?>
                        </a>
                    <?php endif; ?>
                    
                    <h1 class="text-4xl md:text-5xl font-bold mb-4"><?php echo htmlspecialchars($post['title']); ?></h1>
                    
                    <div class="flex items-center space-x-4 text-gray-600">
                        <?php if ($post['author']): ?>
                            <span><?php echo htmlspecialchars($post['author']); ?></span>
                        <?php endif; ?>
                        <span>•</span>
                        <time datetime="<?php echo $post['published_at']; ?>"><?php echo formatDate($post['published_at']); ?></time>
                        <span>•</span>
                        <span><?php echo $post['views']; ?> views</span>
                    </div>
                </header>
                
                <!-- Featured Image -->
                <?php if ($post['featured_image']): ?>
                    <div class="mb-8" data-aos="fade-up">
                        <img src="<?php echo UPLOAD_URL . $post['featured_image']; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="w-full h-auto rounded-lg">
                    </div>
                <?php endif; ?>
                
                <!-- Post Content -->
                <div class="prose prose-lg max-w-none" data-aos="fade-up">
                    <?php echo $post['content']; ?>
                </div>
                
                <!-- Share Buttons -->
                <div class="mt-12 pt-8 border-t" data-aos="fade-up">
                    <h3 class="font-semibold mb-4">Share this post:</h3>
                    <div class="flex space-x-4">
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(SITE_URL . '/blog/' . $post['slug']); ?>&text=<?php echo urlencode($post['title']); ?>" target="_blank" rel="noopener" class="bg-blue-400 text-white px-4 py-2 rounded-lg hover:bg-blue-500 transition">Twitter</a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(SITE_URL . '/blog/' . $post['slug']); ?>" target="_blank" rel="noopener" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Facebook</a>
                        <a href="https://www.linkedin.com/shareArticle?url=<?php echo urlencode(SITE_URL . '/blog/' . $post['slug']); ?>" target="_blank" rel="noopener" class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition">LinkedIn</a>
                    </div>
                </div>
            </div>
        </div>
    </article>
    
    <?php
} else {
    // Blog listing
    $page_title = 'Blog - ' . SITE_NAME;
    $meta_description = 'Read our latest blog posts about web development, digital marketing, technology trends, and more.';
    
    // Pagination
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $per_page = 9;
    $offset = ($page - 1) * $per_page;
    
    // Category filter
    $category_slug = isset($_GET['category']) ? sanitize($_GET['category']) : '';
    $category_filter = '';
    $params = [];
    
    if ($category_slug) {
        $cat_stmt = $conn->prepare("SELECT id FROM blog_categories WHERE slug = ?");
        $cat_stmt->execute([$category_slug]);
        $category = $cat_stmt->fetch();
        if ($category) {
            $category_filter = " AND bp.category_id = ?";
            $params[] = $category['id'];
        }
    }
    
    // Get total count
    $count_sql = "SELECT COUNT(*) FROM blog_posts bp WHERE bp.status = 1 AND bp.published_at IS NOT NULL AND bp.published_at <= NOW()" . $category_filter;
    $count_stmt = $conn->prepare($count_sql);
    $count_stmt->execute($params);
    $total = $count_stmt->fetchColumn();
    $total_pages = ceil($total / $per_page);
    
    // Get posts
    $sql = "SELECT bp.*, bc.name as category_name, bc.slug as category_slug 
            FROM blog_posts bp 
            LEFT JOIN blog_categories bc ON bp.category_id = bc.id 
            WHERE bp.status = 1 AND bp.published_at IS NOT NULL AND bp.published_at <= NOW()" . $category_filter . "
            ORDER BY bp.published_at DESC 
            LIMIT ? OFFSET ?";
    $params[] = $per_page;
    $params[] = $offset;
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $posts = $stmt->fetchAll();
    
    // Get categories
    $stmt = $conn->query("SELECT * FROM blog_categories WHERE status = 1 ORDER BY name ASC");
    $categories = $stmt->fetchAll();
    
    include 'includes/header.php';
    ?>
    
    <!-- Page Header -->
    <section class="bg-gradient-to-r from-primary to-blue-600 text-white py-16">
        <div class="container mx-auto px-4" data-aos="fade-up">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Our Blog</h1>
            <p class="text-xl text-blue-100">Insights, tips, and updates from our team</p>
        </div>
    </section>
    
    <!-- Blog Content -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar -->
                <aside class="lg:col-span-1" data-aos="fade-up">
                    <div class="bg-gray-50 p-6 rounded-lg sticky top-24">
                        <h3 class="font-semibold mb-4">Categories</h3>
                        <ul class="space-y-2">
                            <li><a href="/blog" class="text-gray-600 hover:text-primary transition <?php echo !$category_slug ? 'text-primary font-semibold' : ''; ?>">All Posts</a></li>
                            <?php foreach ($categories as $cat): ?>
                                <li><a href="/blog?category=<?php echo $cat['slug']; ?>" class="text-gray-600 hover:text-primary transition <?php echo $category_slug == $cat['slug'] ? 'text-primary font-semibold' : ''; ?>"><?php echo htmlspecialchars($cat['name']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </aside>
                
                <!-- Posts Grid -->
                <div class="lg:col-span-3">
                    <?php if (empty($posts)): ?>
                        <div class="text-center py-12" data-aos="fade-up">
                            <p class="text-gray-600 text-lg">No blog posts found. Please check back later.</p>
                        </div>
                    <?php else: ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <?php foreach ($posts as $index => $post): ?>
                            <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                                <?php if ($post['featured_image']): ?>
                                    <a href="/blog/<?php echo $post['slug']; ?>">
                                        <img src="<?php echo UPLOAD_URL . $post['featured_image']; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="w-full h-48 object-cover">
                                    </a>
                                <?php else: ?>
                                    <a href="/blog/<?php echo $post['slug']; ?>">
                                        <div class="w-full h-48 bg-gradient-to-br from-primary to-accent"></div>
                                    </a>
                                <?php endif; ?>
                                <div class="p-6">
                                    <?php if ($post['category_name']): ?>
                                        <a href="/blog?category=<?php echo $post['category_slug']; ?>" class="inline-block bg-primary bg-opacity-10 text-primary text-xs font-semibold px-3 py-1 rounded-full mb-3 hover:bg-opacity-20 transition">
                                            <?php echo htmlspecialchars($post['category_name']); ?>
                                        </a>
                                    <?php endif; ?>
                                    <h2 class="text-2xl font-semibold mb-2">
                                        <a href="/blog/<?php echo $post['slug']; ?>" class="hover:text-primary transition"><?php echo htmlspecialchars($post['title']); ?></a>
                                    </h2>
                                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars(truncate($post['excerpt'] ?: strip_tags($post['content']), 120)); ?></p>
                                    <div class="flex items-center justify-between text-sm text-gray-500">
                                        <time datetime="<?php echo $post['published_at']; ?>"><?php echo formatDate($post['published_at']); ?></time>
                                        <a href="/blog/<?php echo $post['slug']; ?>" class="text-primary font-semibold hover:underline">Read More →</a>
                                    </div>
                                </div>
                            </article>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Pagination -->
                        <?php if ($total_pages > 1): ?>
                            <div class="mt-12 flex justify-center space-x-2" data-aos="fade-up">
                                <?php if ($page > 1): ?>
                                    <a href="/blog?page=<?php echo $page - 1; ?><?php echo $category_slug ? '&category=' . $category_slug : ''; ?>" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition">Previous</a>
                                <?php endif; ?>
                                
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <?php if ($i == $page): ?>
                                        <span class="bg-primary text-white px-4 py-2 rounded-lg"><?php echo $i; ?></span>
                                    <?php else: ?>
                                        <a href="/blog?page=<?php echo $i; ?><?php echo $category_slug ? '&category=' . $category_slug : ''; ?>" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition"><?php echo $i; ?></a>
                                    <?php endif; ?>
                                <?php endfor; ?>
                                
                                <?php if ($page < $total_pages): ?>
                                    <a href="/blog?page=<?php echo $page + 1; ?><?php echo $category_slug ? '&category=' . $category_slug : ''; ?>" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition">Next</a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    
    <?php
}

include 'includes/footer.php';
?>

