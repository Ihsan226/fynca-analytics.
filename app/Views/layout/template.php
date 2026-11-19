<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Fynca Analytics</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="flex min-h-screen text-gray-800">

    <!-- Liquid Glass Ambient Background -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none" aria-hidden="true" id="glass-bg">
        <div class="absolute -top-[40%] -left-[20%] w-[70vw] h-[70vw] rounded-full opacity-55" style="background: radial-gradient(circle, rgba(129,140,248,0.32) 0%, rgba(129,140,248,0) 70%); filter: blur(80px);"></div>
        <div class="absolute -bottom-[30%] -right-[15%] w-[60vw] h-[60vw] rounded-full opacity-50" style="background: radial-gradient(circle, rgba(192,132,252,0.26) 0%, rgba(192,132,252,0) 70%); filter: blur(80px);"></div>
        <div class="absolute top-[20%] right-[10%] w-[40vw] h-[40vw] rounded-full opacity-45" style="background: radial-gradient(circle, rgba(56,189,248,0.2) 0%, rgba(56,189,248,0) 70%); filter: blur(60px);"></div>
        <div class="absolute bottom-[10%] left-[20%] w-[30vw] h-[30vw] rounded-full opacity-35" style="background: radial-gradient(circle, rgba(244,114,182,0.18) 0%, rgba(244,114,182,0) 70%); filter: blur(60px);"></div>
    </div>

    <!-- Sidebar (Kiri — Dark Purple) -->
    <?= $this->include('layout/sidebar') ?>

    <!-- Area Kanan: Topbar + Konten -->
    <div class="flex-1 flex flex-col overflow-hidden relative">
        
        <!-- Topbar Terintegrasi (di dalam area konten) -->
        <?= $this->include('layout/topbar') ?>

        <!-- Area Konten Dinamis -->
        <main id="main-content" class="flex-1 p-3 sm:p-4 md:p-6 overflow-y-auto">
            
            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert-message p-3 sm:p-4 mb-4 text-sm text-teal-700 bg-teal-100 rounded-lg border border-teal-200 flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-circle-check"></i> <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>
            
            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert-message p-3 sm:p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg border border-red-200 flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-triangle-exclamation"></i> <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <div class="animate-fade-in-up">
                <?= $this->renderSection('content') ?>
            </div>
        </main>
    </div>

    <!-- Scroll to Top Button -->
    <button id="btn-scroll-top" class="fixed bottom-6 right-6 w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-xl text-white shadow-lg shadow-indigo-500/20 border border-white/20 flex items-center justify-center z-30 opacity-0 translate-y-4 pointer-events-none transition-all duration-300 hover:bg-white/20 hover:shadow-xl hover:shadow-indigo-500/30 hover:scale-110 active:scale-90" aria-label="Scroll to top">
        <i class="fa-solid fa-arrow-up text-sm"></i>
    </button>

    <script src="<?= base_url('js/script.js') ?>"></script>
</body>
</html>