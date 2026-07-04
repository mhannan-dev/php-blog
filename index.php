<?php
require_once __DIR__ . '/app/bootstrap.php';

$perPage     = 5;
$currentPage = max(1, (int) ($_GET['page'] ?? 1));
$offset      = ($currentPage - 1) * $perPage;
$totalPosts  = $postModel->getTotalCount();
$totalPages  = (int) ceil($totalPosts / $perPage);
$posts       = $postModel->getPaginated($offset, $perPage);
?>

<?php include './inc/header.php'; ?>

<!-- Slider Included inside Main Content Column -->
<?php include './inc/slider.php'; ?>

<div class="flex flex-col gap-8">
    <?php if ($posts): while ($post = $posts->fetch_assoc()): ?>
        <!-- Premium Post Card -->
        <article class="glass-card rounded-3xl overflow-hidden shadow-lg shadow-black/5 hover:shadow-brand-500/5 hover:-translate-y-1 transition-all duration-300 flex flex-col md:flex-row gap-6 p-6">
            
            <?php if ($post['image']): ?>
                <!-- Post Thumbnail -->
                <div class="w-full md:w-2/5 shrink-0 rounded-2xl overflow-hidden aspect-[16/10] bg-slate-900 border border-white/5 relative group">
                    <a href="post.php?slug=<?php echo Format::e($post['slug']); ?>" class="block w-full h-full">
                        <img src="admin/<?php echo Format::e($post['image']); ?>"
                             alt="<?php echo Format::e($post['title']); ?>"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                    </a>
                </div>
            <?php endif; ?>

            <!-- Post Body info -->
            <div class="flex flex-col justify-between py-1 flex-grow gap-4">
                <div class="flex flex-col gap-3">
                    <!-- Title -->
                    <h2 class="text-xl sm:text-2xl font-bold font-outfit text-white leading-snug hover:text-brand-300 transition-colors duration-200">
                        <a href="post.php?slug=<?php echo Format::e($post['slug']); ?>">
                            <?php echo Format::e($post['title']); ?>
                        </a>
                    </h2>
                    
                    <!-- Metadata Info -->
                    <div class="flex items-center gap-3 text-xs text-slate-400 font-medium tracking-wide">
                        <span class="flex items-center gap-1.5">
                            <i class="fa-regular fa-user text-brand-400"></i>
                            <?php echo Format::e($post['author']); ?>
                        </span>
                        <span class="w-1 h-1 rounded-full bg-slate-700"></span>
                        <span class="flex items-center gap-1.5">
                            <i class="fa-regular fa-calendar text-brand-400"></i>
                            <?php echo Format::e(Format::formatDate($post['date'])); ?>
                        </span>
                    </div>

                    <!-- Description Snippet -->
                    <p class="text-slate-350 text-sm leading-relaxed line-clamp-3">
                        <?php echo Format::e(Format::textShorten(strip_tags($post['body']), 220)); ?>
                    </p>
                </div>

                <!-- Footer details -->
                <div class="flex justify-between items-center mt-2 border-t border-white/5 pt-4">
                    <a href="post.php?slug=<?php echo Format::e($post['slug']); ?>" 
                       class="inline-flex items-center gap-1.5 text-xs font-semibold text-brand-400 hover:text-brand-300 transition-colors duration-200 uppercase tracking-wider">
                        Read Full Article <i class="fa-solid fa-arrow-right-long"></i>
                    </a>
                </div>
            </div>
        </article>
    <?php endwhile; else: ?>
        <div class="glass-card rounded-2xl p-8 text-center text-slate-400 shadow-md">
            <i class="fa-solid fa-folder-open text-slate-600 text-3xl mb-3"></i>
            <p>No posts found in the database.</p>
        </div>
    <?php endif; ?>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <nav class="flex justify-center items-center gap-2 mt-4">
            <?php if ($currentPage > 1): ?>
                <a href="?page=<?php echo $currentPage - 1; ?>" 
                   class="h-10 px-4 rounded-xl border border-white/10 text-sm font-semibold flex items-center text-slate-300 hover:bg-white/5 hover:text-white transition-colors duration-200">&laquo; Prev</a>
            <?php endif; ?>

            <div class="flex items-center gap-1.5">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i === $currentPage): ?>
                        <span class="h-10 w-10 rounded-xl bg-brand-500 text-white font-bold flex items-center justify-center text-sm ring-4 ring-brand-500/20"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?page=<?php echo $i; ?>" 
                           class="h-10 w-10 rounded-xl border border-white/10 text-slate-300 font-semibold flex items-center justify-center text-sm hover:bg-white/5 hover:text-white transition-colors duration-250"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>

            <?php if ($currentPage < $totalPages): ?>
                <a href="?page=<?php echo $currentPage + 1; ?>" 
                   class="h-10 px-4 rounded-xl border border-white/10 text-sm font-semibold flex items-center text-slate-300 hover:bg-white/5 hover:text-white transition-colors duration-200">Next &raquo;</a>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
</div>

<?php include './inc/footer.php'; ?>