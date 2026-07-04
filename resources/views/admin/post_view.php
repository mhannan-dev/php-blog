<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>

<?php 
if (!isset($_GET['post_id']) || (int) $_GET['post_id'] <= 0) {
    header('Location: post_list.php');
    exit();
}

$id = (int) $_GET['post_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Location: post_list.php');
    exit();
}

$post_result = $postModel->getById($id);
if (!$post_result) {
    header('Location: post_list.php');
    exit();
}
?>

<div class="glass-card rounded-3xl p-6 sm:p-8 shadow-xl shadow-black/5 flex flex-col gap-6">
    <div class="flex flex-col gap-1 border-b border-white/5 pb-4">
        <h2 class="text-xl font-bold font-outfit text-white">View Post Details</h2>
        <p class="text-slate-400 text-xs font-medium">Read-only presentation of post contents</p>
    </div>

    <form action="" method="post" class="flex flex-col gap-5">
        <!-- Row 1: Title & Author -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="flex flex-col gap-1.5">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-450">Post Title</span>
                <div class="w-full bg-slate-900/50 border border-white/5 rounded-xl px-4 py-3 text-sm text-slate-300 font-medium">
                    <?php echo Format::e($post_result['title']); ?>
                </div>
            </div>
            
            <div class="flex flex-col gap-1.5">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-455">Author Name</span>
                <div class="w-full bg-slate-900/50 border border-white/5 rounded-xl px-4 py-3 text-sm text-slate-350">
                    <?php echo Format::e($post_result['author']); ?>
                </div>
            </div>
        </div>

        <!-- Row 2: Category & Tags -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="flex flex-col gap-1.5">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-455">Category ID</span>
                <div class="w-full bg-slate-900/50 border border-white/5 rounded-xl px-4 py-3 text-sm text-slate-350">
                    <?php
                    $catData = $categoryModel->getById((int) $post_result['cat']);
                    echo $catData ? Format::e($catData['name']) : 'None';
                    ?>
                </div>
            </div>
            
            <div class="flex flex-col gap-1.5">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-455">SEO Tags</span>
                <div class="w-full bg-slate-900/50 border border-white/5 rounded-xl px-4 py-3 text-sm text-slate-350">
                    <?php echo Format::e($post_result['tags']); ?>
                </div>
            </div>
        </div>

        <!-- Featured Image -->
        <?php if ($post_result['image']): ?>
            <div class="flex flex-col gap-1.5">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-455">Featured Image</span>
                <div class="rounded-xl overflow-hidden max-w-sm aspect-[16/10] bg-slate-900 border border-white/5">
                    <img src="<?php echo Format::e($post_result['image']); ?>" class="w-full h-full object-cover" alt="Post featured image" />
                </div>
            </div>
        <?php endif; ?>

        <!-- Post Body -->
        <div class="flex flex-col gap-1.5">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-455">Post Content Body</span>
            <div class="w-full bg-slate-900/50 border border-white/5 rounded-2xl p-6 text-sm text-slate-300 leading-relaxed max-h-[300px] overflow-y-auto">
                <?php echo $post_result['body']; ?>
            </div>
        </div>

        <div class="mt-2">
            <button type="submit" name="submit" 
                    class="px-6 py-3 bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white text-sm font-semibold rounded-xl transition-colors duration-200 cursor-pointer shadow-md shadow-brand-500/10">
                Back to Post List
            </button>
        </div>
    </form>
</div>

<?php include '../admin/inc/footer.php'; ?>