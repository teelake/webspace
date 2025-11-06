<?php
/**
 * Debug Script: Check Blog Post Status
 * Use this to diagnose why a blog post isn't showing
 */

require_once __DIR__ . '/config/config.php';

$db = new Database();
$conn = $db->getConnection();

// Get the slug from URL or set manually
$slug = isset($_GET['slug']) ? $_GET['slug'] : 'web-design-vs-web-development-whats-the-real-difference';

echo "<h1>Blog Post Debug Information</h1>";
echo "<p><strong>Checking slug:</strong> " . htmlspecialchars($slug) . "</p>";

// Check if post exists at all
$stmt = $conn->prepare("SELECT * FROM blog_posts WHERE slug = ?");
$stmt->execute([$slug]);
$post = $stmt->fetch();

if (!$post) {
    echo "<p style='color: red;'><strong>ERROR:</strong> Post not found with slug: " . htmlspecialchars($slug) . "</p>";
    echo "<p>Checking all posts in database:</p>";
    $all_posts = $conn->query("SELECT id, title, slug, status, published_at FROM blog_posts ORDER BY id DESC LIMIT 10")->fetchAll();
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Title</th><th>Slug</th><th>Status</th><th>Published At</th></tr>";
    foreach ($all_posts as $p) {
        echo "<tr>";
        echo "<td>" . $p['id'] . "</td>";
        echo "<td>" . htmlspecialchars($p['title']) . "</td>";
        echo "<td>" . htmlspecialchars($p['slug']) . "</td>";
        echo "<td>" . $p['status'] . "</td>";
        echo "<td>" . ($p['published_at'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    exit;
}

echo "<h2>Post Found!</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Field</th><th>Value</th></tr>";
echo "<tr><td>ID</td><td>" . $post['id'] . "</td></tr>";
echo "<tr><td>Title</td><td>" . htmlspecialchars($post['title']) . "</td></tr>";
echo "<tr><td>Slug</td><td>" . htmlspecialchars($post['slug']) . "</td></tr>";
echo "<tr><td>Status</td><td>" . $post['status'] . " (" . ($post['status'] == 1 ? 'Published' : 'Draft') . ")</td></tr>";
echo "<tr><td>Published At</td><td>" . ($post['published_at'] ?? 'NULL') . "</td></tr>";
echo "<tr><td>Current Time</td><td>" . date('Y-m-d H:i:s') . "</td></tr>";

// Check if it meets all conditions
$issues = [];
if ($post['status'] != 1) {
    $issues[] = "Status is " . $post['status'] . " (should be 1)";
}
if (empty($post['published_at'])) {
    $issues[] = "published_at is NULL (should have a date)";
} elseif (strtotime($post['published_at']) > time()) {
    $issues[] = "published_at is in the future: " . $post['published_at'];
}

if (empty($issues)) {
    echo "<tr><td colspan='2' style='color: green;'><strong>✓ All conditions met - post should be visible!</strong></td></tr>";
} else {
    echo "<tr><td colspan='2' style='color: red;'><strong>Issues found:</strong><ul>";
    foreach ($issues as $issue) {
        echo "<li>" . $issue . "</li>";
    }
    echo "</ul></td></tr>";
}

echo "</table>";

// Test the actual query used in blog.php
echo "<h2>Testing Blog.php Query</h2>";
$test_stmt = $conn->prepare("SELECT bp.*, bc.name as category_name, bc.slug as category_slug,
                            COALESCE(bp.likes_count, 0) as likes_count,
                            COALESCE(bp.comments_count, 0) as comments_count
                            FROM blog_posts bp 
                            LEFT JOIN blog_categories bc ON bp.category_id = bc.id 
                            WHERE bp.slug = ? AND bp.status = 1 AND bp.published_at IS NOT NULL AND bp.published_at <= NOW()");
$test_stmt->execute([$slug]);
$test_post = $test_stmt->fetch();

if ($test_post) {
    echo "<p style='color: green;'><strong>✓ Query returns result - post should work!</strong></p>";
} else {
    echo "<p style='color: red;'><strong>✗ Query returns no result - this is why you get 404</strong></p>";
    echo "<p>Fixing the issues above should resolve this.</p>";
}

echo "<hr>";
echo "<p><a href='/blog/" . htmlspecialchars($slug) . "'>Try viewing the post</a></p>";
echo "<p><a href='/blog'>Back to blog listing</a></p>";

