<?php
require_once __DIR__ . '/config/config.php';

$page_title = 'Contact Us - ' . SITE_NAME;
$meta_description = 'Get in touch with Webspace Innovation Hub Limited. Contact us for web development, digital marketing, IT consultancy, and training services.';

$success = false;
$error = '';

// Initialize form start time for timing check
if (!isset($_SESSION['form_start_time'])) {
    $_SESSION['form_start_time'] = time();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');
    $honeypot = $_POST['website'] ?? ''; // Honeypot field (hidden from users)
    
    // Spam protection checks
    if (isSpamSubmission($honeypot)) {
        $error = 'Spam detected. Please try again later.';
    } elseif (!isValidFormTiming(3)) {
        $error = 'Form submitted too quickly. Please take your time filling out the form.';
    } elseif (empty($name) || empty($email) || empty($message)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($message) < 10) {
        $error = 'Message is too short. Please provide more details.';
    } else {
        // Save to database
        $db = new Database();
        $conn = $db->getConnection();
        
        try {
            $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $phone ?: null, $subject ?: null, $message]);
            
            // Send email notification
            $to = SITE_EMAIL;
            $email_subject = 'New Contact Form Submission: ' . ($subject ?: 'No Subject');
            $email_message = "New contact form submission:\n\n";
            $email_message .= "Name: $name\n";
            $email_message .= "Email: $email\n";
            if ($phone) $email_message .= "Phone: $phone\n";
            if ($subject) $email_message .= "Subject: $subject\n";
            $email_message .= "\nMessage:\n$message\n";
            
            $headers = "From: $email\r\n";
            $headers .= "Reply-To: $email\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            
            @mail($to, $email_subject, $email_message, $headers);
            
            $success = true;
        } catch (Exception $e) {
            $error = 'Sorry, there was an error sending your message. Please try again later.';
            error_log("Contact form error: " . $e->getMessage());
        }
    }
}

// Get subject from query string if available
$default_subject = isset($_GET['subject']) ? sanitize($_GET['subject']) : '';

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-primary to-blue-600 text-white py-16">
    <div class="container mx-auto px-4" data-aos="fade-up">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Contact Us</h1>
        <p class="text-xl text-blue-100">We'd love to hear from you</p>
    </div>
</section>

<!-- Contact Section -->
<section class="py-20">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div data-aos="fade-right">
                <h2 class="text-3xl font-bold mb-6">Send us a Message</h2>
                
                <?php if ($success): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                        Thank you for your message! We'll get back to you soon.
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="/contact" class="space-y-6" id="contactForm">
                    <!-- Honeypot field (hidden from users, bots will fill it) -->
                    <div style="position: absolute; left: -9999px; opacity: 0; pointer-events: none;" aria-hidden="true">
                        <label for="website">Website (leave blank)</label>
                        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                    </div>
                    
                    <div>
                        <label for="name" class="block text-gray-700 font-semibold mb-2">Name <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-gray-700 font-semibold mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-gray-700 font-semibold mb-2">Phone</label>
                        <input type="tel" id="phone" name="phone" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                    </div>
                    
                    <div>
                        <label for="subject" class="block text-gray-700 font-semibold mb-2">Subject</label>
                        <input type="text" id="subject" name="subject" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : $default_subject; ?>">
                    </div>
                    
                    <div>
                        <label for="message" class="block text-gray-700 font-semibold mb-2">Message <span class="text-red-500">*</span></label>
                        <textarea id="message" name="message" rows="6" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">Send Message</button>
                </form>
            </div>
            
            <!-- Contact Info -->
            <div data-aos="fade-left">
                <h2 class="text-3xl font-bold mb-6">Get in Touch</h2>
                <p class="text-gray-600 mb-8">We're here to help! Reach out to us through any of the following channels.</p>
                
                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="bg-primary bg-opacity-10 w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-1">Email</h3>
                            <a href="mailto:<?php echo SITE_EMAIL; ?>" class="text-gray-600 hover:text-primary transition"><?php echo SITE_EMAIL; ?></a>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="bg-primary bg-opacity-10 w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-1">Phone</h3>
                            <a href="tel:<?php echo SITE_PHONE_1; ?>" class="text-gray-600 hover:text-primary transition block"><?php echo SITE_PHONE_1; ?></a>
                            <a href="tel:<?php echo SITE_PHONE_2; ?>" class="text-gray-600 hover:text-primary transition block"><?php echo SITE_PHONE_2; ?></a>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="bg-primary bg-opacity-10 w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-1">Business Hours</h3>
                            <p class="text-gray-600">Monday - Friday: 9:00 AM - 6:00 PM<br>Saturday: 10:00 AM - 4:00 PM<br>Sunday: Closed</p>
                        </div>
                    </div>
                </div>
                
                <!-- Company Info -->
                <div class="mt-8 p-6 bg-gray-50 rounded-lg">
                    <h3 class="font-semibold mb-2">Company Information</h3>
                    <p class="text-gray-600 text-sm">
                        <strong>Registration No:</strong> <?php echo REGISTRATION_NO; ?><br>
                        <strong>Tagline:</strong> <?php echo SITE_TAGLINE; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Google Maps Section -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Find Us</h2>
            <p class="text-gray-600">Visit us at our location</p>
        </div>
        <div class="max-w-6xl mx-auto" data-aos="fade-up">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m23!1m12!1m3!1d105681.43880896235!2d3.746626563213522!3d7.368301663522166!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m8!3e6!4m0!4m5!1s0x10398db8b38547bb%3A0x15577ea8f163c31c!2sOlapade%20Agoro%20Leaders%20High%20School%2C%20Adedeji%20Bero%20St%2C%20Oluyole%2C%20Ibadan%20200261%2C%20Oyo!3m2!1d7.3683092!2d3.8290284999999997!5e1!3m2!1sen!2sng!4v1762417305785!5m2!1sen!2sng" 
                    width="100%" 
                    height="500" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade"
                    class="w-full">
                </iframe>
            </div>
            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    <strong>Address:</strong> Olapade Agoro Leaders High School, Adedeji Bero St, Oluyole, Ibadan 200261, Oyo
                </p>
            </div>
        </div>
    </div>
</section>

<script>
// Form timing validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    if (form) {
        const formStartTime = <?php echo isset($_SESSION['form_start_time']) ? $_SESSION['form_start_time'] : time(); ?>;
        
        form.addEventListener('submit', function(e) {
            const elapsed = Math.floor((Date.now() / 1000) - formStartTime);
            
            if (elapsed < 3) {
                e.preventDefault();
                alert('Please take your time filling out the form. Forms filled too quickly are rejected.');
                return false;
            }
        });
    }
});
</script>

<?php include 'includes/footer.php'; ?>

