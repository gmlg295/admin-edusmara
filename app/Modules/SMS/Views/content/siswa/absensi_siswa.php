<?php $this->extend('layout/main'); ?>
<?php $this->section('content'); ?>


<!-- ============ HEADER ============ -->
<header class="page-header">
    <div class="custom-container">
        <div class="header-panel">
            <div class="header-left">
                <button class="back-btn" onclick="window.history.back()" title="Kembali">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <div class="header-title">
                    <h1>Absensi Siswa</h1>
                    <p>SMAN 3 Bengkulu Tengah • TP 2025/2026</p>
                </div>
            </div>
            <div class="admin-badge">
                <i class="fa-solid fa-user-shield"></i>
                <span>Mode Guru</span>
            </div>
        </div>
    </div>
</header>

<div class="custom-container">
    <!-- ============ MODE TABS ============ -->
    <div class="mode-tabs">
        <div class="mode-tab qr active" data-mode="qrcode">
            <span class="mode-badge recommended">✨ Modern</span>
            <div class="mode-icon"><i class="fa-solid fa-qrcode"></i></div>
            <div class="mode-title">Absensi QR Code</div>
            <div class="mode-desc">Siswa scan QR dengan HP masing-masing untuk absen otomatis</div>
        </div>
        <div class="mode-tab manual" data-mode="manual">
            <div class="mode-icon"><i class="fa-solid fa-clipboard-user"></i></div>
            <div class="mode-title">Absensi Manual</div>
            <div class="mode-desc">Panggil nama siswa satu per satu dan tandai kehadiran</div>
        </div>
        <div class="mode-tab history" data-mode="history">
            <div class="mode-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
            <div class="mode-title">Riwayat Absensi</div>
            <div class="mode-desc">Lihat rekap absensi yang sudah dilakukan sebelumnya</div>
        </div>
    </div>

    <!-- ============ MODE 1: QR CODE ============ -->
    <div class="mode-content active" id="content-qrcode">
        <!-- Setup Form -->
        <div class="setup-card" id="qrSetupCard">
            <div class="setup-header">
                <i class="fa-solid fa-gear"></i>
                <h3>Pengaturan Sesi Absensi QR</h3>
            </div>
            <div class="setup-body">
                <div class="form-row three-col">
                    <div class="form-group">
                        <label class="form-label-custom">Kelas <span class="required">*</span></label>
                        <select class="form-control-custom" id="qrKelas">
                            <option value="">Pilih Kelas</option>
                            <option value="10">Kelas 10</option>
                            <option value="11">Kelas 11</option>
                            <option value="12">Kelas 12</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">Nama Kelas <span class="required">*</span></label>
                        <select class="form-control-custom" id="qrNamaKelas">
                            <option value="">Pilih Rombel</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">Mata Pelajaran <span class="required">*</span></label>
                        <select class="form-control-custom" id="qrMapel">
                            <option value="">Pilih Mapel</option>
                            <option>Matematika</option>
                            <option>Bahasa Indonesia</option>
                            <option>Bahasa Inggris</option>
                            <option>Fisika</option>
                            <option>Kimia</option>
                            <option>Biologi</option>
                            <option>Sejarah</option>
                            <option>Geografi</option>
                            <option>Ekonomi</option>
                            <option>Pendidikan Agama</option>
                            <option>PJOK</option>
                            <option>Seni Budaya</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label-custom">Judul Materi Hari Ini <span class="required">*</span></label>
                    <input type="text" class="form-control-custom" id="qrMateri" placeholder="Contoh: Persamaan Kuadrat dan Aplikasinya">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label-custom">Durasi Absensi (menit)</label>
                        <select class="form-control-custom" id="qrDurasi">
                            <option value="5">5 menit</option>
                            <option value="10">10 menit</option>
                            <option value="15" selected>15 menit</option>
                            <option value="20">20 menit</option>
                            <option value="30">30 menit</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">Jam Mulai</label>
                        <input type="time" class="form-control-custom" id="qrJamMulai">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label-custom">Keterangan <span style="font-size:10px;color:var(--text-tertiary);">(opsional)</span></label>
                    <textarea class="form-control-custom" id="qrKeterangan" placeholder="Catatan tambahan untuk sesi absensi ini..."></textarea>
                </div>
                <button class="btn-start-session" onclick="startQRSession()">
                    <i class="fa-solid fa-qrcode"></i>
                    <span>Mulai Sesi Absensi QR</span>
                </button>
            </div>
        </div>

        <!-- Active Session -->
        <div class="hidden" id="qrActiveSession">
            <div class="qr-layout">
                <!-- QR Display -->
                <div class="qr-display-card">
                    <div class="session-status active">
                        <span class="status-dot"></span>
                        <span>Sesi Berlangsung</span>
                    </div>

                    <div class="qr-wrapper">
                        <img id="qrCodeImage" src="" alt="QR Code Absensi">
                    </div>

                    <div style="font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:4px;">
                        <i class="fa-solid fa-mobile-screen"></i> Arahkan kamera HP ke QR Code
                    </div>
                    <div style="font-size:11px;color:var(--text-tertiary);">
                        Siswa scan untuk absen otomatis
                    </div>

                    <div class="timer-display">
                        <i class="fa-solid fa-hourglass-half"></i>
                        <div>
                            <div class="timer-value" id="qrTimer">15:00</div>
                            <div class="timer-label">Sisa Waktu Absensi</div>
                        </div>
                    </div>

                    <div class="session-info">
                        <div class="session-info-row">
                            <span class="session-info-label">Kelas</span>
                            <span class="session-info-value" id="sessionKelas">-</span>
                        </div>
                        <div class="session-info-row">
                            <span class="session-info-label">Mata Pelajaran</span>
                            <span class="session-info-value" id="sessionMapel">-</span>
                        </div>
                        <div class="session-info-row">
                            <span class="session-info-label">Materi</span>
                            <span class="session-info-value" id="sessionMateri">-</span>
                        </div>
                        <div class="session-info-row">
                            <span class="session-info-label">Kode Sesi</span>
                            <span class="session-info-value" style="color:var(--primary);" id="sessionCode">-</span>
                        </div>
                    </div>

                    <div style="display:flex;gap:10px;margin-top:16px;">
                        <button class="btn-start-session" style="background:var(--bg-primary);color:var(--text-primary);box-shadow:none;flex:1;" onclick="refreshQR()">
                            <i class="fa-solid fa-rotate"></i> Refresh QR
                        </button>
                        <button class="btn-start-session" style="background:var(--danger);box-shadow:0 4px 12px rgba(239,68,68,0.3);flex:1;" onclick="endQRSession()">
                            <i class="fa-solid fa-stop"></i> Akhiri Sesi
                        </button>
                    </div>

                    <!-- Demo: Simulate Scan -->
                    <button class="btn-start-session" style="background:var(--bg-gradient-green);margin-top:10px;" onclick="simulateScan()">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        <span>Simulasi Siswa Scan (Demo)</span>
                    </button>
                </div>

                <!-- Live Counter -->
                <div class="live-counter-card">
                    <div class="counter-header">
                        <div class="counter-title">
                            <i class="fa-solid fa-users" style="color:var(--secondary);"></i>
                            <span>Kehadiran Real-time</span>
                        </div>
                        <span style="font-size:11px;color:var(--text-tertiary);" id="lastUpdate">Update: -</span>
                    </div>
                    <div class="counter-body">
                        <div class="counter-grid">
                            <div class="counter-item highlight">
                                <div class="counter-value" id="countHadirQR">0</div>
                                <div class="counter-label">Hadir</div>
                            </div>
                            <div class="counter-item">
                                <div class="counter-value" id="countTotalQR">0</div>
                                <div class="counter-label">Total Siswa</div>
                            </div>
                        </div>

                        <div class="progress-wrapper">
                            <div class="progress-info">
                                <span>Progress Kehadiran</span>
                                <span id="progressPercent">0%</span>
                            </div>
                            <div class="progress-bar-bg">
                                <div class="progress-bar-fill" id="progressBar" style="width:0%;"></div>
                            </div>
                        </div>

                        <div style="font-size:12px;font-weight:700;margin-bottom:8px;display:flex;align-items:center;gap:6px;">
                            <i class="fa-solid fa-list-check" style="color:var(--secondary);"></i>
                            <span>Siswa Sudah Absen</span>
                        </div>
                        <div class="attendee-list" id="attendeeList">
                            <div class="empty-state" style="padding:20px;">
                                <i class="fa-solid fa-user-clock" style="font-size:32px;"></i>
                                <p>Belum ada siswa yang absen</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ MODE 2: MANUAL ============ -->
    <div class="mode-content" id="content-manual">
        <!-- Setup Form Manual -->
        <div class="setup-card" id="manualSetupCard">
            <div class="setup-header" style="background:var(--bg-gradient-green);">
                <i class="fa-solid fa-clipboard-user"></i>
                <h3>Pengaturan Absensi Manual</h3>
            </div>
            <div class="setup-body">
                <div class="form-row three-col">
                    <div class="form-group">
                        <label class="form-label-custom">Kelas <span class="required">*</span></label>
                        <select class="form-control-custom" id="manualKelas">
                            <option value="">Pilih Kelas</option>
                            <option value="10">Kelas 10</option>
                            <option value="11">Kelas 11</option>
                            <option value="12">Kelas 12</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">Nama Kelas <span class="required">*</span></label>
                        <select class="form-control-custom" id="manualNamaKelas">
                            <option value="">Pilih Rombel</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">Mata Pelajaran <span class="required">*</span></label>
                        <select class="form-control-custom" id="manualMapel">
                            <option value="">Pilih Mapel</option>
                            <option>Matematika</option>
                            <option>Bahasa Indonesia</option>
                            <option>Bahasa Inggris</option>
                            <option>Fisika</option>
                            <option>Kimia</option>
                            <option>Biologi</option>
                            <option>Sejarah</option>
                            <option>Geografi</option>
                            <option>Ekonomi</option>
                            <option>Pendidikan Agama</option>
                            <option>PJOK</option>
                            <option>Seni Budaya</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label-custom">Judul Materi Hari Ini <span class="required">*</span></label>
                    <input type="text" class="form-control-custom" id="manualMateri" placeholder="Contoh: Sistem Persamaan Linear Tiga Variabel">
                </div>
                <button class="btn-start-session" style="background:var(--bg-gradient-green);box-shadow:0 4px 12px rgba(16,185,129,0.3);" onclick="startManualSession()">
                    <i class="fa-solid fa-clipboard-check"></i>
                    <span>Mulai Absensi Manual</span>
                </button>
            </div>
        </div>

        <!-- Manual Session Active -->
        <div class="hidden" id="manualActiveSession">
            <div class="manual-layout">
                <!-- Summary Panel -->
                <div class="summary-panel">
                    <div style="margin-bottom:16px;">
                        <div style="font-size:14px;font-weight:700;margin-bottom:4px;" id="manualSessionInfo">10.I • Matematika</div>
                        <div style="font-size:11px;color:var(--text-tertiary);" id="manualSessionMateri">Materi: -</div>
                    </div>

                    <div class="summary-grid">
                        <div class="summary-item hadir">
                            <div class="value" id="countH">0</div>
                            <div class="label">Hadir</div>
                        </div>
                        <div class="summary-item izin">
                            <div class="value" id="countI">0</div>
                            <div class="label">Izin</div>
                        </div>
                        <div class="summary-item sakit">
                            <div class="value" id="countS">0</div>
                            <div class="label">Sakit</div>
                        </div>
                        <div class="summary-item alpa">
                            <div class="value" id="countA">0</div>
                            <div class="label">Alpa</div>
                        </div>
                    </div>

                    <div class="progress-wrapper">
                        <div class="progress-info">
                            <span>Progress</span>
                            <span id="manualProgress">0/30</span>
                        </div>
                        <div class="progress-bar-bg">
                            <div class="progress-bar-fill" id="manualProgressBar" style="width:0%;"></div>
                        </div>
                    </div>

                    <button class="btn-save-absensi" onclick="saveManualAbsensi()">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Absensi</span>
                    </button>
                    <button class="btn-start-session" style="background:var(--bg-primary);color:var(--text-primary);box-shadow:none;margin-top:8px;" onclick="cancelManualSession()">
                        <i class="fa-solid fa-xmark"></i>
                        <span>Batal</span>
                    </button>
                </div>

                <!-- Student List -->
                <div class="student-attendance-list">
                    <div class="list-header">
                        <div class="search-mini">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="search" id="searchStudent" placeholder="Cari siswa...">
                        </div>
                        <button class="btn-mark-all" onclick="markAllHadir()">
                            <i class="fa-solid fa-check-double"></i> Tandai Semua Hadir
                        </button>
                    </div>
                    <div id="manualStudentList"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ MODE 3: HISTORY ============ -->
    <div class="mode-content" id="content-history">
        <div class="history-filters">
            <select class="form-control-custom" style="width:auto;min-width:140px;" id="filterHistoryKelas">
                <option value="all">Semua Kelas</option>
                <option value="10">Kelas 10</option>
                <option value="11">Kelas 11</option>
                <option value="12">Kelas 12</option>
            </select>
            <select class="form-control-custom" style="width:auto;min-width:160px;" id="filterHistoryMapel">
                <option value="all">Semua Mapel</option>
                <option>Matematika</option>
                <option>Bahasa Indonesia</option>
                <option>Fisika</option>
            </select>
            <input type="date" class="form-control-custom" style="width:auto;" id="filterHistoryDate">
        </div>
        <div id="historyList">
            <!-- Filled by JS -->
        </div>
    </div>
</div>

<?php $this->endSection(); ?>