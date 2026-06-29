<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>

<?php 
// Only admins (role = '0') can edit social links
if (Session::get('userRole') !== '0') {
    header('Location: index.php');
    exit();
}

if (!isset($_GET['social_id']) || (int) $_GET['social_id'] <= 0) {
    header('Location: social_list.php');
    exit();
}

$social_id = (int) $_GET['social_id'];

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
            $updated = $siteModel->updateSocial($social_id, $fb, $tw, $ln);
            if ($updated) {
                header('Location: social_list.php');
                exit();
            } else {
                $error = 'Social links not updated.';
            }
        }
    }
}

$social_data = $siteModel->getSocialById($social_id);
if (!$social_data) {
    header('Location: social_list.php');
    exit();
}
?>

<div class="glass-card rounded-3xl p-6 sm:p-8 shadow-xl shadow-black/5 flex flex-col gap-6">
    <div class="flex flex-col gap-1 border-b border-white/5 pb-4">
        <h2 class="text-xl font-bold font-outfit text-white">Update Social Link</h2>
        <p class="text-slate-400 text-xs font-medium">Modify existing social platform link parameters</p>
    </div>
    
    <?php if ($error): ?>
        <div class="bg-red-500/10 border border-red-500/20 text-red-350 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation shrink-0"></i>
            <span><?php echo Format::e($error); ?></span>
        </div>
    <?php endif; ?>

    <form method="post" action="" class="flex flex-col gap-4 max-w-xl">
        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrfToken(); ?>">

        <div class="flex flex-col gap-1.5">
            <label for="fb" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Facebook URL</label>
            <input type="url" name="fb" id="fb" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200"
                   value="<?php echo Format::e($social_data['fb']); ?>" required />
        </div>
        
        <div class="flex flex-col gap-1.5">
            <label for="tw" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Twitter/GitHub URL</label>
            <input type="url" name="tw" id="tw" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200"
                   value="<?php echo Format::e($social_data['tw']); ?>" required />
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="ln" class="text-xs font-semibold uppercase tracking-wider text-slate-400">LinkedIn URL</label>
            <input type="url" name="ln" id="ln" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200"
                   value="<?php echo Format::e($social_data['ln']); ?>" required />
        </div>

        <div class="mt-2 flex gap-3">
            <button type="submit" name="submit" 
                    class="px-6 py-3 bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white text-sm font-semibold rounded-xl transition-colors duration-200 cursor-pointer shadow-md shadow-brand-500/10">
                Update Social Link
            </button>
            <a href="social_list.php" 
               class="px-6 py-3 border border-white/10 hover:bg-white/5 text-slate-350 hover:text-white text-sm font-semibold rounded-xl transition-colors duration-200">
                Cancel
            </a>
        </div>
    </form>
</div>

<?php include '../admin/inc/footer.php'; ?>
