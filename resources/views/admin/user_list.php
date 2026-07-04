<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>

<?php
$error   = '';
$success = '';

if (isset($_GET['delUser'])) {
    if (Session::get('userRole') !== '0') {
        $error = 'You do not have permission to delete users.';
    } else {
        $delId = (int) $_GET['delUser'];
        if ($delId > 0) {
            $deleted = $userModel->delete($delId);
            if ($deleted) {
                $success = 'User deleted successfully.';
            } else {
                $error = 'User not deleted.';
            }
        }
    }
}
?>

<div class="glass-card rounded-3xl p-6 sm:p-8 shadow-xl shadow-black/5 flex flex-col gap-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-white/5 pb-4">
        <div class="flex flex-col gap-1">
            <h2 class="text-xl font-bold font-outfit text-white">User List</h2>
            <p class="text-slate-400 text-xs font-medium">Manage dashboard operators and admin profiles</p>
        </div>
        <?php if (Session::get('userRole') === '0'): ?>
            <a href="add_user.php" 
               class="px-4 py-2 bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white text-xs font-bold rounded-xl transition-all duration-200 shadow-md shadow-brand-500/10 cursor-pointer flex items-center gap-1.5 shrink-0">
                <i class="fa-solid fa-plus-circle text-[10px]"></i> Create User
            </a>
        <?php endif; ?>
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
                    <th class="px-6 py-4">Username</th>
                    <th class="px-6 py-4">Role</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5 text-slate-350">
                <?php
                $users = $userModel->getAll();
                if ($users && $users->num_rows > 0) {
                    $i = 0;
                    while ($result = $users->fetch_assoc()) {
                        $i++;
                        ?>
                        <tr class="hover:bg-white/2 transition-colors duration-150">
                            <td class="px-6 py-4 text-slate-400 font-medium"><?php echo $i; ?></td>
                            <td class="px-6 py-4 text-slate-200 font-bold"><?php echo Format::e($result['username']); ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold border 
                                    <?php 
                                    echo match((string)$result['role']) {
                                        '0' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                        '1' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                        default => 'bg-slate-500/10 text-slate-400 border-slate-500/20'
                                    };
                                    ?>">
                                    <?php echo Format::e(UserModel::roleLabel($result['role'])); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-xs font-bold flex items-center justify-end gap-2.5">
                                <a href="views_user.php?userId=<?php echo (int) $result['id']; ?>" 
                                   class="px-3 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-slate-350 hover:text-white border border-white/5 transition-all duration-200">
                                    View
                                </a> 
                                <?php if (Session::get('userRole') === '0') { ?>
                                    <a onclick="return confirm('Are you sure want to delete this user?')" 
                                       href="?delUser=<?php echo (int) $result['id']; ?>"
                                       class="px-3 py-1.5 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 border border-red-500/20 transition-all duration-200">
                                        Delete
                                    </a>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php 
                    } 
                } else {
                    ?>
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-500 italic">No operators created.</td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../admin/inc/footer.php'; ?>
