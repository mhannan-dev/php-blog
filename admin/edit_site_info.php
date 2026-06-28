<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>

<?php 
// Only admins (role = '0') can edit site info
if (Session::get('userRole') !== '0') {
    header('Location: index.php');
    exit();
}

if (!isset($_GET['info_id']) || (int) $_GET['info_id'] <= 0) {
    header('Location: site_info_list.php');
    exit();
}

$id = (int) $_GET['info_id'];

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Protection Check
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!Session::checkCsrfToken($csrfToken)) {
        $error = 'Security check failed. Please refresh the page.';
    } else {
        $title  = trim($_POST['title']  ?? '');
        $slogan = trim($_POST['slogan'] ?? '');

        // Image upload
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
        $file        = $_FILES['logo']  ?? null;
        $fileName    = $file['name']     ?? '';
        $fileSize    = $file['size']     ?? 0;
        $fileTmp     = $file['tmp_name'] ?? '';
        $fileExt     = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($title === '' || $slogan === '') {
            $error = 'Title and slogan must not be empty.';
        } else {
            if (!empty($fileName)) {
                if ($fileSize > 1_048_576) {
                    $error = 'Logo size must be less than 1 MB.';
                } elseif (!in_array($fileExt, $allowedExts, true)) {
                    $error = 'Allowed image types: ' . implode(', ', $allowedExts) . '.';
                } else {
                    $uniqueName   = bin2hex(random_bytes(8)) . '.' . $fileExt;
                    $uploadedPath = 'upload/' . $uniqueName;

                    if (!move_uploaded_file($fileTmp, $uploadedPath)) {
                        $error = 'Failed to upload logo.';
                    } else {
                        $updated = $siteModel->updateSiteInfo($id, $title, $slogan, $uploadedPath);
                        if ($updated) {
                            header('Location: site_info_list.php');
                            exit();
                        } else {
                            $error = 'Failed to update site info.';
                        }
                    }
                }
            } else {
                $updated = $siteModel->updateSiteInfo($id, $title, $slogan);
                if ($updated) {
                    header('Location: site_info_list.php');
                    exit();
                } else {
                    $error = 'Failed to update site info.';
                }
            }
        }
    }
}

$post_result = $siteModel->getSiteInfoById($id);
if (!$post_result) {
    header('Location: site_info_list.php');
    exit();
}
?>

<div class="glass-card rounded-3xl p-6 sm:p-8 shadow-xl shadow-black/5 flex flex-col gap-6">
    <div class="flex flex-col gap-1 border-b border-white/5 pb-4">
        <h2 class="text-xl font-bold font-outfit text-white">Update Site Information</h2>
        <p class="text-slate-400 text-xs font-medium">Modify site logo, slogan and title parameters</p>
    </div>

    <?php if ($error): ?>
        <div class="bg-red-500/10 border border-red-500/20 text-red-350 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation shrink-0"></i>
            <span><?php echo Format::e($error); ?></span>
        </div>
    <?php endif; ?>

    <form action="" method="post" enctype="multipart/form-data" class="flex flex-col gap-5 max-w-xl">
        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrfToken(); ?>">

        <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">Website Logo Image</label>
            <div class="w-full bg-slate-900 border border-white/10 rounded-xl p-4 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <?php if ($post_result['logo']): ?>
                    <img src="<?php echo Format::e($post_result['logo']); ?>" 
                         class="h-16 w-32 rounded-lg object-contain border border-white/10 bg-slate-950 shrink-0" alt="Site Logo">
                <?php endif; ?>
                <div class="flex flex-col gap-2">
                    <input name="logo" type="file" class="text-sm text-slate-400 cursor-pointer" />
                    <span class="text-[10px] text-slate-500">Leave blank to keep current logo. Max: 1 MB.</span>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="title" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Website Title</label>
            <input type="text" value="<?php echo Format::e($post_result['title']); ?>" name="title" id="title" required
                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200" />
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="slogan" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Website Slogan</label>
            <input type="text" value="<?php echo Format::e($post_result['slogan']); ?>" name="slogan" id="slogan" required
                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200" />
        </div>

        <div class="mt-2 flex gap-3">
            <button type="submit" name="submit" 
                    class="px-6 py-3 bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white text-sm font-semibold rounded-xl transition-colors duration-200 cursor-pointer shadow-md shadow-brand-500/10">
                Update Site Info
            </button>
            <a href="site_info_list.php" 
               class="px-6 py-3 border border-white/10 hover:bg-white/5 text-slate-350 hover:text-white text-sm font-semibold rounded-xl transition-colors duration-200">
                Cancel
            </a>
        </div>
    </form>
</div>

<?php include '../admin/inc/footer.php'; ?>