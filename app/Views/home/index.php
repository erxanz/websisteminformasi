<?php
/**
 * @var int                              $totalMahasiswa
 * @var int                              $totalProdi
 * @var int                              $totalFakultas
 * @var array<int, array<string,mixed>>  $prodiList
 * @var array<int, array<string,mixed>>  $mahasiswaList
 * @var \App\Core\Paginator             $paginator
 * @var string                           $search
 * @var array<int, string>               $cardColors
 * @var array<int, string>               $cardIcons
 */
$bodyClass = 'bg-slate-50 text-slate-800 font-sans antialiased';
?>
<?php $navPrefix = ''; $isHome = true; require __DIR__ . '/../partials/public-navbar.php'; ?>

<!-- 2. HERO SECTION -->
<section id="beranda" class="relative bg-gradient-to-b from-blue-50/60 to-white py-16 md:py-20 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-12 items-center">
        <div>
            <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 leading-tight">
                Data Mahasiswa <br>
                <span class="text-blue-600">Per Program Studi</span>
            </h2>
            <p class="mt-4 text-slate-600 text-base leading-relaxed">
                Akses, eksplorasi, dan manfaatkan data mahasiswa secara terbuka, terstruktur, dan terhubung untuk mendukung tata kelola universitas yang lebih baik.
            </p>

            <div class="mt-8 flex flex-wrap gap-4">
                <a href="#data-mahasiswa" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-3 rounded-lg flex items-center space-x-2 shadow-sm transition">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    <span>Lihat Data Mahasiswa</span>
                </a>
                <a href="#web-semantik" class="border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-medium px-5 py-3 rounded-lg flex items-center space-x-2 shadow-sm transition">
                    <i class="fa-solid fa-book-open text-sm text-slate-500"></i>
                    <span>Pelajari Web Semantik</span>
                </a>
            </div>

            <p class="mt-8 italic text-slate-500 text-sm">
                "Data yang terhubung, pengetahuan yang lebih luas"
            </p>
        </div>

        <!-- Graphic / Mockup -->
        <div class="relative flex justify-center">
            <div class="relative w-full max-w-lg bg-white rounded-xl shadow-xl border border-slate-200 p-4">
                <div class="bg-slate-900 rounded-lg p-3 text-white">
                    <div class="flex items-center space-x-1.5 mb-3">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                    </div>
                    <div class="bg-white text-slate-800 rounded p-4 h-60 flex flex-col items-center justify-center text-center relative overflow-hidden">
                        <div class="flex items-center justify-center space-x-6">
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center text-lg"><i class="fa-solid fa-user-graduate"></i></div>
                                <span class="text-[10px] font-semibold mt-1">Mahasiswa</span>
                            </div>
                            <div class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-full border border-blue-200">RDF</div>
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full bg-purple-500 text-white flex items-center justify-center text-lg"><i class="fa-solid fa-graduation-cap"></i></div>
                                <span class="text-[10px] font-semibold mt-1">Program Studi</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-8 mt-4">
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center text-lg"><i class="fa-solid fa-book"></i></div>
                                <span class="text-[10px] font-semibold mt-1">Mata Kuliah</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full bg-amber-500 text-white flex items-center justify-center text-lg"><i class="fa-solid fa-building-columns"></i></div>
                                <span class="text-[10px] font-semibold mt-1">Universitas</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3. PROGRAM STUDI SECTION (DINAMIS DARI DATABASE) -->
<section id="program-studi" class="py-16 bg-slate-50/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Daftar Program Studi</h2>
                <p class="text-slate-500 text-sm mt-1">Pilih program studi untuk melihat data mahasiswa secara detail</p>
            </div>
        </div>

        <!-- Grid Dynamic Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <?php if ($prodiList === []): ?>
                <div class="col-span-4 text-center py-6 text-slate-500">Belum ada data program studi.</div>
            <?php else: ?>
                <?php foreach ($prodiList as $index => $prodi): ?>
                    <a href="/program-studi/<?= (int) $prodi['id_program_studi']; ?>" class="p-4 bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition flex items-center justify-between group">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg <?= esc($cardColors[$index % count($cardColors)]); ?> text-white flex items-center justify-center text-lg">
                                <i class="fa-solid <?= esc($cardIcons[$index % count($cardIcons)]); ?>"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm group-hover:text-blue-600 transition"><?= esc($prodi['nama_program_studi']); ?></h4>
                                <p class="text-xs text-slate-500"><?= (int) $prodi['jumlah_mhs']; ?> Mahasiswa</p>
                            </div>
                        </div>
                        <i class="fa-solid fa-arrow-right text-slate-400 group-hover:text-blue-600 group-hover:translate-x-1 transition text-sm"></i>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- 4. DATA MAHASISWA SECTION (DINAMIS DARI DATABASE) -->
