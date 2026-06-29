<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>

<?php
// Only admins (role = '0') may add users.
if (Session::get('userRole') !== '0') {
    header('Location: index.php');
    exit();
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Protection Check
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!Session::checkCsrfToken($csrfToken)) {
        $error = 'Security check failed. Please refresh the page.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $role     = trim($_POST['role']     ?? '');

        if ($username === '' || $password === '' || $role === '') {
            $error = 'All fields are required.';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters.';
        } elseif (!in_array($role, ['0', '1', '2'], true)) {
            $error = 'Invalid role selected.';
        } else {
            // Check for duplicate username using UserModel
            if ($userModel->usernameExists($username)) {
                $error = 'Username already exists. Please choose a different username.';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $inserted       = $userModel->create($username, $hashedPassword, (int) $role);

                if ($inserted) {
                    $success  = 'User created successfully.';
                    $username = ''; // Clear form
                } else {
                    $error = 'Failed to create user. Please try again.';
                }
            }
        }
    }
}
?>

<div class="glass-card rounded-3xl p-6 sm:p-8 shadow-xl shadow-black/5 flex flex-col gap-6">
    <div class="flex flex-col gap-1 border-b border-white/5 pb-4">
        <h2 class="text-xl font-bold font-outfit text-white">Add New User</h2>
        <p class="text-slate-400 text-xs font-medium">Create a new user profile with specific authorization roles</p>
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

    <form action="" method="post" class="flex flex-col gap-5 max-w-lg">
        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrfToken(); ?>">

        <div class="flex flex-col gap-1.5">
            <label for="new-username" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Username</label>
            <input type="text" id="new-username" name="username"
                   placeholder="Enter username" required
                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 placeholder-slate-650 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200"
                   value="<?php echo Format::e($username ?? ''); ?>" />
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="new-password" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Password</label>
            <input type="password" id="new-password" name="password"
                   placeholder="Minimum 6 characters" required
                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 placeholder-slate-655 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200" />
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="user-role" class="text-xs font-semibold uppercase tracking-wider text-slate-400">User Role</label>
            <select id="user-role" name="role" required
                    class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200">
                <option value="">— Select Role —</option>
                <option value="0">Admin</option>
                <option value="1">Author</option>
                <option value="2">Editor</option>
            </select>
        </div>

        <div class="mt-2 flex gap-3">
            <button type="submit" name="submit" 
                    class="px-6 py-3 bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white text-sm font-semibold rounded-xl transition-colors duration-200 cursor-pointer shadow-md shadow-brand-500/10">
                Create User
            </button>
            <a href="user_list.php" 
               class="px-6 py-3 border border-white/10 hover:bg-white/5 text-slate-350 hover:text-white text-sm font-semibold rounded-xl transition-colors duration-200">
                Cancel
            </a>
        </div>
    </form>
</div>

<?php include '../admin/inc/footer.php'; ?>
