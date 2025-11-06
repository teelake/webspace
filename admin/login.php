<?php
require_once __DIR__ . '/../config/config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: ' . adminUrl('index'));
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        // Check login attempts (rate limiting)
        $attempt_check = checkLoginAttempts($username);
        if ($attempt_check['locked']) {
            $error = $attempt_check['message'];
            logLoginAttempt($username, false);
        } else {
            $db = new Database();
            $conn = $db->getConnection();
            
            $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Successful login
                logLoginAttempt($username, true);
                
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['admin_email'] = $user['email'];
                $_SESSION['last_activity'] = time();
                
                header('Location: ' . adminUrl('index'));
                exit;
            } else {
                // Failed login
                logLoginAttempt($username, false);
                $error = 'Invalid username or password.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin Login - <?php echo SITE_NAME; ?></title>
    <link rel="icon" type="image/png" href="<?php echo LOGO_URL; ?>webspace-favicon.png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1D4ED8',
                        brandYellow: '#FCD34D',
                        accent: '#059669',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        /* Animated background elements */
        body::before,
        body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 20s infinite ease-in-out;
        }
        
        body::before {
            width: 500px;
            height: 500px;
            top: -250px;
            right: -250px;
            animation-delay: 0s;
        }
        
        body::after {
            width: 400px;
            height: 400px;
            bottom: -200px;
            left: -200px;
            animation-delay: 10s;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translate(0, 0) scale(1);
                opacity: 0.3;
            }
            50% {
                transform: translate(50px, 50px) scale(1.1);
                opacity: 0.5;
            }
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.3);
            animation: slideUp 0.6s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group input {
            transition: all 0.3s ease;
        }
        
        .input-group input:focus {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(29, 78, 216, 0.15);
        }
        
        .input-icon {
            transition: all 0.3s ease;
        }
        
        .input-group:focus-within .input-icon {
            color: #1D4ED8;
            transform: scale(1.1);
        }
        
        .btn-login {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn-login:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(29, 78, 216, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .error-message {
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        
        .logo-container {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.8;
            }
        }
        
        /* Password toggle */
        .password-toggle {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .password-toggle:hover {
            color: #1D4ED8;
            transform: scale(1.1);
        }
        
        /* Loading spinner */
        .spinner {
            display: none;
        }
        
        .loading .spinner {
            display: inline-block;
        }
        
        .loading .btn-text {
            display: none;
        }
    </style>
</head>
<body>
    <div class="w-full max-w-lg px-6 py-8 sm:px-10 sm:py-12">
        <div class="login-container p-10 sm:p-12">
            <!-- Logo and Header -->
            <div class="text-center mb-10">
                <div class="logo-container inline-block mb-5">
                    <img src="<?php echo LOGO_URL; ?>webspaceng-logo-renewed.png" 
                         alt="<?php echo SITE_NAME; ?>" 
                         class="h-20 mx-auto object-contain">
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-3">Welcome Back</h1>
                <p class="text-gray-600 text-base">Sign in to access your admin dashboard</p>
            </div>
            
            <!-- Error Message -->
            <?php if ($error): ?>
                <div class="error-message mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-red-700 text-sm font-medium"><?php echo htmlspecialchars($error); ?></p>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Login Form -->
            <form id="loginForm" class="space-y-7" method="POST" action="" novalidate>
                <!-- Username/Email Field -->
                <div class="input-group">
                    <label for="username" class="block text-base font-semibold text-gray-700 mb-3">
                        Username or Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <svg class="input-icon h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <input 
                            id="username" 
                            name="username" 
                            type="text" 
                            required 
                            autocomplete="username"
                            autofocus
                            class="block w-full pl-14 pr-5 py-4 text-base border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-300 outline-none text-gray-900 placeholder-gray-400"
                            placeholder="Enter your username or email"
                            aria-label="Username or Email"
                            aria-required="true">
                    </div>
                </div>
                
                <!-- Password Field -->
                <div class="input-group">
                    <label for="password" class="block text-base font-semibold text-gray-700 mb-3">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <svg class="input-icon h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            required 
                            autocomplete="current-password"
                            class="block w-full pl-14 pr-14 py-4 text-base border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-300 outline-none text-gray-900 placeholder-gray-400"
                            placeholder="Enter your password"
                            aria-label="Password"
                            aria-required="true">
                        <button 
                            type="button" 
                            id="togglePassword"
                            class="password-toggle absolute inset-y-0 right-0 pr-5 flex items-center text-gray-400 hover:text-gray-600"
                            aria-label="Toggle password visibility">
                            <svg id="eyeIcon" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eyeOffIcon" class="h-6 w-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center cursor-pointer group">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            class="w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary focus:ring-2 cursor-pointer"
                            aria-label="Remember me">
                        <span class="ml-3 text-base text-gray-600 group-hover:text-gray-900 transition-colors">Remember me</span>
                    </label>
                    <a href="#" class="text-base font-medium text-primary hover:text-blue-700 transition-colors">
                        Forgot password?
                    </a>
                </div>
                
                <!-- Submit Button -->
                <button 
                    type="submit" 
                    id="submitBtn"
                    class="btn-login w-full flex items-center justify-center px-6 py-4 text-lg bg-gradient-to-r from-primary to-blue-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-primary focus:ring-opacity-50">
                    <span class="btn-text">Sign In</span>
                    <svg class="spinner h-5 w-5 animate-spin ml-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </form>
            
            <!-- Footer -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-center text-xs text-gray-500">
                    © <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.
                </p>
                <p class="text-center text-xs text-gray-400 mt-2">
                    Secure login with encryption
                </p>
            </div>
        </div>
    </div>
    
    <script>
        // Password visibility toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeOffIcon = document.getElementById('eyeOffIcon');
        
        if (togglePassword) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                if (type === 'password') {
                    eyeIcon.classList.remove('hidden');
                    eyeOffIcon.classList.add('hidden');
                } else {
                    eyeIcon.classList.add('hidden');
                    eyeOffIcon.classList.remove('hidden');
                }
            });
        }
        
        // Form submission with loading state
        const loginForm = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                // Add loading state
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
                
                // Re-enable after 5 seconds (in case of slow response)
                setTimeout(function() {
                    submitBtn.classList.remove('loading');
                    submitBtn.disabled = false;
                }, 5000);
            });
        }
        
        // Auto-focus on username field
        const usernameInput = document.getElementById('username');
        if (usernameInput && !usernameInput.value) {
            usernameInput.focus();
        }
        
        // Enter key to submit
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && document.activeElement.tagName !== 'BUTTON') {
                const form = document.getElementById('loginForm');
                if (form) {
                    form.requestSubmit();
                }
            }
        });
    </script>
</body>
</html>