<section id="data-mahasiswa" class="py-16 bg-white border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Data Mahasiswa Terdaftar</h2>
                <p class="text-slate-500 text-sm mt-1">Tabel eksplorasi seluruh data mahasiswa yang ada di dalam database.</p>
            </div>

            <!-- Pencarian -->
            <form action="/#table-mhs" method="GET" class="flex items-center gap-2">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </div>
                    <input type="search" name="q" value="<?= esc($search); ?>" maxlength="50"
                        placeholder="Cari NPM, nama, prodi, fakultas..."
                        class="w-full sm:w-72 pl-9 pr-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition">
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow-sm transition">Cari</button>
                <?php if ($search !== ''): ?>
                    <a href="/#table-mhs" class="text-xs font-medium text-slate-500 hover:text-blue-600 transition whitespace-nowrap">Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Ringkasan hasil -->
        <p class="text-xs text-slate-500 mb-3">
            <?php if ($paginator->total() === 0): ?>
                Tidak ada mahasiswa yang cocok<?= $search !== '' ? ' dengan kata kunci &ldquo;' . esc($search) . '&rdquo;' : ''; ?>.
            <?php else: ?>
                Menampilkan <span class="font-semibold text-slate-700"><?= $paginator->from(); ?></span>&ndash;<span class="font-semibold text-slate-700"><?= $paginator->to(); ?></span>
                dari <span class="font-semibold text-slate-700"><?= number_format($paginator->total()); ?></span> mahasiswa<?= $search !== '' ? ' (hasil pencarian untuk &ldquo;' . esc($search) . '&rdquo;)' : ''; ?>.
            <?php endif; ?>
        </p>

        <div id="table-mhs" class="overflow-x-auto bg-white rounded-xl border border-slate-200 shadow-sm">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-800 text-white text-xs uppercase font-semibold">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">NPM</th>
                        <th class="px-4 py-3">Nama Mahasiswa</th>
                        <th class="px-4 py-3">L/P</th>
                        <th class="px-4 py-3">TTL</th>
                        <th class="px-4 py-3">Tgl Masuk</th>
                        <th class="px-4 py-3">Program Studi</th>
                        <th class="px-4 py-3">Fakultas</th>
                        <th class="px-4 py-3">Alamat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if ($mahasiswaList === []): ?>
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-slate-400">
                                <?= $search === ''
                                    ? 'Belum ada data mahasiswa yang tersimpan.'
                                    : 'Tidak ada mahasiswa yang cocok dengan kata kunci tersebut.'; ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($mahasiswaList as $index => $mhs): ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-3 font-medium text-slate-900"><?= $paginator->offset() + $index + 1; ?></td>
                                <td class="px-4 py-3 font-semibold text-blue-600"><?= esc($mhs['npm']); ?></td>
                                <td class="px-4 py-3 font-medium text-slate-800"><?= esc($mhs['nama_mahasiswa']); ?></td>
                                <td class="px-4 py-3"><?= esc($mhs['jenis_kelamin']); ?></td>
                                <td class="px-4 py-3"><?= esc($mhs['tempat_lahir'] . ', ' . $mhs['tanggal_lahir']); ?></td>
                                <td class="px-4 py-3"><?= esc($mhs['tanggal_masuk']); ?></td>
                                <td class="px-4 py-3"><span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-semibold"><?= esc($mhs['nama_program_studi']); ?></span></td>
                                <td class="px-4 py-3"><?= esc($mhs['nama_fakultas']); ?></td>
                                <td class="px-4 py-3 text-xs text-slate-500"><?= esc($mhs['alamat']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Navigasi halaman -->
        <?php if ($paginator->hasPages()): ?>
        <nav class="mt-4 bg-white border border-slate-200 rounded-xl shadow-sm px-4 py-3 flex flex-col sm:flex-row items-center justify-between gap-3" aria-label="Navigasi halaman">
            <?php if ($paginator->currentPage() > 1): ?>
                <a href="<?= esc($paginator->url($paginator->currentPage() - 1)); ?>#table-mhs"
                   class="text-xs font-medium text-slate-600 hover:text-blue-600 border border-slate-200 rounded-lg px-3 py-2 transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    <span>Sebelumnya</span>
                </a>
            <?php else: ?>
                <span class="text-xs font-medium text-slate-300 border border-slate-100 rounded-lg px-3 py-2 flex items-center space-x-1.5 cursor-not-allowed">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    <span>Sebelumnya</span>
                </span>
            <?php endif; ?>

            <div class="flex items-center space-x-1">
                <?php foreach ($paginator->window() as $page): ?>
                    <?php if ($page === null): ?>
                        <span class="px-2 py-1.5 text-xs text-slate-400">&hellip;</span>
                    <?php elseif ($page === $paginator->currentPage()): ?>
                        <span class="px-3 py-1.5 text-xs font-bold rounded-lg bg-blue-600 text-white"><?= $page; ?></span>
                    <?php else: ?>
                        <a href="<?= esc($paginator->url($page)); ?>#table-mhs"
                           class="px-3 py-1.5 text-xs font-medium rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition"><?= $page; ?></a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <?php if ($paginator->currentPage() < $paginator->lastPage()): ?>
                <a href="<?= esc($paginator->url($paginator->currentPage() + 1)); ?>#table-mhs"
                   class="text-xs font-medium text-slate-600 hover:text-blue-600 border border-slate-200 rounded-lg px-3 py-2 transition flex items-center space-x-1.5">
                    <span>Berikutnya</span>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </a>
            <?php else: ?>
                <span class="text-xs font-medium text-slate-300 border border-slate-100 rounded-lg px-3 py-2 flex items-center space-x-1.5 cursor-not-allowed">
                    <span>Berikutnya</span>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </span>
            <?php endif; ?>
        </nav>
        <?php endif; ?>
    </div>
</section>

<!-- 5. STATISTIK BANNER (DINAMIS DARI DATABASE) -->
<section class="py-12 bg-gradient-to-r from-blue-50 via-indigo-50 to-blue-50 border-y border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
        <div class="space-y-1">
            <div class="w-10 h-10 mx-auto bg-blue-600 text-white rounded-full flex items-center justify-center mb-2">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <h3 class="text-2xl md:text-3xl font-extrabold text-slate-900"><?= number_format($totalMahasiswa); ?></h3>
            <p class="text-xs font-medium text-slate-500">Total Mahasiswa</p>
        </div>
        <div class="space-y-1">
            <div class="w-10 h-10 mx-auto bg-blue-600 text-white rounded-full flex items-center justify-center mb-2">
                <i class="fa-solid fa-users"></i>
            </div>
            <h3 class="text-2xl md:text-3xl font-extrabold text-slate-900"><?= number_format($totalProdi); ?></h3>
            <p class="text-xs font-medium text-slate-500">Program Studi</p>
        </div>
        <div class="space-y-1">
            <div class="w-10 h-10 mx-auto bg-blue-600 text-white rounded-full flex items-center justify-center mb-2">
                <i class="fa-solid fa-building-columns"></i>
            </div>
            <h3 class="text-2xl md:text-3xl font-extrabold text-slate-900"><?= number_format($totalFakultas); ?></h3>
            <p class="text-xs font-medium text-slate-500">Fakultas</p>
        </div>
        <div class="space-y-1">
            <div class="w-10 h-10 mx-auto bg-blue-600 text-white rounded-full flex items-center justify-center mb-2">
                <i class="fa-solid fa-globe"></i>
            </div>
            <h3 class="text-lg md:text-xl font-bold text-slate-900 mt-2">Data Terhubung</h3>
            <p class="text-xs font-medium text-slate-500">dengan Web Semantik</p>
        </div>
    </div>
</section>

<!-- 6. WEB SEMANTIK & TENTANG SECTION -->
<section id="web-semantik" class="py-16 bg-white">
    <div id="tentang" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-8 items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 mb-3">Membangun Ekosistem Data Terbuka</h2>
            <p class="text-slate-600 text-sm leading-relaxed mb-6">
                Dengan pendekatan Web Semantik, data mahasiswa tidak hanya disimpan, tetapi juga dapat dipahami, dihubungkan, dan dimanfaatkan oleh berbagai aplikasi untuk mendukung pendidikan, penelitian, dan inovasi.
            </p>
            <a href="#beranda" class="inline-flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg shadow-sm transition">
                <i class="fa-solid fa-book-open text-xs"></i>
                <span>Tentang Web Semantik</span>
            </a>
        </div>

        <div class="bg-slate-50 p-6 rounded-xl border border-slate-200">
            <blockquote class="italic text-slate-600 text-sm leading-relaxed">
                "Web Semantik memungkinkan data di universitas tidak hanya dilihat oleh manusia, tetapi juga dipahami oleh mesin."
            </blockquote>
            <p class="text-xs font-semibold text-blue-600 mt-3">— Menuju Universitas yang Lebih Cerdas</p>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../partials/public-footer.php'; ?>
