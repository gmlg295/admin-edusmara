<?php $this->extend('layout/main'); ?>
<?php $this->section('content'); ?>
<style>
    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .form-header-left {
        flex: 1;
    }

    .form-header h3 {
        margin: 0;
    }

    .form-header p {
        margin: 5px 0 0 0;
    }
</style>
<!-- ============ HEADER ============ -->
<header class="page-header">
    <div class="custom-container">
        <div class="header-panel">
            <div class="header-left">
                <button class="back-btn" onclick="window.history.back()" title="Kembali">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <div class="header-title">
                    <h1>Data Siswa</h1>
                    <p>SMAN 3 Bengkulu Tengah • TP 2025/2026</p>
                </div>
            </div>
            <div class="admin-badge">
                <i class="fa-solid fa-user-shield"></i>
                <span>Mode Admin</span>
            </div>
        </div>
    </div>
</header>

<div class="custom-container">
    <!-- ============ CLASS TABS ============ -->
    <div class="class-tabs">
        <button class="class-tab active kelas-10" data-kelas="10">
            <div class="tab-icon"><i class="fa-solid fa-seedling"></i></div>
            <span>Kelas 10</span>
            <span class="tab-count" id="count10">0</span>
        </button>
        <button class="class-tab kelas-11" data-kelas="11">
            <div class="tab-icon"><i class="fa-solid fa-book-open"></i></div>
            <span>Kelas 11</span>
            <span class="tab-count" id="count11">0</span>
        </button>
        <button class="class-tab kelas-12" data-kelas="12">
            <div class="tab-icon"><i class="fa-solid fa-graduation-cap"></i></div>
            <span>Kelas 12</span>
            <span class="tab-count" id="count12">0</span>
        </button>
        <button class="add-student-btn" onclick="switchToForm()">
            <i class="fa-solid fa-user-plus"></i>
            <span>Tambah Siswa</span>
        </button>
    </div>

    <!-- ============ CLASS 10 CONTENT ============ -->
    <div class="class-content active" id="content-10">
        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-icon blue"><i class="fa-solid fa-users"></i></div>
                <div class="summary-info">
                    <div class="summary-value" id="total10">0</div>
                    <div class="summary-label">Total Siswa</div>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon green"><i class="fa-solid fa-mars"></i></div>
                <div class="summary-info">
                    <div class="summary-value" id="male10">0</div>
                    <div class="summary-label">Laki-laki</div>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon rose"><i class="fa-solid fa-venus"></i></div>
                <div class="summary-info">
                    <div class="summary-value" id="female10">0</div>
                    <div class="summary-label">Perempuan</div>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon purple"><i class="fa-solid fa-door-open"></i></div>
                <div class="summary-info">
                    <div class="summary-value" id="rombel10">0</div>
                    <div class="summary-label">Rombel</div>
                </div>
            </div>
        </div>

        <div class="toolbar">
            <div class="search-box">
                <div class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                <input type="search" class="search-input" id="search10" placeholder="Cari nama atau NISN siswa...">
            </div>
            <select class="filter-select" id="filterGender10">
                <option value="all">Semua Gender</option>
                <option value="L">👨 Laki-laki</option>
                <option value="P">👩 Perempuan</option>
            </select>
            <select class="filter-select" id="filterRombel10">
                <option value="all">Semua Rombel</option>
                <option value="10.I">10.I</option>
                <option value="10.II">10.II</option>
                <option value="10.III">10.III</option>
                <option value="10.IV">10.IV</option>
                <option value="10.V">10.V</option>
            </select>
        </div>

        <div class="student-list" id="list10"></div>
    </div>

    <!-- ============ CLASS 11 CONTENT ============ -->
    <div class="class-content" id="content-11">
        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-icon blue"><i class="fa-solid fa-users"></i></div>
                <div class="summary-info">
                    <div class="summary-value" id="total11">0</div>
                    <div class="summary-label">Total Siswa</div>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon green"><i class="fa-solid fa-mars"></i></div>
                <div class="summary-info">
                    <div class="summary-value" id="male11">0</div>
                    <div class="summary-label">Laki-laki</div>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon rose"><i class="fa-solid fa-venus"></i></div>
                <div class="summary-info">
                    <div class="summary-value" id="female11">0</div>
                    <div class="summary-label">Perempuan</div>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon purple"><i class="fa-solid fa-door-open"></i></div>
                <div class="summary-info">
                    <div class="summary-value" id="rombel11">0</div>
                    <div class="summary-label">Rombel</div>
                </div>
            </div>
        </div>

        <div class="toolbar">
            <div class="search-box">
                <div class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                <input type="search" class="search-input" id="search11" placeholder="Cari nama atau NISN siswa...">
            </div>
            <select class="filter-select" id="filterGender11">
                <option value="all">Semua Gender</option>
                <option value="L">👨 Laki-laki</option>
                <option value="P">👩 Perempuan</option>
            </select>
            <select class="filter-select" id="filterRombel11">
                <option value="all">Semua Rombel</option>
                <option value="11.I">11.I</option>
                <option value="11.II">11.II</option>
                <option value="11.III">11.III</option>
                <option value="11.IV">11.IV</option>
                <option value="11.V">11.V</option>
            </select>
        </div>

        <div class="student-list" id="list11"></div>
    </div>

    <!-- ============ CLASS 12 CONTENT ============ -->
    <div class="class-content" id="content-12">
        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-icon blue"><i class="fa-solid fa-users"></i></div>
                <div class="summary-info">
                    <div class="summary-value" id="total12">0</div>
                    <div class="summary-label">Total Siswa</div>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon green"><i class="fa-solid fa-mars"></i></div>
                <div class="summary-info">
                    <div class="summary-value" id="male12">0</div>
                    <div class="summary-label">Laki-laki</div>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon rose"><i class="fa-solid fa-venus"></i></div>
                <div class="summary-info">
                    <div class="summary-value" id="female12">0</div>
                    <div class="summary-label">Perempuan</div>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon purple"><i class="fa-solid fa-door-open"></i></div>
                <div class="summary-info">
                    <div class="summary-value" id="rombel12">0</div>
                    <div class="summary-label">Rombel</div>
                </div>
            </div>
        </div>

        <div class="toolbar">
            <div class="search-box">
                <div class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                <input type="search" class="search-input" id="search12" placeholder="Cari nama atau NISN siswa...">
            </div>
            <select class="filter-select" id="filterGender12">
                <option value="all">Semua Gender</option>
                <option value="L">👨 Laki-laki</option>
                <option value="P">👩 Perempuan</option>
            </select>
            <select class="filter-select" id="filterRombel12">
                <option value="all">Semua Rombel</option>
                <option value="12.I">12.I</option>
                <option value="12.II">12.II</option>
                <option value="12.III">12.III</option>
                <option value="12.IV">12.IV</option>
                <option value="12.V">12.V</option>
            </select>
        </div>

        <div class="student-list" id="list12"></div>
    </div>

    <!-- ============ FORM TAMBAH SISWA ============ -->
    <div class="class-content" id="content-form">
        <div class="form-card">
            <div class="form-header">
                <div class="form-header-left">
                    <h3><i class="fa-solid fa-user-plus"></i> Form Tambah Siswa Baru</h3>
                    <p>Lengkapi data profil siswa dan orang tua/wali dengan benar</p>
                </div>
                <div class="form-header-actions">
                    <a href="/siswa/import" class="btn-secondary-custom" >
                        <i class="fa-solid fa-file-import"></i> Import Siswa
                    </a>
                </div>
            </div>
            <div class="form-body">
                <form id="studentForm">
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fa-solid fa-user-graduate"></i>
                            <span>Profil Siswa</span>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label-custom">Nama Lengkap <span class="required">*</span></label>
                                <input type="text" class="form-control-custom" id="inputNama" placeholder="Contoh: Ahmad Fauzi" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label-custom">NISN <span class="required">*</span></label>
                                <input type="text" class="form-control-custom" id="inputNisn" placeholder="10 digit NISN" maxlength="10" required>
                            </div>
                        </div>
                        <div class="form-row three-col">
                            <div class="form-group">
                                <label class="form-label-custom">Jenis Kelamin <span class="required">*</span></label>
                                <select class="form-control-custom" id="inputGender" required>
                                    <option value="">Pilih</option>
                                    <option value="L">👨 Laki-laki</option>
                                    <option value="P">👩 Perempuan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label-custom">Tanggal Lahir <span class="required">*</span></label>
                                <input type="date" class="form-control-custom" id="inputTglLahir" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label-custom">Nomor HP <span class="required">*</span></label>
                                <input type="tel" class="form-control-custom" id="inputHp" placeholder="08xxxxxxxxxx" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label-custom">Alamat Lengkap <span class="required">*</span></label>
                            <textarea class="form-control-custom" id="inputAlamat" placeholder="Jl. Contoh No. 123, Desa, Kecamatan, Kabupaten" required></textarea>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fa-solid fa-school"></i>
                            <span>Data Kelas & Pendaftaran</span>
                        </div>
                        <div class="form-row three-col">
                            <div class="form-group">
                                <label class="form-label-custom">Kelas <span class="required">*</span></label>
                                <select class="form-control-custom" id="inputKelas" required>
                                    <option value="">Pilih Kelas</option>
                                    <option value="10">Kelas 10</option>
                                    <option value="11">Kelas 11</option>
                                    <option value="12">Kelas 12</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label-custom">Nama Kelas <span class="required">*</span></label>
                                <select class="form-control-custom" id="inputNamaKelas" required>
                                    <option value="">Pilih Rombel</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label-custom">Diterima dari Kelas <span class="required">*</span></label>
                                <select class="form-control-custom" id="inputDiterimaDari" required>
                                    <option value="">Pilih</option>
                                    <option value="10">Kelas 10 (Siswa Baru)</option>
                                    <option value="11">Kelas 11 (Pindahan)</option>
                                    <option value="12">Kelas 12 (Pindahan)</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label-custom">Tanggal Masuk <span class="required">*</span></label>
                            <input type="date" class="form-control-custom" id="inputTglMasuk" required>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fa-solid fa-people-roof"></i>
                            <span>Profil Orang Tua / Wali</span>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label-custom">Nama Wali <span class="required">*</span></label>
                                <input type="text" class="form-control-custom" id="inputNamaWali" placeholder="Contoh: Budi Santoso" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label-custom">Nomor HP Wali <span class="required">*</span></label>
                                <input type="tel" class="form-control-custom" id="inputHpWali" placeholder="08xxxxxxxxxx" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label-custom">Alamat Wali <span class="required">*</span></label>
                            <textarea class="form-control-custom" id="inputAlamatWali" placeholder="Alamat lengkap wali" required></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label-custom">Hubungan dengan Siswa <span class="required">*</span></label>
                            <select class="form-control-custom" id="inputHubungan" required>
                                <option value="">Pilih Hubungan</option>
                                <option value="Orang Tua Kandung">👨‍👩 Orang Tua Kandung</option>
                                <option value="Orang Tua Asuh">🤱 Orang Tua Asuh</option>
                                <option value="Ayah Tiri">👨 Ayah Tiri</option>
                                <option value="Ibu Tiri">👩 Ibu Tiri</option>
                                <option value="Kakak">🧑 Kakak</option>
                                <option value="Kakek">👴 Kakek</option>
                                <option value="Nenek">👵 Nenek</option>
                                <option value="Paman/Bibi">👨‍👦 Paman/Bibi</option>
                                <option value="Wali Lainnya">👤 Wali Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-secondary-custom" onclick="resetStudentForm()">
                            <i class="fa-solid fa-rotate-left"></i> Reset
                        </button>
                        <button type="submit" class="btn-primary-custom">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Data Siswa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="panel-space"></div>

