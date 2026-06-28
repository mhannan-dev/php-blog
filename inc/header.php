<?php require_once __DIR__ . '/../app/bootstrap.php'; ?>
<?php
// ─── Dynamic SEO meta ──────────────────────────────────────────────────────
$metaTitle       = TITLE;
$metaDescription = '';
$metaKeywords    = '';

$postIdMeta = (int) ($_GET['id']      ?? 0);
$catIdMeta  = (int) ($_GET['cat_post'] ?? 0);
$pageIdMeta = (int) ($_GET['page_id']  ?? 0);

if ($postIdMeta > 0) {
    $metaPost = $postModel->getById($postIdMeta);
    if ($metaPost) {
        $metaTitle       = Format::e($metaPost['title']) . ' — ' . TITLE;
        $metaDescription = Format::e(mb_substr(strip_tags($metaPost['body']), 0, 160));
        $metaKeywords    = Format::e($metaPost['tags']);
    }
} elseif ($catIdMeta > 0) {
    $metaCat = $categoryModel->getById($catIdMeta);
    if ($metaCat) {
        $metaTitle = Format::e($metaCat['name']) . ' — ' . TITLE;
    }
} elseif ($pageIdMeta > 0) {
    $metaPage = $pageModel->getById($pageIdMeta);
    if ($metaPage) {
        $metaTitle       = Format::e($metaPage['name']) . ' — ' . TITLE;
        $metaDescription = Format::e(mb_substr(strip_tags($metaPage['body']), 0, 160));
    }
}

// ─── Site info ─────────────────────────────────────────────────────────────
$siteInfo   = $siteModel->getInfo();
$siteLogo   = $siteInfo['logo']   ?? '';
$siteTitle  = $siteInfo['title']  ?? TITLE;
$siteSlogan = $siteInfo['slogan'] ?? '';

// ─── Navigation pages ──────────────────────────────────────────────────────
$navPages = $pageModel->getAll();

// ─── Navigation categories ─────────────────────────────────────────────────
$navCats = $categoryModel->getAll();

