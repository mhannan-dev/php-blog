<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>

<?php
// Only admins (role = '0') can add sliders
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
        $title = trim($_POST['title'] ?? '');

        // Image upload
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
        $file        = $_FILES['image'] ?? null;
        $fileName    = $file['name']     ?? '';
        $fileSize    = $file['size']     ?? 0;
        $fileTmp     = $file['tmp_name'] ?? '';
        $fileExt     = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($title === '' || $fileName === '') {
            $error = 'Fields must not be empty.';
        } elseif ($fileSize > 1_048_576) {
            $error = 'Image size must be less than 1 MB.';
        } elseif (!in_array($fileExt, $allowedExts, true)) {
            $error = 'Allowed image types: ' . implode(', ', $allowedExts) . '.';
        } else {
            $uniqueName   = bin2hex(random_bytes(8)) . '.' . $fileExt;
            $uploadedPath = 'upload/' . $uniqueName;

            if (!move_uploaded_file($fileTmp, $uploadedPath)) {
                $error = 'Failed to upload image.';
            } else {
                $inserted = $siteModel->createSlider($uploadedPath, $title);
                if ($inserted) {
                    header('Location: slider_list.php');
                    exit();
                } else {
                    $error = 'Slider Not Inserted!';
                }
            }
        }
    }
}
?>

<div class="glass-card rounded-3xl p-6 sm:p-8 shadow-xl shadow-black/5 flex flex-col gap-6">
    <div class="flex flex-col gap-1 border-b border-white/5 pb-4">
        <h2 class="text-xl font-bold font-outfit text-white">Add New Slider</h2>
        <p class="text-slate-400 text-xs font-medium">Create a homepage slideshow banner</p>
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
            <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">Slider Image</label>
            <div class="w-full bg-slate-900 border border-white/10 rounded-xl p-4 flex flex-col gap-2">
                <input name="image" type="file" class="text-sm text-slate-400 cursor-pointer" required />
                <span class="text-[10px] text-slate-500">Dimensions should ideally be W=960px & H=350px. Max: 1 MB.</span>
            </div>
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="title" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Description / Title</label>
            <input type="text" name="title" id="title" placeholder="Enter slider caption title..." 
                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 placeholder-slate-655 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200"
                   required value="<?php echo isset($_POST['title']) ? Format::e($_POST['title']) : ''; ?>" />
        </div>

        <div class="mt-2 flex gap-3">
            <button type="submit" name="submit" 
                    class="px-6 py-3 bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white text-sm font-semibold rounded-xl transition-colors duration-200 cursor-pointer shadow-md shadow-brand-500/10">
                Save Slider
            </button>
            <a href="slider_list.php" 
               class="px-6 py-3 border border-white/10 hover:bg-white/5 text-slate-350 hover:text-white text-sm font-semibold rounded-xl transition-colors duration-200">
                Cancel
            </a>
        </div>
    </form>
</div>

<?php include '../admin/inc/footer.php'; ?>