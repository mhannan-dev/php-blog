<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>

<?php 
// Only admins (role = '0') can edit copyright
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
        $note = trim($_POST['note'] ?? '');

        if ($note === '') {
            $error = 'Field must not be empty.';
        } else {
            $updated = $siteModel->updateFooter($note);
            if ($updated) {
                header('Location: index.php');
                exit();
            } else {
                $error = 'Failed to update copyright note.';
            }
        }
    }
}

$result = $siteModel->getFooterNote();
?>
<div class="glass-card rounded-3xl p-6 sm:p-8 shadow-xl shadow-black/5 flex flex-col gap-6">
    <div class="flex flex-col gap-1 border-b border-white/5 pb-4">
        <h2 class="text-xl font-bold font-outfit text-white">Update Copyright Note</h2>
        <p class="text-slate-400 text-xs font-medium">Edit the public copyright footer note statement</p>
    </div>
    
    <?php if ($error): ?>
        <div class="bg-red-500/10 border border-red-500/20 text-red-350 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation shrink-0"></i>
            <span><?php echo Format::e($error); ?></span>
        </div>
    <?php endif; ?>

    <?php if ($result): ?>
        <form method="post" action="" class="flex flex-col gap-4 max-w-xl">
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrfToken(); ?>">

            <div class="flex flex-col gap-1.5">
                <label for="note" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Copyright Statement</label>
                <input type="text" name="note" id="note" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200"
                       value="<?php echo Format::e($result['note']); ?>" required />
            </div>
             
            <div class="mt-2 flex gap-3">
                <button type="submit" name="submit" 
                        class="px-6 py-3 bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white text-sm font-semibold rounded-xl transition-colors duration-200 cursor-pointer shadow-md shadow-brand-500/10">
                    Update Copyright
                </button>
                <a href="index.php" 
                   class="px-6 py-3 border border-white/10 hover:bg-white/5 text-slate-350 hover:text-white text-sm font-semibold rounded-xl transition-colors duration-200">
                    Cancel
                </a>
            </div>
        </form>
    <?php else: ?>
        <p class="text-slate-455 text-sm italic">Copyright note row not found in the database.</p>
    <?php endif; ?>
</div>
<?php include '../admin/inc/footer.php'; ?>
