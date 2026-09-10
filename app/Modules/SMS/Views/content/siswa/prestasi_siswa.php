<?php $this->extend('layout/main'); ?>
<?php $this->section('content'); ?>
<!-- ============ HEADER ============ -->
<header class="page-header">
    <div class="custom-container">
        <div class="header-panel">
            <div class="header-left">
                <button class="back-btn" onclick="window.history.back()"><i class="fa-solid fa-arrow-left"></i></button>
                <div class="header-title">
                    <h1>Siswa Berprestasi</h1>
                    <p>SMAN Taruna Bengkulu • TP 2025/2026</p>
                </div>
            </div>
            <div class="admin-badge"><i class="fa-solid fa-user-shield"></i><span>Mode Admin</span></div>
        </div>
    </div>
</header>
 
<div class="custom-container">
    <!-- ============ TABS ============ -->
    <div class="page-tabs">
        <button class="page-tab active" data-tab="list"><i class="fa-solid fa-users"></i><span>Data Siswa Berprestasi</span></button>
        <button class="page-tab" data-tab="form"><i class="fa-solid fa-user-plus"></i><span>Tambah / Edit</span></button>
    </div>

    <!-- ============ TAB: LIST ============ -->
    <div class="tab-content active" id="tab-list">
        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-icon blue"><i class="fa-solid fa-chalkboard-user"></i></div>
                <div><div class="summary-value" id="sumTotal">0</div><div class="summary-label">Total Siswa Berprestasi</div></div>
            </div>
            <div class="summary-card">
                <div class="summary-icon green"><i class="fa-solid fa-id-badge"></i></div>
                <div><div class="summary-value" id="sumPNS">0</div><div class="summary-label">PNS</div></div>
            </div>
            <div class="summary-card">
                <div class="summary-icon yellow"><i class="fa-solid fa-file-contract"></i></div>
                <div><div class="summary-value" id="sumP3K">0</div><div class="summary-label">PPPK / P3K</div></div>
            </div>
            <div class="summary-card">
                <div class="summary-icon purple"><i class="fa-solid fa-user-clock"></i></div>
                <div><div class="summary-value" id="sumHonorer">0</div><div class="summary-label">Honorer</div></div>
            </div>
        </div>

        <div class="toolbar">
            <div class="search-box">
                <div class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                <input type="search" class="search-input" id="searchGuru" placeholder="Cari nama atau NIP/NUPTK...">
            </div>
            <select class="filter-select" id="filterStatus">
                <option value="all">Semua Status</option>
                <option value="PNS">PNS</option>
                <option value="PPPK">PPPK / P3K</option>
                <option value="Honorer">Honorer</option>
                <option value="GTT">GTT</option>
            </select>
            <select class="filter-select" id="filterJabatan">
                <option value="all">Semua Jabatan</option>
                <option value="Kepala Sekolah">Kepala Sekolah</option>
                <option value="Wakil Kepala Sekolah">Wakil Kepala Sekolah</option>
                <option value="Guru Mata Pelajaran">Guru Mata Pelajaran</option>
                <option value="Guru BK">Guru BK</option>
                <option value="Staff TU">Staff TU</option>
            </select>
            <button class="btn-add" onclick="switchToForm()"><i class="fa-solid fa-plus"></i><span>Tambah Siswa Berprestasi</span></button>
        </div>

        <div class="teacher-list" id="teacherList"></div>
    </div>

    <!-- ============ TAB: FORM ============ -->
    <div class="tab-content" id="tab-form">
        <div class="form-card">
            <div class="form-header">
                <h3 id="formTitle"><i class="fa-solid fa-user-plus"></i> Tambah Data Siswa Berprestasi Baru</h3>
                <p id="formSubtitle">Lengkapi profil Siswa Berprestasi dengan benar</p>
            </div>
            <div class="form-body">
                <form id="guruForm">
                    <input type="hidden" id="editId" value="">

                    <div class="form-section">
                        <div class="form-section-title"><i class="fa-solid fa-user-tie"></i><span>Data Siswa Berprestasi</span></div>
                        
                        <div class="form-row three-col">
                            <div class="form-group">
                                <label class="form-label-custom">Nama Kelas/Ruangan <span class="required">*</span></label>
                                <select class="form-control-custom" id="inputNamaKelas" required>
                                    <option value="">Pilih</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label-custom">Pilih Siswa <span class="required">*</span></label>
                                <select class="form-control-custom" id="inputSiswa" name="inputSiswa" required>
                                    <option value="">Pilih</option>
                                    
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label-custom">Jenis Kelamin <span class="required">*</span></label>
                                <select class="form-control-custom" id="inputGender" required>
                                    <option value="">Pilih</option>
                                    <option value="L">👨 Laki-laki</option>
                                    <option value="P">👩 Perempuan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label-custom">Tempat Lahir <span class="required">*</span></label>
                                <input type="text" class="form-control-custom" id="inputTmpLahir" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label-custom">Tanggal Lahir <span class="required">*</span></label>
                                <input type="date" class="form-control-custom" id="inputTglLahir" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label-custom">Nomor HP <span class="required">*</span></label>
                                <input type="tel" class="form-control-custom" id="inputHp" placeholder="08xxxxxxxxxx" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label-custom">Email <span style="font-size:10px;color:var(--text-tertiary);">(opsional)</span></label>
                                <input type="email" class="form-control-custom" id="inputEmail" placeholder="guru@smantig.sch.id">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label-custom">Alamat <span class="required">*</span></label>
                            <textarea class="form-control-custom" id="inputAlamat" placeholder="Alamat lengkap..." required></textarea>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-section-title"><i class="fa-solid fa-graduation-cap"></i><span>Pendidikan & Jabatan</span></div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label-custom">Jenjang Pendidikan <span class="required">*</span></label>
                                <select class="form-control-custom" id="inputJenjang" required>
                                    <option value="">Pilih Jenjang</option>
                                    <option value="S1">S1 - Sarjana</option>
                                    <option value="S2">S2 - Magister</option>
                                    <option value="S3">S3 - Doktor</option>
                                    <option value="D4">D4 - Diploma IV</option>
                                    <option value="D3">D3 - Diploma III</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label-custom">Jurusan / Program Studi <span class="required">*</span></label>
                                <input type="text" class="form-control-custom" id="inputJurusan" placeholder="Contoh: Pendidikan Matematika" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label-custom">Universitas / Institusi <span class="required">*</span></label>
                                <input type="text" class="form-control-custom" id="inputUniv" placeholder="Contoh: Universitas Bengkulu" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label-custom">Tahun Lulus</label>
                                <input type="number" class="form-control-custom" id="inputTahunLulus" placeholder="2010" min="1980" max="2026">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label-custom">Jabatan <span class="required">*</span></label>
                                <select class="form-control-custom" id="inputJabatan" required>
                                    <option value="">Pilih Jabatan</option>
                                    <option value="Kepala Sekolah">Kepala Sekolah</option>
                                    <option value="Wakil Kepala Sekolah">Wakil Kepala Sekolah</option>
                                    <option value="Guru Mata Pelajaran">Guru Mata Pelajaran</option>
                                    <option value="Guru BK">Guru BK / Konseling</option>
                                    <option value="Staff TU">Staff Tata Usaha</option>
                                    <option value="Pustakawan">Pustakawan</option>
                                    <option value="Laboran">Laboran</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label-custom">Mata Pelajaran <span style="font-size:10px;color:var(--text-tertiary);">(jika guru mapel)</span></label>
                                <select class="form-control-custom" id="inputMapel">
                                    <option value="">Tidak applicable</option>
                                    <option>Matematika</option>
                                    <option>Bahasa Indonesia</option>
                                    <option>Bahasa Inggris</option>
                                    <option>Fisika</option>
                                    <option>Kimia</option>
                                    <option>Biologi</option>
                                    <option>Sejarah</option>
                                    <option>Geografi</option>
                                    <option>Ekonomi</option>
                                    <option>Sosiologi</option>
                                    <option>Pendidikan Agama Islam</option>
                                    <option>PKN</option>
                                    <option>PJOK</option>
                                    <option>Seni Budaya</option>
                                    <option>Prakarya & KWU</option>
                                    <option>TIK / Informatika</option>
                                    <option>BK / Konseling</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-section-title"><i class="fa-solid fa-briefcase"></i><span>Status Kepegawaian</span></div>
                        <div class="form-row three-col">
                            <div class="form-group">
                                <label class="form-label-custom">Status <span class="required">*</span></label>
                                <select class="form-control-custom" id="inputStatus" required>
                                    <option value="">Pilih Status</option>
                                    <option value="PNS">🏛️ PNS</option>
                                    <option value="PPPK">📋 PPPK / P3K</option>
                                    <option value="Honorer">⏰ Honorer</option>
                                    <option value="GTT">📝 GTT</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label-custom">Golongan / Pangkat</label>
                                <input type="text" class="form-control-custom" id="inputGolongan" placeholder="Contoh: III/c Penata Muda Tk.I">
                            </div>
                            <div class="form-group">
                                <label class="form-label-custom">TMT (Terhitung Mulai Tanggal)</label>
                                <input type="date" class="form-control-custom" id="inputTmt">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label-custom">Catatan Tambahan <span style="font-size:10px;color:var(--text-tertiary);">(opsional)</span></label>
                            <textarea class="form-control-custom" id="inputCatatan" placeholder="Informasi tambahan seperti sertifikasi, tugas tambahan, dll..."></textarea>
                        </div>
                    </div>


                    <div class="form-section">
                        <div class="form-section-title"><i class="fa-solid fa-briefcase"></i><span>Status Sertifikasi</span></div>
                        <div class="form-row three-col">
                            <div class="form-group">
                                <label class="form-label-custom">Tersertifikasi? <span class="required">*</span></label>
                                <select class="form-control-custom" id="inputSertifikasi" required>
                                    <option value="">Pilih Status</option>
                                    <option value="1">Tersertifikasi</option>
                                    <option value="0">Belum Tersertifikasi</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label-custom">Nomor Sertifikasi</label>
                                <input type="text" class="form-control-custom" id="inputNoSertifikasi" placeholder="Contoh: III/c Penata Muda Tk.I">
                            </div>
                            <div class="form-group">
                                <label class="form-label-custom">Bidang Sertifikasi</label>
                                <input type="text" class="form-control-custom" id="inputBidangSertifikasi" placeholder="Contoh: III/c Penata Muda Tk.I">
                            </div>
                            <div class="form-group">
                                <label class="form-label-custom">Tahun sertifikasi</label>
                                <input type="number" class="form-control-custom" id="inputTahunSertifikasi" placeholder="Contoh: 2010" min="1980" max="2026">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <div class="form-section-title"><i class="fa-solid fa-briefcase"></i><span>Status Pegawai</span></div>
                        <div class="form-row three-col">
                            <div class="form-group">
                                <label class="form-label-custom">Status Pegawai <span class="required">*</span></label>
                                <select class="form-control-custom" id="inputStatusPegawai" required>
                                    <option value="">Pilih Status</option>
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Non aktif</option>
                                    <option value="cuti">Cuti</option>
                                    <option value="mutasi">Mutasi</option>
                                    <option value="pensiun">Pensiun</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="form-actions">
                        <button type="button" class="btn-secondary-custom" onclick="closeForm()" ><i class="fa-solid fa-xmark"></i> Batal</button>
                        <button type="button" class="btn-secondary-custom" onclick="resetForm()"><i class="fa-solid fa-rotate-left"></i> Reset</button>
                        <button type="submit" class="btn-primary-custom" id="btnSubmit"><i class="fa-solid fa-floppy-disk"></i> Simpan Data Siswa Berprestasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="panel-space"></div>

