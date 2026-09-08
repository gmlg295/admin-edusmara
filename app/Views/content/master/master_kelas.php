<?php $this->extend('layout/main'); ?>
<?php $this->section('content'); ?>
<!-- ============ HEADER ============ -->
<header class="page-header">
    <div class="custom-container">
        <div class="header-panel">
            <div class="header-left">
                <button class="back-btn" onclick="window.history.back()"><i class="fa-solid fa-arrow-left"></i></button>
                <div class="header-title">
                    <h1>Data Ruang Kelas</h1>
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
        <button class="page-tab active" data-tab="list"><i class="fa-solid fa-users"></i><span>Data Kelas</span></button>
        <button class="page-tab" data-tab="form"><i class="fa-solid fa-user-plus"></i><span>Tambah / Edit</span></button>
    </div>

    <!-- ============ TAB: LIST ============ -->
    <div class="tab-content active" id="tab-list">
        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-icon blue"><i class="fa-solid fa-chalkboard-user"></i></div>
                <div><div class="summary-value" id="sumTotal">0</div><div class="summary-label">Total Ruang</div></div>
            </div>
            <div class="summary-card">
                <div class="summary-icon yellow"><i class="fa-solid fa-file-contract"></i></div>
                <div><div class="summary-value" id="sumKelas10">0</div><div class="summary-label">Kelas 10</div></div>
            </div>
            <div class="summary-card">
                <div class="summary-icon purple"><i class="fa-solid fa-user-clock"></i></div>
                <div><div class="summary-value" id="sumKelas11">0</div><div class="summary-label">Kelas 11</div></div>
            </div>
            
            <div class="summary-card">
                <div class="summary-icon purple"><i class="fa-solid fa-user-clock"></i></div>
                <div><div class="summary-value" id="sumKelas12">0</div><div class="summary-label">Kelas 12</div></div>
            </div>
        </div>

        <div class="toolbar">
            <div class="search-box">
                <div class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                <input type="search" class="search-input" id="searchKelas" placeholder="Cari nama atau NIP/NUPTK...">
            </div>
            <select class="filter-select" id="filterTingkat">
                <option value="all">Semua Kelas</option>
                <option value="10">Kelas 10</option>
                <option value="11">Kelas 11</option>
                <option value="12">Kelas 12</option>
            </select>
            <select class="filter-select" id="filterJurusan">
                <option value="all">Semua Jurusan</option>
                <option value="IPA">IPA</option>
                <option value="IPS">IPS</option>
                <option value="Bahasa">Bahasa</option>
            </select>
            <button class="btn-add" onclick="switchToForm()"><i class="fa-solid fa-plus"></i><span>Tambah Kelas</span></button>
        </div>

        <div class="kelas-list" id="kelasList"></div>
    </div>

    <!-- ============ TAB: FORM ============ -->
    <div class="tab-content" id="tab-form">
        <div class="form-card">
            <div class="form-header">
                <h3 id="formTitle"><i class="fa-solid fa-user-plus"></i> Tambah Data Kelas Baru</h3>
                <p id="formSubtitle">Lengkapi profil Kelas dengan benar</p>
            </div>
            <div class="form-body">
                <form id="kelasForm">
                    <input type="hidden" id="editId" value="">

                    <div class="form-section">
                        <div class="form-section-title"><i class="fa-solid fa-user-tie"></i><span>Data Pribadi</span></div>
                        <div class="form-group">
                            <label class="form-label-custom">Kelas <span class="required">*</span></label>
                            <input type="text" class="form-control-custom" id="inputNamaKelas" name="input-nama_kelas" placeholder="Contoh: 10.I, 10.II, 11.I, dst." required>
                            <p class="form-note"></p>
                        </div>
                        <div class="form-row three-col">
                            <div class="form-group">
                                <label class="form-label-custom">Tingkat / Jenjang <span class="required">*</span></label>
                                <select class="form-control-custom" id="inputTingkat" name="input-tingkat" required>
                                    <option value="">Pilih</option>
                                    <option value="10">Kelas 10</option>
                                    <option value="11">Kelas 11</option>
                                    <option value="12">Kelas 12</option>
                                </select>
                                <p class="form-note"></p>
                            </div>
                            <div class="form-group">
                                <label class="form-label-custom">Kapasitas <span class="required">*</span></label>
                                <input type="number" class="form-control-custom" id="inputKapasitas" name="input-kapasitas" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label-custom">Nama Ruang / Kelas <span style="font-size:10px;color:var(--text-tertiary);">*</span></label>
                                <input type="text" class="form-control-custom" id="inputRuangKelas" name="input-ruang_kelas" placeholder="R. Kelas 10 A">
                                <p class="form-note"></p>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label-custom">Jurusan / program studi <span style="font-size:10px;color:var(--text-tertiary);">(opsional)</span></label>
                                <input type="text" class="form-control-custom" id="inputJurusan" name="input-jurusan" placeholder="IPA / IPS / Bahasa / Keagamaan">
                                <p class="form-note"></p>
                            </div>
                            
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-section-title"><i class="fa-solid fa-graduation-cap"></i><span>Pendidikan & Jabatan</span></div>
                        <div class="form-row">

                            <div class="form-group">
                                <label class="form-label-custom">Nama Kurikulum <span style="font-size:10px;color:var(--text-tertiary);">(opsional)</span></label>
                                <input type="text" class="form-control-custom" id="inputKurikulum" name="input-kurikulum" placeholder="Kurikulum KTSP / Kurikulum 2013">
                                <p class="form-note"></p>
                            </div>

                            <div class="form-group">
                                <label class="form-label-custom">Tahun Pelajaran saat ini <span class="required">*</span></label>
                                    <select class="form-control-custom" id="inputTahunPelajaran" name="input-tahun_pelajaran" required>
                                        <option value="">Pilih ...</option>

                                        <?php
                                        $currentYear = date('Y');
                                        for ($year = $currentYear; $year >= 2024; $year--) {
                                            $nextYear = $year + 1;
                                            $optionValue = "$year/$nextYear";
                                            echo "<option value=\"$optionValue\">$optionValue</option>";
                                        }
                                        ?>
                                    </select>
                                    <p class="form-note"></p>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label-custom">Wali Kelas saat ini <span class="required">*</span></label>
                                    <select class="form-control-custom" id="inputWaliKelas" name="input-wali_kelas" required>
                                        <option value="">Pilih ...</option>
                                        <option value="1">aktif</option>
                                        <option value="0">non-aktif</option>
                                    </select>
                                    <p class="form-note"></p>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label-custom">Apakah kelas ini aktif? <span class="required">*</span></label>
                                    <select class="form-control-custom" id="inputIsAktif" name="input-is_aktif" required>
                                        <option value="">Pilih ...</option>
                                        <option value="1">aktif</option>
                                        <option value="0">non-aktif</option>
                                    </select>
                                    <p class="form-note"></p>
                            </div>

                        </div>
                        
                    </div>


                    <div class="form-actions">
                        <button type="button" class="btn-secondary-custom" onclick="closeForm()" ><i class="fa-solid fa-xmark"></i> Batal</button>
                        <button type="button" class="btn-secondary-custom" onclick="resetForm()"><i class="fa-solid fa-rotate-left"></i> Reset</button>
                        <button type="submit" class="btn-primary-custom" id="btnSubmit"><i class="fa-solid fa-floppy-disk"></i> Simpan Data Kelas</button>
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
                        <div class="modal-nip-badge"><i class="fa-solid fa-id-badge"></i><span id="mNip">-</span></div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="position:absolute;top:20px;right:20px;z-index:3;"></button>
            </div>
            <div class="modal-body-custom">
                <div class="detail-section">
                    <div class="detail-section-title"><i class="fa-solid fa-user"></i><span>Data Kelas</span></div>
                    <div class="detail-grid">
                        <div class="detail-item"><i class="fa-solid fa-venus-mars"></i><div><div class="detail-label">Nama Kelas</div><div class="detail-value" id="mNamaKelas">-</div></div></div>
                        <div class="detail-item"><i class="fa-solid fa-cake-candles"></i><div><div class="detail-label">Nama Ruang</div><div class="detail-value" id="mRuang">-</div></div></div>
                        <div class="detail-item"><i class="fa-solid fa-graduation-cap"></i><div><div class="detail-label">Jenjang / Tingkat</div><div class="detail-value" id="mTingkat">-</div></div></div>
                        <div class="detail-item"><i class="fa-solid fa-school"></i><div><div class="detail-label">Kapasitas</div><div class="detail-value" id="mKapasitas">-</div></div></div>
                        <div class="detail-item full"><i class="fa-solid fa-location-dot"></i><div><div class="detail-label">Jurusan</div><div class="detail-value" id="mJurusan">-</div></div></div>
                    </div>
                </div>
                <div class="detail-section">
                    <div class="detail-section-title"><i class="fa-solid fa-graduation-cap"></i><span>Wali Kelas</span></div>
                    <div class="detail-grid">
                        <div class="detail-item"><i class="fa-solid fa-scroll"></i><div><div class="detail-label">Nama Wali Kelas</div><div class="detail-value" id="mEducation">-</div></div></div>
                        <div class="detail-item"><i class="fa-solid fa-school"></i><div><div class="detail-label">Tahun Pelajaran</div><div class="detail-value" id="mTahunAjaran">-</div></div></div>
                        <div class="detail-item"><i class="fa-solid fa-calendar-check"></i><div><div class="detail-label">Aktif?</div><div class="detail-value" id="mStatus">-</div></div></div>
                        <div class="detail-item"><i class="fa-solid fa-book-open"></i><div><div class="detail-label">Mata Pelajaran</div><div class="detail-value" id="mMapel">-</div></div></div>
                    </div>
                </div>
            </div>
            <div class="modal-action-footer">
                <button class="modal-action-btn edit" onclick="openEditFromModal()"><i class="fa-solid fa-pen-to-square"></i><span>Ubah Data</span></button>
                <button class="modal-action-btn delete" onclick="openDeleteConfirm()"><i class="fa-solid fa-trash-can"></i><span>Hapus Kelas</span></button>
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
                <h4 class="confirm-title">Hapus Data Kelas?</h4>
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