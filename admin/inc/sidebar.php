<aside class="w-full md:w-1/4 shrink-0 flex flex-col gap-6">

    <!-- Admin Operations Menu -->
    <div class="glass-card rounded-2xl p-5 shadow-lg shadow-black/10 flex flex-col gap-5">
        
        <!-- Section: Content Manager -->
        <div>
            <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3 font-sans px-2">Content Options</h4>
            <ul class="flex flex-col gap-1 text-sm font-medium">
                <li>
                    <a href="addpost.php" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-350 hover:text-white hover:bg-white/5 transition-all duration-200">
                        <i class="fa-solid fa-plus-circle text-brand-400 text-xs"></i> Add Post
                    </a>
                </li>
                <li>
                    <a href="post_list.php" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-350 hover:text-white hover:bg-white/5 transition-all duration-200">
                        <i class="fa-solid fa-file-invoice text-brand-400 text-xs"></i> Post List
                    </a>
                </li>
                <li>
                    <a href="addcat.php" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-350 hover:text-white hover:bg-white/5 transition-all duration-200">
                        <i class="fa-solid fa-folder-plus text-brand-400 text-xs"></i> Add Category
                    </a>
                </li>
                <li>
                    <a href="catlist.php" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-350 hover:text-white hover:bg-white/5 transition-all duration-200">
                        <i class="fa-solid fa-folder-tree text-brand-400 text-xs"></i> Category List
                    </a>
                </li>
            </ul>
        </div>

        <!-- Section: Configuration Manager (Admins Only) -->
        <?php if (Session::get('userRole') === '0'): ?>
        <div>
            <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3 font-sans px-2">Site Settings</h4>
            <ul class="flex flex-col gap-1 text-sm font-medium">
                <li>
                    <a href="titleslogan.php" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-350 hover:text-white hover:bg-white/5 transition-all duration-200">
                        <i class="fa-solid fa-heading text-brand-400 text-xs"></i> Title & Logo
                    </a>
                </li>
                <li>
                    <a href="site_info_list.php" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-350 hover:text-white hover:bg-white/5 transition-all duration-200">
                        <i class="fa-solid fa-circle-info text-brand-400 text-xs"></i> Site Info List
                    </a>
                </li>
                <li>
                    <a href="social_add.php" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-350 hover:text-white hover:bg-white/5 transition-all duration-200">
                        <i class="fa-solid fa-share-nodes text-brand-400 text-xs"></i> Add Social Link
                    </a>
                </li>
                <li>
                    <a href="social_list.php" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-350 hover:text-white hover:bg-white/5 transition-all duration-200">
                        <i class="fa-solid fa-globe text-brand-400 text-xs"></i> Social Media List
                    </a>
                </li>
                <li>
                    <a href="add_slider.php" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-350 hover:text-white hover:bg-white/5 transition-all duration-200">
                        <i class="fa-solid fa-image text-brand-400 text-xs"></i> New Slider
                    </a>
                </li>
                <li>
                    <a href="slider_list.php" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-350 hover:text-white hover:bg-white/5 transition-all duration-200">
                        <i class="fa-solid fa-images text-brand-400 text-xs"></i> Slider List
                    </a>
                </li>
                <li>
                    <a href="copyright.php" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-350 hover:text-white hover:bg-white/5 transition-all duration-200">
                        <i class="fa-regular fa-copyright text-brand-400 text-xs"></i> Copyright Note
                    </a>
                </li>
            </ul>
        </div>
        <?php endif; ?>

        <!-- Section: Pages Manager -->
        <div>
            <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3 font-sans px-2">Dynamic Pages</h4>
            <ul class="flex flex-col gap-1 text-sm font-medium">
                <?php if (Session::get('userRole') === '0'): ?>
                    <li>
                        <a href="add_new_page.php" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-350 hover:text-white hover:bg-white/5 transition-all duration-200">
                            <i class="fa-solid fa-file-circle-plus text-brand-400 text-xs"></i> Add New Page
                        </a>
                    </li>
                <?php endif; ?>
                <?php
                $pages = $pageModel->getAll();
                if ($pages) {
                    while ($result = $pages->fetch_assoc()) {
                        ?>
                        <li>
                            <a href="page.php?page_id=<?php echo (int) $result['id']; ?>" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-350 hover:text-white hover:bg-white/5 transition-all duration-200">
                                <i class="fa-regular fa-file-lines text-brand-400 text-xs"></i> <?php echo Format::e($result['name']); ?>
                            </a>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>
        </div>

        <!-- Section: User Account Management -->
        <div>
            <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3 font-sans px-2">Account Options</h4>
            <ul class="flex flex-col gap-1 text-sm font-medium">
                <li>
                    <a href="profile.php" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-350 hover:text-white hover:bg-white/5 transition-all duration-200">
                        <i class="fa-regular fa-user text-brand-400 text-xs"></i> Edit Profile
                    </a>
                </li>
                <li>
                    <a href="change_password.php" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-350 hover:text-white hover:bg-white/5 transition-all duration-200">
                        <i class="fa-solid fa-key text-brand-400 text-xs"></i> Change Password
                    </a>
                </li>
            </ul>
        </div>

    </div>
</aside>