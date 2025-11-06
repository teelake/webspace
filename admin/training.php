<?php
$page_title = 'Training Programs';
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
        $content = $_POST['content'] ?? '';
        $duration = sanitize($_POST['duration'] ?? '');
        $price = !empty($_POST['price']) ? (float)$_POST['price'] : null;
        $instructor = sanitize($_POST['instructor'] ?? '');
        $features = sanitize($_POST['features'] ?? '');
        $order = (int)($_POST['order'] ?? 0);
        $status = isset($_POST['status']) ? 1 : 0;
        
        // Handle image upload
        $image = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $upload = uploadImage($_FILES['image'], 'training');
            if ($upload['success']) {
                $image = $upload['path'];
            }
        }
        
        if ($action === 'create') {
            $stmt = $conn->prepare("INSERT INTO training_programs (title, slug, description, content, duration, price, instructor, features, image, `order`, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $slug, $description, $content, $duration, $price, $instructor, $features, $image, $order, $status]);
            header('Location: /admin/training.php?success=created');
        } else {
            if ($image) {
                $old = $conn->prepare("SELECT image FROM training_programs WHERE id = ?");
                $old->execute([$id]);
                $old_data = $old->fetch();
                if ($old_data && $old_data['image']) {
                    deleteImage($old_data['image']);
                }
                $stmt = $conn->prepare("UPDATE training_programs SET title = ?, slug = ?, description = ?, content = ?, duration = ?, price = ?, instructor = ?, features = ?, image = ?, `order` = ?, status = ? WHERE id = ?");
                $stmt->execute([$title, $slug, $description, $content, $duration, $price, $instructor, $features, $image, $order, $status, $id]);
            } else {
                $stmt = $conn->prepare("UPDATE training_programs SET title = ?, slug = ?, description = ?, content = ?, duration = ?, price = ?, instructor = ?, features = ?, `order` = ?, status = ? WHERE id = ?");
                $stmt->execute([$title, $slug, $description, $content, $duration, $price, $instructor, $features, $order, $status, $id]);
            }
            header('Location: /admin/training.php?success=updated');
        }
        exit;
    } elseif ($action === 'delete') {
        $stmt = $conn->prepare("SELECT image FROM training_programs WHERE id = ?");
        $stmt->execute([$id]);
        $program = $stmt->fetch();
        if ($program && $program['image']) {
            deleteImage($program['image']);
        }
        $stmt = $conn->prepare("DELETE FROM training_programs WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: /admin/training.php?success=deleted');
        exit;
    }
}

// Get program for edit
$program = null;
if ($action === 'edit' && $id) {
    $stmt = $conn->prepare("SELECT * FROM training_programs WHERE id = ?");
    $stmt->execute([$id]);
    $program = $stmt->fetch();
}

// Get all programs
$programs = $conn->query("SELECT * FROM training_programs ORDER BY `order` ASC, id DESC")->fetchAll();
?>

<?php if (isset($_GET['success'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        Training program <?php echo $_GET['success']; ?> successfully!
    </div>
<?php endif; ?>

<?php if ($action === 'list'): ?>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Training Programs</h2>
        <a href="/admin/training.php?action=create" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-700">Add New Program</a>
    </div>
    
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($programs as $p): ?>
                <tr>
                    <td class="px-6 py-4"><?php echo htmlspecialchars($p['title']); ?></td>
                    <td class="px-6 py-4"><?php echo htmlspecialchars($p['duration'] ?: '-'); ?></td>
                    <td class="px-6 py-4"><?php echo $p['price'] ? '₦' . number_format($p['price'], 2) : '-'; ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full <?php echo $p['status'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                            <?php echo $p['status'] ? 'Active' : 'Inactive'; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="/admin/training.php?action=edit&id=<?php echo $p['id']; ?>" class="text-primary hover:underline mr-3">Edit</a>
                        <a href="/admin/training.php?action=delete&id=<?php echo $p['id']; ?>" class="text-red-600 hover:underline" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-semibold mb-6"><?php echo $action === 'create' ? 'Add New Training Program' : 'Edit Training Program'; ?></h2>
        
        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Title *</label>
                <input type="text" name="title" required class="w-full px-4 py-2 border rounded-lg" value="<?php echo $program ? htmlspecialchars($program['title']) : ''; ?>">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2 border rounded-lg"><?php echo $program ? htmlspecialchars($program['description']) : ''; ?></textarea>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Content</label>
                <textarea name="content" rows="10" class="w-full px-4 py-2 border rounded-lg"><?php echo $program ? htmlspecialchars($program['content']) : ''; ?></textarea>
            </div>
            
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Duration</label>
                    <input type="text" name="duration" class="w-full px-4 py-2 border rounded-lg" value="<?php echo $program ? htmlspecialchars($program['duration']) : ''; ?>" placeholder="e.g., 4 weeks">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Price</label>
                    <input type="number" step="0.01" name="price" class="w-full px-4 py-2 border rounded-lg" value="<?php echo $program ? $program['price'] : ''; ?>">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Instructor</label>
                    <input type="text" name="instructor" class="w-full px-4 py-2 border rounded-lg" value="<?php echo $program ? htmlspecialchars($program['instructor']) : ''; ?>">
                </div>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Features (one per line)</label>
                <textarea name="features" rows="6" class="w-full px-4 py-2 border rounded-lg"><?php echo $program ? htmlspecialchars($program['features']) : ''; ?></textarea>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Image</label>
                <?php if ($program && $program['image']): ?>
                    <img src="<?php echo UPLOAD_URL . $program['image']; ?>" alt="" class="h-32 mb-2">
                <?php endif; ?>
                <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Order</label>
                    <input type="number" name="order" class="w-full px-4 py-2 border rounded-lg" value="<?php echo $program ? $program['order'] : 0; ?>">
                </div>
                <div>
                    <label class="flex items-center space-x-2 mt-8">
                        <input type="checkbox" name="status" <?php echo ($program && $program['status']) || !$program ? 'checked' : ''; ?>>
                        <span>Active</span>
                    </label>
                </div>
            </div>
            
            <div class="flex space-x-4">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-blue-700">Save</button>
                <a href="/admin/training.php" class="bg-gray-200 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-300">Cancel</a>
            </div>
        </form>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

