<?php
require_once __DIR__ . '/app/bootstrap.php';

if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    header('Location: index.php');
    exit();
}

$pageSlug = $_GET['slug'];
$pageData = $pageModel->getBySlug($pageSlug);

if (!$pageData) {
    header('Location: 404.php');
    exit();
}
?>

<?php include './inc/header.php'; ?>

<article class="glass-card rounded-3xl p-6 sm:p-8 shadow-lg shadow-black/5">
    <div class="flex flex-col gap-6">
        <!-- Title Header -->
        <h1 class="text-2xl sm:text-4xl font-extrabold font-outfit text-white leading-tight border-b border-white/5 pb-4">
            <?php echo Format::e($pageData['name']); ?>
        </h1>

        <!-- Page body -->
        <div class="text-slate-300 text-base leading-relaxed prose prose-invert max-w-none prose-headings:text-white prose-a:text-brand-400">
            <?php echo $pageData['body']; ?>
        </div>
    </div>
</article>

<?php include './inc/footer.php'; ?>