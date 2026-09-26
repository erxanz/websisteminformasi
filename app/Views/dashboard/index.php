<?php
/**
 * @var array<string, mixed> $mhs
 * @var string               $csrfToken
 * @var string|null          $flashSuccess
 */
$bodyClass = 'bg-slate-100 text-slate-800 font-sans antialiased min-h-screen flex flex-col';
?>
<?php require __DIR__ . '/../partials/portal-navbar.php'; ?>

<!-- 2. MAIN CONTENT -->
<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- NOTIFIKASI SUKSES -->
    <?php if (!empty($flashSuccess)): ?>
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-r-lg text-xs flex items-center space-x-3 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check text-lg text-emerald-500 shrink-0"></i>
            <p class="font-semibold"><?= esc($flashSuccess); ?></p>
        </div>
    <?php endif; ?>

    <!-- WELCOME BANNER -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 md:p-8 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10 space-y-2">
            <span class="bg-blue-500/40 text-blue-100 text-xs font-medium px-3 py-1 rounded-full border border-blue-400/30">
                Status: Mahasiswa Aktif
            </span>
            <h2 class="text-2xl md:text-3xl font-extrabold">
                Selamat Datang kembali, <?= esc($mhs['nama_mahasiswa']); ?>! 👋
            </h2>
            <p class="text-blue-100 text-xs md:text-sm max-w-xl">
                Anda terdaftar pada Program Studi <span class="font-semibold text-white"><?= esc($mhs['nama_program_studi']); ?></span> - <span class="font-semibold text-white"><?= esc($mhs['nama_fakultas']); ?></span>.
            </p>
        </div>
        <i class="fa-solid fa-user-graduate absolute -right-4 -bottom-6 text-9xl text-white/10 pointer-events-none"></i>
    </div>

    <!-- GRID CONTENT (STATS & PROFILE) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- LEFT COLUMN: PROFIL MAHASISWA -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <h3 class="font-bold text-slate-800 text-base flex items-center space-x-2">
                    <i class="fa-solid fa-address-card text-blue-600"></i>
                    <span>Biodata Lengkap Mahasiswa</span>
                </h3>
                <span class="text-xs bg-emerald-50 text-emerald-600 border border-emerald-200 px-2.5 py-1 rounded-md font-medium">Terverifikasi</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                    <p class="text-xs text-slate-400 font-medium">Nomor Pokok Mahasiswa (NPM)</p>
                    <p class="font-bold text-slate-800 mt-1"><?= esc($mhs['npm']); ?></p>
                </div>

                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                    <p class="text-xs text-slate-400 font-medium">Nama Lengkap</p>
                    <p class="font-bold text-slate-800 mt-1"><?= esc($mhs['nama_mahasiswa']); ?></p>
                </div>

                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                    <p class="text-xs text-slate-400 font-medium">Jenis Kelamin</p>
                    <p class="font-bold text-slate-800 mt-1">
                        <?= $mhs['jenis_kelamin'] === 'L' ? 'Laki-Laki' : 'Perempuan'; ?>
                    </p>
                </div>

                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                    <p class="text-xs text-slate-400 font-medium">Tempat, Tanggal Lahir</p>
                    <p class="font-bold text-slate-800 mt-1">
                        <?= esc($mhs['tempat_lahir'] . ', ' . format_date($mhs['tanggal_lahir'])); ?>
                    </p>
                </div>

                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                    <p class="text-xs text-slate-400 font-medium">Tanggal Masuk</p>
                    <p class="font-bold text-slate-800 mt-1">
                        <?= esc(format_date($mhs['tanggal_masuk'])); ?>
                    </p>
                </div>

                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                    <p class="text-xs text-slate-400 font-medium">Fakultas / Program Studi</p>
                    <p class="font-bold text-slate-800 mt-1">
                        <?= esc($mhs['nama_fakultas']); ?> / <?= esc($mhs['nama_program_studi']); ?>
                    </p>
                </div>

                <div class="sm:col-span-2 bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                    <p class="text-xs text-slate-400 font-medium">Alamat Lengkap</p>
                    <p class="font-semibold text-slate-800 mt-1"><?= esc($mhs['alamat']); ?></p>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: QUICK MENU & ANNOUNCEMENTS -->
        <div class="space-y-6">

            <!-- Ringkasan Akademik -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                <h3 class="font-bold text-slate-800 text-base flex items-center space-x-2">
                    <i class="fa-solid fa-chart-pie text-blue-600"></i>
                    <span>Ringkasan Akademik</span>
                </h3>

                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-blue-50 rounded-xl text-blue-900">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-book-bookmark text-blue-600"></i>
                            <span class="text-xs font-medium">Status Pembayaran</span>
                        </div>
                        <span class="text-xs font-bold bg-blue-200 text-blue-800 px-2 py-0.5 rounded">Lunas</span>
                    </div>

                    <div class="flex justify-between items-center p-3 bg-purple-50 rounded-xl text-purple-900">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-building-columns text-purple-600"></i>
                            <span class="text-xs font-medium">Tahun Akademik</span>
                        </div>
                        <span class="text-xs font-bold text-purple-800">2026/2027 Ganjil</span>
                    </div>
                </div>
            </div>

            <!-- Akses Cepat -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                <h3 class="font-bold text-slate-800 text-base flex items-center space-x-2">
                    <i class="fa-solid fa-rocket text-blue-600"></i>
                    <span>Akses Cepat</span>
                </h3>

                <div class="grid grid-cols-2 gap-3">
                    <a href="/" class="p-3 bg-slate-50 hover:bg-blue-50 hover:text-blue-600 rounded-xl border border-slate-100 text-center transition group">
                        <i class="fa-solid fa-globe text-lg text-slate-400 group-hover:text-blue-600 mb-1 block"></i>
                        <span class="text-xs font-medium">Landing Page</span>
                    </a>
                    <a href="#" class="p-3 bg-slate-50 hover:bg-blue-50 hover:text-blue-600 rounded-xl border border-slate-100 text-center transition group">
                        <i class="fa-solid fa-file-invoice text-lg text-slate-400 group-hover:text-blue-600 mb-1 block"></i>
                        <span class="text-xs font-medium">KRS Online</span>
                    </a>
                    <a href="/profil" class="p-3 bg-slate-50 hover:bg-blue-50 hover:text-blue-600 rounded-xl border border-slate-100 text-center transition group">
                        <i class="fa-solid fa-user-pen text-lg text-slate-400 group-hover:text-blue-600 mb-1 block"></i>
                        <span class="text-xs font-medium">Profil Saya</span>
                    </a>
                    <a href="/ubah-password" class="p-3 bg-slate-50 hover:bg-blue-50 hover:text-blue-600 rounded-xl border border-slate-100 text-center transition group">
                        <i class="fa-solid fa-key text-lg text-slate-400 group-hover:text-blue-600 mb-1 block"></i>
                        <span class="text-xs font-medium">Ganti Password</span>
                    </a>
                </div>
            </div>

        </div>

    </div>

</main>

<!-- 3. FOOTER -->
<footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-500">
    <p>© 2026 Universitas Contoh. Sistem Informasi Akademik.</p>
</footer>