<!-- ============ MODAL DETAIL ============ -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header-custom">
                <div class="modal-profile">
                    <div class="modal-avatar" id="mAvatar">BS</div>
                    <div>
                        <h5 class="modal-name" id="mName">-</h5>
                        <div class="modal-subtitle" id="mJabatan">-</div>
                        <div class="modal-nip-badge"><i class="fa-solid fa-id-badge"></i><span id="mNip">-</span></div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="position:absolute;top:20px;right:20px;z-index:3;"></button>
            </div>
            <div class="modal-body-custom">
                <div class="detail-section">
                    <div class="detail-section-title"><i class="fa-solid fa-user"></i><span>Data Pribadi</span></div>
                    <div class="detail-grid">
                        <div class="detail-item"><i class="fa-solid fa-venus-mars"></i><div><div class="detail-label">Jenis Kelamin</div><div class="detail-value" id="mGender">-</div></div></div>
                        <div class="detail-item"><i class="fa-solid fa-cake-candles"></i><div><div class="detail-label">Tanggal Lahir</div><div class="detail-value" id="mBirth">-</div></div></div>
                        <div class="detail-item"><i class="fa-solid fa-phone"></i><div><div class="detail-label">Nomor HP</div><div class="detail-value" id="mPhone">-</div></div></div>
                        <div class="detail-item"><i class="fa-solid fa-envelope"></i><div><div class="detail-label">Email</div><div class="detail-value" id="mEmail">-</div></div></div>
                        <div class="detail-item full"><i class="fa-solid fa-location-dot"></i><div><div class="detail-label">Alamat</div><div class="detail-value" id="mAddress">-</div></div></div>
                    </div>
                </div>
                <div class="detail-section">
                    <div class="detail-section-title"><i class="fa-solid fa-graduation-cap"></i><span>Pendidikan</span></div>
                    <div class="detail-grid">
                        <div class="detail-item"><i class="fa-solid fa-scroll"></i><div><div class="detail-label">Jenjang & Jurusan</div><div class="detail-value" id="mEducation">-</div></div></div>
                        <div class="detail-item"><i class="fa-solid fa-school"></i><div><div class="detail-label">Universitas</div><div class="detail-value" id="mUniv">-</div></div></div>
                        <div class="detail-item"><i class="fa-solid fa-calendar-check"></i><div><div class="detail-label">Tahun Lulus</div><div class="detail-value" id="mYear">-</div></div></div>
                        <div class="detail-item"><i class="fa-solid fa-book-open"></i><div><div class="detail-label">Mata Pelajaran</div><div class="detail-value" id="mMapel">-</div></div></div>
                    </div>
                </div>
                <div class="detail-section">
                    <div class="detail-section-title"><i class="fa-solid fa-briefcase"></i><span>Kepegawaian</span></div>
                    <div class="detail-grid">
                        <div class="detail-item"><i class="fa-solid fa-id-card"></i><div><div class="detail-label">Status</div><div class="detail-value" id="mStatus">-</div></div></div>
                        <div class="detail-item"><i class="fa-solid fa-ranking-star"></i><div><div class="detail-label">Golongan / Pangkat</div><div class="detail-value" id="mGolongan">-</div></div></div>
                        <div class="detail-item"><i class="fa-solid fa-calendar-day"></i><div><div class="detail-label">TMT</div><div class="detail-value" id="mTmt">-</div></div></div>
                        <div class="detail-item full"><i class="fa-solid fa-sticky-note"></i><div><div class="detail-label">Catatan</div><div class="detail-value" id="mNotes">-</div></div></div>
                    </div>
                </div>
            </div>
            <div class="modal-action-footer">
                <button class="modal-action-btn edit" onclick="openEditFromModal()"><i class="fa-solid fa-pen-to-square"></i><span>Ubah Data</span></button>
                <button class="modal-action-btn delete" onclick="openDeleteConfirm()"><i class="fa-solid fa-trash-can"></i><span>Hapus Siswa Berprestasi</span></button>
            </div>
        </div>
    </div>
</div>

<!-- ============ MODAL DELETE CONFIRM ============ -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body-custom" style="padding:30px 24px;text-align:center;">
                <input type="hidden" id="deleteId">
                <div class="confirm-icon danger"><i class="fa-solid fa-trash-can"></i></div>
                <h4 class="confirm-title">Hapus Data Siswa Berprestasi?</h4>
                <p class="confirm-desc">Anda akan menghapus data<br><strong id="deleteName" style="color:var(--text-primary);">-</strong><br><br>Tindakan ini <strong>tidak dapat dibatalkan</strong>.</p>
            </div>
            <div class="modal-action-footer">
                <button class="btn-secondary-custom" data-bs-dismiss="modal" style="flex:1;"><i class="fa-solid fa-xmark"></i> Batal</button>
                <button class="modal-action-btn delete" onclick="confirmDelete()" style="flex:1;"><i class="fa-solid fa-trash"></i><span>Ya, Hapus</span></button>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>