<?php
require_once __DIR__ . '/../../app/bootstrap.php';
Session::checkSession();
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel — <?php echo TITLE; ?></title>

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
                        red: {
                            350: '#f87171'
                        }
                    }
                }
            }
        }
    </script>

    <!-- Core Scripts -->
    <script src="js/jquery-1.6.4.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/jquery-ui/jquery.ui.core.min.js"></script>
    <script src="js/jquery-ui/jquery.ui.widget.min.js"    type="text/javascript"></script>
    <script src="js/jquery-ui/jquery.ui.accordion.min.js" type="text/javascript"></script>
    <script src="js/jquery-ui/jquery.effects.core.min.js" type="text/javascript"></script>
    <script src="js/jquery-ui/jquery.effects.slide.min.js" type="text/javascript"></script>
    <script src="js/jquery-ui/jquery.ui.mouse.min.js"     type="text/javascript"></script>
    <script src="js/jquery-ui/jquery.ui.sortable.min.js"  type="text/javascript"></script>
    <script src="https://cdn.tiny.cloud/1/lxciupl9njkl5ls3keg2ggdfrdqvc65znxc65h791rsrs9g1/tinymce/5/tinymce.min.js"></script>
    
    <style type="text/css">
        .glass-card {
            background: rgba(30, 41, 59, 0.45);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #090c15;
            color: #f1f5f9;
        }
        /* Custom styled scrolls */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>
    
    <script type="text/javascript">
    $(document).ready(function () {
        // Init TinyMCE
        tinymce.init({ 
            selector: '.tinymce',
            theme: 'silver',
            skin: 'oxide-dark',
            content_css: 'dark'
        });
    });
    </script>
</head>
<body class="h-full flex flex-col justify-between">

<!-- Top Admin Header Bar -->
<header class="w-full bg-slate-950 border-b border-white/5 py-4 px-6 sticky top-0 z-30 shadow-md">
    <div class="max-w-8xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
        <!-- Logo / Branding -->
        <div class="flex items-center gap-3">
            <div class="h-9 w-9 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-md shadow-blue-600/20 shrink-0">
                <i class="fa-solid fa-cube text-base"></i>
            </div>
            <div class="flex flex-col">
                <h1 class="text-base font-bold font-outfit text-white leading-tight tracking-tight">Admin Dashboard</h1>
                <p class="text-[10px] text-slate-500 font-semibold tracking-wider uppercase font-sans">CMS Console Panel</p>
            </div>
        </div>

        <!-- Right info profile and logout -->
        <div class="flex items-center gap-4 text-sm font-medium">
            <?php
            if (isset($_GET['action']) && $_GET['action'] === 'logout') {
                Session::destroy();
            }
            ?>
            <div class="flex items-center gap-2.5 bg-white/5 border border-white/5 px-3 py-1.5 rounded-xl">
                <div class="h-6 w-6 rounded-full bg-blue-500/20 text-blue-300 font-bold flex items-center justify-center text-xs border border-blue-500/30">
                    <?php echo strtoupper(substr(Session::get('userName'), 0, 1)); ?>
                </div>
                <span class="text-slate-300 text-xs">Hello, <strong class="text-white"><?php echo Format::e(Session::get('userName')); ?></strong></span>
            </div>
            <a href="?action=logout" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 border border-red-500/20 transition-all duration-200 cursor-pointer">
                Logout <i class="fa-solid fa-sign-out-alt ml-1"></i>
            </a>
        </div>
    </div>
</header>

<!-- Main Wrapper Layout (Sidebar + Content Area) -->
<div class="w-full flex-grow max-w-8xl mx-auto flex flex-col md:flex-row gap-6 p-6">
    
    <!-- Sidebar Left Column -->
    <?php include '../admin/inc/sidebar.php'; ?>
    
    <!-- Content Area Right Column -->
    <div class="flex-grow md:w-3/4 flex flex-col gap-6">