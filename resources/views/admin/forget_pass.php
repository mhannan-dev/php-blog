<?php 
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkLogin();
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Recovery — <?php echo TITLE; ?></title>
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
            <h1 class="text-3xl font-extrabold font-outfit text-white tracking-tight">Recover Password</h1>
            <p class="text-slate-400 text-sm mt-1.5 font-medium font-sans">We will send a temporary password to your email</p>
        </div>

        <?php 
        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF Protection Check
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (!Session::checkCsrfToken($csrfToken)) {
                $error = 'Security check failed. Please refresh the page.';
            } else {
                $email = trim($_POST['email'] ?? '');

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = 'Invalid email address.';
                } else {
                    $emailEsc = $db->escape($email);
                    $query    = "SELECT * FROM users WHERE email = '$emailEsc' LIMIT 1";
                    $result   = $db->select($query);

                    if ($result) {
                        $user     = $result->fetch_assoc();
                        $userId   = (int) $user['id'];
                        $username = $user['username'];

                        // Generate temporary random password
                        $prefix    = substr($email, 0, 3);
                        $randDigit = random_int(10000, 99999);
                        $newPass   = $prefix . $randDigit;
                        $newHash   = password_hash($newPass, PASSWORD_BCRYPT);
                        $newHashEsc = $db->escape($newHash);

                        $updated = $db->update("UPDATE users SET password = '$newHashEsc' WHERE id = $userId");

                        if ($updated) {
                            $to      = $email;
                            $from    = "admin@example.com";
                            $subject = "Your New Password";
                            $message = "Hello, $username. Your new temporary password is: $newPass\r\n\r\nPlease log in and change it immediately.";
                            
                            $headers = "From: $from\r\n";
                            $headers .= "Reply-To: $from\r\n";
                            $headers .= "MIME-Version: 1.0\r\n";
                            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

                            $sendMail = mail($to, $subject, $message, $headers);
                            
                            if ($sendMail) {
                                $success = 'Please check your email for your new password.';
                            } else {
                                $success = 'Temporary password generated. (Mail could not be sent. New temporary password: ' . $newPass . ')';
                            }
                        } else {
                            $error = 'Failed to generate temporary password. Please try again.';
                        }
                    } else {
                        $error = 'No account found with that email address.';
                    }
                }
            }
        }
        ?>

        <!-- Alerts -->
        <?php if ($error): ?>
            <div class="bg-red-500/10 border border-red-500/20 text-red-350 px-4 py-3 rounded-xl text-xs flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation shrink-0"></i>
                <span><?php echo Format::e($error); ?></span>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-350 px-4 py-3 rounded-xl text-xs flex items-center gap-2">
                <i class="fa-solid fa-circle-check shrink-0"></i>
                <span><?php echo Format::e($success); ?></span>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form action="" method="post" class="flex flex-col gap-5">
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrfToken(); ?>">

            <div class="flex flex-col gap-1">
                <label for="email" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Email Address</label>
                <div class="relative flex items-center">
                    <input type="email" id="email" placeholder="Enter your email" required name="email" 
                           value="<?php echo isset($_POST['email']) ? Format::e($_POST['email']) : ''; ?>"
                           class="w-full bg-slate-900 border border-white/10 rounded-xl py-3 pl-11 pr-4 text-sm text-slate-200 placeholder-slate-650 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200" />
                    <i class="fa-regular fa-envelope absolute left-4 text-slate-500 text-sm"></i>
                </div>
            </div>
            
            <div class="mt-2">
                <input type="submit" name="submit" value="Send password" 
                       class="w-full py-3 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-semibold rounded-xl transition-colors duration-205 cursor-pointer shadow-lg shadow-blue-600/10" />
            </div>
        </form>

        <div class="text-center border-t border-white/5 pt-4">
            <a href="login.php" class="text-xs text-slate-400 hover:text-white transition-colors duration-200">Back to Login</a>
        </div>
    </div>
</div>
</body>
</html>