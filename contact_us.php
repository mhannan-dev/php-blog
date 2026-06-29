<?php include __DIR__ . '/inc/header.php'; ?>

<?php
$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $msg   = trim($_POST['msg']   ?? '');

    if (empty($fname)) {
        $error = 'First name must not be empty.';
    } elseif (empty($lname)) {
        $error = 'Last name must not be empty.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (empty($msg)) {
        $error = 'Message must not be empty.';
    } else {
        $inserted = $contactModel->create($fname, $lname, $email, $msg);

        if ($inserted) {
            $success = 'Your message has been sent successfully.';
            $_POST = [];
        } else {
            $error = 'Failed to send your message. Please try again.';
        }
    }
}
?>

<div class="glass-card rounded-3xl p-6 sm:p-8 shadow-lg shadow-black/5 flex flex-col gap-6">
    <div>
        <h2 class="text-2xl sm:text-3xl font-extrabold font-outfit text-white tracking-tight border-b border-white/5 pb-4">Contact Us</h2>
        <p class="text-slate-400 text-sm mt-3 leading-relaxed">Have a question, feedback, or want to collaborate? Fill out the form below and we will get back to you shortly.</p>
    </div>

    <!-- Feedback Alerts -->
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
        <!-- Names Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="flex flex-col gap-1.5">
                <label for="fname" class="text-xs font-semibold uppercase tracking-wider text-slate-400">First Name</label>
                <input type="text" id="fname" name="fname"
                       placeholder="First name"
                       value="<?php echo isset($_POST['fname']) ? Format::e($_POST['fname']) : ''; ?>"
                       class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 placeholder-slate-650 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200"
                       required />
            </div>
            
            <div class="flex flex-col gap-1.5">
                <label for="lname" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Last Name</label>
                <input type="text" id="lname" name="lname"
                       placeholder="Last name"
                       value="<?php echo isset($_POST['lname']) ? Format::e($_POST['lname']) : ''; ?>"
                       class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 placeholder-slate-650 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200"
                       required />
            </div>
        </div>

        <!-- Email -->
        <div class="flex flex-col gap-1.5">
            <label for="email" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Email Address</label>
            <input type="email" id="email" name="email"
                   placeholder="you@example.com"
                   value="<?php echo isset($_POST['email']) ? Format::e($_POST['email']) : ''; ?>"
                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 placeholder-slate-650 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200"
                   required />
        </div>

        <!-- Message -->
        <div class="flex flex-col gap-1.5">
            <label for="msg" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Your Message</label>
            <textarea id="msg" name="msg" rows="5"
                      placeholder="Write your message here..."
                      class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 placeholder-slate-650 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200"
                      required><?php echo isset($_POST['msg']) ? Format::e($_POST['msg']) : ''; ?></textarea>
        </div>

        <!-- Submit -->
        <div class="mt-2">
            <button type="submit" name="submit" 
                    class="px-6 py-3 bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white text-sm font-semibold rounded-xl transition-colors duration-200 flex items-center justify-center gap-2 cursor-pointer shadow-md shadow-brand-500/10">
                Send Message <i class="fa-solid fa-paper-plane text-xs"></i>
            </button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/inc/footer.php'; ?>