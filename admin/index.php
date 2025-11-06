<?php
$page_title = 'Dashboard';
include 'includes/header.php';

$db = new Database();
$conn = $db->getConnection();

// Get statistics
$stats = [
    'services' => $conn->query("SELECT COUNT(*) FROM services")->fetchColumn(),
    'portfolio' => $conn->query("SELECT COUNT(*) FROM portfolio")->fetchColumn(),
    'blog_posts' => $conn->query("SELECT COUNT(*) FROM blog_posts")->fetchColumn(),
    'training' => $conn->query("SELECT COUNT(*) FROM training_programs")->fetchColumn(),
    'partners' => $conn->query("SELECT COUNT(*) FROM partners")->fetchColumn(),
    'messages' => $conn->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'new'")->fetchColumn(),
];

// Get recent messages
$recent_messages = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Services</p>
                <p class="text-3xl font-bold text-gray-800"><?php echo $stats['services']; ?></p>
            </div>
            <div class="bg-primary bg-opacity-10 p-3 rounded-lg">
                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Portfolio Items</p>
                <p class="text-3xl font-bold text-gray-800"><?php echo $stats['portfolio']; ?></p>
            </div>
            <div class="bg-accent bg-opacity-10 p-3 rounded-lg">
                <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Blog Posts</p>
                <p class="text-3xl font-bold text-gray-800"><?php echo $stats['blog_posts']; ?></p>
            </div>
            <div class="bg-primary bg-opacity-10 p-3 rounded-lg">
                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Training Programs</p>
                <p class="text-3xl font-bold text-gray-800"><?php echo $stats['training']; ?></p>
            </div>
            <div class="bg-accent bg-opacity-10 p-3 rounded-lg">
                <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Partners</p>
                <p class="text-3xl font-bold text-gray-800"><?php echo $stats['partners']; ?></p>
            </div>
            <div class="bg-primary bg-opacity-10 p-3 rounded-lg">
                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">New Messages</p>
                <p class="text-3xl font-bold text-gray-800"><?php echo $stats['messages']; ?></p>
            </div>
            <div class="bg-red-100 p-3 rounded-lg">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Recent Messages -->
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b">
        <h3 class="text-lg font-semibold">Recent Contact Messages</h3>
    </div>
    <div class="overflow-x-auto">
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
                <?php if (empty($recent_messages)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No messages yet</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recent_messages as $message): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($message['name']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($message['email']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($message['subject'] ?: 'No subject'); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo formatDate($message['created_at']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full <?php echo $message['status'] == 'new' ? 'bg-yellow-100 text-yellow-800' : ($message['status'] == 'read' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'); ?>">
                                <?php echo ucfirst($message['status']); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="/admin/messages.php?id=<?php echo $message['id']; ?>" class="text-primary hover:underline">View</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

