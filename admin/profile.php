<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>

<?php
$userId   = (int) Session::get('userId');
$error    = '';
$success  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Protection Check
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!Session::checkCsrfToken($csrfToken)) {
        $error = 'Security check failed. Please refresh the page.';
    } else {
        $name     = trim($_POST['name']     ?? '');
        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email']    ?? '');
        $details  = trim($_POST['details']  ?? '');

        $data = [
            'name'     => $name,
            'username' => $username,
            'email'    => $email,
            'details'  => $details
        ];

        if (empty($name) || empty($username) || empty($email)) {
            $error = 'Name, username, and email are required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } else {
            $updated = $userModel->update($userId, $data);

            if ($updated) {
                Session::set('userName', $username);
                $success = 'Profile updated successfully.';
            } else {
                $error = 'Failed to update profile. Please try again.';
            }
        }
    }
}

$user = $userModel->getById($userId);
if (!$user) {
    header('Location: index.php');
    exit();
}
?>

<div class="glass-card rounded-3xl p-6 sm:p-8 shadow-xl shadow-black/5 flex flex-col gap-6">
    <div class="flex flex-col gap-1 border-b border-white/5 pb-4">
        <h2 class="text-xl font-bold font-outfit text-white">Update Profile</h2>
        <p class="text-slate-400 text-xs font-medium">Edit your personal contact and login credentials</p>
    </div>

    <?php if ($success): ?>
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-350 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check shrink-0"></i>
            <span><?php echo Format::e($success); ?></span>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="bg-red-500/10 border border-red-500/20 text-red-350 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation shrink-0"></i>
            <span><?php echo Format::e($error); ?></span>
        </div>
    <?php endif; ?>

    <form action="" method="post" class="flex flex-col gap-5 max-w-xl">
        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrfToken(); ?>">

        <div class="flex flex-col gap-1.5">
            <label for="prof-name" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Full Name</label>
            <input type="text" id="prof-name" name="name" 
                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200"
                   value="<?php echo Format::e($user['name']); ?>" required />
        </div>
        
        <div class="flex flex-col gap-1.5">
            <label for="prof-username" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Username</label>
            <input type="text" id="prof-username" name="username" 
                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200"
                   value="<?php echo Format::e($user['username']); ?>" required />
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="prof-email" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Email Address</label>
            <input type="email" id="prof-email" name="email" 
                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200"
                   value="<?php echo Format::e($user['email']); ?>" required />
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="prof-details" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Bio Details</label>
            <textarea id="prof-details" name="details" rows="4" 
                      class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200"><?php echo Format::e($user['details']); ?></textarea>
        </div>

        <div class="mt-2 flex gap-3">
            <button type="submit" name="submit" 
                    class="px-6 py-3 bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white text-sm font-semibold rounded-xl transition-colors duration-200 cursor-pointer shadow-md shadow-brand-500/10">
                Update Profile
            </button>
            <a href="index.php" 
               class="px-6 py-3 border border-white/10 hover:bg-white/5 text-slate-350 hover:text-white text-sm font-semibold rounded-xl transition-colors duration-200">
                Cancel
            </a>
        </div>
    </form>
</div>

<?php include '../admin/inc/footer.php'; ?>