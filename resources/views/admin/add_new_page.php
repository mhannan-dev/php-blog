<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>

<?php
// Only admins (role = '0') can add pages
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
        $name = trim($_POST['name'] ?? '');
        $body = trim($_POST['body'] ?? '');

        if ($name === '' || $body === '') {
            $error = 'Fields must not be empty.';
        } else {
            $inserted = $pageModel->create($name, $body);
            if ($inserted) {
                header('Location: index.php');
                exit();
            } else {
                $error = 'Page Not Inserted!';
            }
        }
    }
}
?>
<div class="glass-card rounded-3xl p-6 sm:p-8 shadow-xl shadow-black/5 flex flex-col gap-6">
    <div class="flex flex-col gap-1 border-b border-white/5 pb-4">
        <h2 class="text-xl font-bold font-outfit text-white">Add New Page</h2>
        <p class="text-slate-400 text-xs font-medium">Create a new static content page link in navigations</p>
    </div>

    <?php if ($error): ?>
        <div class="bg-red-500/10 border border-red-500/20 text-red-350 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation shrink-0"></i>
            <span><?php echo Format::e($error); ?></span>
        </div>
    <?php endif; ?>

    <form action="" method="post" class="flex flex-col gap-5">
        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrfToken(); ?>">

        <div class="flex flex-col gap-1.5 max-w-xl">
            <label for="name" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Page Name / Title</label>
            <input type="text" name="name" id="name" placeholder="Enter Page Name" required 
                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 placeholder-slate-650 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200"
                   value="<?php echo isset($_POST['name']) ? Format::e($_POST['name']) : ''; ?>"/>
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="mytextarea" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Page Content</label>
            <textarea id="mytextarea" class="tinymce" name="body"><?php echo isset($_POST['body']) ? Format::e($_POST['body']) : ''; ?></textarea>
        </div>

        <div class="mt-2 flex gap-3">
            <button type="submit" name="submit" 
                    class="px-6 py-3 bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white text-sm font-semibold rounded-xl transition-colors duration-200 cursor-pointer shadow-md shadow-brand-500/10">
                Save Page
            </button>
            <a href="index.php" 
               class="px-6 py-3 border border-white/10 hover:bg-white/5 text-slate-350 hover:text-white text-sm font-semibold rounded-xl transition-colors duration-200">
                Cancel
            </a>
        </div>
    </form>
</div>

<?php include '../admin/inc/footer.php'; ?>