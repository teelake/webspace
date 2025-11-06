<?php
$page_title = 'Settings';
include 'includes/header.php';

$db = new Database();
$conn = $db->getConnection();

$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        if ($key !== 'submit') {
            setSetting($key, sanitize($value));
        }
    }
    $success = true;
}

// Get all settings
$settings = [
    'site_title' => getSetting('site_title', SITE_NAME),
    'site_tagline' => getSetting('site_tagline', SITE_TAGLINE),
    'site_description' => getSetting('site_description', ''),
    'site_keywords' => getSetting('site_keywords', ''),
    'footer_text' => getSetting('footer_text', ''),
];
?>

<?php if ($success): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        Settings saved successfully!
    </div>
<?php endif; ?>

<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-semibold mb-6">Global Settings</h2>
    
    <form method="POST" class="space-y-6">
        <div>
            <label class="block text-gray-700 font-semibold mb-2">Site Title</label>
            <input type="text" name="site_title" class="w-full px-4 py-2 border rounded-lg" value="<?php echo htmlspecialchars($settings['site_title']); ?>">
        </div>
        
        <div>
            <label class="block text-gray-700 font-semibold mb-2">Site Tagline</label>
            <input type="text" name="site_tagline" class="w-full px-4 py-2 border rounded-lg" value="<?php echo htmlspecialchars($settings['site_tagline']); ?>">
        </div>
        
        <div>
            <label class="block text-gray-700 font-semibold mb-2">Site Description (Meta)</label>
            <textarea name="site_description" rows="3" class="w-full px-4 py-2 border rounded-lg"><?php echo htmlspecialchars($settings['site_description']); ?></textarea>
        </div>
        
        <div>
            <label class="block text-gray-700 font-semibold mb-2">Site Keywords (Meta)</label>
            <input type="text" name="site_keywords" class="w-full px-4 py-2 border rounded-lg" value="<?php echo htmlspecialchars($settings['site_keywords']); ?>" placeholder="keyword1, keyword2, keyword3">
        </div>
        
        <div>
            <label class="block text-gray-700 font-semibold mb-2">Footer Text</label>
            <textarea name="footer_text" rows="2" class="w-full px-4 py-2 border rounded-lg"><?php echo htmlspecialchars($settings['footer_text']); ?></textarea>
        </div>
        
        <div class="flex space-x-4">
            <button type="submit" name="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-blue-700">Save Settings</button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