<!-- ============ MODAL DETAIL SISWA (dengan aksi) ============ -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header-custom">
                <div class="modal-profile">
                    <div class="modal-avatar" id="modalAvatar">AF</div>
                    <div>
                        <h5 class="modal-name" id="modalName">Ahmad Fauzi</h5>
                        <div class="modal-class" id="modalClass">10.I • Kelas 10</div>
                        <div class="modal-nisn">
                            <i class="fa-solid fa-id-card"></i>
                            <span id="modalNisn">0051234567</span>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="position:absolute;top:20px;right:20px;z-index:3;"></button>
            </div>
            <div class="modal-body-custom">
                <div class="detail-section">
                    <div class="detail-section-title">
                        <i class="fa-solid fa-user"></i>
                        <span>Profil Siswa</span>
                    </div>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <i class="fa-solid fa-venus-mars"></i>
                            <div>
                                <div class="detail-label">Jenis Kelamin</div>
                                <div class="detail-value" id="modalGender">Laki-laki</div>
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="fa-solid fa-cake-candles"></i>
                            <div>
                                <div class="detail-label">Tanggal Lahir</div>
                                <div class="detail-value" id="modalBirth">15 Mei 2008</div>
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="fa-solid fa-phone"></i>
                            <div>
                                <div class="detail-label">Nomor HP</div>
                                <div class="detail-value" id="modalPhone">081234567890</div>
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="fa-solid fa-calendar-check"></i>
                            <div>
                                <div class="detail-label">Tanggal Masuk</div>
                                <div class="detail-value" id="modalEntry">15 Juli 2025</div>
                            </div>
                        </div>
                        <div class="detail-item full">
                            <i class="fa-solid fa-location-dot"></i>
                            <div>
                                <div class="detail-label">Alamat</div>
                                <div class="detail-value" id="modalAddress">-</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <div class="detail-section-title">
                        <i class="fa-solid fa-school"></i>
                        <span>Data Kelas</span>
                    </div>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <i class="fa-solid fa-chalkboard"></i>
                            <div>
                                <div class="detail-label">Nama Kelas</div>
                                <div class="detail-value" id="modalClassName">10.I</div>
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="fa-solid fa-door-open"></i>
                            <div>
                                <div class="detail-label">Diterima dari Kelas</div>
                                <div class="detail-value" id="modalEntryFrom">Kelas 10 (Siswa Baru)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <div class="detail-section-title">
                        <i class="fa-solid fa-people-roof"></i>
                        <span>Profil Orang Tua / Wali</span>
                    </div>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <i class="fa-solid fa-user-tie"></i>
                            <div>
                                <div class="detail-label">Nama Wali</div>
                                <div class="detail-value" id="modalWaliName">-</div>
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="fa-solid fa-heart"></i>
                            <div>
                                <div class="detail-label">Hubungan</div>
                                <div class="detail-value" id="modalRelation">-</div>
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="fa-solid fa-phone"></i>
                            <div>
                                <div class="detail-label">HP Wali</div>
                                <div class="detail-value" id="modalWaliPhone">-</div>
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="fa-solid fa-location-dot"></i>
                            <div>
                                <div class="detail-label">Alamat Wali</div>
                                <div class="detail-value" id="modalWaliAddress">-</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- ============ MODAL ACTION FOOTER ============ -->
            <div class="modal-action-footer">
                <button class="modal-action-btn edit" onclick="openEditModal()">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span>Ubah Data</span>
                </button>
                <button class="modal-action-btn transfer" onclick="openTransferModal()">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Pindahkan Sekolah</span>
                </button>
                <button class="modal-action-btn delete" onclick="openDeleteConfirm()">
                    <i class="fa-solid fa-trash-can"></i>
                    <span>Hapus Siswa</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============ MODAL EDIT SISWA ============ -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header-custom">
                <div style="position:relative;z-index:2;">
                    <h5 class="modal-name"><i class="fa-solid fa-pen-to-square"></i> Ubah Data Siswa</h5>
                    <div class="modal-class" id="editModalSubtitle">Perbarui informasi siswa</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="position:absolute;top:20px;right:20px;z-index:3;"></button>
            </div>
            <div class="modal-body-custom" style="max-height:65vh;">
                <input type="hidden" id="editStudentId">
                
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fa-solid fa-user-graduate"></i>
                        <span>Profil Siswa</span>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label-custom">Nama Lengkap <span class="required">*</span></label>
                            <input type="text" class="form-control-custom" id="editNama" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label-custom">NISN <span class="required">*</span></label>
                            <input type="text" class="form-control-custom" id="editNisn" maxlength="10" required>
                        </div>
                    </div>
                    <div class="form-row three-col">
                        <div class="form-group">
                            <label class="form-label-custom">Jenis Kelamin <span class="required">*</span></label>
                            <select class="form-control-custom" id="editGender" required>
                                <option value="L">👨 Laki-laki</option>
                                <option value="P">👩 Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label-custom">Tanggal Lahir <span class="required">*</span></label>
                            <input type="date" class="form-control-custom" id="editTglLahir" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label-custom">Nomor HP <span class="required">*</span></label>
                            <input type="tel" class="form-control-custom" id="editHp" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">Alamat Lengkap <span class="required">*</span></label>
                        <textarea class="form-control-custom" id="editAlamat" required></textarea>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fa-solid fa-school"></i>
                        <span>Data Kelas</span>
                    </div>
                    <div class="form-row three-col">
                        <div class="form-group">
                            <label class="form-label-custom">Kelas <span class="required">*</span></label>
                            <select class="form-control-custom" id="editKelas" required>
                                <option value="10">Kelas 10</option>
                                <option value="11">Kelas 11</option>
                                <option value="12">Kelas 12</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label-custom">Nama Kelas <span class="required">*</span></label>
                            <select class="form-control-custom" id="editNamaKelas" required></select>
                        </div>
                        <div class="form-group">
                            <label class="form-label-custom">Tanggal Masuk <span class="required">*</span></label>
                            <input type="date" class="form-control-custom" id="editTglMasuk" required>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fa-solid fa-people-roof"></i>
                        <span>Profil Orang Tua / Wali</span>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label-custom">Nama Wali <span class="required">*</span></label>
                            <input type="text" class="form-control-custom" id="editNamaWali" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label-custom">Nomor HP Wali <span class="required">*</span></label>
                            <input type="tel" class="form-control-custom" id="editHpWali" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">Alamat Wali <span class="required">*</span></label>
                        <textarea class="form-control-custom" id="editAlamatWali" required></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">Hubungan dengan Siswa <span class="required">*</span></label>
                        <select class="form-control-custom" id="editHubungan" required>
                            <option value="Orang Tua Kandung">👨‍👩 Orang Tua Kandung</option>
                            <option value="Orang Tua Asuh">🤱 Orang Tua Asuh</option>
                            <option value="Ayah Tiri">👨 Ayah Tiri</option>
                            <option value="Ibu Tiri">👩 Ibu Tiri</option>
                            <option value="Kakak">🧑 Kakak</option>
                            <option value="Kakek">👴 Kakek</option>
                            <option value="Nenek">👵 Nenek</option>
                            <option value="Paman/Bibi">👨‍👦 Paman/Bibi</option>
                            <option value="Wali Lainnya">👤 Wali Lainnya</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-action-footer">
                <button class="btn-secondary-custom" data-bs-dismiss="modal" style="flex:1;">
                    <i class="fa-solid fa-xmark"></i> Batal
                </button>
                <button class="btn-primary-custom" onclick="saveEditStudent()">
                    <i class="fa-solid fa-check"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============ MODAL PINDAH SEKOLAH ============ -->
