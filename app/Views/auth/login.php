<?php
/**
 * @var array<string, string> $errors
 * @var string                $oldNpm
 * @var string                $csrfToken
 */
$bodyClass = 'bg-slate-100 min-h-screen flex items-center justify-center p-4 font-sans antialiased';
?>
<!-- Container Card -->
<div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-200/80 p-8 space-y-6">

    <!-- Header & Logo -->
    <div class="text-center space-y-2">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-blue-600 text-white font-bold text-2xl shadow-md mb-2">
            <i class="fa-solid fa-graduation-cap"></i>
        </div>
        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Universitas Contoh</h2>
        <p class="text-xs text-slate-500 font-medium">Sistem Informasi Akademik Mahasiswa</p>
    </div>

    <!-- Alert Error Message -->
    <?php if ($errors !== []): ?>
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg text-xs space-y-1 shadow-sm" role="alert">
            <p class="font-bold flex items-center space-x-2">
                <i class="fa-solid fa-circle-exclamation text-lg text-red-500"></i>
                <span>Login Gagal</span>
            </p>
            <?php foreach ($errors as $message): ?>
                <p><?= esc($message); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Form Login -->
    <form action="/login" method="POST" class="space-y-4">
        <input type="hidden" name="_token" value="<?= esc($csrfToken); ?>">

        <!-- Input NPM -->
        <div>
            <label for="npm" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                NPM (Nomor Pokok Mahasiswa)
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-id-card text-sm"></i>
                </div>
                <input type="text" id="npm" name="npm" required placeholder="Contoh: 2101010001"
                    value="<?= esc($oldNpm); ?>"
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition">
            </div>
        </div>

        <!-- Input Password -->
        <div>
            <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                Password
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-lock text-sm"></i>
                </div>
                <input type="password" id="password" name="password" required placeholder="••••••••"
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition">
            </div>
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between text-xs text-slate-500 pt-1">
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                <span>Ingat saya</span>
            </label>
            <a href="#" class="text-blue-600 hover:underline font-medium">Lupa Password?</a>
        </div>

        <!-- Submit Button -->
        <button type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg shadow-md hover:shadow-lg transition flex items-center justify-center space-x-2 text-sm">
            <i class="fa-solid fa-right-to-bracket text-xs"></i>
            <span>Masuk Sekarang</span>
        </button>
    </form>

    <!-- Back Link -->
    <div class="text-center pt-2 border-t border-slate-100">
        <a href="/" class="inline-flex items-center space-x-2 text-xs font-medium text-slate-500 hover:text-blue-600 transition">
            <i class="fa-solid fa-arrow-left text-[10px]"></i>
            <span>Kembali ke Halaman Utama</span>
        </a>
    </div>

</div>
