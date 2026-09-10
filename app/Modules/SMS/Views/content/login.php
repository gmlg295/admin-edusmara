<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login App - EDUSMARA</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('public/bank/css/login-style.css') ?>">
</head>
<body>
<div id="<?= csrf_token() ?>" style="display:none;"><?= csrf_hash() ?></div>
<div class="login-wrapper">
    <!-- ============ LEFT SIDE ============ -->
    <div class="login-left">
        <div class="login-left-bg"></div>
        <div class="login-left-overlay"></div>
        <div class="login-left-content">
            <!-- Inline SVG Logo -->
            <svg class="left-logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60">
                <defs>
                    <linearGradient id="logoGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#FFFFFF;stop-opacity:0.9"/>
                        <stop offset="100%" style="stop-color:#D4A843;stop-opacity:0.9"/>
                    </linearGradient>
                </defs>
                <path d="M30 2 L58 11 V30 C58 47 45 57 30 60 C15 57 2 47 2 30 V11 L30 2Z" fill="url(#logoGrad)"/>
                <path d="M19 19 H41 V25 H26 V30 H39 V36 H26 V41 H41 V47 H19 V19Z" fill="#1A3F7A"/>
            </svg>

            <h1 class="left-title">EDUSMARA</h1>
            <p class="left-tagline">Ekosistem Digital Sekolah Menengah Atas<br>Satu platform untuk mengelola, memantau, dan mengembangkan potensi sekolah Anda.</p>

            <div class="left-features">
                <div class="feature-item">
                    <div class="feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
                    <span>Data terenkripsi & keamanan berlapis</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="fa-solid fa-chart-line"></i></div>
                    <span>Dashboard analitik real-time</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="fa-solid fa-users"></i></div>
                    <span>Kolaborasi guru, siswa & orang tua</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="fa-solid fa-mobile-screen"></i></div>
                    <span>Akses dari perangkat apa saja</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ RIGHT SIDE ============ -->
    <div class="login-right">
        <div class="login-form-container">
            <div class="form-header">
                <h2>Masuk ke Dashboard</h2>
                <p>Silakan masukkan kredensial admin Anda</p>
            </div>

            <!-- Lockout Banner -->
            <div class="lockout-banner" id="lockoutBanner">
                <i class="fa-solid fa-lock"></i>
                <div>
                    <div class="lockout-title">Akun Sementara Terkunci</div>
                    <div class="lockout-msg">
                        Terlalu banyak percobaan gagal. Coba lagi dalam
                        <span class="lockout-timer" id="lockoutTimer">00:00</span>
                    </div>
                </div>
            </div>

            <form id="loginForm" novalidate>
                <!-- Username -->
                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <div class="input-wrapper">
                        <input type="text" class="form-input" id="username" placeholder="Masukkan username" autocomplete="username" required>
                        <i class="fa-regular fa-user icon"></i>
                    </div>
                    <div class="field-error" id="errorUsername"><i class="fa-solid fa-circle-exclamation"></i> <span></span></div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" class="form-input" id="password" placeholder="Masukkan password" autocomplete="current-password" required>
                        <i class="fa-solid fa-lock icon"></i>
                        <button type="button" class="toggle-password" id="togglePassword" aria-label="Toggle password visibility">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    <div class="field-error" id="errorPassword"><i class="fa-solid fa-circle-exclamation"></i> <span></span></div>
                </div>

                <!-- CAPTCHA -->
                <div class="form-group">
                    <label class="form-label">Verifikasi Keamanan</label>
                    <div class="captcha-wrapper">
                        <div class="captcha-display" id="captchaDisplay" title="Kode verifikasi">
                            <svg class="captcha-noise" viewBox="0 0 200 48" preserveAspectRatio="none">
                                <line x1="0" y1="10" x2="200" y2="38" stroke="#ffffff" stroke-width="1"/>
                                <line x1="0" y1="35" x2="200" y2="8" stroke="#ffffff" stroke-width="1"/>
                                <line x1="20" y1="0" x2="180" y2="48" stroke="#ffffff" stroke-width="0.5"/>
                                <circle cx="40" cy="24" r="15" fill="none" stroke="#ffffff" stroke-width="0.5"/>
                                <circle cx="160" cy="20" r="10" fill="none" stroke="#ffffff" stroke-width="0.5"/>
                            </svg>
                            <span class="captcha-text" id="captchaText">----</span>
                        </div>
                        <button type="button" class="captcha-refresh" id="refreshCaptcha" title="Generate kode baru">
                            <i class="fa-solid fa-arrows-rotate"></i>
                        </button>
                        <div class="captcha-input">
                            <input type="text" class="form-input" id="captchaInput" placeholder="Hasil perhitungan?" autocomplete="off" required inputmode="numeric">
                        </div>
                    </div>
                    <div class="field-error" id="errorCaptcha"><i class="fa-solid fa-circle-exclamation"></i> <span></span></div>
                </div>

                <!-- Options -->
                <div class="form-options">
                    <label class="checkbox-wrapper">
                        <input type="checkbox" id="rememberMe">
                        <span>Ingat saya</span>
                    </label>
                    <a href="#" class="forgot-link" onclick="showToast('info','Reset Password','Hubungi administrator untuk reset password')">Lupa password?</a>
                </div>
                
                <input type="hidden" name="captchaToken" id="captchaToken">
                <!-- Submit -->
                <button type="submit" class="btn-login" id="btnLogin">
                    <span class="btn-text">Masuk</span>
                    <i class="fa-solid fa-arrow-right btn-icon"></i>
                    <div class="btn-spinner"></div>
                </button>
            </form>

            <div class="form-footer">
                <p>&copy; 2025 EDUSMARA • SMAN 3 Bengkulu Tengah</p>
                <p style="margin-top:4px;">Sistem dilindungi enkripsi SSL/TLS</p>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<script type="text/javascript">
    const baseUrl = '<?= base_url() ?>';
</script>

<script type="text/javascript" src="<?= base_url('public/bank/js/scripts/DynamicFetch.js') ?>"></script>
 <script src="<?= base_url('public/bank/js/login.js') ?>"></script>

</body>
</html>