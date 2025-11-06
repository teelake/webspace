<?php
$page_title = 'Contact Messages';
include 'includes/header.php';

$db = new Database();
$conn = $db->getConnection();

$id = $_GET['id'] ?? null;

// Mark as read
if ($id && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $status = sanitize($_POST['status']);
    $stmt = $conn->prepare("UPDATE contact_messages SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    header('Location: /admin/messages.php?id=' . $id);
    exit;
}

// Get single message
$message = null;
if ($id) {
    $stmt = $conn->prepare("SELECT * FROM contact_messages WHERE id = ?");
    $stmt->execute([$id]);
    $message = $stmt->fetch();
    
    // Mark as read if new
    if ($message && $message['status'] === 'new') {
        $conn->prepare("UPDATE contact_messages SET status = 'read' WHERE id = ?")->execute([$id]);
        $message['status'] = 'read';
    }
}

// Get all messages
$messages = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll();
?>

<?php if ($message): ?>
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-semibold">Message from <?php echo htmlspecialchars($message['name']); ?></h2>
                <p class="text-gray-600"><?php echo formatDate($message['created_at']); ?></p>
            </div>
            <a href="/admin/messages.php" class="text-gray-600 hover:text-gray-800">← Back to Messages</a>
        </div>
        
        <div class="space-y-4">
            <div>
                <label class="block text-gray-700 font-semibold mb-1">Name</label>
                <p class="text-gray-900"><?php echo htmlspecialchars($message['name']); ?></p>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-1">Email</label>
                <p class="text-gray-900"><a href="mailto:<?php echo htmlspecialchars($message['email']); ?>" class="text-primary hover:underline"><?php echo htmlspecialchars($message['email']); ?></a></p>
            </div>
            
            <?php if ($message['phone']): ?>
            <div>
                <label class="block text-gray-700 font-semibold mb-1">Phone</label>
                <p class="text-gray-900"><a href="tel:<?php echo htmlspecialchars($message['phone']); ?>" class="text-primary hover:underline"><?php echo htmlspecialchars($message['phone']); ?></a></p>
            </div>
            <?php endif; ?>
            
            <?php if ($message['subject']): ?>
            <div>
                <label class="block text-gray-700 font-semibold mb-1">Subject</label>
                <p class="text-gray-900"><?php echo htmlspecialchars($message['subject']); ?></p>
            </div>
            <?php endif; ?>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-1">Message</label>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-900 whitespace-pre-wrap"><?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                </div>
            </div>
            
            <div>
                <form method="POST" class="flex items-center space-x-4">
                    <label class="block text-gray-700 font-semibold">Status:</label>
                    <select name="status" class="px-4 py-2 border rounded-lg">
                        <option value="new" <?php echo $message['status'] === 'new' ? 'selected' : ''; ?>>New</option>
                        <option value="read" <?php echo $message['status'] === 'read' ? 'selected' : ''; ?>>Read</option>
                        <option value="replied" <?php echo $message['status'] === 'replied' ? 'selected' : ''; ?>>Replied</option>
                    </select>
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-700">Update Status</button>
                </form>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b">
            <h2 class="text-2xl font-semibold">Contact Messages</h2>
        </div>
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($messages as $m): ?>
                <tr class="<?php echo $m['status'] === 'new' ? 'bg-yellow-50' : ''; ?>">
                    <td class="px-6 py-4"><?php echo htmlspecialchars($m['name']); ?></td>
                    <td class="px-6 py-4"><?php echo htmlspecialchars($m['email']); ?></td>
                    <td class="px-6 py-4"><?php echo htmlspecialchars($m['subject'] ?: 'No subject'); ?></td>
                    <td class="px-6 py-4"><?php echo formatDate($m['created_at']); ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full <?php echo $m['status'] === 'new' ? 'bg-yellow-100 text-yellow-800' : ($m['status'] === 'read' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'); ?>">
                            <?php echo ucfirst($m['status']); ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="/admin/messages.php?id=<?php echo $m['id']; ?>" class="text-primary hover:underline">View</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

