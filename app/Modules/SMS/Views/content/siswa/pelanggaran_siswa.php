<?php $this->extend('layout/main'); ?>
<?php $this->section('content'); ?>


<header class="page-header">
    <div class="custom-container">
        <div class="header-panel">
            <div class="header-left">
                <button class="back-btn" onclick="window.history.back()"><i class="fa-solid fa-arrow-left"></i></button>
                <div class="header-title">
                    <h1>Pelanggaran Disiplin</h1>
                    <p>SMAN 3 Bengkulu Tengah • TP 2025/2026</p>
                </div>
            </div>
            <div class="admin-badge"><i class="fa-solid fa-shield-halved"></i><span>Mode Admin</span></div>
        </div>
    </div>
</header>

<div class="custom-container">

    <!-- ============ MAIN TABS ============ -->
    <div class="main-tabs">
        <div class="main-tab terlambat active" data-main="terlambat">
            <span class="tab-count" id="countMainTerlambat">0</span>
            <div class="tab-icon"><i class="fa-solid fa-clock"></i></div>
            <div class="tab-label">Terlambat</div>
            <div class="tab-desc">Datang melewati jam masuk</div>
        </div>
        <div class="main-tab bolos" data-main="bolos">
            <span class="tab-count" id="countMainBolos">0</span>
            <div class="tab-icon"><i class="fa-solid fa-person-walking-dashed-line-arrow-right"></i></div>
            <div class="tab-label">Bolos Sekolah</div>
            <div class="tab-desc">Tidak hadir tanpa keterangan</div>
        </div>
        <div class="main-tab kasus" data-main="kasus">
            <span class="tab-count" id="countMainKasus">0</span>
            <div class="tab-icon"><i class="fa-solid fa-gavel"></i></div>
            <div class="tab-label">Kasus Sedang / Berat</div>
            <div class="tab-desc">Pelanggaran tata tertib serius</div>
        </div>
    </div>

    <!-- ===================== TERLAMBAT ===================== -->
    <div class="main-content active" id="main-terlambat">
        <div class="sub-tabs" id="subTabsTerlambat">
            <div class="sub-tab active terlambat-theme" data-sub="10" data-parent="terlambat"><i class="fa-solid fa-seedling"></i> Kelas 10 <span class="sub-count" id="subCount-terlambat-10">0</span></div>
            <div class="sub-tab terlambat-theme" data-sub="11" data-parent="terlambat"><i class="fa-solid fa-book-open"></i> Kelas 11 <span class="sub-count" id="subCount-terlambat-11">0</span></div>
            <div class="sub-tab terlambat-theme" data-sub="12" data-parent="terlambat"><i class="fa-solid fa-graduation-cap"></i> Kelas 12 <span class="sub-count" id="subCount-terlambat-12">0</span></div>
        </div>
        <div id="sub-terlambat-10" class="sub-content active"></div>
        <div id="sub-terlambat-11" class="sub-content"></div>
        <div id="sub-terlambat-12" class="sub-content"></div>
    </div>

    <!-- ===================== BOLOS ===================== -->
    <div class="main-content" id="main-bolos">
        <div class="sub-tabs" id="subTabsBolos">
            <div class="sub-tab active bolos-theme" data-sub="10" data-parent="bolos"><i class="fa-solid fa-seedling"></i> Kelas 10 <span class="sub-count" id="subCount-bolos-10">0</span></div>
            <div class="sub-tab bolos-theme" data-sub="11" data-parent="bolos"><i class="fa-solid fa-book-open"></i> Kelas 11 <span class="sub-count" id="subCount-bolos-11">0</span></div>
            <div class="sub-tab bolos-theme" data-sub="12" data-parent="bolos"><i class="fa-solid fa-graduation-cap"></i> Kelas 12 <span class="sub-count" id="subCount-bolos-12">0</span></div>
        </div>
        <div id="sub-bolos-10" class="sub-content active"></div>
        <div id="sub-bolos-11" class="sub-content"></div>
        <div id="sub-bolos-12" class="sub-content"></div>
    </div>

    <!-- ===================== KASUS ===================== -->
    <div class="main-content" id="main-kasus">
        <div class="sub-tabs" id="subTabsKasus">
            <div class="sub-tab active kasus-theme" data-sub="10" data-parent="kasus"><i class="fa-solid fa-seedling"></i> Kelas 10 <span class="sub-count" id="subCount-kasus-10">0</span></div>
            <div class="sub-tab kasus-theme" data-sub="11" data-parent="kasus"><i class="fa-solid fa-book-open"></i> Kelas 11 <span class="sub-count" id="subCount-kasus-11">0</span></div>
            <div class="sub-tab kasus-theme" data-sub="12" data-parent="kasus"><i class="fa-solid fa-graduation-cap"></i> Kelas 12 <span class="sub-count" id="subCount-kasus-12">0</span></div>
        </div>
        <div id="sub-kasus-10" class="sub-content active"></div>
        <div id="sub-kasus-11" class="sub-content"></div>
        <div id="sub-kasus-12" class="sub-content"></div>
    </div>

    <!-- ============ FORM TAMBAH PELANGGARAN ============ -->
    <div class="main-content" id="main-form">
        <div class="form-card">
            <div class="form-header">
                <h3 id="formTitle"><i class="fa-solid fa-circle-exclamation"></i> Catat Pelanggaran Baru</h3>
                <p id="formSubtitle">Lengkapi data pelanggaran disiplin siswa</p>
            </div>
            <div class="form-body">
                <form id="vioForm">
                    <input type="hidden" id="editId" value="">

                    <div class="form-section">
                        <div class="form-section-title"><i class="fa-solid fa-user-graduate"></i><span>Data Siswa</span></div>
                        <div class="form-group">
                            <label class="form-label-custom">Nama Siswa <span class="required">*</span></label>
                            <input type="text" class="form-control-custom" id="inputNama" placeholder="Ketik nama siswa..." required>
                        </div>
                        <div class="form-row three-col">
                            <div class="form-group">
                                <label class="form-label-custom">Kelas <span class="required">*</span></label>
                                <select class="form-control-custom" id="inputKelas" required>
                                    <option value="">Pilih</option>
                                    <option value="10">Kelas 10</option>
                                    <option value="11">Kelas 11</option>
                                    <option value="12">Kelas 12</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label-custom">Nama Kelas <span class="required">*</span></label>
                                <select class="form-control-custom" id="inputNamaKelas" required><option value="">Pilih Rombel</option></select>
                            </div>
                            <div class="form-group">
                                <label class="form-label-custom">Jenis Kelamin <span class="required">*</span></label>
                                <select class="form-control-custom" id="inputGender" required>
                                    <option value="">Pilih</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-section-title"><i class="fa-solid fa-triangle-exclamation"></i><span>Detail Pelanggaran</span></div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label-custom">Jenis Pelanggaran <span class="required">*</span></label>
                                <select class="form-control-custom" id="inputJenis" required>
                                    <option value="">Pilih Jenis</option>
                                    <option value="terlambat">⏰ Terlambat</option>
                                    <option value="bolos">🚪 Bolos Sekolah</option>
                                    <option value="kasus">⚖️ Kasus Sedang / Berat</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label-custom">Tanggal Kejadian <span class="required">*</span></label>
                                <input type="date" class="form-control-custom" id="inputTanggal" required>
                            </div>
                        </div>

                        <!-- Field khusus Terlambat -->
                        <div class="conditional-field" id="fieldTerlambat">
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label-custom">Jam Masuk Sekolah <span class="required">*</span></label>
                                    <input type="time" class="form-control-custom" id="inputJamMasuk" value="07:00">
                                </div>
                                <div class="form-group">
                                    <label class="form-label-custom">Jam Tiba di Sekolah <span class="required">*</span></label>
                                    <input type="time" class="form-control-custom" id="inputJamTiba">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label-custom">Alasan Terlambat</label>
                                <select class="form-control-custom" id="inputAlasanTerlambat">
                                    <option value="">Pilih Alasan</option>
                                    <option value="Bangun kesiangan">Bangun kesiangan</option>
                                    <option value="Kemacetan">Kemacetan</option>
                                    <option value="Sakit mendadak">Sakit mendadak</option>
                                    <option value="Urusan keluarga">Urusan keluarga</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <!-- Field khusus Bolos -->
                        <div class="conditional-field" id="fieldBolos">
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label-custom">Durasi Bolos</label>
                                    <select class="form-control-custom" id="inputDurasiBolos">
                                        <option value="1 jam pelajaran">1 Jam Pelajaran</option>
                                        <option value="2 jam pelajaran">2 Jam Pelajaran</option>
                                        <option value="Setengah hari">Setengah Hari</option>
                                        <option value="Sehari penuh" selected>Sehari Penuh</option>
                                        <option value="Lebih dari 1 hari">Lebih dari 1 Hari</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-custom">Diketahui Oleh</label>
                                    <input type="text" class="form-control-custom" id="inputDiketahuiBolos" placeholder="Contoh: Guru Piket / Wali Kelas">
                                </div>
                            </div>
                        </div>

                        <!-- Field khusus Kasus -->
                        <div class="conditional-field" id="fieldKasus">
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label-custom">Tingkat Kasus <span class="required">*</span></label>
                                    <select class="form-control-custom" id="inputTingkatKasus">
                                        <option value="">Pilih Tingkat</option>
                                        <option value="ringan">🟡 Ringan (teguran lisan)</option>
                                        <option value="sedang">🟠 Sedang (surat peringatan)</option>
                                        <option value="berat">🔴 Berat (pemanggilan ortu / skorsing)</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-custom">Jenis Kasus <span class="required">*</span></label>
                                    <select class="form-control-custom" id="inputJenisKasus">
                                        <option value="">Pilih Jenis</option>
                                        <option value="Berkelahi">Berkelahi</option>
                                        <option value="Bullying">Bullying / Perundungan</option>
                                        <option value="Merokok">Merokok di lingkungan sekolah</option>
                                        <option value="Membawa senjata">Membawa senjata / benda tajam</option>
                                        <option value="Narkoba">Penyalahgunaan narkoba</option>
                                        <option value="Vandalisme">Vandalisme / merusak fasilitas</option>
                                        <option value="Pencurian">Pencurian</option>
                                        <option value="Tidak sopan">Perilaku tidak sopan kepada guru</option>
                                        <option value="HP saat ujian">Menggunakan HP saat ujian</option>
                                        <option value="Seragam tidak lengkap">Seragam tidak lengkap berulang</option>
                                        <option value="Rambut tidak rapi">Rambut tidak rapi / dicat</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label-custom">Tindakan / Sanksi yang Diberikan</label>
                                <select class="form-control-custom" id="inputSanksi">
                                    <option value="">Pilih Sanksi</option>
                                    <option value="Teguran lisan">Teguran Lisan</option>
                                    <option value="Teguran tertulis">Teguran Tertulis</option>
                                    <option value="Surat peringatan 1">Surat Peringatan 1</option>
                                    <option value="Surat peringatan 2">Surat Peringatan 2</option>
                                    <option value="Surat peringatan 3">Surat Peringatan 3</option>
                                    <option value="Pemanggilan orang tua">Pemanggilan Orang Tua</option>
                                    <option value="Skorsing 3 hari">Skorsing 3 Hari</option>
                                    <option value="Skorsing 7 hari">Skorsing 7 Hari</option>
                                    <option value="Dikeluarkan">Dikeluarkan dari sekolah</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label-custom">Keterangan Tambahan <span class="required">*</span></label>
                            <textarea class="form-control-custom" id="inputKeterangan" placeholder="Deskripsi detail kejadian..." required></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label-custom">Dicatat Oleh <span class="required">*</span></label>
                            <input type="text" class="form-control-custom" id="inputPencatat" placeholder="Nama guru / staff yang mencatat" required>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-reset" onclick="resetForm()"><i class="fa-solid fa-rotate-left"></i> Reset</button>
                        <button type="submit" class="btn-submit"><i class="fa-solid fa-floppy-disk"></i> Simpan Pelanggaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="panel-space"></div>

