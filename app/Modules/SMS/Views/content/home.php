<?php
$this->extend('layout/main');
$this->section('content');
?>


<div class="custom-container">
    <!-- ============ WELCOME BANNER ============ -->
    <div class="welcome-banner">
        <div class="welcome-content">
            <div class="welcome-text">
                <h2 id="greetingText">Selamat Pagi, Admin! 👋</h2>
                <p>Berikut ringkasan aktivitas sekolah hari ini</p>
            </div>
            <div class="welcome-date">
                <i class="fa-regular fa-calendar"></i>
                <span id="currentDate">Sabtu, 16 Agustus 2026</span>
            </div>
        </div>
    </div>
    

    <!-- ============ STATS CARDS ============ -->
    <div class="stats-grid">
        <div class="stat-card blue" onclick="navigateTo('siswa')">
            <div class="stat-header">
                <div class="stat-icon blue"><i class="fa-solid fa-user-graduate"></i></div>
                <span class="stat-trend up"><i class="fa-solid fa-arrow-up"></i> +12</span>
            </div>
            <div class="stat-value" data-count="847">0</div>
            <div class="stat-label">Total Siswa Aktif</div>
            <div class="stat-sublabel">Kelas 10: 285 • Kelas 11: 281 • Kelas 12: 281</div>
        </div>

        <div class="stat-card green" onclick="navigateTo('prestasi')">
            <div class="stat-header">
                <div class="stat-icon green"><i class="fa-solid fa-trophy"></i></div>
                <span class="stat-trend up"><i class="fa-solid fa-arrow-up"></i> +5</span>
            </div>
            <div class="stat-value" data-count="47">0</div>
            <div class="stat-label">Total Prestasi</div>
            <div class="stat-sublabel">3 Internasional • 8 Nasional • 36 Provinsi/Kab</div>
        </div>

        <div class="stat-card yellow" onclick="navigateTo('agenda')">
            <div class="stat-header">
                <div class="stat-icon yellow"><i class="fa-solid fa-calendar-check"></i></div>
                <span class="stat-trend up"><i class="fa-solid fa-arrow-up"></i> +2</span>
            </div>
            <div class="stat-value" data-count="8">0</div>
            <div class="stat-label">Agenda Bulan Ini</div>
            <div class="stat-sublabel">2 agenda minggu ini</div>
        </div>

        <div class="stat-card purple" onclick="navigateTo('pengumuman')">
            <div class="stat-header">
                <div class="stat-icon purple"><i class="fa-solid fa-bullhorn"></i></div>
                <span class="stat-trend down"><i class="fa-solid fa-arrow-down"></i> -1</span>
            </div>
            <div class="stat-value" data-count="16">0</div>
            <div class="stat-label">Pengumuman Aktif</div>
            <div class="stat-sublabel">4 pengumuman baru minggu ini</div>
        </div>
    </div>

    <!-- ============ CHARTS ROW ============ -->
    <div class="content-grid">
        <!-- Bar Chart: Prestasi per Kategori -->
        <div class="card-custom">
            <div class="card-header-custom">
                <div class="card-title-custom">
                    <div class="title-icon"><i class="fa-solid fa-chart-column"></i></div>
                    <h3>Prestasi per Kategori</h3>
                </div>
                <a class="card-link" onclick="navigateTo('prestasi')">Lihat Semua <i class="fa-solid fa-chevron-right"></i></a>
            </div>
            <div class="card-body-custom">
                <div class="bar-chart">
                    <div class="bar-item">
                        <div class="bar-value">12</div>
                        <div class="bar-fill blue" style="height: 80%;"></div>
                        <div class="bar-label">Akademik</div>
                    </div>
                    <div class="bar-item">
                        <div class="bar-value">18</div>
                        <div class="bar-fill green" style="height: 100%;"></div>
                        <div class="bar-label">Olahraga</div>
                    </div>
                    <div class="bar-item">
                        <div class="bar-value">6</div>
                        <div class="bar-fill yellow" style="height: 40%;"></div>
                        <div class="bar-label">Seni</div>
                    </div>
                    <div class="bar-item">
                        <div class="bar-value">5</div>
                        <div class="bar-fill purple" style="height: 33%;"></div>
                        <div class="bar-label">Agama</div>
                    </div>
                    <div class="bar-item">
                        <div class="bar-value">6</div>
                        <div class="bar-fill cyan" style="height: 40%;"></div>
                        <div class="bar-label">KTI</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Donut Chart: Siswa per Kelas -->
        <div class="card-custom">
            <div class="card-header-custom">
                <div class="card-title-custom">
                    <div class="title-icon"><i class="fa-solid fa-users"></i></div>
                    <h3>Distribusi Siswa</h3>
                </div>
            </div>
            <div class="card-body-custom">
                <div class="donut-container">
                    <div class="donut-chart">
                        <svg viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="40" stroke="#e2e8f0" />
                            <circle cx="50" cy="50" r="40" stroke="#1e40af" stroke-dasharray="85 251" stroke-dashoffset="0" />
                            <circle cx="50" cy="50" r="40" stroke="#10b981" stroke-dasharray="84 251" stroke-dashoffset="-85" />
                            <circle cx="50" cy="50" r="40" stroke="#8b5cf6" stroke-dasharray="84 251" stroke-dashoffset="-169" />
                        </svg>
                        <div class="donut-center">
                            <div class="value">847</div>
                            <div class="label">Total</div>
                        </div>
                    </div>
                    <div class="donut-legend">
                        <div class="legend-item">
                            <div class="legend-color" style="background:#1e40af;"></div>
                            <div class="legend-label">Kelas 10</div>
                            <div class="legend-value">285</div>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background:#10b981;"></div>
                            <div class="legend-label">Kelas 11</div>
                            <div class="legend-value">281</div>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background:#8b5cf6;"></div>
                            <div class="legend-label">Kelas 12</div>
                            <div class="legend-value">281</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ QUICK ACTIONS + AGENDA ============ -->
    <div class="content-grid">
        <!-- Quick Actions -->
        <div class="card-custom">
            <div class="card-header-custom">
                <div class="card-title-custom">
                    <div class="title-icon"><i class="fa-solid fa-bolt"></i></div>
                    <h3>Aksi Cepat</h3>
                </div>
            </div>
            <div class="card-body-custom">
                <div class="quick-actions-grid">
                    <div class="quick-action-card" onclick="navigateTo('siswa', 'Tambah Siswa Baru')">
                        <div class="quick-action-icon blue"><i class="fa-solid fa-user-plus"></i></div>
                        <div>
                            <div class="quick-action-title">Tambah Siswa</div>
                            <div class="quick-action-desc">Input siswa baru</div>
                        </div>
                    </div>
                    <div class="quick-action-card" onclick="navigateTo('prestasi', 'Input Prestasi')">
                        <div class="quick-action-icon green"><i class="fa-solid fa-medal"></i></div>
                        <div>
                            <div class="quick-action-title">Input Prestasi</div>
                            <div class="quick-action-desc">Catat prestasi siswa</div>
                        </div>
                    </div>
                    <div class="quick-action-card" onclick="navigateTo('agenda', 'Tambah Agenda')">
                        <div class="quick-action-icon yellow"><i class="fa-solid fa-calendar-plus"></i></div>
                        <div>
                            <div class="quick-action-title">Tambah Agenda</div>
                            <div class="quick-action-desc">Buat agenda baru</div>
                        </div>
                    </div>
                    <div class="quick-action-card" onclick="navigateTo('pengumuman', 'Buat Pengumuman')">
                        <div class="quick-action-icon purple"><i class="fa-solid fa-pen-to-square"></i></div>
                        <div>
                            <div class="quick-action-title">Buat Pengumuman</div>
                            <div class="quick-action-desc">Publikasi informasi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Agenda -->
        <div class="card-custom">
            <div class="card-header-custom">
                <div class="card-title-custom">
                    <div class="title-icon"><i class="fa-solid fa-calendar-day"></i></div>
                    <h3>Agenda Terdekat</h3>
                </div>
                <a class="card-link" onclick="navigateTo('agenda')">Semua <i class="fa-solid fa-chevron-right"></i></a>
            </div>
            <div class="card-body-custom">
                <div class="agenda-item" onclick="showToast('info', 'Agenda', 'Rapat Koordinasi Panitia 17 Agustus')">
                    <div class="agenda-date-box">
                        <div class="day">16</div>
                        <div class="month">Agt</div>
                    </div>
                    <div class="agenda-info">
                        <div class="agenda-title">Rapat Panitia 17 Agustus</div>
                        <div class="agenda-meta">
                            <i class="fa-regular fa-clock"></i> 14:00
                            <span class="agenda-category akademik">Rapat</span>
                        </div>
                    </div>
                </div>
                <div class="agenda-item" onclick="showToast('info', 'Agenda', 'Upacara HUT RI ke-81')">
                    <div class="agenda-date-box">
                        <div class="day">17</div>
                        <div class="month">Agt</div>
                    </div>
                    <div class="agenda-info">
                        <div class="agenda-title">Upacara HUT RI ke-81</div>
                        <div class="agenda-meta">
                            <i class="fa-regular fa-clock"></i> 07:00
                            <span class="agenda-category akademik">Akademik</span>
                        </div>
                    </div>
                </div>
                <div class="agenda-item" onclick="showToast('info', 'Agenda', 'Libur Isra Mi\'raj')">
                    <div class="agenda-date-box" style="background: var(--bg-gradient-purple);">
                        <div class="day">20</div>
                        <div class="month">Agt</div>
                    </div>
                    <div class="agenda-info">
                        <div class="agenda-title">Libur Isra Mi'raj</div>
                        <div class="agenda-meta">
                            <i class="fa-solid fa-moon"></i> Full Day
                            <span class="agenda-category libur">Libur</span>
                        </div>
                    </div>
                </div>
                <div class="agenda-item" onclick="showToast('info', 'Agenda', 'ASTS Semester Ganjil')">
                    <div class="agenda-date-box" style="background: var(--bg-gradient-gold);">
                        <div class="day">25</div>
                        <div class="month">Agt</div>
                    </div>
                    <div class="agenda-info">
                        <div class="agenda-title">ASTS Semester Ganjil</div>
                        <div class="agenda-meta">
                            <i class="fa-regular fa-clock"></i> 07:30
                            <span class="agenda-category ujian">Ujian</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ ACTIVITY + TOP STUDENTS ============ -->
    <div class="content-grid">
        <!-- Recent Activity -->
        <div class="card-custom">
            <div class="card-header-custom">
                <div class="card-title-custom">
                    <div class="title-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
                    <h3>Aktivitas Terbaru</h3>
                </div>
                <a class="card-link">Semua <i class="fa-solid fa-chevron-right"></i></a>
            </div>
            <div class="card-body-custom">
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon green"><i class="fa-solid fa-user-plus"></i></div>
                        <div class="activity-content">
                            <div class="activity-title">Siswa baru <strong>Ahmad Fauzi</strong> ditambahkan ke kelas 10.I</div>
                            <div class="activity-meta">
                                <i class="fa-solid fa-user-shield"></i> Admin
                            </div>
                        </div>
                        <div class="activity-time">5 mnt lalu</div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon yellow"><i class="fa-solid fa-trophy"></i></div>
                        <div class="activity-content">
                            <div class="activity-title">Prestasi baru: <strong>Juara 1 MTQ PAI Fair</strong> - Siti Nurhaliza</div>
                            <div class="activity-meta">
                                <i class="fa-solid fa-medal"></i> +90 poin
                            </div>
                        </div>
                        <div class="activity-time">1 jam lalu</div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon blue"><i class="fa-solid fa-bullhorn"></i></div>
                        <div class="activity-content">
                            <div class="activity-title">Pengumuman <strong>Ujian Praktik Kelas XII</strong> dipublikasikan</div>
                            <div class="activity-meta">
                                <i class="fa-solid fa-eye"></i> 245 views
                            </div>
                        </div>
                        <div class="activity-time">3 jam lalu</div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon purple"><i class="fa-solid fa-right-from-bracket"></i></div>
                        <div class="activity-content">
                            <div class="activity-title">Siswa <strong>Dewi Lestari</strong> dipindahkan ke SMAN 1 Kota Bengkulu</div>
                            <div class="activity-meta">
                                <i class="fa-solid fa-file-signature"></i> Surat: 421/123/2026
                            </div>
                        </div>
                        <div class="activity-time">5 jam lalu</div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon red"><i class="fa-solid fa-calendar-plus"></i></div>
                        <div class="activity-content">
                            <div class="activity-title">Agenda baru: <strong>Upacara HUT RI ke-81</strong> ditambahkan</div>
                            <div class="activity-meta">
                                <i class="fa-regular fa-calendar"></i> 17 Agustus 2026
                            </div>
                        </div>
                        <div class="activity-time">1 hari lalu</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Students -->
        <div class="card-custom">
            <div class="card-header-custom">
                <div class="card-title-custom">
                    <div class="title-icon" style="background:#fef3c7;color:var(--accent);"><i class="fa-solid fa-crown"></i></div>
                    <h3>Top 5 Siswa Berprestasi</h3>
                </div>
                <a class="card-link" onclick="navigateTo('prestasi', 'Leaderboard')">Leaderboard <i class="fa-solid fa-chevron-right"></i></a>
            </div>
            <div class="card-body-custom">
                <div class="top-student-item" onclick="showToast('info', 'Profil Siswa', 'Aisyah Putri - 580 poin')">
                    <div class="top-rank gold">1</div>
                    <div class="top-avatar avatar-1">AP</div>
                    <div class="top-info">
                        <div class="top-name">Aisyah Putri</div>
                        <div class="top-class">XII IPA 1</div>
                    </div>
                    <div class="top-points">580 pts</div>
                </div>
                <div class="top-student-item" onclick="showToast('info', 'Profil Siswa', 'Rizky Pratama - 425 poin')">
                    <div class="top-rank silver">2</div>
                    <div class="top-avatar avatar-2">RP</div>
                    <div class="top-info">
                        <div class="top-name">Rizky Pratama</div>
                        <div class="top-class">XII IPA 2</div>
                    </div>
                    <div class="top-points">425 pts</div>
                </div>
                <div class="top-student-item" onclick="showToast('info', 'Profil Siswa', 'Muhammad Fauzi - 395 poin')">
                    <div class="top-rank bronze">3</div>
                    <div class="top-avatar avatar-3">MF</div>
                    <div class="top-info">
                        <div class="top-name">Muhammad Fauzi</div>
                        <div class="top-class">XI IPS 1</div>
                    </div>
                    <div class="top-points">395 pts</div>
                </div>
                <div class="top-student-item" onclick="showToast('info', 'Profil Siswa', 'Dewi Anggraini - 350 poin')">
                    <div class="top-rank normal">4</div>
                    <div class="top-avatar avatar-4">DA</div>
                    <div class="top-info">
                        <div class="top-name">Dewi Anggraini</div>
                        <div class="top-class">XI IPA 3</div>
                    </div>
                    <div class="top-points">350 pts</div>
                </div>
                <div class="top-student-item" onclick="showToast('info', 'Profil Siswa', 'Budi Santoso - 320 poin')">
                    <div class="top-rank normal">5</div>
                    <div class="top-avatar avatar-5">BS</div>
                    <div class="top-info">
                        <div class="top-name">Budi Santoso</div>
                        <div class="top-class">XII IPS 2</div>
                    </div>
                    <div class="top-points">320 pts</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>