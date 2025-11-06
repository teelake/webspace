<?php
$page_title = 'Portfolio';
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
        $description = sanitize($_POST['description'] ?? '');
        $client = sanitize($_POST['client'] ?? '');
        $category = sanitize($_POST['category'] ?? '');
        $project_url = sanitize($_POST['project_url'] ?? '');
        $technologies = sanitize($_POST['technologies'] ?? '');
        $order = (int)($_POST['order'] ?? 0);
        $status = isset($_POST['status']) ? 1 : 0;
        
        // Handle image upload
        $image = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $upload = uploadImage($_FILES['image'], 'portfolio');
            if ($upload['success']) {
                $image = $upload['path'];
            }
        }
        
        if ($action === 'create') {
            $stmt = $conn->prepare("INSERT INTO portfolio (title, slug, description, client, category, project_url, technologies, image, `order`, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $slug, $description, $client, $category, $project_url, $technologies, $image, $order, $status]);
            header('Location: /admin/portfolio.php?success=created');
        } else {
            if ($image) {
                $old = $conn->prepare("SELECT image FROM portfolio WHERE id = ?");
                $old->execute([$id]);
                $old_data = $old->fetch();
                if ($old_data && $old_data['image']) {
                    deleteImage($old_data['image']);
                }
                $stmt = $conn->prepare("UPDATE portfolio SET title = ?, slug = ?, description = ?, client = ?, category = ?, project_url = ?, technologies = ?, image = ?, `order` = ?, status = ? WHERE id = ?");
                $stmt->execute([$title, $slug, $description, $client, $category, $project_url, $technologies, $image, $order, $status, $id]);
            } else {
                $stmt = $conn->prepare("UPDATE portfolio SET title = ?, slug = ?, description = ?, client = ?, category = ?, project_url = ?, technologies = ?, `order` = ?, status = ? WHERE id = ?");
                $stmt->execute([$title, $slug, $description, $client, $category, $project_url, $technologies, $order, $status, $id]);
            }
            header('Location: /admin/portfolio.php?success=updated');
        }
        exit;
    } elseif ($action === 'delete') {
        $stmt = $conn->prepare("SELECT image FROM portfolio WHERE id = ?");
        $stmt->execute([$id]);
        $item = $stmt->fetch();
        if ($item && $item['image']) {
            deleteImage($item['image']);
        }
        $stmt = $conn->prepare("DELETE FROM portfolio WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: /admin/portfolio.php?success=deleted');
        exit;
    }
}

// Get item for edit
$item = null;
if ($action === 'edit' && $id) {
    $stmt = $conn->prepare("SELECT * FROM portfolio WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
}

// Get all items
$items = $conn->query("SELECT * FROM portfolio ORDER BY `order` ASC, id DESC")->fetchAll();
?>

<?php if (isset($_GET['success'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        Portfolio item <?php echo $_GET['success']; ?> successfully!
    </div>
<?php endif; ?>

<?php if ($action === 'list'): ?>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Portfolio</h2>
        <a href="/admin/portfolio.php?action=create" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-700">Add New Item</a>
    </div>
    
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($items as $p): ?>
                <tr>
                    <td class="px-6 py-4"><?php echo htmlspecialchars($p['title']); ?></td>
                    <td class="px-6 py-4"><?php echo htmlspecialchars($p['category'] ?: '-'); ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full <?php echo $p['status'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                            <?php echo $p['status'] ? 'Active' : 'Inactive'; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="/admin/portfolio.php?action=edit&id=<?php echo $p['id']; ?>" class="text-primary hover:underline mr-3">Edit</a>
                        <a href="/admin/portfolio.php?action=delete&id=<?php echo $p['id']; ?>" class="text-red-600 hover:underline" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-semibold mb-6"><?php echo $action === 'create' ? 'Add New Portfolio Item' : 'Edit Portfolio Item'; ?></h2>
        
        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Title *</label>
                <input type="text" name="title" required class="w-full px-4 py-2 border rounded-lg" value="<?php echo $item ? htmlspecialchars($item['title']) : ''; ?>">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full px-4 py-2 border rounded-lg"><?php echo $item ? htmlspecialchars($item['description']) : ''; ?></textarea>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Client</label>
                    <input type="text" name="client" class="w-full px-4 py-2 border rounded-lg" value="<?php echo $item ? htmlspecialchars($item['client']) : ''; ?>">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Category</label>
                    <input type="text" name="category" class="w-full px-4 py-2 border rounded-lg" value="<?php echo $item ? htmlspecialchars($item['category']) : ''; ?>">
                </div>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Project URL</label>
                <input type="url" name="project_url" class="w-full px-4 py-2 border rounded-lg" value="<?php echo $item ? htmlspecialchars($item['project_url']) : ''; ?>">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Technologies</label>
                <input type="text" name="technologies" class="w-full px-4 py-2 border rounded-lg" value="<?php echo $item ? htmlspecialchars($item['technologies']) : ''; ?>" placeholder="e.g., PHP, MySQL, JavaScript">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Image</label>
                <?php if ($item && $item['image']): ?>
                    <img src="<?php echo UPLOAD_URL . $item['image']; ?>" alt="" class="h-32 mb-2">
                <?php endif; ?>
                <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Order</label>
                    <input type="number" name="order" class="w-full px-4 py-2 border rounded-lg" value="<?php echo $item ? $item['order'] : 0; ?>">
                </div>
                <div>
                    <label class="flex items-center space-x-2 mt-8">
                        <input type="checkbox" name="status" <?php echo ($item && $item['status']) || !$item ? 'checked' : ''; ?>>
                        <span>Active</span>
                    </label>
                </div>
            </div>
            
            <div class="flex space-x-4">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-blue-700">Save</button>
                <a href="/admin/portfolio.php" class="bg-gray-200 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-300">Cancel</a>
            </div>
        </form>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

