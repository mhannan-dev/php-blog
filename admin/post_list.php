<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>

<?php
$success = '';
$error   = '';

// Single delete action handler — only via GET param 'delpost'
if (isset($_GET['delpost']) && (int) $_GET['delpost'] > 0) {
    $delId = (int) $_GET['delpost'];
    
    $post = $postModel->getById($delId);
    if ($post) {
        $canDelete = ((int) Session::get('userId') === (int) $post['userid'] || Session::get('userRole') === '0');
        if (!$canDelete) {
            $error = 'You do not have permission to delete this post.';
        } else {
            $deleted = $postModel->delete($delId);
            if ($deleted) {
                $success = 'Post deleted successfully.';
            } else {
                $error = 'Failed to delete the post.';
            }
        }
    } else {
        $error = 'Post not found.';
    }
}
?>

<div class="glass-card rounded-3xl p-6 sm:p-8 shadow-xl shadow-black/5 flex flex-col gap-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-white/5 pb-4">
        <div class="flex flex-col gap-1">
            <h2 class="text-xl font-bold font-outfit text-white">Post List</h2>
            <p class="text-slate-400 text-xs font-medium">Manage published blog posts and articles</p>
        </div>
        <a href="addpost.php" 
           class="px-4 py-2 bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white text-xs font-bold rounded-xl transition-all duration-200 shadow-md shadow-brand-500/10 cursor-pointer flex items-center gap-1.5 shrink-0">
            <i class="fa-solid fa-plus-circle text-[10px]"></i> Create Post
        </a>
    </div>

    <?php if ($success): ?>
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-350 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check shrink-0"></i>
            <span><?php echo Format::e($success); ?></span>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="bg-red-500/10 border border-red-500/20 text-red-350 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation shrink-0"></i>
            <span><?php echo Format::e($error); ?></span>
        </div>
    <?php endif; ?>

    <div class="overflow-x-auto rounded-2xl border border-white/5 bg-slate-900/30">
        <table class="w-full text-left text-sm border-collapse">
            <thead>
                <tr class="border-b border-white/5 bg-slate-950/60 text-xs font-bold uppercase tracking-wider text-slate-400 whitespace-nowrap">
                    <th class="px-5 py-4">#</th>
                    <th class="px-5 py-4">Post Title</th>
                    <th class="px-5 py-4">Category</th>
                    <th class="px-5 py-4">Author</th>
                    <th class="px-5 py-4">Date</th>
                    <th class="px-5 py-4">Image</th>
                    <th class="px-5 py-4">Tags</th>
                    <th class="px-5 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php
                $posts  = $postModel->getAllWithCategory();
                $rowNum = 0;

                if ($posts && $posts->num_rows > 0) {
                    while ($post = $posts->fetch_assoc()) {
                        $rowNum++;
                        $canEdit = (
                            (int) Session::get('userId') === (int) $post['userid']
                            || Session::get('userRole') === '0'
                        );
                ?>
                <tr class="hover:bg-white/2 transition-colors duration-150">
                    <td class="px-5 py-4 text-slate-400 font-medium"><?php echo $rowNum; ?></td>
                    <td class="px-5 py-4 text-slate-200 font-bold max-w-[200px] truncate"><?php echo Format::e($post['title']); ?></td>
                    <td class="px-5 py-4 text-slate-300 font-medium"><?php echo Format::e($post['cat_name']); ?></td>
                    <td class="px-5 py-4 text-slate-350"><?php echo Format::e($post['author']); ?></td>
                    <td class="px-5 py-4 text-slate-400 whitespace-nowrap text-xs"><?php echo Format::e(Format::formatDate($post['date'])); ?></td>
                    <td class="px-5 py-4">
                        <?php if ($post['image']): ?>
                            <img src="<?php echo Format::e($post['image']); ?>"
                                 class="h-8 w-12 rounded-lg object-cover border border-white/10 bg-slate-950 shrink-0" alt="Post thumbnail" />
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-4 text-slate-400 max-w-[120px] truncate text-xs"><?php echo Format::e($post['tags']); ?></td>
                    <td class="px-5 py-4 text-right whitespace-nowrap text-xs font-bold flex items-center justify-end gap-2.5">
                        <a href="post_view.php?post_id=<?php echo (int) $post['id']; ?>" 
                           class="px-2.5 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white border border-white/5 transition-all duration-200">
                            View
                        </a>
                        <?php if ($canEdit): ?>
                            <a href="edit_post.php?post_id=<?php echo (int) $post['id']; ?>" 
                               class="px-2.5 py-1.5 rounded-lg bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 border border-blue-500/20 transition-all duration-200">
                                Edit
                            </a>
                            <a href="?delpost=<?php echo (int) $post['id']; ?>"
                               onclick="return confirm('Are you sure you want to delete this post?')"
                               class="px-2.5 py-1.5 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 border border-red-500/20 transition-all duration-200">
                                Delete
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="8" class="px-5 py-8 text-center text-slate-500 italic">No posts found in the list.</td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../admin/inc/footer.php'; ?>
