<?php
require_once __DIR__ . '/../config/config.php';

$current_page = basename($_SERVER['PHP_SELF'], '.php');
$page_title = isset($page_title) ? $page_title : SITE_NAME;
$meta_description = isset($meta_description) ? $meta_description : SITE_TAGLINE;
$meta_keywords = isset($meta_keywords) ? $meta_keywords : 'web design, digital marketing, IT consultancy, software development';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- SEO Meta Tags -->
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($meta_description); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($meta_keywords); ?>">
    <meta name="author" content="<?php echo SITE_NAME; ?>">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph Tags -->
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($meta_description); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo SITE_URL . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:image" content="<?php echo LOGO_URL; ?>webspace-logo-renewed-blue.jpg">
    <meta property="og:site_name" content="<?php echo SITE_NAME; ?>">
    
    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($meta_description); ?>">
    <meta name="twitter:image" content="<?php echo LOGO_URL; ?>webspace-logo-renewed-blue.jpg">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo LOGO_URL; ?>webspace-favicon.png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Custom Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1D4ED8',
                        accent: '#059669', // Enhanced green for better visibility
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        html {
            scroll-behavior: smooth;
        }
    </style>
    
    <!-- Analytics Placeholder -->
    <?php
    // Analytics file will be included here
    $analytics_file = ROOT_PATH . '/analytics.php';
    if (file_exists($analytics_file)) {
        include $analytics_file;
    }
    ?>
</head>
<body class="bg-white text-gray-900 antialiased">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 bg-white shadow-md z-50" data-aos="fade-down">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <a href="/" class="flex items-center space-x-2">
                    <img src="<?php echo LOGO_URL; ?>webspaceng-logo-renewed.png" alt="<?php echo SITE_NAME; ?>" class="h-16 md:h-20 w-auto">
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/" class="text-gray-700 hover:text-primary transition <?php echo $current_page == 'index' ? 'text-primary font-semibold' : ''; ?>">Home</a>
                    <a href="/about" class="text-gray-700 hover:text-primary transition <?php echo $current_page == 'about' ? 'text-primary font-semibold' : ''; ?>">About Us</a>
                    <a href="/services" class="text-gray-700 hover:text-primary transition <?php echo $current_page == 'services' ? 'text-primary font-semibold' : ''; ?>">Services</a>
                    <a href="/training" class="text-gray-700 hover:text-primary transition <?php echo $current_page == 'training' ? 'text-primary font-semibold' : ''; ?>">Training</a>
                    <a href="/portfolio" class="text-gray-700 hover:text-primary transition <?php echo $current_page == 'portfolio' ? 'text-primary font-semibold' : ''; ?>">Portfolio</a>
                    <a href="/blog" class="text-gray-700 hover:text-primary transition <?php echo $current_page == 'blog' ? 'text-primary font-semibold' : ''; ?>">Blog</a>
                    <a href="/contact" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">Contact</a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button id="mobileMenuBtn" class="md:hidden text-gray-700 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden bg-white border-t">
            <div class="container mx-auto px-4 py-4 space-y-4">
                <a href="/" class="block text-gray-700 hover:text-primary transition <?php echo $current_page == 'index' ? 'text-primary font-semibold' : ''; ?>">Home</a>
                <a href="/about" class="block text-gray-700 hover:text-primary transition <?php echo $current_page == 'about' ? 'text-primary font-semibold' : ''; ?>">About Us</a>
                <a href="/services" class="block text-gray-700 hover:text-primary transition <?php echo $current_page == 'services' ? 'text-primary font-semibold' : ''; ?>">Services</a>
                <a href="/training" class="block text-gray-700 hover:text-primary transition <?php echo $current_page == 'training' ? 'text-primary font-semibold' : ''; ?>">Training</a>
                <a href="/portfolio" class="block text-gray-700 hover:text-primary transition <?php echo $current_page == 'portfolio' ? 'text-primary font-semibold' : ''; ?>">Portfolio</a>
                <a href="/blog" class="block text-gray-700 hover:text-primary transition <?php echo $current_page == 'blog' ? 'text-primary font-semibold' : ''; ?>">Blog</a>
                <a href="/contact" class="block bg-primary text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition text-center">Contact</a>
            </div>
        </div>
    </nav>
    
    <!-- Main Content Wrapper -->
    <main class="pt-20">

