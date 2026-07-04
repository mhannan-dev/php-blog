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
        $title  = trim($_POST['title']  ?? '');
        $body   = trim($_POST['body']   ?? '');
        $cat    = (int) ($_POST['cat']    ?? 0);
        $author = trim($_POST['author'] ?? '');
        $tags   = trim($_POST['tags']   ?? '');
        $userId = (int) Session::get('userId');

        // Image upload
        $allowedExts  = ['jpg', 'jpeg', 'png', 'gif'];
        $file         = $_FILES['image'] ?? null;
        $fileName     = $file['name']     ?? '';
        $fileSize     = $file['size']     ?? 0;
        $fileTmp      = $file['tmp_name'] ?? '';
        $fileExt      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($title === '' || $body === '' || $cat <= 0 || $author === '' || $fileName === '' || $tags === '') {
            $error = 'All fields are required.';
        } elseif ($fileSize > 1_048_576) {
            $error = 'Image size must be less than 1 MB.';
        } elseif (!in_array($fileExt, $allowedExts, true)) {
            $error = 'Allowed image types: ' . implode(', ', $allowedExts) . '.';
        } else {
            $uniqueName   = bin2hex(random_bytes(8)) . '.' . $fileExt;
            $uploadedPath = 'upload/' . $uniqueName;

            if (!move_uploaded_file($fileTmp, $uploadedPath)) {
                $error = 'Failed to upload image. Check folder permissions.';
            } else {
                $inserted = $postModel->create($title, $body, $cat, $author, $uploadedPath, $tags, $userId);

                if ($inserted) {
                    header('Location: post_list.php');
                    exit();
                } else {
                    $error = 'Failed to save the post. Please try again.';
                }
            }
        }
    }
}
?>

<div class="glass-card rounded-3xl p-6 sm:p-8 shadow-xl shadow-black/5 flex flex-col gap-6">
    <div class="flex flex-col gap-1 border-b border-white/5 pb-4">
        <h2 class="text-xl font-bold font-outfit text-white">Add New Post</h2>
        <p class="text-slate-400 text-xs font-medium">Create a new blog article to publish</p>
    </div>

    <?php if ($error): ?>
        <div class="bg-red-500/10 border border-red-500/20 text-red-350 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation shrink-0"></i>
            <span><?php echo Format::e($error); ?></span>
        </div>
    <?php endif; ?>

    <form action="" method="post" enctype="multipart/form-data" class="flex flex-col gap-5">
        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrfToken(); ?>">

        <!-- Row 1: Title & Author -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="flex flex-col gap-1.5">
                <label for="post-title" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Post Title</label>
                <input type="text" id="post-title" name="title" placeholder="Enter post title..." 
                       class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 placeholder-slate-655 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200"
                       value="<?php echo isset($_POST['title']) ? Format::e($_POST['title']) : ''; ?>" required />
            </div>
            
            <div class="flex flex-col gap-1.5">
                <label for="post-author" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Author Name</label>
                <input type="text" id="post-author" name="author" 
                       class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200"
                       value="<?php echo Format::e(Session::get('userName')); ?>" required />
            </div>
        </div>

        <!-- Row 2: Category & Tags -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="flex flex-col gap-1.5">
                <label for="post-cat" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Category</label>
                <select id="post-cat" name="cat" 
                        class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200" required>
                    <option value="">— Select Category —</option>
                    <?php
                    $categories = $categoryModel->getAll();
                    if ($categories) {
                        while ($category = $categories->fetch_assoc()) {
                            $selected = (isset($_POST['cat']) && (int)$_POST['cat'] === (int)$category['id']) ? 'selected' : '';
                            echo '<option value="' . (int) $category['id'] . '" ' . $selected . '>'
                               . Format::e($category['name']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            
            <div class="flex flex-col gap-1.5">
                <label for="post-tags" class="text-xs font-semibold uppercase tracking-wider text-slate-400">SEO Tags (comma-separated)</label>
                <input type="text" id="post-tags" name="tags" placeholder="e.g. php, tutorials, design" 
                       class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 placeholder-slate-655 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200"
                       value="<?php echo isset($_POST['tags']) ? Format::e($_POST['tags']) : ''; ?>" required />
            </div>
        </div>

        <!-- Row 3: Image Upload -->
        <div class="flex flex-col gap-1.5">
            <label for="post-image" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Featured Image</label>
            <div class="w-full bg-slate-900 border border-white/10 rounded-xl p-4 flex flex-col gap-2">
                <input id="post-image" name="image" type="file" class="text-sm text-slate-400 cursor-pointer" required />
                <span class="text-[11px] text-slate-500">Allowed formats: JPG, JPEG, PNG, GIF. Max size: 1 MB.</span>
            </div>
        </div>

        <!-- Row 4: TinyMCE Editor -->
        <div class="flex flex-col gap-1.5">
            <label for="mytextarea" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Article Content</label>
            <textarea id="mytextarea" class="tinymce" name="body"><?php echo isset($_POST['body']) ? Format::e($_POST['body']) : ''; ?></textarea>
        </div>

        <div class="mt-2 flex gap-3">
            <button type="submit" name="submit" 
                    class="px-6 py-3 bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white text-sm font-semibold rounded-xl transition-colors duration-200 cursor-pointer shadow-md shadow-brand-500/10">
                Publish Post
            </button>
            <a href="post_list.php" 
               class="px-6 py-3 border border-white/10 hover:bg-white/5 text-slate-350 hover:text-white text-sm font-semibold rounded-xl transition-colors duration-200">
                Cancel
            </a>
        </div>
    </form>
</div>

<?php include '../admin/inc/footer.php'; ?>