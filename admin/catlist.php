<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>

<?php
$error   = '';
$success = '';

if (isset($_GET['delcat'])) {
    $delId = (int) $_GET['delcat'];
    if ($delId > 0) {
        $deleted = $categoryModel->delete($delId);
        if ($deleted) {
            $success = 'Category deleted successfully.';
        } else {
            $error = 'Category not deleted successfully.';
        }
    }
}
?>

<div class="glass-card rounded-3xl p-6 sm:p-8 shadow-xl shadow-black/5 flex flex-col gap-6">
    <div class="flex flex-col gap-1 border-b border-white/5 pb-4">
        <h2 class="text-xl font-bold font-outfit text-white">Category List</h2>
        <p class="text-slate-400 text-xs font-medium">View and manage blog article categories</p>
    </div>
    
    <?php if ($error): ?>
        <div class="bg-red-500/10 border border-red-500/20 text-red-350 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation shrink-0"></i>
            <span><?php echo Format::e($error); ?></span>
        </div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-350 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check shrink-0"></i>
            <span><?php echo Format::e($success); ?></span>
        </div>
    <?php endif; ?>

    <div class="overflow-x-auto rounded-2xl border border-white/5 bg-slate-900/30">        
        <table class="w-full text-left text-sm border-collapse">
            <thead>
                <tr class="border-b border-white/5 bg-slate-950/60 text-xs font-bold uppercase tracking-wider text-slate-400">
                    <th class="px-6 py-4">Serial No.</th>
                    <th class="px-6 py-4">Category Name</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php
                $categories = $categoryModel->getAll();
                if ($categories && $categories->num_rows > 0) {
                    $i = 0;
                    while ($result = $categories->fetch_assoc()) {
                        $i++;
                        ?>
                        <tr class="hover:bg-white/2 transition-colors duration-150">
                            <td class="px-6 py-4 text-slate-400 font-medium"><?php echo $i; ?></td>
                            <td class="px-6 py-4 text-slate-200 font-semibold"><?php echo Format::e($result['name']); ?></td>
                            <td class="px-6 py-4 text-right flex items-center justify-end gap-3 text-xs font-bold">
                                <?php if (Session::get('userRole') === '0') { ?>
                                    <a href="editcat.php?cat_id=<?php echo (int) $result['id']; ?>" 
                                       class="px-3 py-1.5 rounded-lg bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 border border-blue-500/20 transition-all duration-200">
                                        Edit
                                    </a> 
                                    <a onclick="return confirm('Are you sure want to delete this item?')" 
                                       href="?delcat=<?php echo (int) $result['id']; ?>"
                                       class="px-3 py-1.5 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 border border-red-500/20 transition-all duration-200">
                                        Delete
                                    </a>
                                <?php } else { ?>
                                    <span class="text-slate-500 italic font-medium">None</span>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php 
                    } 
                } else {
                    ?>
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-slate-500 italic">No categories found.</td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../admin/inc/footer.php'; ?>
