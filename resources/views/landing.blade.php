<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Logbook PKL</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/landing.css') }}" rel="stylesheet">
</head>
<body>

    {{-- ============ NAVBAR ============ --}}
    <nav class="navbar" id="navbar">
        <div class="container navbar-inner">
            <a href="{{ route('home') }}" class="navbar-brand">
                <i class="bi bi-journal-text"></i>
                Logbook PKL
            </a>

            <div class="navbar-links" id="navLinks">
                <a href="#beranda" class="nav-link">Beranda</a>
                <a href="#tentang" class="nav-link">Tentang</a>
                <a href="#fitur" class="nav-link">Fitur</a>
                <a href="#cara-kerja" class="nav-link">Cara Kerja</a>
            </div>

            <div class="navbar-actions">
                <a href="{{ route('login') }}" class="btn-ghost">Login</a>
                <a href="{{ route('register') }}" class="btn-primary-sm">Register</a>
            </div>

            <button class="mobile-toggle" id="mobileToggle" aria-label="Menu">
                <i class="bi bi-list" id="toggleIcon"></i>
            </button>
        </div>

        {{-- mobile menu --}}
        <div class="mobile-menu" id="mobileMenu">
            <a href="#beranda" class="mobile-link">Beranda</a>
            <a href="#tentang" class="mobile-link">Tentang</a>
            <a href="#fitur" class="mobile-link">Fitur</a>
            <a href="#cara-kerja" class="mobile-link">Cara Kerja</a>
            <div class="mobile-actions">
                <a href="{{ route('login') }}" class="btn-ghost" style="width:100%;text-align:center;">Login</a>
                <a href="{{ route('register') }}" class="btn-primary-sm" style="width:100%;text-align:center;">Register</a>
            </div>
        </div>
    </nav>

    {{-- ============ HERO ============ --}}
    <section class="hero" id="beranda">
        <div class="hero-glow"></div>
        <div class="hero-grid"></div>
        <div class="container hero-inner">
            <div class="hero-content">
                <h1 class="hero-title">
                    PKL bukan sekadar absen dan pulang.<br>
                    Ini adalah langkah pertama menuju <span class="highlight">karier yang kamu impikan</span>.
                </h1>
                <p class="hero-sub">
                    Catat perjalananmu. Nikmati prosesnya. Lihat sejauh apa kamu berkembang.
                </p>
                <div class="hero-cta">
                    <a href="{{ route('register') }}" class="btn-primary-lg">
                        <i class="bi bi-rocket-takeoff"></i>
                        Mulai Catat Perjalananmu
                    </a>
                    <a href="{{ route('login') }}" class="btn-ghost-lg">
                        Sudah punya akun? <span>Login</span>
                    </a>
                </div>
            </div>
            <div class="hero-visual">
                <div class="mockup">
                    <div class="mockup-bar">
                        <span class="dot red"></span>
                        <span class="dot yellow"></span>
                        <span class="dot green"></span>
                        <span class="mockup-title">Logbook PKL — Dashboard</span>
                    </div>
                    <div class="mockup-body">
                        <div class="mockup-row">
                            <div class="mockup-card mc-purple">
                                <i class="bi bi-box-arrow-in-right"></i>
                                <div>
                                    <div class="mc-label">Jam Masuk</div>
                                    <div class="mc-value">08:00</div>
                                </div>
                            </div>
                            <div class="mockup-card mc-mauve">
                                <i class="bi bi-box-arrow-right"></i>
                                <div>
                                    <div class="mc-label">Jam Keluar</div>
                                    <div class="mc-value">16:00</div>
                                </div>
                            </div>
                            <div class="mockup-card mc-peach">
                                <i class="bi bi-check-circle"></i>
                                <div>
                                    <div class="mc-label">Status</div>
                                    <div class="mc-value">Selesai</div>
                                </div>
                            </div>
                        </div>
                        <div class="mockup-timeline">
                            <div class="mt-item active">
                                <div class="mt-dot"></div>
                                <div>
                                    <div class="mt-label">Absen Masuk</div>
                                    <div class="mt-time">08:00</div>
                                </div>
                            </div>
                            <div class="mt-line"></div>
                            <div class="mt-item active">
                                <div class="mt-dot"></div>
                                <div>
                                    <div class="mt-label">Absen Keluar</div>
                                    <div class="mt-time">16:00</div>
                                </div>
                            </div>
                        </div>
                        <div class="mockup-log">
                            <div class="mockup-log-header">
                                <i class="bi bi-journal-text"></i>
                                Logbook Hari Ini
                            </div>
                            <div class="mockup-log-line"></div>
                            <div class="mockup-log-line short"></div>
                            <div class="mockup-log-line"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-fade"></div>
    </section>

    {{-- ============ STORY ============ --}}
    <section class="section section-dark" id="tentang">
        <div class="container">
            <div class="story-content">
                <p class="story-lead">Hari ini mungkin terasa biasa.</p>
                <p class="story-lead accent">Tapi suatu hari nanti, kamu akan mengingatnya.</p>

                <div class="story-lines">
                    <p>Jam pertama kali datang.</p>
                    <p>Tugas pertama yang kamu kerjakan.</p>
                    <p>Hal baru yang akhirnya kamu pahami.</p>
                    <p>Kesalahan yang mengajarkan sesuatu.</p>
                </div>

                <p class="story-closing">Semuanya adalah bagian dari perjalananmu.</p>
                <p class="story-cta-text">Jangan biarkan pengalaman itu hanya lewat begitu saja.</p>
            </div>
        </div>
    </section>

    {{-- ============ FITUR ============ --}}
    <section class="section section-surface" id="fitur">
        <div class="container">
            <div class="section-header">
                <h2>Bukan sekadar fitur.<br><span class="accent">Ini tempat kamu menyimpan perjalananmu.</span></h2>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon fi-purple">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <h3>Absensi</h3>
                    <p>Catat kehadiranmu dengan mudah setiap hari.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon fi-mauve">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <h3>Logbook</h3>
                    <p>Tulis apa yang kamu kerjakan hari ini. Sekecil apa pun itu, tetap bagian dari perjalananmu.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon fi-peach">
                        <i class="bi bi-person-check"></i>
                    </div>
                    <h3>Persetujuan Pembimbing</h3>
                    <p>Pastikan kegiatanmu tercatat dan diketahui oleh pembimbing.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon fi-purple">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h3>Riwayat</h3>
                    <p>Lihat kembali semua yang sudah kamu lewati, dari hari pertama sampai hari terakhir.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ CARA KERJA ============ --}}
    <section class="section section-dark" id="cara-kerja">
        <div class="container">
            <div class="section-header">
                <h2>Sesederhana perjalanan PKL itu sendiri.</h2>
            </div>
            <div class="steps-timeline">
                <div class="step-item">
                    <div class="step-number">01</div>
                    <div class="step-line"></div>
                    <div class="step-body">
                        <h3>Datang</h3>
                        <p>Mulai hari dengan mencatat kehadiranmu.</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-number accent">02</div>
                    <div class="step-line"></div>
                    <div class="step-body">
                        <h3>Kerjakan</h3>
                        <p>Fokus pada tugasmu, belajar hal baru, dan nikmati prosesnya.</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-number">03</div>
                    <div class="step-line"></div>
                    <div class="step-body">
                        <h3>Ceritakan</h3>
                        <p>Selesai kerja? Catat apa yang kamu kerjakan hari ini.</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-number accent">04</div>
                    <div class="step-body">
                        <h3>Simpan</h3>
                        <p>Satu hari selesai. Satu cerita tersimpan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ ABOUT / PURPOSE ============ --}}
    <section class="section section-surface">
        <div class="container">
            <div class="about-content">
                <h2 class="about-title">Karena setiap hari punya cerita.</h2>
                <p>
                    PKL bukan hanya tentang menyelesaikan kewajiban sekolah.<br>
                    Ada pengalaman, tantangan, kesalahan, dan hal-hal baru yang kamu pelajari di sepanjang perjalanan.
                </p>
                <p class="about-highlight">
                    Logbook PKL hadir untuk membantu kamu mencatat semuanya dengan lebih mudah dan teratur.
                </p>
            </div>
        </div>
    </section>

    {{-- ============ FINAL CTA ============ --}}
    <section class="section section-cta" id="mulai">
        <div class="cta-glow"></div>
        <div class="container cta-inner">
            <h2 class="cta-title">
                Mulai dari satu hari.<br>
                Catat setiap langkahnya.
            </h2>
            <p class="cta-sub">
                Karena suatu hari nanti, catatan kecil ini akan menjadi bukti seberapa jauh kamu telah berkembang.
            </p>
            <a href="{{ route('register') }}" class="btn-primary-lg cta-btn">
                <i class="bi bi-rocket-takeoff"></i>
                Mulai Perjalananmu
            </a>
        </div>
    </section>

    {{-- ============ FOOTER ============ --}}
    <footer class="footer">
        <div class="container footer-inner">
            <div class="footer-brand">
                <a href="{{ route('home') }}">
                    <i class="bi bi-journal-text"></i>
                    Logbook PKL
                </a>
                <p>Teman perjalananmu selama PKL.</p>
            </div>
            <div class="footer-copy">
                &copy; {{ date('Y') }} Logbook PKL. Dibuat untuk pelajar Indonesia.
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Sticky navbar
            var navbar = document.getElementById('navbar');
            window.addEventListener('scroll', function () {
                navbar.classList.toggle('scrolled', window.scrollY > 40);
            });

            // Mobile toggle
            var toggle = document.getElementById('mobileToggle');
            var menu = document.getElementById('mobileMenu');
            var icon = document.getElementById('toggleIcon');
            toggle.addEventListener('click', function () {
                menu.classList.toggle('open');
                icon.className = menu.classList.contains('open') ? 'bi bi-x-lg' : 'bi bi-list';
            });

            // Close mobile menu on link click
            document.querySelectorAll('.mobile-link, .mobile-actions a').forEach(function (el) {
                el.addEventListener('click', function () {
                    menu.classList.remove('open');
                    icon.className = 'bi bi-list';
                });
            });

            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(function (link) {
                link.addEventListener('click', function (e) {
                    var target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        e.preventDefault();
                        var offset = navbar.offsetHeight + 16;
                        var top = target.getBoundingClientRect().top + window.pageYOffset - offset;
                        window.scrollTo({ top: top, behavior: 'smooth' });
                    }
                });
            });

            // Active nav-link highlight on scroll
            var sections = document.querySelectorAll('section[id]');
            var navLinks = document.querySelectorAll('.navbar-links .nav-link');
            window.addEventListener('scroll', function () {
                var scrollY = window.scrollY + navbar.offsetHeight + 80;
                sections.forEach(function (sec) {
                    if (scrollY >= sec.offsetTop && scrollY < sec.offsetTop + sec.offsetHeight) {
                        navLinks.forEach(function (l) { l.classList.remove('active'); });
                        var active = document.querySelector('.navbar-links .nav-link[href="#' + sec.id + '"]');
                        if (active) active.classList.add('active');
                    }
                });
            });
        });
    </script>
</body>
</html>