<div class="modal fade" id="transferModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header-custom transfer-header">
                <div style="position:relative;z-index:2;">
                    <h5 class="modal-name"><i class="fa-solid fa-right-from-bracket"></i> Pindahkan Siswa</h5>
                    <div class="modal-class">Proses mutasi keluar sekolah</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="position:absolute;top:20px;right:20px;z-index:3;"></button>
            </div>
            <div class="modal-body-custom">
                <input type="hidden" id="transferStudentId">
                
                <!-- Profil Siswa (Read Only) -->
                <div class="student-summary-card">
                    <div class="student-avatar avatar-male" id="transferAvatar">AF</div>
                    <div class="student-summary-info">
                        <div class="student-summary-name" id="transferName">Ahmad Fauzi</div>
                        <div class="student-summary-detail" id="transferClass">10.I • Kelas 10</div>
                        <div class="student-summary-nisn">
                            <i class="fa-solid fa-id-card"></i>
                            <span id="transferNisn">0051234567</span>
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="transfer-info-box">
                    <i class="fa-solid fa-circle-info"></i>
                    <div>
                        <div class="info-title">Informasi Penting</div>
                        <div class="info-desc">Setelah proses pindah selesai, status siswa akan berubah menjadi "Pindah" dan tidak akan muncul di daftar siswa aktif. Data tetap tersimpan untuk keperluan arsip.</div>
                    </div>
                </div>

                <!-- Form Pindah -->
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fa-solid fa-file-signature"></i>
                        <span>Data Kepindahan</span>
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">Sekolah Tujuan <span class="required">*</span></label>
                        <input type="text" class="form-control-custom" id="transferSchool" placeholder="Contoh: SMAN 1 Kota Bengkulu" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">Alasan Pindah <span class="required">*</span></label>
                        <select class="form-control-custom" id="transferReason" required>
                            <option value="">Pilih Alasan</option>
                            <option value="Mengikuti Orang Tua">👨‍👩‍👧 Mengikuti Orang Tua</option>
                            <option value="Pindah Domisili">🏠 Pindah Domisili</option>
                            <option value="Beasiswa">🎓 Beasiswa di Sekolah Lain</option>
                            <option value="Kesehatan">🏥 Alasan Kesehatan</option>
                            <option value="Permintaan Sendiri">✍️ Permintaan Sendiri</option>
                            <option value="Alasan Keluarga">👨‍👩‍👧‍👦 Alasan Keluarga</option>
                            <option value="Lainnya">📝 Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group" id="otherReasonGroup" style="display:none;">
                        <label class="form-label-custom">Alasan Lainnya <span class="required">*</span></label>
                        <textarea class="form-control-custom" id="transferOtherReason" placeholder="Jelaskan alasan pindah..."></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label-custom">No. Surat Persetujuan <span class="required">*</span></label>
                            <input type="text" class="form-control-custom" id="transferLetterNo" placeholder="Contoh: 421/123/SMAN3/2026" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label-custom">Tanggal Pindah <span class="required">*</span></label>
                            <input type="date" class="form-control-custom" id="transferDate" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">Catatan Tambahan <span style="font-size:10px;color:var(--text-tertiary);">(opsional)</span></label>
                        <textarea class="form-control-custom" id="transferNotes" placeholder="Catatan tambahan jika ada..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-action-footer">
                <button class="btn-secondary-custom" data-bs-dismiss="modal" style="flex:1;">
                    <i class="fa-solid fa-xmark"></i> Batal
                </button>
                <button class="modal-action-btn transfer" onclick="processTransfer()" style="flex:2;">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Proses Kepindahan</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============ MODAL KONFIRMASI HAPUS ============ -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body-custom" style="padding:30px 24px;text-align:center;">
                <input type="hidden" id="deleteStudentId">
                <div class="confirm-icon danger">
                    <i class="fa-solid fa-trash-can"></i>
                </div>
                <h4 class="confirm-title">Hapus Siswa?</h4>
                <p class="confirm-desc">
                    Anda akan menghapus data siswa<br>
                    <span class="confirm-student-name" id="deleteStudentName">Ahmad Fauzi</span><br><br>
                    Tindakan ini <strong>tidak dapat dibatalkan</strong>. Data akan dihapus permanen dari sistem.
                </p>
            </div>
            <div class="modal-action-footer">
                <button class="btn-secondary-custom" data-bs-dismiss="modal" style="flex:1;">
                    <i class="fa-solid fa-xmark"></i> Batal
                </button>
                <button class="modal-action-btn delete" onclick="confirmDelete()" style="flex:1;">
                    <i class="fa-solid fa-trash"></i>
                    <span>Ya, Hapus</span>
                </button>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>