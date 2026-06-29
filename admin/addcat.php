<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>

<?php
$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Protection Check
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!Session::checkCsrfToken($csrfToken)) {
        $error = 'Security check failed. Please refresh the page.';
    } else {
        $name = trim($_POST['name'] ?? '');

        if (empty($name)) {
            $error = 'Field must not be empty.';
        } else {
            $inserted = $categoryModel->create($name);
            if ($inserted) {
                $success = 'Category inserted successfully.';
            } else {
                $error = 'Category not inserted successfully.';
            }
        }
    }
}
?>
       
<div class="glass-card rounded-3xl p-6 sm:p-8 shadow-xl shadow-black/5 flex flex-col gap-6">
    <div class="flex flex-col gap-1 border-b border-white/5 pb-4">
        <h2 class="text-xl font-bold font-outfit text-white">Add New Category</h2>
        <p class="text-slate-400 text-xs font-medium">Add a category to assign articles into</p>
    </div>

    <?php if ($error): ?>
        <div class="bg-red-500/10 border border-red-500/20 text-red-350 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation shrink-0"></i>
            <span><?php echo Format::e($error); ?></span>
        </div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-350 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check shrink-0"></i>
            <span><?php echo Format::e($success); ?></span>
        </div>
    <?php endif; ?>
    
    <form method="post" action="addcat.php" class="flex flex-col gap-4">
        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrfToken(); ?>">

        <div class="flex flex-col gap-1.5">
            <label for="name" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Category Name</label>
            <input name="name" type="text" id="name" placeholder="Enter Category Name..." 
                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 placeholder-slate-650 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200" required />
        </div>

        <div class="mt-2">
            <button type="submit" name="submit" 
                    class="px-6 py-3 bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white text-sm font-semibold rounded-xl transition-colors duration-200 cursor-pointer shadow-md shadow-brand-500/10">
                Save Category
            </button>
        </div>
    </form>
</div>

<?php include '../admin/inc/footer.php'; ?>
