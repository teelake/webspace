<?php
$page_title = 'Partners';
include 'includes/header.php';

$db = new Database();
$conn = $db->getConnection();

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'create' || $action === 'edit') {
        $name = sanitize($_POST['name'] ?? '');
        $website = sanitize($_POST['website'] ?? '');
        $order = (int)($_POST['order'] ?? 0);
        $status = isset($_POST['status']) ? 1 : 0;
        
        // Handle logo upload
        $logo = null;
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
            $upload = uploadImage($_FILES['logo'], 'partners');
            if ($upload['success']) {
                $logo = $upload['path'];
            }
        }
        
        if ($action === 'create') {
            $stmt = $conn->prepare("INSERT INTO partners (name, logo, website, `order`, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $logo, $website, $order, $status]);
            header('Location: /admin/partners.php?success=created');
        } else {
            if ($logo) {
                $old = $conn->prepare("SELECT logo FROM partners WHERE id = ?");
                $old->execute([$id]);
                $old_data = $old->fetch();
                if ($old_data && $old_data['logo']) {
                    deleteImage($old_data['logo']);
                }
                $stmt = $conn->prepare("UPDATE partners SET name = ?, logo = ?, website = ?, `order` = ?, status = ? WHERE id = ?");
                $stmt->execute([$name, $logo, $website, $order, $status, $id]);
            } else {
                $stmt = $conn->prepare("UPDATE partners SET name = ?, website = ?, `order` = ?, status = ? WHERE id = ?");
                $stmt->execute([$name, $website, $order, $status, $id]);
            }
            header('Location: /admin/partners.php?success=updated');
        }
        exit;
    } elseif ($action === 'delete') {
        $stmt = $conn->prepare("SELECT logo FROM partners WHERE id = ?");
        $stmt->execute([$id]);
        $partner = $stmt->fetch();
        if ($partner && $partner['logo']) {
            deleteImage($partner['logo']);
        }
        $stmt = $conn->prepare("DELETE FROM partners WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: /admin/partners.php?success=deleted');
        exit;
    }
}

// Get partner for edit
$partner = null;
if ($action === 'edit' && $id) {
    $stmt = $conn->prepare("SELECT * FROM partners WHERE id = ?");
    $stmt->execute([$id]);
    $partner = $stmt->fetch();
}

// Get all partners
$partners = $conn->query("SELECT * FROM partners ORDER BY `order` ASC, id DESC")->fetchAll();
?>

<?php if (isset($_GET['success'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        Partner <?php echo $_GET['success']; ?> successfully!
    </div>
<?php endif; ?>

<?php if ($action === 'list'): ?>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Partners</h2>
        <a href="/admin/partners.php?action=create" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-700">Add New Partner</a>
    </div>
    
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Logo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Website</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($partners as $p): ?>
                <tr>
                    <td class="px-6 py-4">
                        <?php if ($p['logo']): ?>
                            <img src="<?php echo UPLOAD_URL . $p['logo']; ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" class="h-12 w-auto">
                        <?php else: ?>
                            <span class="text-gray-400">No logo</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4"><?php echo htmlspecialchars($p['name']); ?></td>
                    <td class="px-6 py-4">
                        <?php if ($p['website']): ?>
                            <a href="<?php echo htmlspecialchars($p['website']); ?>" target="_blank" class="text-primary hover:underline"><?php echo htmlspecialchars($p['website']); ?></a>
                        <?php else: ?>
                            <span class="text-gray-400">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full <?php echo $p['status'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                            <?php echo $p['status'] ? 'Active' : 'Inactive'; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="/admin/partners.php?action=edit&id=<?php echo $p['id']; ?>" class="text-primary hover:underline mr-3">Edit</a>
                        <a href="/admin/partners.php?action=delete&id=<?php echo $p['id']; ?>" class="text-red-600 hover:underline" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-semibold mb-6"><?php echo $action === 'create' ? 'Add New Partner' : 'Edit Partner'; ?></h2>
        
        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Name *</label>
                <input type="text" name="name" required class="w-full px-4 py-2 border rounded-lg" value="<?php echo $partner ? htmlspecialchars($partner['name']) : ''; ?>">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Website</label>
                <input type="url" name="website" class="w-full px-4 py-2 border rounded-lg" value="<?php echo $partner ? htmlspecialchars($partner['website']) : ''; ?>" placeholder="https://example.com">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Logo</label>
                <?php if ($partner && $partner['logo']): ?>
                    <img src="<?php echo UPLOAD_URL . $partner['logo']; ?>" alt="" class="h-24 mb-2">
                <?php endif; ?>
                <input type="file" name="logo" accept="image/*" class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Order</label>
                    <input type="number" name="order" class="w-full px-4 py-2 border rounded-lg" value="<?php echo $partner ? $partner['order'] : 0; ?>">
                </div>
                <div>
                    <label class="flex items-center space-x-2 mt-8">
                        <input type="checkbox" name="status" <?php echo ($partner && $partner['status']) || !$partner ? 'checked' : ''; ?>>
                        <span>Active</span>
                    </label>
                </div>
            </div>
            
            <div class="flex space-x-4">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-blue-700">Save</button>
                <a href="/admin/partners.php" class="bg-gray-200 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-300">Cancel</a>
            </div>
        </form>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

