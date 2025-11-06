<?php
$page_title = 'Blog Categories';
include 'includes/header.php';

$db = new Database();
$conn = $db->getConnection();

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'create' || $action === 'edit') {
        $name = sanitize($_POST['name'] ?? '');
        $slug = generateSlug($name);
        $description = sanitize($_POST['description'] ?? '');
        $status = isset($_POST['status']) ? 1 : 0;
        
        if ($action === 'create') {
            $stmt = $conn->prepare("INSERT INTO blog_categories (name, slug, description, status) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $slug, $description, $status]);
            header('Location: /admin/blog-categories.php?success=created');
        } else {
            $stmt = $conn->prepare("UPDATE blog_categories SET name = ?, slug = ?, description = ?, status = ? WHERE id = ?");
            $stmt->execute([$name, $slug, $description, $status, $id]);
            header('Location: /admin/blog-categories.php?success=updated');
        }
        exit;
    } elseif ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM blog_categories WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: /admin/blog-categories.php?success=deleted');
        exit;
    }
}

// Get category for edit
$category = null;
if ($action === 'edit' && $id) {
    $stmt = $conn->prepare("SELECT * FROM blog_categories WHERE id = ?");
    $stmt->execute([$id]);
    $category = $stmt->fetch();
}

// Get all categories
$categories = $conn->query("SELECT * FROM blog_categories ORDER BY name ASC")->fetchAll();
?>

<?php if (isset($_GET['success'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        Category <?php echo $_GET['success']; ?> successfully!
    </div>
<?php endif; ?>

<?php if ($action === 'list'): ?>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Blog Categories</h2>
        <div class="space-x-2">
            <a href="/admin/blog.php" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300">Back to Blog</a>
            <a href="/admin/blog-categories.php?action=create" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-700">Add New Category</a>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($categories as $cat): ?>
                <tr>
                    <td class="px-6 py-4"><?php echo htmlspecialchars($cat['name']); ?></td>
                    <td class="px-6 py-4"><?php echo htmlspecialchars($cat['slug']); ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full <?php echo $cat['status'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                            <?php echo $cat['status'] ? 'Active' : 'Inactive'; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="/admin/blog-categories.php?action=edit&id=<?php echo $cat['id']; ?>" class="text-primary hover:underline mr-3">Edit</a>
                        <a href="/admin/blog-categories.php?action=delete&id=<?php echo $cat['id']; ?>" class="text-red-600 hover:underline" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-semibold mb-6"><?php echo $action === 'create' ? 'Add New Category' : 'Edit Category'; ?></h2>
        
        <form method="POST" class="space-y-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Name *</label>
                <input type="text" name="name" required class="w-full px-4 py-2 border rounded-lg" value="<?php echo $category ? htmlspecialchars($category['name']) : ''; ?>">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2 border rounded-lg"><?php echo $category ? htmlspecialchars($category['description']) : ''; ?></textarea>
            </div>
            
            <div>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="status" <?php echo ($category && $category['status']) || !$category ? 'checked' : ''; ?>>
                    <span>Active</span>
                </label>
            </div>
            
            <div class="flex space-x-4">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-blue-700">Save</button>
                <a href="/admin/blog-categories.php" class="bg-gray-200 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-300">Cancel</a>
            </div>
        </form>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

