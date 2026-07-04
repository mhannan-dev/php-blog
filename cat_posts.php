<?php
require_once __DIR__ . '/app/bootstrap.php';

$catParam = trim($_GET['cat_post'] ?? '');

if (empty($catParam)) {
    header('Location: index.php');
    exit();
}

$category = $categoryModel->getByParam($catParam);

if (!$category) {
    header('Location: 404.php');
    exit();
}

$catPosts = $postModel->getByCategory((int) $category['id']);
?>

<?php include './inc/header.php'; ?>

<div class="flex flex-col gap-6">
    <!-- Category Title header -->
    <div class="glass-card rounded-2xl p-6 shadow-md shadow-black/10">
        <h2 class="text-xl font-bold font-outfit text-white flex items-center gap-2">
            <i class="fa-solid fa-folder text-brand-400 text-sm"></i> Category: <span class="gradient-text"><?php echo Format::e($category['name']); ?></span>
        </h2>
    </div>

    <!-- Category Posts feed -->
    <?php if ($catPosts && $catPosts->num_rows > 0): ?>
        <div class="flex flex-col gap-6">
            <?php while ($post = $catPosts->fetch_assoc()): ?>
                <article class="glass-card rounded-3xl overflow-hidden shadow-lg shadow-black/5 hover:-translate-y-0.5 transition-all duration-200 flex flex-col md:flex-row gap-6 p-6">
                    
                    <?php if ($post['image']): ?>
                        <div class="w-full md:w-1/3 shrink-0 rounded-2xl overflow-hidden aspect-[16/10] bg-slate-900 border border-white/5 relative group">
                            <a href="post.php?slug=<?php echo Format::e($post['slug']); ?>" class="block w-full h-full">
                                <img src="admin/<?php echo Format::e($post['image']); ?>"
                                     alt="<?php echo Format::e($post['title']); ?>"
                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="flex flex-col justify-between py-1 flex-grow gap-3">
                        <div class="flex flex-col gap-2">
                            <h3 class="text-xl font-bold font-outfit text-white hover:text-brand-300 transition-colors duration-200 leading-snug">
                                <a href="post.php?slug=<?php echo Format::e($post['slug']); ?>">
                                    <?php echo Format::e($post['title']); ?>
                                </a>
                            </h3>
                            
                            <div class="flex items-center gap-3 text-xs text-slate-400 font-medium">
                                <span><i class="fa-regular fa-user text-brand-400 mr-1"></i><?php echo Format::e($post['author']); ?></span>
                                <span class="w-1 h-1 rounded-full bg-slate-700"></span>
                                <span><i class="fa-regular fa-calendar text-brand-400 mr-1"></i><?php echo Format::e(Format::formatDate($post['date'])); ?></span>
                            </div>

                            <p class="text-slate-350 text-sm leading-relaxed line-clamp-2">
                                <?php echo Format::e(Format::textShorten(strip_tags($post['body']), 180)); ?>
                            </p>
                        </div>

                        <a href="post.php?slug=<?php echo Format::e($post['slug']); ?>" 
                           class="inline-flex items-center gap-1.5 text-xs font-semibold text-brand-400 hover:text-brand-300 transition-colors duration-205 uppercase tracking-wider">
                            Read More <i class="fa-solid fa-arrow-right-long"></i>
                        </a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="glass-card rounded-3xl p-12 text-center text-slate-400 shadow-md">
            <i class="fa-solid fa-folder-open text-slate-600 text-3xl mb-3"></i>
            <p>No posts in this category yet.</p>
        </div>
    <?php endif; ?>
</div>

<?php include './inc/footer.php'; ?>