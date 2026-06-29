<?php include './inc/header.php'; ?>

<div class="glass-card rounded-3xl p-12 text-center shadow-lg shadow-black/5 flex flex-col items-center justify-center min-h-[400px] gap-6">
    <div class="relative">
        <h1 class="text-7xl sm:text-9xl font-extrabold font-outfit tracking-tighter text-slate-800">404</h1>
        <span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-2xl font-bold font-outfit text-white whitespace-nowrap bg-brand-500 px-4 py-1.5 rounded-xl shadow-lg shadow-brand-500/25 rotate-3">Page Not Found</span>
    </div>
    
    <p class="text-slate-400 text-sm sm:text-base max-w-md leading-relaxed mt-2">
        The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
    </p>

    <div class="mt-4 flex gap-4">
        <a href="index.php" 
           class="px-5 py-2.5 bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-md shadow-brand-500/10">
            Go to Homepage
        </a>
    </div>
</div>

<?php include './inc/footer.php'; ?>