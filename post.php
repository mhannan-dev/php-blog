<?php
require_once __DIR__ . '/app/bootstrap.php';

if (!isset($_GET['id']) || (int) $_GET['id'] <= 0) {
    header('Location: index.php');
    exit();
}

$postId = (int) $_GET['id'];
$post   = $postModel->getById($postId);

if (!$post) {
    header('Location: 404.php');
    exit();
}

$relatedPosts = $postModel->getRelated((int) $post['cat'], $postId, 6);
?>

<?php include './inc/header.php'; ?>

<article class="flex flex-col gap-6">
    
    <!-- Main Article Body -->
    <div class="glass-card rounded-3xl p-6 sm:p-8 shadow-lg shadow-black/5">
        <div class="flex flex-col gap-4">
            <!-- Title -->
            <h1 class="text-2xl sm:text-4xl font-extrabold font-outfit text-white leading-tight">
                <?php echo Format::e($post['title']); ?>
            </h1>

            <!-- Meta details -->
            <div class="flex items-center gap-3 text-xs text-slate-400 font-medium tracking-wide border-b border-white/5 pb-4">
                <span class="flex items-center gap-1.5">
                    <i class="fa-regular fa-user text-brand-400"></i>
                    Post By: <strong><?php echo Format::e($post['author']); ?></strong>
                </span>
                <span class="w-1 h-1 rounded-full bg-slate-700"></span>
                <span class="flex items-center gap-1.5">
                    <i class="fa-regular fa-calendar text-brand-400"></i>
                    <?php echo Format::e(Format::formatDate($post['date'])); ?>
                </span>
            </div>

            <?php if ($post['image']): ?>
                <!-- Large Post Image -->
                <div class="w-full rounded-2xl overflow-hidden aspect-[16/9] bg-slate-900 border border-white/5 mt-2">
                    <img src="admin/<?php echo Format::e($post['image']); ?>"
                         alt="<?php echo Format::e($post['title']); ?>"
                         class="w-full h-full object-cover" />
                </div>
            <?php endif; ?>

            <!-- Rich Body Content -->
            <div class="text-slate-300 text-base leading-relaxed mt-4 prose prose-invert max-w-none prose-headings:text-white prose-a:text-brand-400">
                <?php echo $post['body']; ?>
            </div>
        </div>
    </div>

    <!-- Related Articles -->
    <?php if ($relatedPosts && $relatedPosts->num_rows > 0): ?>
        <div class="flex flex-col gap-4 mt-6">
            <h3 class="text-xl font-bold font-outfit text-white flex items-center gap-2">
                <i class="fa-solid fa-link text-brand-400 text-sm"></i> Related Articles
            </h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <?php while ($related = $relatedPosts->fetch_assoc()): ?>
                    <div class="glass-card rounded-2xl p-4 overflow-hidden shadow-md shadow-black/5 hover:shadow-brand-500/5 hover:-translate-y-0.5 transition-all duration-200 flex flex-col gap-3">
                        <a href="post.php?id=<?php echo (int) $related['id']; ?>" class="block rounded-xl overflow-hidden aspect-[16/10] bg-slate-900 border border-white/5 relative group">
                            <img src="admin/<?php echo Format::e($related['image']); ?>"
                                 alt="<?php echo Format::e($related['title']); ?>"
                                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                        </a>
                        <h4 class="font-bold text-sm text-slate-200 hover:text-white transition-colors duration-200 line-clamp-2">
                            <a href="post.php?id=<?php echo (int) $related['id']; ?>">
                                <?php echo Format::e(Format::textShorten($related['title'], 50)); ?>
                            </a>
                        </h4>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    <?php endif; ?>

</article>

<?php include './inc/footer.php'; ?>