<!-- ============ HEADER ============ -->
<header class="page-header">
    <div class="custom-container">
        <div class="header-panel">
            <div class="header-left">
                <button class="menu-btn" id="btnMenu" title="Buka Menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="header-brand">
                    <div class="brand-logo">
                        <img src="https://smantigque.id/public/bank/images/icon/icon-smantig-7.png" alt="Logo">
                    </div>
                    <div class="header-title">
                        <h1>EDUSMARA Admin</h1>
                        <p>SMAN 3 Bengkulu Tengah</p>
                    </div>
                </div>
            </div>
            <div class="header-actions">
                <button class="icon-btn" id="btnSearch" title="Pencarian">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <button class="icon-btn" id="btnNotif" title="Notifikasi">
                    <i class="fa-regular fa-bell"></i>
                    <span class="badge-dot"></span>
                </button>
                <div class="admin-avatar" id="btnProfile" title="Profil Admin">AD</div>
            </div>
        </div>
    </div>
</header>
<!-- ============ SIDEBAR OFFCANVAS ============ -->
<div class="offcanvas offcanvas-start sidebar-custom" tabindex="-1" id="sidebarMenu">
    <div class="sidebar-header-custom">
        <button class="sidebar-close" data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="sidebar-profile">
            <div class="profile-pic">
                <img src="https://smantigque.id/public/bank/images/icon/user.256x256.png" alt="Admin">
            </div>
            <div>
                <h3>Administrator</h3>
                <small><i class="fa-solid fa-circle-check"></i> admin@smantig.sch.id</small>
            </div>
        </div>
    </div>

    <div class="sidebar-body">
        <!-- Menu Utama -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">Menu Utama</div>
            <div class="sidebar-link active" data-menu="dashboard">
                <i class="fa-solid fa-gauge-high"></i>
                <span>Dashboard</span>
            </div>
            <div class="sidebar-link" data-menu="siswa">
                <i class="fa-solid fa-user-graduate"></i>
                <span>Data Siswa</span>
                <span class="badge-count">847</span>
            </div>
            <div class="sidebar-link" data-menu="prestasi">
                <i class="fa-solid fa-trophy"></i>
                <span>Data Prestasi</span>
                <span class="badge-count">47</span>
            </div>
            <div class="sidebar-link" data-menu="agenda">
                <i class="fa-solid fa-calendar-days"></i>
                <span>Agenda Sekolah</span>
                <span class="badge-count">8</span>
            </div>
            <div class="sidebar-link" data-menu="pengumuman">
                <i class="fa-solid fa-bullhorn"></i>
                <span>Pengumuman</span>
                <span class="badge-new">4</span>
            </div>
            
            <div class="sidebar-link" data-menu="pelanggaran">
                <i class="fa-solid fa-bullhorn"></i>
                <span>Pelanggaran Siswa </span>
                <span class="badge-new">4</span>
            </div>
        </div>

        <!-- Menu Akademik -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">Akademik</div>
            <div class="sidebar-link" data-menu="nilai">
                <i class="fa-solid fa-chart-line"></i>
                <span>Nilai & Raport</span>
            </div>
            <div class="sidebar-link" data-menu="absensi">
                <i class="fa-solid fa-clipboard-check"></i>
                <span>Absensi</span>
            </div>

            <div class="sidebar-link" data-menu="mapel">
                <i class="fa-solid fa-clipboard-check"></i>
                <span>Mata Pelajaran</span>
            </div>
            
        </div>

        <!-- Menu Sistem -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">Data Master</div>
            <div class="sidebar-link" data-menu="master/guru">
                <i class="fa-solid fa-chalkboard-user"></i>
                <span>Guru</span>
            </div>
            
            <div class="sidebar-link" data-menu="master/kelas">
                <i class="fa-solid fa-person-chalkboard"></i> 
                <span>Ruang Kelas</span>
            </div>
            
            <div class="sidebar-link" data-menu="master/mapel">
                <i class="fa-solid fa-book-open"></i> 
                <span>Mata Pelajaran</span>
            </div>
            
            <div class="sidebar-link" data-menu="master/jenis-agenda">
                <i class="fa-solid fa-clipboard-list"></i>
                <span>Jenis Agenda</span>
            </div>
            
            <div class="sidebar-link" data-menu="master/kategori-informasi">
                <i class="fa-solid fa-layer-group"></i>
                <span>Kategori Informasi</span>
            </div>
            
            <div class="sidebar-link" data-menu="master/jenis-pelanggaran">
                <i class="fa-solid fa-person-circle-xmark"></i>
                <span>Jenis Pelanggaran</span>
            </div>
            
            <div class="sidebar-link" data-menu="master/sub-pelanggaran">
                <i class="fa-solid fa-person-circle-xmark"></i>
                <span>Sub Jenis Pelanggaran</span>
            </div>
            
            <div class="sidebar-link" data-menu="master/tingkat-kasus">
                <i class="fa-solid fa-briefcase"></i>
                <span>Tingkat Kasus</span>
            </div>
            
            <div class="sidebar-link" data-menu="master/alasan-terlambat">
                <i class="fa-solid fa-calendar-xmark"></i>
                <span>Alasan Terlambat</span>
            </div>
            
            <div class="sidebar-link" data-menu="master/sanksi">
                <i class="fa-solid fa-file-lines"></i>
                <span>Sanksi</span>
            </div>
            
            <div class="sidebar-link" data-menu="master/kategori-prestasi">
                <i class="fa-solid fa-award"></i>
                <span>Kategori Prestasi</span>
            </div>
            
            <div class="sidebar-link" data-menu="master/jenjang">
                <i class="fa-solid fa-list-ol"></i>
                <span>Jenjang / Tingkat</span>
            </div>
            
            <div class="sidebar-link" data-menu="master/profil-sekolah">
                <i class="fa-solid fa-school"></i>
                <span>Profil Sekolah</span>
            </div>
            <div class="sidebar-link" data-menu="pengaturan">
                <i class="fa-solid fa-gear"></i>
                <span>Pengaturan</span>
            </div>
        </div>
    </div>

    <div class="sidebar-footer">
        <button class="sidebar-logout" id="btnLogout">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span style="font-weight:600;">Keluar</span>
        </button>
        <div class="sidebar-version">EDUSMARA v2.0 • © 2026</div>
    </div>
</div>
