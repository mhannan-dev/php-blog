<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>

<?php 
// Only admins (role = '0') can reply to messages
if (Session::get('userRole') !== '0') {
    header('Location: index.php');
    exit();
}

if (!isset($_GET['msg_id']) || (int) $_GET['msg_id'] <= 0) {
    header('Location: inbox.php');
    exit();
}

$id = (int) $_GET['msg_id'];

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Protection Check
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!Session::checkCsrfToken($csrfToken)) {
        $error = 'Security check failed. Please refresh the page.';
    } else {
        $to       = trim($_POST['toEmail']  ?? '');
        $from     = trim($_POST['frmEmail'] ?? '');
        $subject  = trim($_POST['subj']     ?? '');
        $msg      = trim($_POST['msg']      ?? '');

        if (empty($to) || empty($from) || empty($subject) || empty($msg)) {
            $error = 'All fields are required.';
        } elseif (!filter_var($from, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid sender email address.';
        } else {
            $headers = "From: $from\r\n";
            $headers .= "Reply-To: $from\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            $sendmail = mail($to, $subject, $msg, $headers);
            if ($sendmail) {
                $success = 'Message sent successfully.';
            } else {
                $error = 'Something went wrong. Could not send mail.';
            }
        }
    }
}

$result = $contactModel->getById($id);
if (!$result) {
    header('Location: inbox.php');
    exit();
}
?>
<div class="glass-card rounded-3xl p-6 sm:p-8 shadow-xl shadow-black/5 flex flex-col gap-6">
    <div class="flex flex-col gap-1 border-b border-white/5 pb-4">
        <h2 class="text-xl font-bold font-outfit text-white">Reply Message</h2>
        <p class="text-slate-400 text-xs font-medium">Compose an email reply to user submission</p>
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

    <form action="" method="post" class="flex flex-col gap-5">
        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrfToken(); ?>">

        <div class="flex flex-col gap-1.5 max-w-xl">
            <label for="toEmail" class="text-xs font-semibold uppercase tracking-wider text-slate-400">To (Recipient)</label>
            <input type="text" readonly name="toEmail" id="toEmail" value="<?php echo Format::e($result['email']); ?>" 
                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-400 focus:outline-none" />
        </div>
        
        <div class="flex flex-col gap-1.5 max-w-xl">
            <label for="frmEmail" class="text-xs font-semibold uppercase tracking-wider text-slate-400">From (Your Email)</label>
            <input type="email" name="frmEmail" id="frmEmail" placeholder="Enter your sender email address" required 
                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 placeholder-slate-650 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200" />
        </div>

        <div class="flex flex-col gap-1.5 max-w-xl">
            <label for="subj" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Subject</label>
            <input type="text" name="subj" id="subj" placeholder="Enter email subject header..." required 
                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 placeholder-slate-650 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200" />
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="mytextarea" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Reply Message Body</label>
            <textarea class="tinymce" id="mytextarea" name="msg" rows="10" cols="50"></textarea>
        </div>

        <div class="mt-2 flex gap-3">
            <button type="submit" name="submit" 
                    class="px-6 py-3 bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white text-sm font-semibold rounded-xl transition-colors duration-200 cursor-pointer shadow-md shadow-brand-500/10">
                Send Reply
            </button>
            <a href="inbox.php" 
               class="px-6 py-3 border border-white/10 hover:bg-white/5 text-slate-350 hover:text-white text-sm font-semibold rounded-xl transition-colors duration-200">
                Cancel
            </a>
        </div>
    </form>
</div>
<?php include '../admin/inc/footer.php'; ?>