<!-- MODAL DETAIL -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header-custom">
                <div style="position:relative;z-index:2;">
                    <div id="mBadge" style="margin-bottom:6px;"></div>
                    <h5 class="modal-title-custom" id="mTitle">-</h5>
                    <div style="font-size:11px;opacity:0.9;margin-top:3px;" id="mStudent">-</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="position:absolute;top:16px;right:16px;z-index:3;"></button>
            </div>
            <div class="modal-body-custom">
                <div class="detail-grid">
                    <div class="detail-item"><i class="fa-solid fa-tag"></i><div><div class="detail-label">Jenis</div><div class="detail-value" id="mJenis">-</div></div></div>
                    <div class="detail-item"><i class="fa-regular fa-calendar"></i><div><div class="detail-label">Tanggal</div><div class="detail-value" id="mTanggal">-</div></div></div>
                    <div class="detail-item"><i class="fa-solid fa-chalkboard-user"></i><div><div class="detail-label">Kelas</div><div class="detail-value" id="mKelas">-</div></div></div>
                    <div class="detail-item"><i class="fa-solid fa-user-pen"></i><div><div class="detail-label">Dicatat Oleh</div><div class="detail-value" id="mPencatat">-</div></div></div>
                    <div class="detail-item full"><i class="fa-solid fa-file-lines"></i><div><div class="detail-label">Keterangan</div><div class="detail-value" id="mKeterangan">-</div></div></div>
                    <div class="detail-item full conditional-field" id="mExtraField"><i class="fa-solid fa-circle-info"></i><div><div class="detail-label" id="mExtraLabel">-</div><div class="detail-value" id="mExtraValue">-</div></div></div>
                </div>
            </div>
            <div class="modal-footer-custom">
                <button class="modal-action-btn edit" onclick="openEditFromModal()"><i class="fa-solid fa-pen-to-square"></i><span>Ubah</span></button>
                <button class="modal-action-btn delete" onclick="openDeleteConfirm()"><i class="fa-solid fa-trash-can"></i><span>Hapus</span></button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DELETE -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body-custom" style="padding:28px 20px;text-align:center;">
                <input type="hidden" id="deleteId">
                <div class="confirm-icon"><i class="fa-solid fa-trash-can"></i></div>
                <h4 class="confirm-title">Hapus Data?</h4>
                <p class="confirm-desc">Data pelanggaran <strong id="deleteName">-</strong> akan dihapus permanen.</p>
            </div>
            <div class="modal-footer-custom">
                <button class="btn-reset" data-bs-dismiss="modal" style="flex:1;"><i class="fa-solid fa-xmark"></i> Batal</button>
                <button class="modal-action-btn delete" onclick="confirmDelete()" style="flex:1;"><i class="fa-solid fa-trash"></i><span>Hapus</span></button>
            </div>
        </div>
    </div>
</div>


<?php $this->endSection(); ?>