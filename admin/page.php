<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>

<?php
// Only admins (role = '0') can view or edit pages
if (Session::get('userRole') !== '0') {
    header('Location: index.php');
    exit();
}

if (!isset($_GET['page_id']) || (int) $_GET['page_id'] <= 0) {
    header('Location: index.php');
    exit();
}

$id = (int) $_GET['page_id'];

$error   = '';
$success = '';

// Handle page deletion
if (isset($_GET['del_page'])) {
    $delpage = (int) $_GET['del_page'];
    if ($delpage > 0) {
        $dlt_page = $pageModel->delete($delpage);
        if ($dlt_page) {
            header('Location: index.php');
            exit();
        } else {
            $error = 'Page not deleted successfully.';
        }
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Protection Check
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!Session::checkCsrfToken($csrfToken)) {
        $error = 'Security check failed. Please refresh the page.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $body = trim($_POST['body'] ?? '');

        if ($name === '' || $body === '') {
            $error = 'Field must not be empty.';
        } else {
            $updated_rows = $pageModel->update($id, $name, $body);
            if ($updated_rows) {
                header('Location: index.php');
                exit();
            } else {
                $error = 'Page Not Updated!';
            }
        }
    }
}

$result = $pageModel->getById($id);
if (!$result) {
    header('Location: index.php');
    exit();
}
?>

<div class="glass-card rounded-3xl p-6 sm:p-8 shadow-xl shadow-black/5 flex flex-col gap-6">
    <div class="flex flex-col gap-1 border-b border-white/5 pb-4">
        <h2 class="text-xl font-bold font-outfit text-white">Edit Dynamic Page</h2>
        <p class="text-slate-400 text-xs font-medium">Modify name or body settings for dynamic layout pages</p>
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
            <label for="name" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Page Title</label>
            <input type="text" name="name" id="name" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200"
                   value="<?php echo Format::e($result['name']); ?>" required />
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="mytextarea" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Page Content</label>
            <textarea id="mytextarea" class="tinymce" name="body"><?php echo Format::e($result['body']); ?></textarea>
        </div>

        <div class="mt-2 flex flex-wrap gap-3">
            <button type="submit" name="submit" 
                    class="px-6 py-3 bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white text-sm font-semibold rounded-xl transition-colors duration-200 cursor-pointer shadow-md shadow-brand-500/10">
                Update Page
            </button>
            <a onclick="return confirm('Are you sure want to delete this page?')" 
               href="?page_id=<?php echo $id; ?>&del_page=<?php echo $id; ?>"
               class="px-6 py-3 bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 border border-red-500/20 text-sm font-semibold rounded-xl transition-colors duration-205">
                Delete Page
            </a>
            <a href="index.php" 
               class="px-6 py-3 border border-white/10 hover:bg-white/5 text-slate-350 hover:text-white text-sm font-semibold rounded-xl transition-colors duration-200">
                Cancel
            </a>
        </div>
    </form>
</div>

<?php include '../admin/inc/footer.php'; ?>