// ─── Social links ──────────────────────────────────────────────────────────
$socialLinks = $siteModel->getSocialLinks();
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author"      content="<?php echo Format::e($siteTitle); ?>" />
    <meta name="description" content="<?php echo $metaDescription; ?>" />
    <meta name="keywords"    content="<?php echo $metaKeywords; ?>" />
    <title><?php echo $metaTitle; ?></title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
          integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2zippi1Mun+0cFqCavcor+Bq3UMKrJvF7KZIZeq3aEznMfGt01Ow=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
          
    <!-- TailwindCSS v3 CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f4f6fe',
                            100: '#e9edfd',
                            200: '#cdd5fb',
                            300: '#9faef8',
                            400: '#697bf3',
                            500: '#4353eb',
                            600: '#2c37db',
                            700: '#2329b3',
                            800: '#202492',
                            900: '#1d2274',
                            950: '#111347',
                        }
                    }
                }
            }
        }
    </script>
    
    <style type="text/css">
        .glass {
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glass-card {
            background: rgba(30, 41, 59, 0.45);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }
        .gradient-text {
            background: linear-gradient(135deg, #cdd5fb 0%, #697bf3 50%, #2c37db 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0b0f19;
            color: #f1f5f9;
        }
    </style>
</head>
<body class="min-h-screen selection:bg-brand-500 selection:text-white flex flex-col justify-between overflow-x-hidden">

<!-- Header Top Social bar -->
<div class="w-full bg-slate-950/80 backdrop-blur-md border-b border-white/5 py-2.5 px-4 text-sm z-50">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-2">
        <p class="text-slate-400 font-medium text-center sm:text-left text-xs outfit tracking-wider uppercase"><?php echo Format::e($siteSlogan); ?></p>
        <div class="flex items-center gap-4 text-slate-400">
            <?php if ($socialLinks): ?>
                <a href="<?php echo Format::e($socialLinks['fb']); ?>" target="_blank" class="hover:text-brand-400 transition-colors duration-200">
                    <i class="fa-brands fa-facebook text-base"></i>
                </a>
                <a href="<?php echo Format::e($socialLinks['tw']); ?>" target="_blank" class="hover:text-brand-400 transition-colors duration-200">
                    <i class="fa-brands fa-github text-base"></i>
                </a>
                <a href="<?php echo Format::e($socialLinks['ln']); ?>" target="_blank" class="hover:text-brand-400 transition-colors duration-200">
                    <i class="fa-brands fa-linkedin text-base"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Main Sticky Nav -->
<header class="sticky top-0 z-40 glass w-full shadow-lg shadow-black/20">
    <div class="max-w-7xl mx-auto px-4 py-4 flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Logo and Site Name -->
        <div class="flex items-center gap-3">
            <a href="index.php" class="flex items-center gap-3 group">
                <?php if ($siteLogo): ?>
                    <img src="admin/<?php echo Format::e($siteLogo); ?>"
                         alt="<?php echo Format::e($siteTitle); ?>"
                         class="h-10 w-auto object-contain transition-transform duration-300 group-hover:scale-105" />
                <?php else: ?>
                    <span class="text-2xl font-bold font-outfit text-white tracking-tight gradient-text"><?php echo Format::e($siteTitle); ?></span>
                <?php endif; ?>
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="flex flex-wrap items-center justify-center gap-1 sm:gap-2">
            <a href="index.php" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 hover:bg-white/5 hover:text-white <?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'bg-brand-500/20 text-brand-300 border border-brand-500/30' : 'text-slate-300'; ?>">Home</a>
            
            <?php if ($navPages): while ($p = $navPages->fetch_assoc()): ?>
                <a href="page.php?page_id=<?php echo (int) $p['id']; ?>" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 hover:bg-white/5 hover:text-white <?php echo (isset($_GET['page_id']) && (int)$_GET['page_id'] === (int)$p['id']) ? 'bg-brand-500/20 text-brand-300 border border-brand-500/30' : 'text-slate-300'; ?>">
                    <?php echo Format::e($p['name']); ?>
                </a>
            <?php endwhile; endif; ?>

            <?php if ($navCats): while ($c = $navCats->fetch_assoc()): ?>
                <a href="cat_posts.php?cat_post=<?php echo (int) $c['id']; ?>" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 hover:bg-white/5 hover:text-white <?php echo (isset($_GET['cat_post']) && (int)$_GET['cat_post'] === (int)$c['id']) ? 'bg-brand-500/20 text-brand-300 border border-brand-500/30' : 'text-slate-300'; ?>">
                    <?php echo Format::e($c['name']); ?>
                </a>
            <?php endwhile; endif; ?>

            <a href="contact_us.php" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 hover:bg-white/5 hover:text-white <?php echo basename($_SERVER['PHP_SELF']) === 'contact_us.php' ? 'bg-brand-500/20 text-brand-300 border border-brand-500/30' : 'text-slate-300'; ?>">Contact</a>
        </nav>

        <!-- Search Form -->
        <form action="search.php" method="get" class="relative flex items-center w-full md:w-auto">
            <input type="text" name="search"
                   placeholder="Search articles..."
                   value="<?php echo Format::e($_GET['search'] ?? ''); ?>"
                   class="w-full md:w-60 bg-slate-900/80 border border-white/10 rounded-xl py-2 pl-4 pr-10 text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-250" />
            <button type="submit" class="absolute right-3 text-slate-500 hover:text-white transition-colors duration-200">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </button>
        </form>
    </div>
</header>

<main class="max-w-7xl mx-auto px-4 py-8 flex-grow w-full flex flex-col lg:flex-row gap-8">
    <div class="flex-grow lg:w-3/4 flex flex-col gap-8">