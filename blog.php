<?php
require_once __DIR__ . '/config/config.php';

$db = new Database();
$conn = $db->getConnection();

// Check if single post
$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';

if ($slug) {
    // Single post view
    $stmt = $conn->prepare("SELECT bp.*, bc.name as category_name, bc.slug as category_slug,
                            COALESCE(bp.likes_count, 0) as likes_count,
                            COALESCE(bp.comments_count, 0) as comments_count
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
    $post['views'] = $post['views'] + 1;
    
    // Calculate reading time
    $reading_time = calculateReadingTime($post['content']);
    
    // Get likes count and check if current user has liked
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $likes_count = $post['likes_count'] ?? 0;
    $has_liked = hasLikedPost($post['id'], $ip_address);
    
    // Get comments count
    $comments_count = $post['comments_count'] ?? 0;
    $approved_comments = getBlogComments($post['id'], null, 'approved');
    
    // SEO Meta Tags
    $page_title = $post['meta_title'] ?: $post['title'] . ' - ' . SITE_NAME;
    $meta_description = $post['meta_description'] ?: truncate(strip_tags($post['content']), 160);
    $meta_keywords = $post['meta_keywords'] ?? '';
    $canonical_url = $post['canonical_url'] ?: SITE_URL . '/blog/' . $post['slug'];
    $post_url = SITE_URL . '/blog/' . $post['slug'];
    $featured_image_url = $post['featured_image'] ? SITE_URL . UPLOAD_URL . $post['featured_image'] : SITE_URL . LOGO_URL . 'webspace-logo-renewed-blue.jpg';
    
    // Set additional meta tags for blog post
    $og_type = 'article';
    $og_image = $featured_image_url;
    
    include 'includes/header.php';
    
    // Structured Data (JSON-LD) for SEO
    $structured_data = [
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => $post['title'],
        'description' => $meta_description,
        'image' => $featured_image_url,
        'datePublished' => $post['published_at'],
        'dateModified' => $post['updated_at'],
        'author' => [
            '@type' => 'Organization',
            'name' => SITE_NAME
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => SITE_NAME,
            'logo' => [
                '@type' => 'ImageObject',
                'url' => SITE_URL . LOGO_URL . 'webspace-logo-renewed-blue.jpg'
            ]
        ],
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => $post_url
        ]
    ];
    
    if ($post['category_name']) {
        $structured_data['articleSection'] = $post['category_name'];
    }
    ?>
    
    <!-- Structured Data for SEO -->
    <script type="application/ld+json">
    <?php echo json_encode($structured_data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
    </script>
    
    <!-- Single Post -->
    <article class="py-20" itemscope itemtype="https://schema.org/BlogPosting">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <!-- Breadcrumb -->
                <nav class="mb-8 text-sm text-gray-600" data-aos="fade-up" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2">
                        <li><a href="/" class="hover:text-primary">Home</a></li>
                        <li><span>/</span></li>
                        <li><a href="/blog" class="hover:text-primary">Blog</a></li>
                        <li><span>/</span></li>
                        <li class="text-gray-900" aria-current="page"><?php echo htmlspecialchars($post['title']); ?></li>
                    </ol>
                </nav>
                
                <!-- Post Header -->
                <header class="mb-8" data-aos="fade-up">
                    <?php if ($post['category_name']): ?>
                        <a href="/blog?category=<?php echo $post['category_slug']; ?>" class="inline-block bg-primary bg-opacity-10 text-primary text-sm font-semibold px-4 py-2 rounded-full mb-4 hover:bg-opacity-20 transition" itemprop="articleSection">
                            <?php echo htmlspecialchars($post['category_name']); ?>
                        </a>
                    <?php endif; ?>
                    
                    <h1 class="text-4xl md:text-5xl font-bold mb-4" itemprop="headline"><?php echo htmlspecialchars($post['title']); ?></h1>
                    
                    <div class="flex flex-wrap items-center gap-3 text-gray-600 text-sm">
                        <?php if ($post['author']): ?>
                            <span itemprop="author" itemscope itemtype="https://schema.org/Organization">
                                <span itemprop="name"><?php echo htmlspecialchars($post['author']); ?></span>
                            </span>
                            <span>•</span>
                        <?php endif; ?>
                        <time datetime="<?php echo $post['published_at']; ?>" itemprop="datePublished"><?php echo formatDate($post['published_at']); ?></time>
                        <span>•</span>
                        <span><?php echo $reading_time; ?> min read</span>
                        <span>•</span>
                        <span><?php echo number_format($post['views']); ?> views</span>
                        <span>•</span>
                        <span id="likesCountDisplay"><?php echo number_format($likes_count); ?> likes</span>
                        <span>•</span>
                        <span><?php echo number_format($comments_count); ?> comments</span>
                    </div>
                </header>
                
                <!-- Featured Image -->
                <?php if ($post['featured_image']): ?>
                    <div class="mb-8" data-aos="fade-up">
                        <img src="<?php echo UPLOAD_URL . $post['featured_image']; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="w-full h-auto rounded-lg shadow-xl" itemprop="image">
                    </div>
                <?php endif; ?>
                
                <!-- Post Content -->
                <div class="prose prose-lg max-w-none mb-8" data-aos="fade-up" itemprop="articleBody">
                    <?php echo $post['content']; ?>
                </div>
                
                <!-- Like and Share Section -->
                <div class="mt-12 pt-8 border-t border-gray-200" data-aos="fade-up">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <!-- Like Button -->
                        <button id="likeBtn" onclick="toggleLike(<?php echo $post['id']; ?>)" class="flex items-center space-x-2 px-6 py-3 rounded-lg font-semibold transition <?php echo $has_liked ? 'bg-red-100 text-red-600 hover:bg-red-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?>">
                            <svg class="w-5 h-5" fill="<?php echo $has_liked ? 'currentColor' : 'none'; ?>" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span id="likeText"><?php echo $has_liked ? 'Liked' : 'Like'; ?></span>
                            <span id="likesCount"><?php echo number_format($likes_count); ?></span>
                        </button>
                        
                        <!-- Social Share Buttons -->
                        <div class="flex items-center space-x-3">
                            <span class="text-gray-600 font-medium">Share:</span>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($post_url); ?>" target="_blank" rel="noopener" class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition" aria-label="Share on Facebook" title="Share on Facebook">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($post_url); ?>&text=<?php echo urlencode($post['title']); ?>" target="_blank" rel="noopener" class="flex items-center justify-center w-10 h-10 bg-blue-400 text-white rounded-full hover:bg-blue-500 transition" aria-label="Share on Twitter" title="Share on Twitter">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                            </a>
                            <a href="https://www.linkedin.com/shareArticle?url=<?php echo urlencode($post_url); ?>&title=<?php echo urlencode($post['title']); ?>" target="_blank" rel="noopener" class="flex items-center justify-center w-10 h-10 bg-blue-700 text-white rounded-full hover:bg-blue-800 transition" aria-label="Share on LinkedIn" title="Share on LinkedIn">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            </a>
                            <a href="https://wa.me/?text=<?php echo urlencode($post['title'] . ' ' . $post_url); ?>" target="_blank" rel="noopener" class="flex items-center justify-center w-10 h-10 bg-green-500 text-white rounded-full hover:bg-green-600 transition" aria-label="Share on WhatsApp" title="Share on WhatsApp">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Comments Section -->
                <div class="mt-12 pt-8 border-t border-gray-200" data-aos="fade-up">
                    <h2 class="text-2xl font-bold mb-6">Comments (<?php echo number_format($comments_count); ?>)</h2>
                    
                    <!-- Comment Form -->
                    <div class="bg-gray-50 p-6 rounded-lg mb-8">
                        <h3 class="text-lg font-semibold mb-4">Leave a Comment</h3>
                        <form id="commentForm" onsubmit="submitComment(event, <?php echo $post['id']; ?>)">
                            <!-- Honeypot field -->
                            <input type="text" name="website_hidden" style="position: absolute; left: -9999px; opacity: 0; pointer-events: none;" tabindex="-1" autocomplete="off" aria-hidden="true">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="commentName" class="block text-sm font-medium text-gray-700 mb-2">Name <span class="text-red-500">*</span></label>
                                    <input type="text" id="commentName" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                                <div>
                                    <label for="commentEmail" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                                    <input type="email" id="commentEmail" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="commentWebsite" class="block text-sm font-medium text-gray-700 mb-2">Website (optional)</label>
                                <input type="url" id="commentWebsite" name="website" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                            <div class="mb-4">
                                <label for="commentText" class="block text-sm font-medium text-gray-700 mb-2">Comment <span class="text-red-500">*</span></label>
                                <textarea id="commentText" name="comment" rows="5" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
                            </div>
                            <button type="submit" id="commentSubmitBtn" class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-brandBlue transition">Post Comment</button>
                            <div id="commentMessage" class="mt-4 text-sm"></div>
                        </form>
                    </div>
                    
                    <!-- Comments List -->
                    <div id="commentsList" class="space-y-6">
                        <?php if (empty($approved_comments)): ?>
                            <p class="text-gray-600">No comments yet. Be the first to comment!</p>
                        <?php else: ?>
                            <?php foreach ($approved_comments as $comment): ?>
                                <div class="border-l-4 border-primary pl-4 py-2">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <h4 class="font-semibold text-gray-900">
                                                <?php if ($comment['website']): ?>
                                                    <a href="<?php echo htmlspecialchars($comment['website']); ?>" target="_blank" rel="nofollow" class="hover:text-primary"><?php echo htmlspecialchars($comment['name']); ?></a>
                                                <?php else: ?>
                                                    <?php echo htmlspecialchars($comment['name']); ?>
                                                <?php endif; ?>
                                            </h4>
                                            <time class="text-sm text-gray-500" datetime="<?php echo $comment['created_at']; ?>"><?php echo formatDate($comment['created_at'], 'F j, Y \a\t g:i A'); ?></time>
                                        </div>
                                    </div>
                                    <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </article>
    
    <script>
    async function toggleLike(postId) {
        const btn = document.getElementById('likeBtn');
        const likeText = document.getElementById('likeText');
        const likesCount = document.getElementById('likesCount');
        const likesCountDisplay = document.getElementById('likesCountDisplay');
        const isLiked = btn.classList.contains('bg-red-100');
        
        try {
            const formData = new FormData();
            formData.append('post_id', postId);
            formData.append('action', isLiked ? 'unlike' : 'like');
            
            const response = await fetch('/api/blog-like.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                likesCount.textContent = data.likes_count.toLocaleString();
                likesCountDisplay.textContent = data.likes_count.toLocaleString() + ' likes';
                
                if (data.liked) {
                    btn.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
                    btn.classList.add('bg-red-100', 'text-red-600', 'hover:bg-red-200');
                    likeText.textContent = 'Liked';
                } else {
                    btn.classList.remove('bg-red-100', 'text-red-600', 'hover:bg-red-200');
                    btn.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
                    likeText.textContent = 'Like';
                }
            }
        } catch (error) {
            console.error('Error toggling like:', error);
        }
    }
    
    async function submitComment(event, postId) {
        event.preventDefault();
        const form = event.target;
        const btn = document.getElementById('commentSubmitBtn');
        const messageDiv = document.getElementById('commentMessage');
        const originalBtnText = btn.textContent;
        
        btn.disabled = true;
        btn.textContent = 'Posting...';
        messageDiv.textContent = '';
        messageDiv.className = 'mt-4 text-sm';
        
        try {
            const formData = new FormData(form);
            formData.append('post_id', postId);
            
            const response = await fetch('/api/blog-comment.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                messageDiv.textContent = data.message;
                messageDiv.className = 'mt-4 text-sm text-green-600 font-semibold';
                form.reset();
                // Reload page after 2 seconds to show new comment (if auto-approved)
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                messageDiv.textContent = data.message;
                messageDiv.className = 'mt-4 text-sm text-red-600 font-semibold';
            }
        } catch (error) {
            messageDiv.textContent = 'Sorry, there was an error. Please try again later.';
            messageDiv.className = 'mt-4 text-sm text-red-600 font-semibold';
        } finally {
            btn.disabled = false;
            btn.textContent = originalBtnText;
        }
    }
    </script>
    
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
    
    // Get posts with likes and comments count
    $sql = "SELECT bp.*, bc.name as category_name, bc.slug as category_slug,
            COALESCE(bp.likes_count, 0) as likes_count,
            COALESCE(bp.comments_count, 0) as comments_count
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
                                        <div class="flex items-center space-x-3">
                                            <time datetime="<?php echo $post['published_at']; ?>"><?php echo formatDate($post['published_at']); ?></time>
                                            <span>•</span>
                                            <span><?php echo number_format($post['views'] ?? 0); ?> views</span>
                                            <?php if (($post['likes_count'] ?? 0) > 0): ?>
                                                <span>•</span>
                                                <span><?php echo number_format($post['likes_count']); ?> likes</span>
                                            <?php endif; ?>
                                            <?php if (($post['comments_count'] ?? 0) > 0): ?>
                                                <span>•</span>
                                                <span><?php echo number_format($post['comments_count']); ?> comments</span>
                                            <?php endif; ?>
                                        </div>
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

