<?php
$page_title = 'Blog';
include 'includes/header.php';

$db = new Database();
$conn = $db->getConnection();

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'create' || $action === 'edit') {
        $title = sanitize($_POST['title'] ?? '');
        $slug = generateSlug($title);
        $excerpt = sanitize($_POST['excerpt'] ?? '');
        $content = $_POST['content'] ?? ''; // Don't sanitize HTML content
        $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $author = sanitize($_POST['author'] ?? '');
        $meta_title = sanitize($_POST['meta_title'] ?? '');
        $meta_description = sanitize($_POST['meta_description'] ?? '');
        $published_at = !empty($_POST['published_at']) ? $_POST['published_at'] : null;
        $status = isset($_POST['status']) ? 1 : 0;
        
        // Handle featured image upload
        $featured_image = null;
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === 0) {
            $upload = uploadImage($_FILES['featured_image'], 'blog');
            if ($upload['success']) {
                $featured_image = $upload['path'];
            }
        }
        
        if ($action === 'create') {
            $stmt = $conn->prepare("INSERT INTO blog_posts (title, slug, excerpt, content, featured_image, category_id, author, meta_title, meta_description, published_at, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $slug, $excerpt, $content, $featured_image, $category_id, $author, $meta_title, $meta_description, $published_at, $status]);
            header('Location: /admin/blog.php?success=created');
        } else {
            if ($featured_image) {
                $old = $conn->prepare("SELECT featured_image FROM blog_posts WHERE id = ?");
                $old->execute([$id]);
                $old_data = $old->fetch();
                if ($old_data && $old_data['featured_image']) {
                    deleteImage($old_data['featured_image']);
                }
                $stmt = $conn->prepare("UPDATE blog_posts SET title = ?, slug = ?, excerpt = ?, content = ?, featured_image = ?, category_id = ?, author = ?, meta_title = ?, meta_description = ?, published_at = ?, status = ? WHERE id = ?");
                $stmt->execute([$title, $slug, $excerpt, $content, $featured_image, $category_id, $author, $meta_title, $meta_description, $published_at, $status, $id]);
            } else {
                $stmt = $conn->prepare("UPDATE blog_posts SET title = ?, slug = ?, excerpt = ?, content = ?, category_id = ?, author = ?, meta_title = ?, meta_description = ?, published_at = ?, status = ? WHERE id = ?");
                $stmt->execute([$title, $slug, $excerpt, $content, $category_id, $author, $meta_title, $meta_description, $published_at, $status, $id]);
            }
            header('Location: /admin/blog.php?success=updated');
        }
        exit;
    } elseif ($action === 'delete') {
        $stmt = $conn->prepare("SELECT featured_image FROM blog_posts WHERE id = ?");
        $stmt->execute([$id]);
        $post = $stmt->fetch();
        if ($post && $post['featured_image']) {
            deleteImage($post['featured_image']);
        }
        $stmt = $conn->prepare("DELETE FROM blog_posts WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: /admin/blog.php?success=deleted');
        exit;
    }
}

// Get post for edit
$post = null;
if ($action === 'edit' && $id) {
    $stmt = $conn->prepare("SELECT * FROM blog_posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch();
}

// Get categories
$categories = $conn->query("SELECT * FROM blog_categories WHERE status = 1 ORDER BY name ASC")->fetchAll();

// Get all posts
$posts = $conn->query("SELECT bp.*, bc.name as category_name FROM blog_posts bp LEFT JOIN blog_categories bc ON bp.category_id = bc.id ORDER BY bp.created_at DESC")->fetchAll();
?>

<?php if (isset($_GET['success'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        Blog post <?php echo $_GET['success']; ?> successfully!
    </div>
<?php endif; ?>

<?php if ($action === 'list'): ?>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Blog Posts</h2>
        <div class="space-x-2">
            <a href="/admin/blog-categories.php" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300">Categories</a>
            <a href="/admin/blog.php?action=create" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-700">Add New Post</a>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($posts as $p): ?>
                <tr>
                    <td class="px-6 py-4"><?php echo htmlspecialchars($p['title']); ?></td>
                    <td class="px-6 py-4"><?php echo htmlspecialchars($p['category_name'] ?: '-'); ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full <?php echo $p['status'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                            <?php echo $p['status'] ? 'Published' : 'Draft'; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4"><?php echo $p['published_at'] ? formatDate($p['published_at']) : '-'; ?></td>
                    <td class="px-6 py-4">
                        <a href="/admin/blog.php?action=edit&id=<?php echo $p['id']; ?>" class="text-primary hover:underline mr-3">Edit</a>
                        <a href="/admin/blog.php?action=delete&id=<?php echo $p['id']; ?>" class="text-red-600 hover:underline" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-semibold mb-6"><?php echo $action === 'create' ? 'Add New Blog Post' : 'Edit Blog Post'; ?></h2>
        
        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Title *</label>
                <input type="text" name="title" required class="w-full px-4 py-2 border rounded-lg" value="<?php echo $post ? htmlspecialchars($post['title']) : ''; ?>">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Excerpt</label>
                <textarea name="excerpt" rows="3" class="w-full px-4 py-2 border rounded-lg"><?php echo $post ? htmlspecialchars($post['excerpt']) : ''; ?></textarea>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Content *</label>
                <textarea id="content" name="content" rows="15" required class="w-full px-4 py-2 border rounded-lg"><?php echo $post ? htmlspecialchars($post['content']) : ''; ?></textarea>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Category</label>
                    <select name="category_id" class="w-full px-4 py-2 border rounded-lg">
                        <option value="">None</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo ($post && $post['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Author</label>
                    <input type="text" name="author" class="w-full px-4 py-2 border rounded-lg" value="<?php echo $post ? htmlspecialchars($post['author']) : ''; ?>">
                </div>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Featured Image</label>
                <?php if ($post && $post['featured_image']): ?>
                    <img src="<?php echo UPLOAD_URL . $post['featured_image']; ?>" alt="" class="h-32 mb-2">
                <?php endif; ?>
                <input type="file" name="featured_image" accept="image/*" class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Meta Title</label>
                <input type="text" name="meta_title" class="w-full px-4 py-2 border rounded-lg" value="<?php echo $post ? htmlspecialchars($post['meta_title']) : ''; ?>">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Meta Description</label>
                <textarea name="meta_description" rows="2" class="w-full px-4 py-2 border rounded-lg"><?php echo $post ? htmlspecialchars($post['meta_description']) : ''; ?></textarea>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Published Date</label>
                    <input type="datetime-local" name="published_at" class="w-full px-4 py-2 border rounded-lg" value="<?php echo $post && $post['published_at'] ? date('Y-m-d\TH:i', strtotime($post['published_at'])) : ''; ?>">
                </div>
                <div>
                    <label class="flex items-center space-x-2 mt-8">
                        <input type="checkbox" name="status" <?php echo ($post && $post['status']) || !$post ? 'checked' : ''; ?>>
                        <span>Published</span>
                    </label>
                </div>
            </div>
            
            <div class="flex space-x-4">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-blue-700">Save</button>
                <a href="/admin/blog.php" class="bg-gray-200 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-300">Cancel</a>
            </div>
        </form>
    </div>
    
    <!-- TinyMCE Editor -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#content',
            height: 500,
            plugins: 'lists link image table code',
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
            content_style: 'body { font-family: Inter, sans-serif; font-size: 14px; }'
        });
    </script>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

