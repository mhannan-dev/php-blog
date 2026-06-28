<?php
$footerNote   = $siteModel->getFooterNote();
$footerCats   = $categoryModel->getAll();
$footerPages  = $pageModel->getAll();
?>

    </div><!-- .flex-grow -->
    
    <!-- Sidebar -->
    <?php include __DIR__ . '/sidebar.php'; ?>
    
</main><!-- .max-w-7xl -->

<!-- Premium Footer -->
<footer class="w-full bg-slate-950 border-t border-white/5 py-12 mt-16 px-4">
    <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        
        <!-- About Column -->
        <div class="flex flex-col gap-3">
            <h4 class="text-white font-outfit font-bold text-lg tracking-tight">
                <?php echo Format::e($siteTitle); ?>
            </h4>
            <p class="text-slate-400 text-sm leading-relaxed max-w-sm">
                <?php echo Format::e($siteSlogan); ?>
            </p>
        </div>

        <!-- Categories Column -->
        <div class="flex flex-col gap-3">
            <h4 class="text-white font-outfit font-bold text-sm tracking-wider uppercase text-slate-300">Categories</h4>
            <ul class="grid grid-cols-2 gap-2 text-sm text-slate-450">
                <?php if ($footerCats): while ($cat = $footerCats->fetch_assoc()): ?>
                    <li>
                        <a href="cat_posts.php?cat_post=<?php echo (int) $cat['id']; ?>" 
                           class="text-slate-400 hover:text-white transition-colors duration-200 py-0.5 block">
                            <?php echo Format::e($cat['name']); ?>
                        </a>
                    </li>
                <?php endwhile; endif; ?>
            </ul>
        </div>

        <!-- Pages Column -->
        <div class="flex flex-col gap-3">
            <h4 class="text-white font-outfit font-bold text-sm tracking-wider uppercase text-slate-300">Pages</h4>
            <ul class="grid grid-cols-2 gap-2 text-sm">
                <?php if ($footerPages): while ($page = $footerPages->fetch_assoc()): ?>
                    <li>
                        <a href="page.php?page_id=<?php echo (int) $page['id']; ?>" 
                           class="text-slate-400 hover:text-white transition-colors duration-200 py-0.5 block">
                            <?php echo Format::e($page['name']); ?>
                        </a>
                    </li>
                <?php endwhile; endif; ?>
            </ul>
        </div>

    </div>
    <!-- Copyright Bar -->
    <div class="max-w-7xl mx-auto border-t border-white/5 mt-10 pt-6 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-slate-500">
        <p>
            <?php echo $footerNote ? Format::e($footerNote['note']) : '&copy; All Rights Reserved.'; ?>
        </p>
        <div class="flex gap-4">
            <a href="admin/login.php" class="hover:text-brand-400 transition-colors duration-250">Admin Area</a>
        </div>
    </div>
</footer>
</body>
</html>