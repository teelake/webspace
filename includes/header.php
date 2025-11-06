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
    <?php if (isset($meta_keywords) && !empty($meta_keywords)): ?>
    <meta name="keywords" content="<?php echo htmlspecialchars($meta_keywords); ?>">
    <?php endif; ?>
    <meta name="author" content="<?php echo SITE_NAME; ?>">
    <meta name="robots" content="index, follow">
    <?php if (isset($canonical_url)): ?>
    <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url); ?>">
    <?php endif; ?>
    
    <!-- Open Graph Tags -->
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($meta_description); ?>">
    <meta property="og:type" content="<?php echo isset($og_type) ? $og_type : 'website'; ?>">
    <meta property="og:url" content="<?php echo isset($canonical_url) ? htmlspecialchars($canonical_url) : SITE_URL . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:image" content="<?php echo isset($og_image) ? htmlspecialchars($og_image) : SITE_URL . LOGO_URL . 'webspace-logo-renewed-blue.jpg'; ?>">
    <meta property="og:site_name" content="<?php echo SITE_NAME; ?>">
    <?php if (isset($og_image_width)): ?>
    <meta property="og:image:width" content="<?php echo $og_image_width; ?>">
    <meta property="og:image:height" content="<?php echo $og_image_height; ?>">
    <?php endif; ?>
    
    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($meta_description); ?>">
    <meta name="twitter:image" content="<?php echo isset($og_image) ? htmlspecialchars($og_image) : SITE_URL . LOGO_URL . 'webspace-logo-renewed-blue.jpg'; ?>">
    
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
                        primary: '#1D4ED8', // Dark Blue (brand - "Web" in logo)
                        brandYellow: '#FCD34D', // Bright Yellow (brand - "space" in logo)
                        brandBlue: '#1E40AF', // Darker blue variant
                        accent: '#059669', // Emerald green
                        secondary: '#7C3AED', // Purple (complementary)
                        tertiary: '#F59E0B', // Amber (warm accent)
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
            overflow-x: hidden;
        }
        body {
            overflow-x: hidden;
        }
        * {
            max-width: 100%;
        }
        .container {
            max-width: 1280px;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        @media (min-width: 640px) {
            .container {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
        }
        /* Ensure logo doesn't overflow on small screens */
        nav img {
            max-height: 100%;
        }
        /* Better touch targets for mobile */
        @media (max-width: 1023px) {
            nav a, nav button {
                min-height: 44px;
                display: inline-flex;
                align-items: center;
            }
        }
        img, svg, video {
            max-width: 100%;
            height: auto;
        }
        .nav-gradient {
            background-image: linear-gradient(90deg, rgba(252,211,77,0) 0%, rgba(252,211,77,1) 20%, rgba(29,78,216,1) 50%, rgba(252,211,77,1) 80%, rgba(252,211,77,0) 100%);
            background-size: 200% 100%;
            animation: gradient-shift 6s linear infinite;
            transition: opacity 300ms ease;
        }
        .show-gradient .nav-gradient { opacity: 1; }
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            100% { background-position: 200% 50%; }
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
            <div class="flex items-center justify-between h-16 sm:h-20 md:h-20 lg:h-24">
                <!-- Logo -->
                <a href="/" class="flex items-center space-x-2 sm:space-x-3 lg:space-x-4 flex-shrink-0 group min-w-0 max-w-[60%] sm:max-w-none">
                    <img src="<?php echo LOGO_URL; ?>webspaceng-logo-renewed.png" alt="<?php echo SITE_NAME; ?>" class="h-10 sm:h-12 md:h-14 lg:h-16 xl:h-20 w-auto transition-opacity duration-200 group-hover:opacity-90 object-contain">
                    <span class="hidden xl:block text-xs xl:text-sm text-gray-500/60 font-normal tracking-wide border-l border-gray-200 pl-3 xl:pl-4 whitespace-nowrap">Your Complete Digital Partner</span>
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center space-x-3 xl:space-x-4 2xl:space-x-6">
                    <a href="/" class="text-gray-700 hover:text-primary transition font-medium text-sm xl:text-base <?php echo $current_page == 'index' ? 'text-primary font-semibold' : ''; ?>">Home</a>
                    <a href="/about" class="text-gray-700 hover:text-primary transition font-medium text-sm xl:text-base <?php echo $current_page == 'about' ? 'text-primary font-semibold' : ''; ?>">About Us</a>
                    
                    <!-- Services Mega Menu -->
                    <div class="relative group">
                        <a href="/services" class="text-gray-700 hover:text-primary transition flex items-center font-medium text-sm xl:text-base <?php echo $current_page == 'services' ? 'text-primary font-semibold' : ''; ?>">
                            Services
                            <svg class="w-4 h-4 ml-1 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </a>
                        <!-- Mega Menu Dropdown -->
                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 w-[90vw] max-w-[800px] bg-white rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 border border-gray-200 p-4 md:p-6">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                                <!-- Category 1: Development & Design -->
                                <div>
                                    <h4 class="font-semibold text-primary mb-3 text-sm uppercase tracking-wide">Development & Design</h4>
                                    <ul class="space-y-2">
                                        <li><a href="/services#web-design-development" class="block text-gray-600 hover:text-primary transition text-sm py-1">Web Design & Development</a></li>
                                        <li><a href="/services#mobile-app-development" class="block text-gray-600 hover:text-primary transition text-sm py-1">Mobile App Development</a></li>
                                        <li><a href="/services#graphics-design-branding" class="block text-gray-600 hover:text-primary transition text-sm py-1">Graphics & Branding</a></li>
                                        <li><a href="/services#software-testing" class="block text-gray-600 hover:text-primary transition text-sm py-1">Software Testing</a></li>
                                    </ul>
                                </div>
                                
                                <!-- Category 2: Marketing & Growth -->
                                <div>
                                    <h4 class="font-semibold text-primary mb-3 text-sm uppercase tracking-wide">Marketing & Growth</h4>
                                    <ul class="space-y-2">
                                        <li><a href="/services#digital-marketing" class="block text-gray-600 hover:text-primary transition text-sm py-1">Digital Marketing</a></li>
                                        <li><a href="/services#seo" class="block text-gray-600 hover:text-primary transition text-sm py-1">SEO Services</a></li>
                                    </ul>
                                </div>
                                
                                <!-- Category 3: Infrastructure & Support -->
                                <div>
                                    <h4 class="font-semibold text-primary mb-3 text-sm uppercase tracking-wide">Infrastructure</h4>
                                    <ul class="space-y-2">
                                        <li><a href="/services#domain-webhosting" class="block text-gray-600 hover:text-primary transition text-sm py-1">Domain & Hosting</a></li>
                                        <li><a href="/services#cloud-computing" class="block text-gray-600 hover:text-primary transition text-sm py-1">Cloud Computing</a></li>
                                        <li><a href="/services#it-consultancy-support" class="block text-gray-600 hover:text-primary transition text-sm py-1">IT Consultancy</a></li>
                                    </ul>
                                </div>
                                
                                <!-- Category 4: Data & Training -->
                                <div>
                                    <h4 class="font-semibold text-primary mb-3 text-sm uppercase tracking-wide">Data & Training</h4>
                                    <ul class="space-y-2">
                                        <li><a href="/services#data-analysis" class="block text-gray-600 hover:text-primary transition text-sm py-1">Data Analysis</a></li>
                                        <li><a href="/services#survey-data-management" class="block text-gray-600 hover:text-primary transition text-sm py-1">Survey & Data Mgmt</a></li>
                                        <li><a href="/services#digital-skills-training" class="block text-gray-600 hover:text-primary transition text-sm py-1">Skills Training</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <a href="/services" class="inline-flex items-center text-primary font-semibold hover:underline text-sm">
                                    View All Services
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <a href="/training" class="text-gray-700 hover:text-primary transition font-medium text-sm xl:text-base <?php echo $current_page == 'training' ? 'text-primary font-semibold' : ''; ?>">Training</a>
                    <a href="/portfolio" class="text-gray-700 hover:text-primary transition font-medium text-sm xl:text-base <?php echo $current_page == 'portfolio' ? 'text-primary font-semibold' : ''; ?>">Portfolio</a>
                    <a href="/blog" class="text-gray-700 hover:text-primary transition font-medium text-sm xl:text-base <?php echo $current_page == 'blog' ? 'text-primary font-semibold' : ''; ?>">Blog</a>
                    
                    <a href="/contact" class="bg-brandYellow text-primary px-4 xl:px-6 py-2 xl:py-3 rounded-lg hover:bg-yellow-300 transition font-semibold text-sm xl:text-base shadow-md hover:shadow-lg whitespace-nowrap">Contact</a>
                    <span class="hidden xl:inline mx-2 text-brandYellow text-lg">|</span>
                    <!-- Connect With Us (Social Media) - After Contact -->
                    <div class="hidden xl:flex items-center space-x-2 ml-2 pl-2 border-l border-gray-300">
                        <?php if (defined('SOCIAL_FACEBOOK') && SOCIAL_FACEBOOK): ?>
                            <a href="<?php echo SOCIAL_FACEBOOK; ?>" target="_blank" rel="noopener" class="text-gray-600 hover:text-primary transition" aria-label="Facebook" title="Facebook">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                        <?php endif; ?>
                        <?php if (defined('SOCIAL_TWITTER') && SOCIAL_TWITTER): ?>
                            <a href="<?php echo SOCIAL_TWITTER; ?>" target="_blank" rel="noopener" class="text-gray-600 hover:text-primary transition" aria-label="Twitter" title="Twitter">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                            </a>
                        <?php endif; ?>
                        <?php if (defined('SOCIAL_LINKEDIN') && SOCIAL_LINKEDIN): ?>
                            <a href="<?php echo SOCIAL_LINKEDIN; ?>" target="_blank" rel="noopener" class="text-gray-600 hover:text-primary transition" aria-label="LinkedIn" title="LinkedIn">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            </a>
                        <?php endif; ?>
                        <?php if (defined('SOCIAL_INSTAGRAM') && SOCIAL_INSTAGRAM): ?>
                            <a href="<?php echo SOCIAL_INSTAGRAM; ?>" target="_blank" rel="noopener" class="text-gray-600 hover:text-primary transition" aria-label="Instagram" title="Instagram">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Mobile/Tablet Menu Button & Social Icons -->
                <div class="flex items-center space-x-3 lg:hidden">
                    <!-- Social Icons for Mobile/Tablet -->
                    <div class="flex items-center space-x-2">
                        <?php if (defined('SOCIAL_FACEBOOK') && SOCIAL_FACEBOOK): ?>
                            <a href="<?php echo SOCIAL_FACEBOOK; ?>" target="_blank" rel="noopener" class="text-gray-600 hover:text-primary transition p-1" aria-label="Facebook" title="Facebook">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                        <?php endif; ?>
                        <?php if (defined('SOCIAL_INSTAGRAM') && SOCIAL_INSTAGRAM): ?>
                            <a href="<?php echo SOCIAL_INSTAGRAM; ?>" target="_blank" rel="noopener" class="text-gray-600 hover:text-primary transition p-1" aria-label="Instagram" title="Instagram">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>
                        <?php endif; ?>
                    </div>
                    <!-- Mobile Menu Button -->
                    <button id="mobileMenuBtn" class="text-gray-700 focus:outline-none p-2 -mr-2" aria-label="Toggle menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="pointer-events-none absolute bottom-0 left-0 right-0 h-[2px] nav-gradient opacity-0"></div>
        
        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden lg:hidden bg-white border-t shadow-lg">
            <div class="container mx-auto px-4 py-4 space-y-3">
                <a href="/" class="block text-gray-700 hover:text-primary transition py-2 font-medium <?php echo $current_page == 'index' ? 'text-primary font-semibold' : ''; ?>">Home</a>
                <a href="/about" class="block text-gray-700 hover:text-primary transition py-2 font-medium <?php echo $current_page == 'about' ? 'text-primary font-semibold' : ''; ?>">About Us</a>
                
                <!-- Mobile Services Dropdown -->
                <div>
                    <button id="mobileServicesBtn" class="w-full flex items-center justify-between text-gray-700 hover:text-primary transition py-2 font-medium <?php echo $current_page == 'services' ? 'text-primary font-semibold' : ''; ?>">
                        <span>Services</span>
                        <svg class="w-4 h-4 transition-transform" id="mobileServicesArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="mobileServicesMenu" class="hidden pl-4 mt-2 space-y-3">
                        <div>
                            <h5 class="font-semibold text-primary text-xs uppercase mb-2">Development & Design</h5>
                            <div class="pl-2 space-y-1">
                                <a href="/services#web-design-development" class="block text-gray-600 hover:text-primary transition text-sm py-1">Web Design & Development</a>
                                <a href="/services#mobile-app-development" class="block text-gray-600 hover:text-primary transition text-sm py-1">Mobile App Development</a>
                                <a href="/services#graphics-design-branding" class="block text-gray-600 hover:text-primary transition text-sm py-1">Graphics & Branding</a>
                                <a href="/services#software-testing" class="block text-gray-600 hover:text-primary transition text-sm py-1">Software Testing</a>
                            </div>
                        </div>
                        <div>
                            <h5 class="font-semibold text-primary text-xs uppercase mb-2">Marketing & Growth</h5>
                            <div class="pl-2 space-y-1">
                                <a href="/services#digital-marketing" class="block text-gray-600 hover:text-primary transition text-sm py-1">Digital Marketing</a>
                                <a href="/services#seo" class="block text-gray-600 hover:text-primary transition text-sm py-1">SEO Services</a>
                            </div>
                        </div>
                        <div>
                            <h5 class="font-semibold text-primary text-xs uppercase mb-2">Infrastructure</h5>
                            <div class="pl-2 space-y-1">
                                <a href="/services#domain-webhosting" class="block text-gray-600 hover:text-primary transition text-sm py-1">Domain & Hosting</a>
                                <a href="/services#cloud-computing" class="block text-gray-600 hover:text-primary transition text-sm py-1">Cloud Computing</a>
                                <a href="/services#it-consultancy-support" class="block text-gray-600 hover:text-primary transition text-sm py-1">IT Consultancy</a>
                            </div>
                        </div>
                        <div>
                            <h5 class="font-semibold text-primary text-xs uppercase mb-2">Data & Training</h5>
                            <div class="pl-2 space-y-1">
                                <a href="/services#data-analysis" class="block text-gray-600 hover:text-primary transition text-sm py-1">Data Analysis</a>
                                <a href="/services#survey-data-management" class="block text-gray-600 hover:text-primary transition text-sm py-1">Survey & Data Mgmt</a>
                                <a href="/services#digital-skills-training" class="block text-gray-600 hover:text-primary transition text-sm py-1">Skills Training</a>
                            </div>
                        </div>
                        <a href="/services" class="block text-primary font-semibold text-sm pt-2 border-t">View All Services →</a>
                    </div>
                </div>
                
                <a href="/training" class="block text-gray-700 hover:text-primary transition py-2 font-medium <?php echo $current_page == 'training' ? 'text-primary font-semibold' : ''; ?>">Training</a>
                <a href="/portfolio" class="block text-gray-700 hover:text-primary transition py-2 font-medium <?php echo $current_page == 'portfolio' ? 'text-primary font-semibold' : ''; ?>">Portfolio</a>
                <a href="/blog" class="block text-gray-700 hover:text-primary transition py-2 font-medium <?php echo $current_page == 'blog' ? 'text-primary font-semibold' : ''; ?>">Blog</a>
                
                <a href="/contact" class="block bg-brandYellow text-primary px-6 py-3 rounded-lg hover:bg-yellow-300 transition text-center font-semibold shadow-md mt-4">Contact</a>
                
                <!-- Mobile Social Media Links -->
                <div class="flex items-center justify-center space-x-4 pt-4 border-t">
                    <?php if (defined('SOCIAL_FACEBOOK') && SOCIAL_FACEBOOK): ?>
                        <a href="<?php echo SOCIAL_FACEBOOK; ?>" target="_blank" rel="noopener" class="text-gray-600 hover:text-primary transition" aria-label="Facebook">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                    <?php endif; ?>
                    <?php if (defined('SOCIAL_TWITTER') && SOCIAL_TWITTER): ?>
                        <a href="<?php echo SOCIAL_TWITTER; ?>" target="_blank" rel="noopener" class="text-gray-600 hover:text-primary transition" aria-label="Twitter">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                    <?php endif; ?>
                    <?php if (defined('SOCIAL_LINKEDIN') && SOCIAL_LINKEDIN): ?>
                        <a href="<?php echo SOCIAL_LINKEDIN; ?>" target="_blank" rel="noopener" class="text-gray-600 hover:text-primary transition" aria-label="LinkedIn">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                    <?php endif; ?>
                    <?php if (defined('SOCIAL_INSTAGRAM') && SOCIAL_INSTAGRAM): ?>
                        <a href="<?php echo SOCIAL_INSTAGRAM; ?>" target="_blank" rel="noopener" class="text-gray-600 hover:text-primary transition" aria-label="Instagram">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content Wrapper -->
    <main class="pt-16 sm:pt-20 md:pt-20 lg:pt-24">

