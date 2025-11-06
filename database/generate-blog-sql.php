<?php
/**
 * Blog Content to SQL Generator
 * Converts blog content into SQL INSERT statements
 * 
 * Usage: Place your blog content in the $blog_posts array below
 * Then run: php generate-blog-sql.php > blog-import.sql
 */

require_once __DIR__ . '/../config/config.php';

// ============================================
// ADD YOUR BLOG POSTS HERE
// ============================================
$blog_posts = [
    // Example format - copy and modify for each post
    /*
    [
        'title' => 'Your Blog Post Title',
        'slug' => 'your-blog-post-slug', // Optional - will be generated from title if empty
        'category' => 'Category Name', // Must match existing category
        'author' => 'Author Name',
        'excerpt' => 'Short excerpt or description of the post',
        'content' => '<p>Full blog post content here. Can include HTML.</p><p>Multiple paragraphs supported.</p>',
        'meta_title' => 'SEO Title - Optional',
        'meta_description' => 'SEO description for search engines - Optional',
        'meta_keywords' => 'keyword1, keyword2, keyword3', // Optional
        'featured_image' => 'image-filename.jpg', // Optional - should be in uploads/blog/
        'published_date' => '2024-01-15 10:00:00', // Optional - current date if empty
        'status' => 1 // 1 = published, 0 = draft
    ],
    */
    
    // Add your posts here following the format above
];

// ============================================
// DO NOT MODIFY BELOW THIS LINE
// ============================================

function generateSlug($string) {
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9-]+/', '-', $string);
    $string = trim($string, '-');
    return $string;
}

function escapeSql($string) {
    return addslashes($string);
}

// Get database connection to check categories
$db = new Database();
$conn = $db->getConnection();

// Get existing categories
$stmt = $conn->query("SELECT id, name FROM blog_categories WHERE status = 1");
$categories = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $categories[strtolower($row['name'])] = $row['id'];
}

// Generate SQL
echo "-- Blog Posts Import SQL\n";
echo "-- Generated: " . date('Y-m-d H:i:s') . "\n";
echo "-- Total Posts: " . count($blog_posts) . "\n\n";

foreach ($blog_posts as $index => $post) {
    // Validate required fields
    if (empty($post['title'])) {
        echo "-- ERROR: Post " . ($index + 1) . " missing title - SKIPPED\n";
        continue;
    }
    
    // Generate slug if not provided
    $slug = !empty($post['slug']) ? $post['slug'] : generateSlug($post['title']);
    
    // Get category ID
    $category_id = 'NULL';
    if (!empty($post['category'])) {
        $category_key = strtolower($post['category']);
        if (isset($categories[$category_key])) {
            $category_id = $categories[$category_key];
        } else {
            echo "-- WARNING: Category '{$post['category']}' not found for post '{$post['title']}' - using NULL\n";
        }
    }
    
    // Set defaults
    $author = !empty($post['author']) ? escapeSql($post['author']) : 'Webspace Team';
    $excerpt = !empty($post['excerpt']) ? escapeSql($post['excerpt']) : '';
    $content = !empty($post['content']) ? escapeSql($post['content']) : '';
    $meta_title = !empty($post['meta_title']) ? escapeSql($post['meta_title']) : 'NULL';
    $meta_description = !empty($post['meta_description']) ? escapeSql($post['meta_description']) : 'NULL';
    $meta_keywords = !empty($post['meta_keywords']) ? escapeSql($post['meta_keywords']) : 'NULL';
    $canonical_url = 'NULL';
    $featured_image = !empty($post['featured_image']) ? escapeSql($post['featured_image']) : 'NULL';
    $views = 0;
    $likes_count = 0;
    $comments_count = 0;
    $reading_time = 'NULL';
    $status = isset($post['status']) ? (int)$post['status'] : 1;
    $published_at = !empty($post['published_date']) ? "'" . $post['published_date'] . "'" : 'NOW()';
    
    // Calculate reading time if content provided
    if (!empty($content)) {
        $words = str_word_count(strip_tags($content));
        $reading_time = max(1, ceil($words / 200));
    }
    
    // Generate SQL
    echo "-- Post " . ($index + 1) . ": " . $post['title'] . "\n";
    echo "INSERT INTO `blog_posts` (\n";
    echo "    `title`, `slug`, `excerpt`, `content`, `featured_image`,\n";
    echo "    `category_id`, `author`, `meta_title`, `meta_description`, `meta_keywords`,\n";
    echo "    `canonical_url`, `views`, `likes_count`, `comments_count`, `reading_time`,\n";
    echo "    `status`, `published_at`\n";
    echo ") VALUES (\n";
    echo "    '" . escapeSql($post['title']) . "',\n";
    echo "    '" . $slug . "',\n";
    echo "    '" . $excerpt . "',\n";
    echo "    '" . $content . "',\n";
    echo "    " . ($featured_image !== 'NULL' ? "'" . $featured_image . "'" : 'NULL') . ",\n";
    echo "    " . $category_id . ",\n";
    echo "    '" . $author . "',\n";
    echo "    " . ($meta_title !== 'NULL' ? "'" . $meta_title . "'" : 'NULL') . ",\n";
    echo "    " . ($meta_description !== 'NULL' ? "'" . $meta_description . "'" : 'NULL') . ",\n";
    echo "    " . ($meta_keywords !== 'NULL' ? "'" . $meta_keywords . "'" : 'NULL') . ",\n";
    echo "    " . $canonical_url . ",\n";
    echo "    " . $views . ",\n";
    echo "    " . $likes_count . ",\n";
    echo "    " . $comments_count . ",\n";
    echo "    " . $reading_time . ",\n";
    echo "    " . $status . ",\n";
    echo "    " . $published_at . "\n";
    echo ");\n\n";
}

echo "-- End of blog posts import\n";

