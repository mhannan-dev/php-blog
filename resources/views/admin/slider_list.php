<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>

<?php
// Only admins (role = '0') can view slider list
if (Session::get('userRole') !== '0') {
    header('Location: index.php');
    exit();
}

$error   = '';
$success = '';

if (isset($_GET['delete_slider'])) {
    $delId = (int) $_GET['delete_slider'];
    if ($delId > 0) {
        $deleted = $siteModel->deleteSlider($delId);
        if ($deleted) {
            $success = 'Slider deleted successfully.';
        } else {
            $error = 'Failed to delete slider.';
        }
    }
}
?>

<div class="glass-card rounded-3xl p-6 sm:p-8 shadow-xl shadow-black/5 flex flex-col gap-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-white/5 pb-4">
        <div class="flex flex-col gap-1">
            <h2 class="text-xl font-bold font-outfit text-white">Slider List</h2>
            <p class="text-slate-400 text-xs font-medium">Manage published slideshow banner assets</p>
        </div>
        <a href="add_slider.php" 
           class="px-4 py-2 bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white text-xs font-bold rounded-xl transition-all duration-200 shadow-md shadow-brand-500/10 cursor-pointer flex items-center gap-1.5 shrink-0">
            <i class="fa-solid fa-plus-circle text-[10px]"></i> Create Slider
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
                <tr class="border-b border-white/5 bg-slate-950/60 text-xs font-bold uppercase tracking-wider text-slate-400">
                    <th class="px-6 py-4">SL.</th>
                    <th class="px-6 py-4">Title / Caption</th>
                    <th class="px-6 py-4">Preview</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php
                $sliders = $siteModel->getSliders(20);
                if ($sliders && $sliders->num_rows > 0) {
                    $i = 0;
                    while ($result = $sliders->fetch_assoc()) {
                        $i++;
                        ?>
                        <tr class="hover:bg-white/2 transition-colors duration-150">
                            <td class="px-6 py-4 text-slate-400 font-medium"><?php echo $i; ?></td>
                            <td class="px-6 py-4 text-slate-200 font-bold"><?php echo Format::e($result['title']); ?></td>
                            <td class="px-6 py-4">
                                <?php if ($result['image']): ?>
                                    <img src="<?php echo Format::e($result['image']); ?>" 
                                         class="h-10 w-24 rounded-lg object-cover border border-white/10 bg-slate-950 shrink-0" alt="Slider image">
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-right text-xs font-bold flex items-center justify-end gap-2.5">
                                <a href="edit_slider.php?slider_id=<?php echo (int) $result['id']; ?>"
                                   class="px-3 py-1.5 rounded-lg bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 border border-blue-500/20 transition-all duration-200">
                                    Edit
                                </a>
                                <a onclick="return confirm('Are you sure want to delete this item?')" 
                                   href="?delete_slider=<?php echo (int) $result['id']; ?>"
                                   class="px-3 py-1.5 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 border border-red-500/20 transition-all duration-200">
                                    Delete
                                </a>
                            </td>
                        </tr>
                        <?php 
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-500 italic">No sliders found.</td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../admin/inc/footer.php'; ?>
