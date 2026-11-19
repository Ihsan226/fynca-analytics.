<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Fynca Analytics</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Load FontAwesome untuk Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-950 via-indigo-950 to-purple-900 p-4 relative overflow-hidden">

    <!-- Ambient Glass Orbs -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none" aria-hidden="true">
        <div class="absolute -top-[30%] -left-[15%] w-[50vw] h-[50vw] rounded-full opacity-30" style="background: radial-gradient(circle, rgba(99,102,241,0.5) 0%, transparent 70%); filter: blur(80px);"></div>
        <div class="absolute -bottom-[20%] -right-[10%] w-[45vw] h-[45vw] rounded-full opacity-25" style="background: radial-gradient(circle, rgba(168,85,247,0.4) 0%, transparent 70%); filter: blur(80px);"></div>
        <div class="absolute top-[30%] right-[20%] w-[25vw] h-[25vw] rounded-full opacity-20" style="background: radial-gradient(circle, rgba(59,130,246,0.35) 0%, transparent 70%); filter: blur(60px);"></div>
    </div>

    <!-- Card Container -->
    <div class="flex flex-col md:flex-row w-full max-w-4xl bg-white/10 backdrop-blur-2xl rounded-3xl shadow-2xl shadow-black/20 overflow-hidden min-h-[500px] border border-white/20 relative z-10">
        
        <!-- Bagian Kiri: Form Login -->
        <div class="w-full md:w-1/2 p-10 flex flex-col justify-center bg-white/70 backdrop-blur-xl">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-blue-500 text-white mb-4">
                    <i class="fa-solid fa-calculator text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Login</h2>
                <p class="text-sm text-gray-500 mt-1">Selamat datang kembali! Masuk ke akun Anda.</p>
            </div>

            <?php if(session()->getFlashdata('error')): ?>
                <div class="mb-4 p-3 bg-red-100 text-red-700 text-sm rounded-lg text-center font-medium">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('success')): ?>
                <div class="mb-4 p-3 bg-green-100 text-green-700 text-sm rounded-lg text-center font-medium">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <form action="/auth/loginProcess" method="POST" class="space-y-5">
                <?= csrf_field() ?>
                
                <div>
                    <label class="block text-xs text-gray-600 mb-1 ml-1">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-user text-gray-400 text-sm"></i>
                        </div>
                        <input type="text" name="username" required 
                            class="bg-[#f0f4f8] text-gray-800 text-sm rounded-lg block w-full pl-10 p-3 outline-none focus:ring-2 focus:ring-blue-400 transition-all">
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs text-gray-600 mb-1 ml-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-400 text-sm"></i>
                        </div>
                        <input type="password" name="password" required 
                            class="bg-[#f0f4f8] text-gray-800 text-sm rounded-lg block w-full pl-10 p-3 outline-none focus:ring-2 focus:ring-blue-400 transition-all">
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#5b73e8] hover:bg-blue-600 text-white font-semibold py-3 rounded-lg transition-colors mt-2 text-sm">
                    <i class="fa-solid fa-right-to-bracket mr-2"></i> Masuk Sekarang
                </button>
            </form>

        </div>

        <!-- Bagian Kanan: Banner Info -->
        <div class="hidden md:flex w-full md:w-1/2 bg-gradient-to-br from-[#5b73e8] to-[#8061db] p-10 text-white flex-col items-center justify-center text-center">
            <i class="fa-solid fa-chart-line text-7xl mb-6"></i>
            <h2 class="text-2xl font-bold mb-2">Fynca Analytics</h2>
            <p class="text-sm text-blue-100 px-4 leading-relaxed">
                Sistem manajemen analitik tingkat enterprise untuk menganalisis metrik operasional secara komprehensif, cepat, dan akurat.
            </p>
        </div>

    </div>
</body>
</html>