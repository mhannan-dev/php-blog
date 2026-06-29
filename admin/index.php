<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>

<?php
$totalPosts = $postModel->getTotalCount();
$unreadMsgs = $contactModel->getUnreadCount();
$cats       = $categoryModel->getAll();
$totalCats  = $cats ? $cats->num_rows : 0;
?>

<!-- Premium Dashboard Card -->
<div class="glass-card rounded-3xl p-6 sm:p-8 shadow-xl shadow-black/5 flex flex-col gap-8">
    
    <!-- Welcome Header -->
    <div class="flex flex-col gap-1 border-b border-white/5 pb-5">
        <h2 class="text-2xl sm:text-3xl font-extrabold font-outfit text-white tracking-tight">Dashboard Overview</h2>
        <p class="text-slate-400 text-sm font-medium">Welcome to your blog management panel. Here is a summary of your site status.</p>
    </div>

    <!-- Summary Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Metric Card: Posts -->
        <div class="bg-blue-600/10 border border-blue-500/20 rounded-2xl p-6 flex items-center justify-between shadow-md">
            <div class="flex flex-col gap-1.5">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Total Posts</span>
                <span class="text-3xl font-extrabold font-outfit text-white"><?php echo $totalPosts; ?></span>
            </div>
            <div class="h-12 w-12 rounded-xl bg-blue-600/20 text-blue-400 border border-blue-500/30 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-file-invoice"></i>
            </div>
        </div>

        <!-- Metric Card: Categories -->
        <div class="bg-indigo-600/10 border border-indigo-500/20 rounded-2xl p-6 flex items-center justify-between shadow-md">
            <div class="flex flex-col gap-1.5">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Categories</span>
                <span class="text-3xl font-extrabold font-outfit text-white"><?php echo $totalCats; ?></span>
            </div>
            <div class="h-12 w-12 rounded-xl bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-folder-tree"></i>
            </div>
        </div>

        <!-- Metric Card: Messages -->
        <div class="bg-emerald-600/10 border border-emerald-500/20 rounded-2xl p-6 flex items-center justify-between shadow-md">
            <div class="flex flex-col gap-1.5">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest">New Messages</span>
                <span class="text-3xl font-extrabold font-outfit text-white"><?php echo $unreadMsgs; ?></span>
            </div>
            <div class="h-12 w-12 rounded-xl bg-emerald-600/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-envelope-open-text"></i>
            </div>
        </div>

    </div>

    <!-- Quick Actions Panel -->
    <div class="flex flex-col gap-4 mt-2">
        <h3 class="text-lg font-bold font-outfit text-white flex items-center gap-2">
            <i class="fa-solid fa-bolt text-brand-400 text-base"></i> Quick Actions
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            <a href="addpost.php" 
               class="p-4 rounded-2xl border border-white/5 bg-white/2 hover:bg-white/5 text-slate-200 hover:text-white transition-all duration-200 flex items-center gap-3 text-sm font-medium">
                <i class="fa-solid fa-circle-plus text-brand-400"></i> Write New Post
            </a>
            <a href="post_list.php" 
               class="p-4 rounded-2xl border border-white/5 bg-white/2 hover:bg-white/5 text-slate-200 hover:text-white transition-all duration-200 flex items-center gap-3 text-sm font-medium">
                <i class="fa-solid fa-list-check text-brand-400"></i> Manage Articles
            </a>
            <a href="inbox.php" 
               class="p-4 rounded-2xl border border-white/5 bg-white/2 hover:bg-white/5 text-slate-200 hover:text-white transition-all duration-200 flex items-center gap-3 text-sm font-medium">
                <i class="fa-solid fa-inbox text-brand-400"></i> Check Inbox Messages
            </a>
        </div>
    </div>

</div>

<?php include '../admin/inc/footer.php'; ?>