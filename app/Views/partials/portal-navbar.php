<?php
/**
 * Navbar portal mahasiswa (dipakai halaman dashboard dan profil).
 *
 * @var array<string, mixed> $mhs
 * @var string               $csrfToken
 * @var string|null          $currentPath
 */
$currentPath = $currentPath ?? '';

$avatarFile = !empty($mhs['photo']) ? basename((string) $mhs['photo']) : null;
$initial    = esc(mb_strtoupper(mb_substr((string) $mhs['nama_mahasiswa'], 0, 1)));

$dashboardClass = $currentPath === '/dashboard' ? 'text-blue-600 font-semibold' : 'text-slate-600 hover:text-blue-600 transition';
$profileClass   = $currentPath === '/profil' ? 'text-blue-600 font-semibold' : 'text-slate-600 hover:text-blue-600 transition';
?>
<!-- NAVBAR PORTAL -->
<header class="bg-white border-b border-slate-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

        <!-- Brand Logo -->
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div>
                <h1 class="font-bold text-slate-900 text-base leading-tight">Portal Mahasiswa</h1>
                <p class="text-[10px] text-slate-500 font-medium">Universitas Contoh</p>
            </div>
        </div>

        <!-- Menu -->
        <nav class="hidden md:flex items-center space-x-6 text-sm font-medium">
            <a href="<?= esc(url('/dashboard')); ?>" class="<?= $dashboardClass; ?>">Dashboard</a>
            <a href="<?= esc(url('/profil')); ?>" class="<?= $profileClass; ?>">Profil Saya</a>
        </nav>

        <!-- User Menu & Logout -->
        <div class="flex items-center space-x-3">
            <div class="hidden sm:flex items-center space-x-2 text-right">
                <div>
                    <p class="text-xs font-bold text-slate-800"><?= esc($mhs['nama_mahasiswa']); ?></p>
                    <p class="text-[10px] text-slate-500">NPM: <?= esc($mhs['npm']); ?></p>
                </div>
                <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs overflow-hidden shrink-0">
                    <?php if ($avatarFile !== null): ?>
                        <img src="<?= esc(url('/media/profil/' . $avatarFile)); ?>" alt="Foto profil <?= esc($mhs['nama_mahasiswa']); ?>"
                             class="w-full h-full object-cover">
                    <?php else: ?>
                        <?= $initial; ?>
                    <?php endif; ?>
                </div>
            </div>

            <form action="<?= esc(url('/logout')); ?>" method="POST" class="inline">
                <input type="hidden" name="_token" value="<?= esc($csrfToken); ?>">
                <button type="submit"
                        onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?')"
                        class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 text-xs font-semibold px-3 py-2 rounded-lg transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>

    </div>
</header>
