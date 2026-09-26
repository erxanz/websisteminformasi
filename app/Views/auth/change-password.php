<?php
/**
 * @var array<string, string> $errors
 * @var string                $csrfToken
 */
$bodyClass = 'bg-slate-100 min-h-screen flex items-center justify-center p-4 font-sans antialiased';
?>
<div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-200/80 p-8 space-y-6">

    <!-- Header -->
    <div class="text-center space-y-2">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-blue-600 text-white font-bold text-2xl shadow-md mb-2">
            <i class="fa-solid fa-key"></i>
        </div>
        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Ganti Password</h2>
        <p class="text-xs text-slate-500 font-medium">Perbarui kata sandi akun Portal Mahasiswa Anda</p>
    </div>

    <!-- Alert Error -->
    <?php if ($errors !== []): ?>
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg text-xs space-y-1 shadow-sm" role="alert">
            <p class="font-bold flex items-center space-x-2">
                <i class="fa-solid fa-circle-exclamation text-lg text-red-500"></i>
                <span>Password Gagal Diubah</span>
            </p>
            <?php foreach ($errors as $message): ?>
                <p><?= esc($message); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Form -->
    <form action="/ubah-password" method="POST" class="space-y-4">
        <input type="hidden" name="_token" value="<?= esc($csrfToken); ?>">

        <div>
            <label for="current_password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                Password Saat Ini
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-lock text-sm"></i>
                </div>
                <input type="password" id="current_password" name="current_password" required autocomplete="current-password"
                    placeholder="••••••••"
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition">
            </div>
        </div>

        <div>
            <label for="new_password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                Password Baru
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-key text-sm"></i>
                </div>
                <input type="password" id="new_password" name="new_password" required minlength="8" maxlength="72"
                    autocomplete="new-password" placeholder="Minimal 8 karakter"
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition">
            </div>
            <p class="text-[11px] text-slate-400 mt-1.5">Gunakan minimal 8 karakter dan hindari password yang mudah ditebak.</p>
        </div>

        <div>
            <label for="new_password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                Konfirmasi Password Baru
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-key text-sm"></i>
                </div>
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" required minlength="8" maxlength="72"
                    autocomplete="new-password" placeholder="Ulangi password baru"
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition">
            </div>
        </div>

        <button type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg shadow-md hover:shadow-lg transition flex items-center justify-center space-x-2 text-sm">
            <i class="fa-solid fa-floppy-disk text-xs"></i>
            <span>Simpan Password Baru</span>
        </button>
    </form>

    <!-- Back Link -->
    <div class="text-center pt-2 border-t border-slate-100">
        <a href="/dashboard" class="inline-flex items-center space-x-2 text-xs font-medium text-slate-500 hover:text-blue-600 transition">
            <i class="fa-solid fa-arrow-left text-[10px]"></i>
            <span>Kembali ke Dashboard</span>
        </a>
    </div>

</div>
