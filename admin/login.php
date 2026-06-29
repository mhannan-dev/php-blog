<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkLogin(); // Redirect logged-in users away from login page

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Protection Check
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!Session::checkCsrfToken($csrfToken)) {
        $error = 'Security check failed. Please refresh the page.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($username) || empty($password)) {
            $error = 'Username and password are required.';
        } else {
            $user = $userModel->getByUsername($username);

            if ($user) {
                $passwordValid = false;

                if (password_verify($password, $user['password'])) {
                    $passwordValid = true;
                } elseif ($user['password'] === md5($password)) {
                    // Legacy MD5 match — rehash to bcrypt automatically
                    $newHash = password_hash($password, PASSWORD_BCRYPT);
                    $userModel->updatePassword((int) $user['id'], $newHash);
                    $passwordValid = true;
                }

                if ($passwordValid) {
                    Session::set('login',    true);
                    Session::set('userId',   $user['id']);
                    Session::set('userRole', $user['role']);
                    Session::set('userName', $user['username']);
                    header('Location: index.php');
                    exit();
                } else {
                    $error = 'Username or password is incorrect.';
                }
            } else {
                $error = 'Username or password is incorrect.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — <?php echo TITLE; ?></title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
          integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2zippi1Mun+0cFqCavcor+Bq3UMKrJvF7KZIZeq3aEznMfGt01Ow=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- TailwindCSS v3 CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        red: {
                            350: '#f87171'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass-card {
            background: rgba(30, 41, 59, 0.45);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #090d16;
        }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4">
<div class="w-full max-w-md">
    <div class="glass-card rounded-3xl p-8 shadow-2xl shadow-black/40 flex flex-col gap-6">
        
        <!-- Header -->
        <div class="text-center">
            <h1 class="text-3xl font-extrabold font-outfit text-white tracking-tight">Welcome Back</h1>
            <p class="text-slate-400 text-sm mt-1.5 font-medium">Log in to manage your blog panel</p>
        </div>

        <!-- Alert -->
        <?php if ($error): ?>
            <div class="bg-red-500/10 border border-red-500/20 text-red-350 px-4 py-3 rounded-xl text-xs flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation shrink-0"></i>
                <span><?php echo Format::e($error); ?></span>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form action="" method="post" class="flex flex-col gap-5">
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrfToken(); ?>">

            <div class="flex flex-col gap-1">
                <label for="username" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Username</label>
                <div class="relative flex items-center">
                    <input type="text" id="username" name="username" placeholder="Enter username" required
                           class="w-full bg-slate-900 border border-white/10 rounded-xl py-3 pl-11 pr-4 text-sm text-slate-200 placeholder-slate-600 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200" />
                    <i class="fa-regular fa-user absolute left-4 text-slate-500 text-sm"></i>
                </div>
            </div>

            <div class="flex flex-col gap-1">
                <label for="password" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Password</label>
                <div class="relative flex items-center">
                    <input type="password" id="password" name="password" placeholder="Enter password" required
                           class="w-full bg-slate-900 border border-white/10 rounded-xl py-3 pl-11 pr-4 text-sm text-slate-200 placeholder-slate-600 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200" />
                    <i class="fa-solid fa-lock absolute left-4 text-slate-500 text-sm"></i>
                </div>
            </div>

            <div class="mt-2">
                <button type="submit" name="login" 
                        class="w-full py-3 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-semibold rounded-xl transition-colors duration-200 cursor-pointer shadow-lg shadow-blue-600/10">
                    Sign In
                </button>
            </div>
        </form>

        <div class="text-center border-t border-white/5 pt-4">
            <a href="forget_pass.php" class="text-xs text-slate-400 hover:text-white transition-colors duration-200">Forgot your password?</a>
        </div>
    </div>
</div>
</body>
</html>