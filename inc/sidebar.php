<?php
$sidebarCats   = $categoryModel->getAll();
$sidebarPosts  = $postModel->getLatest(5);
$sidebarPages  = $pageModel->getAll();
?>

<aside class="w-full lg:w-1/4 flex flex-col gap-6 shrink-0">

    <!-- Categories Card -->
    <div class="glass-card rounded-2xl p-6 shadow-md shadow-black/10">
        <h3 class="text-white font-outfit font-semibold text-lg border-b border-white/5 pb-3 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-list text-brand-400 text-sm"></i> Categories
        </h3>
        <ul class="flex flex-col gap-2">
            <?php if ($sidebarCats): while ($cat = $sidebarCats->fetch_assoc()): ?>
                <li>
                    <a href="category/<?php echo Format::e($cat['slug']); ?>" 
                       class="flex items-center justify-between text-sm text-slate-300 hover:text-white hover:translate-x-1 transition-all duration-200 py-1">
                        <span><?php echo Format::e($cat['name']); ?></span>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-500"></i>
                    </a>
                </li>
            <?php endwhile; endif; ?>
        </ul>
    </div>

    <!-- Latest Articles Card -->
    <div class="glass-card rounded-2xl p-6 shadow-md shadow-black/10">
        <h3 class="text-white font-outfit font-semibold text-lg border-b border-white/5 pb-3 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-fire text-brand-400 text-sm"></i> Latest Articles
        </h3>
        <ul class="flex flex-col gap-4">
            <?php if ($sidebarPosts): while ($post = $sidebarPosts->fetch_assoc()): ?>
                <li>
                    <a href="post.php?slug=<?php echo Format::e($post['slug']); ?>" class="flex items-center gap-3 group">
                        <?php if ($post['image']): ?>
                            <img src="admin/<?php echo Format::e($post['image']); ?>" 
                                 class="h-12 w-12 rounded-lg object-cover bg-slate-800 transition-transform duration-200 group-hover:scale-105 shrink-0" 
                                 alt="Post Thumbnail" />
                        <?php endif; ?>
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-slate-300 group-hover:text-white line-clamp-2 transition-colors duration-200">
                                <?php echo Format::e($post['title']); ?>
                            </span>
                        </div>
                    </a>
                </li>
            <?php endwhile; endif; ?>
        </ul>
    </div>

    <!-- Pages Card -->
    <div class="glass-card rounded-2xl p-6 shadow-md shadow-black/10">
        <h3 class="text-white font-outfit font-semibold text-lg border-b border-white/5 pb-3 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-file-lines text-brand-400 text-sm"></i> Pages
        </h3>
        <ul class="flex flex-col gap-2">
            <?php if ($sidebarPages): while ($page = $sidebarPages->fetch_assoc()): ?>
                <li>
                    <a href="page.php?slug=<?php echo Format::e($page['slug']); ?>" 
                       class="flex items-center justify-between text-sm text-slate-300 hover:text-white hover:translate-x-1 transition-all duration-200 py-1">
                        <span><?php echo Format::e($page['name']); ?></span>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-500"></i>
                    </a>
                </li>
            <?php endwhile; endif; ?>
        </ul>
    </div>

</aside>
