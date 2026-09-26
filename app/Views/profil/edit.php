<?php
/**
 * @var array<string, mixed>  $mhs
 * @var array<string, string> $errors
 * @var string                $csrfToken
 * @var string|null           $flashSuccess
 */
$bodyClass = 'bg-slate-100 text-slate-800 font-sans antialiased min-h-screen flex flex-col';

$avatarFile = !empty($mhs['photo']) ? basename((string) $mhs['photo']) : null;
$avatarUrl  = $avatarFile !== null ? url('/media/profil/' . $avatarFile) : null;
$initial    = mb_strtoupper(mb_substr((string) $mhs['nama_mahasiswa'], 0, 1));

$inputClass = 'w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition';
$labelClass = 'block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5';
?>
<?php require __DIR__ . '/../partials/portal-navbar.php'; ?>

<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- NOTIFIKASI SUKSES -->
    <?php if (!empty($flashSuccess)): ?>
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-r-lg text-xs flex items-center space-x-3 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check text-lg text-emerald-500 shrink-0"></i>
            <p class="font-semibold"><?= esc($flashSuccess); ?></p>
        </div>
    <?php endif; ?>

    <!-- NOTIFIKASI GAGAL -->
    <?php if ($errors !== []): ?>
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg text-xs space-y-1 shadow-sm" role="alert">
            <p class="font-bold flex items-center space-x-2">
                <i class="fa-solid fa-circle-exclamation text-lg text-red-500"></i>
                <span>Perubahan tidak dapat disimpan</span>
            </p>
            <?php foreach ($errors as $message): ?>
                <p><?= esc($message); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- KARTU IDENTITAS -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 md:p-8 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center gap-5">
            <div class="w-20 h-20 rounded-2xl bg-white/15 border border-white/25 flex items-center justify-center overflow-hidden shrink-0">
                <?php if ($avatarUrl !== null): ?>
                    <img src="<?= esc($avatarUrl); ?>" alt="Foto profil <?= esc($mhs['nama_mahasiswa']); ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <span class="text-3xl font-extrabold"><?= esc($initial); ?></span>
                <?php endif; ?>
            </div>
            <div class="space-y-1.5">
                <span class="inline-block bg-blue-500/40 text-blue-100 text-xs font-medium px-3 py-1 rounded-full border border-blue-400/30">Profil Saya</span>
                <h2 class="text-2xl md:text-3xl font-extrabold leading-tight"><?= esc($mhs['nama_mahasiswa']); ?></h2>
                <p class="text-blue-100 text-xs md:text-sm">
                    NPM <?= esc($mhs['npm']); ?> &middot; <?= esc($mhs['nama_program_studi']); ?> &middot; Fakultas <?= esc($mhs['nama_fakultas']); ?>
                </p>
            </div>
        </div>
        <i class="fa-solid fa-id-card absolute -right-4 -bottom-6 text-9xl text-white/10 pointer-events-none"></i>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- DATA DIRI -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-slate-100 pb-4">
                <h3 class="font-bold text-slate-800 text-base flex items-center space-x-2">
                    <i class="fa-solid fa-address-card text-blue-600"></i>
                    <span>Data Diri</span>
                </h3>
                <span class="text-[11px] text-slate-400">NPM, program studi, dan fakultas hanya dapat diubah oleh admin</span>
            </div>

            <form action="/profil" method="POST" class="space-y-5">
                <input type="hidden" name="_token" value="<?= esc($csrfToken); ?>">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="nama_mahasiswa" class="<?= $labelClass; ?>">Nama Lengkap</label>
                        <input type="text" id="nama_mahasiswa" name="nama_mahasiswa" required maxlength="100"
                            value="<?= esc($mhs['nama_mahasiswa']); ?>" class="<?= $inputClass; ?>">
                    </div>

                    <div>
                        <label for="npm" class="<?= $labelClass; ?>">NPM</label>
                        <input type="text" id="npm" value="<?= esc($mhs['npm']); ?>" readonly
                            class="<?= $inputClass; ?> bg-slate-100 text-slate-500 cursor-not-allowed">
                    </div>

                    <div>
                        <label for="email" class="<?= $labelClass; ?>">Email</label>
                        <input type="email" id="email" name="email" required maxlength="150"
                            value="<?= esc($mhs['email'] ?? ''); ?>" placeholder="nama@universitascontoh.ac.id"
                            class="<?= $inputClass; ?>">
                    </div>

                    <div>
                        <label for="phone" class="<?= $labelClass; ?>">Nomor HP</label>
                        <input type="tel" id="phone" name="phone" maxlength="20"
                            value="<?= esc($mhs['phone'] ?? ''); ?>" placeholder="08xxxxxxxxxx"
                            class="<?= $inputClass; ?>">
                    </div>

                    <div>
                        <label for="prodi" class="<?= $labelClass; ?>">Program Studi</label>
                        <input type="text" id="prodi" value="<?= esc($mhs['nama_program_studi']); ?>" readonly
                            class="<?= $inputClass; ?> bg-slate-100 text-slate-500 cursor-not-allowed">
                    </div>

                    <div>
                        <label for="fakultas" class="<?= $labelClass; ?>">Fakultas</label>
                        <input type="text" id="fakultas" value="<?= esc($mhs['nama_fakultas']); ?>" readonly
                            class="<?= $inputClass; ?> bg-slate-100 text-slate-500 cursor-not-allowed">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="alamat" class="<?= $labelClass; ?>">Alamat</label>
                        <textarea id="alamat" name="alamat" rows="3" maxlength="1000"
                            placeholder="Jalan, nomor, kelurahan, kota"
                            class="<?= $inputClass; ?> resize-y"><?= esc($mhs['alamat'] ?? ''); ?></textarea>
                    </div>
                </div>

                <div class="flex justify-end pt-1">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-5 rounded-lg shadow-sm transition flex items-center justify-center space-x-2 text-sm">
                        <i class="fa-solid fa-floppy-disk text-xs"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- FOTO PROFIL & KEAMANAN -->
        <div class="space-y-6">

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                <h3 class="font-bold text-slate-800 text-base flex items-center space-x-2">
                    <i class="fa-solid fa-camera text-blue-600"></i>
                    <span>Foto Profil</span>
                </h3>

                <div class="flex justify-center">
                    <div class="w-28 h-28 rounded-2xl bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center">
                        <?php if ($avatarUrl !== null): ?>
                            <img src="<?= esc($avatarUrl); ?>" alt="Foto profil <?= esc($mhs['nama_mahasiswa']); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <span class="text-3xl font-extrabold text-slate-400"><?= esc($initial); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <form action="/profil/foto" method="POST" enctype="multipart/form-data" class="space-y-3">
                    <input type="hidden" name="_token" value="<?= esc($csrfToken); ?>">

                    <label for="photo" class="<?= $labelClass; ?>">Pilih Foto Baru</label>
                    <input type="file" id="photo" name="photo" required accept="image/jpeg,image/png,image/webp"
                        class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 cursor-pointer">

                    <p class="text-[11px] text-slate-400 leading-relaxed">
                        Format JPG, JPEG, PNG, atau WEBP. Ukuran maksimal 2 MB.
                        <?php if ($avatarUrl !== null): ?>
                            Foto lama akan dihapus otomatis setelah foto baru tersimpan.
                        <?php endif; ?>
                    </p>

                    <button type="submit"
                        class="w-full bg-slate-800 hover:bg-slate-900 text-white font-semibold py-2.5 px-4 rounded-lg shadow-sm transition flex items-center justify-center space-x-2 text-sm">
                        <i class="fa-solid fa-upload text-xs"></i>
                        <span>Unggah Foto</span>
                    </button>
                </form>

                <?php if ($avatarUrl !== null): ?>
                    <form action="/profil/foto/hapus" method="POST"
                          onsubmit="return confirm('Hapus foto profil dan kembali ke avatar default?')">
                        <input type="hidden" name="_token" value="<?= esc($csrfToken); ?>">
                        <button type="submit"
                            class="w-full bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 font-semibold py-2.5 px-4 rounded-lg transition flex items-center justify-center space-x-2 text-sm">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                            <span>Hapus Foto</span>
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-3">
                <h3 class="font-bold text-slate-800 text-base flex items-center space-x-2">
                    <i class="fa-solid fa-shield-halved text-blue-600"></i>
                    <span>Keamanan Akun</span>
                </h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Ubah kata sandi secara berkala. Anda perlu memasukkan password lama untuk konfirmasi.
                </p>
                <a href="<?= esc(url('/ubah-password')); ?>"
                    class="w-full bg-slate-50 hover:bg-blue-50 hover:text-blue-600 text-slate-700 border border-slate-200 font-medium py-2.5 px-4 rounded-lg transition flex items-center justify-center space-x-2 text-sm">
                    <i class="fa-solid fa-key text-xs"></i>
                    <span>Ubah Password</span>
                </a>
            </div>

        </div>
    </div>

</main>

<footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-500">
    <p>© 2026 Universitas Contoh. Sistem Informasi Akademik.</p>
</footer>
