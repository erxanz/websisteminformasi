<?php
/**
 * Footer situs publik.
 *
 * @var string|null $navPrefix Awalan anchor: '' di landing page, '/' di halaman lain.
 */
$navPrefix = $navPrefix ?? '';
?>
<!-- FOOTER -->
<footer id="kontak" class="bg-slate-900 text-slate-300 py-12 border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8 mb-8 text-sm">
        <div>
            <div class="flex items-center space-x-3 mb-3">
                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div>
                    <h4 class="font-bold text-white text-base">Universitas Contoh</h4>
                    <p class="text-[10px] text-slate-400">Unggul · Islam · Berkemajuan</p>
                </div>
            </div>
        </div>

        <div>
            <h5 class="text-white font-semibold mb-3 text-xs uppercase tracking-wider">Tautan Cepat</h5>
            <ul class="space-y-2 text-xs text-slate-400">
                <li><a href="<?= esc($navPrefix); ?>#beranda" class="hover:text-white transition">Beranda</a></li>
                <li><a href="<?= esc($navPrefix); ?>#data-mahasiswa" class="hover:text-white transition">Data Mahasiswa</a></li>
                <li><a href="<?= esc($navPrefix); ?>#program-studi" class="hover:text-white transition">Program Studi</a></li>
                <li><a href="<?= esc($navPrefix); ?>#tentang" class="hover:text-white transition">Tentang</a></li>
            </ul>
        </div>

        <div>
            <h5 class="text-white font-semibold mb-3 text-xs uppercase tracking-wider">Sumber Daya</h5>
            <ul class="space-y-2 text-xs text-slate-400">
                <li><a href="#" class="hover:text-white transition">RDF</a></li>
                <li><a href="#" class="hover:text-white transition">OWL</a></li>
                <li><a href="#" class="hover:text-white transition">SPARQL</a></li>
                <li><a href="#" class="hover:text-white transition">Dokumentasi</a></li>
            </ul>
        </div>

        <div>
            <h5 class="text-white font-semibold mb-3 text-xs uppercase tracking-wider">Kontak</h5>
            <ul class="space-y-2 text-xs text-slate-400">
                <li class="flex items-center space-x-2"><i class="fa-solid fa-location-dot text-slate-500"></i><span>Jl. Pendidikan No. 1, Kota Bengkulu</span></li>
                <li class="flex items-center space-x-2"><i class="fa-solid fa-envelope text-slate-500"></i><span>info@universitascontoh.ac.id</span></li>
                <li class="flex items-center space-x-2"><i class="fa-solid fa-phone text-slate-500"></i><span>+62 736 123456</span></li>
            </ul>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 border-t border-slate-800 flex flex-col md:flex-row items-center justify-between text-xs text-slate-500">
        <p>© 2026 Universitas Contoh. All rights reserved.</p>
        <div class="flex space-x-4 mt-4 md:mt-0 text-base">
            <a href="#" class="hover:text-white transition"><i class="fa-brands fa-youtube"></i></a>
            <a href="#" class="hover:text-white transition"><i class="fa-brands fa-instagram"></i></a>
            <a href="#" class="hover:text-white transition"><i class="fa-brands fa-facebook"></i></a>
            <a href="#" class="hover:text-white transition"><i class="fa-brands fa-linkedin"></i></a>
        </div>
    </div>
</footer>
