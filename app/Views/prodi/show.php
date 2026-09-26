<?php
/**
 * @var array<string, mixed>             $programStudi
 * @var array<int, array<string, mixed>> $mahasiswaList
 */
$bodyClass = 'bg-slate-50 text-slate-800 font-sans antialiased';
$navPrefix = '/';
$isHome    = false;
?>
<?php require __DIR__ . '/../partials/public-navbar.php'; ?>

<!-- HEADER DETAIL PROGRAM STUDI -->
<section class="bg-gradient-to-b from-blue-50/60 to-white py-12 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <nav class="text-xs text-slate-500 mb-5 flex items-center space-x-2">
            <a href="/" class="hover:text-blue-600 transition">Beranda</a>
            <i class="fa-solid fa-chevron-right text-[9px]"></i>
            <a href="/#program-studi" class="hover:text-blue-600 transition">Program Studi</a>
            <i class="fa-solid fa-chevron-right text-[9px]"></i>
            <span class="text-slate-700 font-medium"><?= esc($programStudi['nama_program_studi']); ?></span>
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-xl bg-blue-600 text-white flex items-center justify-center text-2xl shadow-sm">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 leading-tight">
                        <?= esc($programStudi['nama_program_studi']); ?>
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">
                        <i class="fa-solid fa-building-columns text-xs mr-1"></i>Fakultas <?= esc($programStudi['nama_fakultas']); ?>
                    </p>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl px-6 py-3 shadow-sm text-center">
                <p class="text-2xl font-extrabold text-blue-600"><?= number_format((int) $programStudi['jumlah_mhs']); ?></p>
                <p class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Mahasiswa</p>
            </div>
        </div>
    </div>
</section>

<!-- DAFTAR MAHASISWA PROGRAM STUDI -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-6 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Daftar Mahasiswa</h3>
                <p class="text-slate-500 text-sm mt-1">Seluruh mahasiswa yang terdaftar pada program studi ini.</p>
            </div>
            <a href="/" class="hidden sm:inline-flex items-center space-x-2 text-xs font-medium text-slate-500 hover:text-blue-600 transition">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                <span>Kembali ke Beranda</span>
            </a>
        </div>

        <div class="overflow-x-auto bg-white rounded-xl border border-slate-200 shadow-sm">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-800 text-white text-xs uppercase font-semibold">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">NPM</th>
                        <th class="px-4 py-3">Nama Mahasiswa</th>
                        <th class="px-4 py-3">L/P</th>
                        <th class="px-4 py-3">TTL</th>
                        <th class="px-4 py-3">Tgl Masuk</th>
                        <th class="px-4 py-3">Alamat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if ($mahasiswaList === []): ?>
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-400">Belum ada mahasiswa pada program studi ini.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($mahasiswaList as $index => $mhs): ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-3 font-medium text-slate-900"><?= $index + 1; ?></td>
                                <td class="px-4 py-3 font-semibold text-blue-600"><?= esc($mhs['npm']); ?></td>
                                <td class="px-4 py-3 font-medium text-slate-800"><?= esc($mhs['nama_mahasiswa']); ?></td>
                                <td class="px-4 py-3"><?= esc($mhs['jenis_kelamin']); ?></td>
                                <td class="px-4 py-3"><?= esc($mhs['tempat_lahir'] . ', ' . $mhs['tanggal_lahir']); ?></td>
                                <td class="px-4 py-3"><?= esc($mhs['tanggal_masuk']); ?></td>
                                <td class="px-4 py-3 text-xs text-slate-500"><?= esc($mhs['alamat']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="sm:hidden mt-4 text-center">
            <a href="/" class="inline-flex items-center space-x-2 text-xs font-medium text-slate-500 hover:text-blue-600 transition">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                <span>Kembali ke Beranda</span>
            </a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../partials/public-footer.php'; ?>
