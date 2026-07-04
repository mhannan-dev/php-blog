<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>

<?php
// Only admins (role = '0') can manage inbox
if (Session::get('userRole') !== '0') {
    header('Location: index.php');
    exit();
}

$error   = '';
$success = '';

// Handle mark as seen
if (isset($_GET['seen_id'])) {
    $seenId = (int) $_GET['seen_id'];
    if ($seenId > 0) {
        $updated = $contactModel->markAsSeen($seenId);
        if ($updated) {
            $success = 'Message sent to seen box.';
        } else {
            $error = 'Something went wrong.';
        }
    }
}

// Handle delete message
if (isset($_GET['del_msg'])) {
    $delId = (int) $_GET['del_msg'];
    if ($delId > 0) {
        $deleted = $contactModel->delete($delId);
        if ($deleted) {
            $success = 'Message deleted successfully.';
        } else {
            $error = 'Message not deleted successfully.';
        }
    }
}
?>

<div class="flex flex-col gap-8">
    
    <!-- Alerts Block -->
    <?php if ($success || $error): ?>
        <div class="w-full">
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
        </div>
    <?php endif; ?>

    <!-- Inbox (Unseen) Card -->
    <div class="glass-card rounded-3xl p-6 sm:p-8 shadow-xl shadow-black/5 flex flex-col gap-6">
        <div class="flex flex-col gap-1 border-b border-white/5 pb-4">
            <h2 class="text-xl font-bold font-outfit text-white flex items-center gap-2">
                <i class="fa-solid fa-inbox text-brand-400 text-base"></i> Inbox (Unread)
            </h2>
            <p class="text-slate-400 text-xs font-medium">New unread contact submissions from users</p>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-white/5 bg-slate-900/30">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="border-b border-white/5 bg-slate-950/60 text-xs font-bold uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-4">SL.</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Message</th>
                        <th class="px-6 py-4">Date Received</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-slate-300">
                    <?php
                    $contacts = $contactModel->getUnread();
                    if ($contacts && $contacts->num_rows > 0) {
                        $i = 0;
                        while ($result = $contacts->fetch_assoc()) {
                            $i++;
                            ?>
                            <tr class="hover:bg-white/2 transition-colors duration-150">
                                <td class="px-6 py-4 text-slate-450 font-medium"><?php echo $i; ?></td>
                                <td class="px-6 py-4 text-slate-200 font-bold"><?php echo Format::e($result['fname'] . ' ' . $result['lname']); ?></td>
                                <td class="px-6 py-4 text-slate-350 max-w-[200px] truncate"><?php echo Format::e(Format::textShorten($result['msg'], 45)); ?></td>
                                <td class="px-6 py-4 text-slate-400 text-xs whitespace-nowrap"><?php echo Format::e(Format::formatDate($result['created'])); ?></td>
                                <td class="px-6 py-4 text-right text-xs font-bold flex items-center justify-end gap-2">
                                    <a href="view_msg.php?msg_id=<?php echo (int) $result['id']; ?>"
                                       class="px-2.5 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-slate-350 hover:text-white border border-white/5 transition-all duration-200">
                                        View
                                    </a>
                                    <a href="reply_msg.php?msg_id=<?php echo (int) $result['id']; ?>"
                                       class="px-2.5 py-1.5 rounded-lg bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 border border-blue-500/20 transition-all duration-200">
                                        Reply
                                    </a>
                                    <a href="?seen_id=<?php echo (int) $result['id']; ?>" 
                                       onclick="return confirm('Are you sure to move this to seen box?')"
                                       class="px-2.5 py-1.5 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 hover:text-emerald-350 border border-emerald-500/20 transition-all duration-200">
                                        Seen
                                    </a>
                                </td>
                            </tr>
                            <?php 
                        } 
                    } else {
                        ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500 italic">Your inbox is empty. No unread messages!</td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Seen Messages Card -->
    <div class="glass-card rounded-3xl p-6 sm:p-8 shadow-xl shadow-black/5 flex flex-col gap-6">
        <div class="flex flex-col gap-1 border-b border-white/5 pb-4">
            <h2 class="text-xl font-bold font-outfit text-white flex items-center gap-2">
                <i class="fa-solid fa-square-check text-brand-400 text-base"></i> Archive (Seen Messages)
            </h2>
            <p class="text-slate-400 text-xs font-medium">Read contact requests archived in system logs</p>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-white/5 bg-slate-900/30">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="border-b border-white/5 bg-slate-950/60 text-xs font-bold uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-4">SL.</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Message</th>
                        <th class="px-6 py-4">Date Received</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-slate-300">
                    <?php
                    $contacts = $contactModel->getSeen();
                    if ($contacts && $contacts->num_rows > 0) {
                        $i = 0;
                        while ($result = $contacts->fetch_assoc()) {
                            $i++;
                            ?>
                            <tr class="hover:bg-white/2 transition-colors duration-150">
                                <td class="px-6 py-4 text-slate-450 font-medium"><?php echo $i; ?></td>
                                <td class="px-6 py-4 text-slate-200 font-bold"><?php echo Format::e($result['fname'] . ' ' . $result['lname']); ?></td>
                                <td class="px-6 py-4 text-slate-350 max-w-[200px] truncate"><?php echo Format::e(Format::textShorten($result['msg'], 45)); ?></td>
                                <td class="px-6 py-4 text-slate-400 text-xs whitespace-nowrap"><?php echo Format::e(Format::formatDate($result['created'])); ?></td>
                                <td class="px-6 py-4 text-right text-xs font-bold">
                                    <a href="?del_msg=<?php echo (int) $result['id']; ?>" 
                                       onclick="return confirm('Are you sure want to delete this msg?')"
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
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500 italic">No archived messages found.</td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php include '../admin/inc/footer.php'; ?>
