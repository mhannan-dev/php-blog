<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>

<?php
// Only admins (role = '0') can add social links
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
        $fb = trim($_POST['fb'] ?? '');
        $tw = trim($_POST['tw'] ?? '');
        $ln = trim($_POST['ln'] ?? '');

        if ($fb === '' || $tw === '' || $ln === '') {
            $error = 'All fields must not be empty.';
        } else {
            $inserted = $siteModel->createSocial($fb, $tw, $ln);
            if ($inserted) {
                $success = 'Social links inserted successfully.';
            } else {
                $error = 'Social links not inserted successfully.';
            }
        }
    }
}
?>
       
<div class="glass-card rounded-3xl p-6 sm:p-8 shadow-xl shadow-black/5 flex flex-col gap-6">
    <div class="flex flex-col gap-1 border-b border-white/5 pb-4">
        <h2 class="text-xl font-bold font-outfit text-white">Add Social Links</h2>
        <p class="text-slate-400 text-xs font-medium">Add platform link sets to render in site layouts</p>
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

    <form method="post" action="" class="flex flex-col gap-4 max-w-xl">
        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrfToken(); ?>">

        <div class="flex flex-col gap-1.5">
            <label for="fb" class="text-xs font-semibold uppercase tracking-wider text-slate-450">Facebook Page URL</label>
            <input name="fb" type="url" id="fb" placeholder="https://facebook.com/yourpage" 
                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 placeholder-slate-650 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200" required />
        </div>
        
        <div class="flex flex-col gap-1.5">
            <label for="tw" class="text-xs font-semibold uppercase tracking-wider text-slate-455">Twitter/GitHub URL</label>
            <input name="tw" type="url" id="tw" placeholder="https://github.com/yourhandle" 
                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 placeholder-slate-650 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200" required />
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="ln" class="text-xs font-semibold uppercase tracking-wider text-slate-455">LinkedIn URL</label>
            <input name="ln" type="url" id="ln" placeholder="https://linkedin.com/in/yourprofile" 
                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 placeholder-slate-650 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200" required />
        </div>

        <div class="mt-2 flex gap-3">
            <button type="submit" name="submit" 
                    class="px-6 py-3 bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white text-sm font-semibold rounded-xl transition-colors duration-200 cursor-pointer shadow-md shadow-brand-500/10">
                Save Links
            </button>
            <a href="social_list.php" 
               class="px-6 py-3 border border-white/10 hover:bg-white/5 text-slate-350 hover:text-white text-sm font-semibold rounded-xl transition-colors duration-200">
                Cancel
            </a>
        </div>
    </form>
</div>
<?php include '../admin/inc/footer.php'; ?>
