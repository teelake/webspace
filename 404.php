<?php
http_response_code(404);
require_once __DIR__ . '/config/config.php';

$page_title = '404 - Page Not Found - ' . SITE_NAME;
$meta_description = 'The page you are looking for could not be found.';

include 'includes/header.php';
?>

<section class="py-20 text-center">
    <div class="container mx-auto px-4">
        <div data-aos="fade-up">
            <h1 class="text-6xl font-bold text-primary mb-4">404</h1>
            <h2 class="text-3xl font-semibold mb-4">Page Not Found</h2>
            <p class="text-gray-600 mb-8">The page you are looking for could not be found.</p>
            <a href="/" class="bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition inline-block">Go Home</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

