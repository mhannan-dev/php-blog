<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>

<?php
// Only admins (role = '0') can view page list
if (Session::get('userRole') !== '0') {
    header('Location: index.php');
    exit();
}
?>

<div class="glass-card rounded-3xl p-6 sm:p-8 shadow-xl shadow-black/5 flex flex-col gap-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-white/5 pb-4">
        <div class="flex flex-col gap-1">
            <h2 class="text-xl font-bold font-outfit text-white">Dynamic Pages</h2>
            <p class="text-slate-400 text-xs font-medium">Review custom site layouts and navigation pages</p>
        </div>
        <a href="add_new_page.php" 
           class="px-4 py-2 bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white text-xs font-bold rounded-xl transition-all duration-200 shadow-md shadow-brand-500/10 cursor-pointer flex items-center gap-1.5 shrink-0">
            <i class="fa-solid fa-plus-circle text-[10px]"></i> Add Page
        </a>
    </div>

    <div class="overflow-x-auto rounded-2xl border border-white/5 bg-slate-900/30">
        <table class="w-full text-left text-sm border-collapse">
            <thead>
                <tr class="border-b border-white/5 bg-slate-950/60 text-xs font-bold uppercase tracking-wider text-slate-400">
                    <th class="px-6 py-4">SL.</th>
                    <th class="px-6 py-4">Page Title</th>
                    <th class="px-6 py-4">Description Snippet</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5 text-slate-300">
                <?php
                $pages = $pageModel->getAll();
                if ($pages && $pages->num_rows > 0) {
                    $i = 0;
                    while ($result = $pages->fetch_assoc()) {
                        $i++;
                        ?>
                        <tr class="hover:bg-white/2 transition-colors duration-150">
                            <td class="px-6 py-4 text-slate-400 font-medium"><?php echo $i; ?></td>
                            <td class="px-6 py-4 text-slate-200 font-bold"><?php echo Format::e($result['name']); ?></td>
                            <td class="px-6 py-4 text-slate-350 max-w-[300px] truncate"><?php echo Format::e(Format::textShorten(strip_tags($result['body']), 90)); ?></td>
                            <td class="px-6 py-4 text-right text-xs font-bold">
                                <a href="page.php?page_id=<?php echo (int) $result['id']; ?>" 
                                   class="px-3 py-1.5 rounded-lg bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 border border-blue-500/20 transition-all duration-200">
                                    Manage
                                </a>
                            </td>
                        </tr>
                        <?php 
                    } 
                } else {
                    ?>
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-500 italic">No custom pages created yet.</td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../admin/inc/footer.php'; ?>
