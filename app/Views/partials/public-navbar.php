<?php
/**
 * Navbar situs publik. Dipakai oleh landing page dan halaman detail program studi.
 *
 * @var string|null $navPrefix  Awalan anchor: '' di landing page, '/' di halaman lain.
 * @var bool|null   $isHome     Tandai menu "Beranda" sebagai aktif.
 */
$navPrefix = $navPrefix ?? '';
$isHome    = $isHome ?? false;

$navActive = 'text-blue-600 border-b-2 border-blue-600 pb-1';
$navIdle   = 'hover:text-blue-600 transition';
?>
<!-- NAVBAR -->
<header class="bg-white border-b border-slate-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xl shadow-sm">
                <i class="fa-solid fa-graduation-cap text-lg"></i>
            </div>
            <div>
                <h1 class="font-bold text-slate-900 text-lg leading-tight">Universitas Contoh</h1>
                <p class="text-xs text-slate-500 font-medium">Unggul · Islam · Berkemajuan</p>
            </div>
        </div>

        <nav class="hidden md:flex items-center space-x-6 text-sm font-medium text-slate-600">
            <a href="<?= esc($navPrefix); ?>#beranda" class="<?= $isHome ? $navActive : $navIdle; ?>">Beranda</a>
            <a href="<?= esc($navPrefix); ?>#data-mahasiswa" class="<?= $navIdle; ?>">Data Mahasiswa</a>
            <a href="<?= esc($navPrefix); ?>#program-studi" class="<?= $navIdle; ?>">Program Studi</a>
            <a href="<?= esc($navPrefix); ?>#tentang" class="<?= $navIdle; ?>">Tentang</a>
            <a href="<?= esc($navPrefix); ?>#web-semantik" class="<?= $navIdle; ?>">Web Semantik</a>
            <a href="<?= esc($navPrefix); ?>#kontak" class="<?= $navIdle; ?>">Kontak</a>
        </nav>

        <a href="/login" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg flex items-center space-x-2 transition shadow-sm">
            <i class="fa-solid fa-user-lock text-xs"></i>
            <span>Login</span>
        </a>
    </div>
</header>
