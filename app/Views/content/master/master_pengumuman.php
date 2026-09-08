<?php $this->extend('layout/main'); ?>
<?php $this->section('content'); ?>


<!-- ============ HEADER ============ -->
<header class="page-header">
    <div class="custom-container">
        <div class="header-panel">
            <div class="header-left">
                <button class="back-btn" onclick="window.history.back()"><i class="fa-solid fa-arrow-left"></i></button>
                <div class="header-title">
                    <h1>Kelola Pengumuman</h1>
                    <p>SMAN 3 Bengkulu Tengah • TP 2025/2026</p>
                </div>
            </div>
            <div class="admin-badge"><i class="fa-solid fa-user-shield"></i><span>Mode Admin</span></div>
        </div>
    </div>
</header>

<div class="custom-container">
    <!-- ============ TABS ============ -->
    <div class="page-tabs">
        <button class="page-tab active" data-tab="list"><i class="fa-solid fa-newspaper"></i><span>Data Pengumuman</span></button>
        <button class="page-tab" data-tab="form"><i class="fa-solid fa-pen-to-square"></i><span>Tambah / Edit</span></button>
    </div>

    <!-- ============ TAB: LIST ============ -->
    <div class="tab-content active" id="tab-list">
        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-icon blue"><i class="fa-solid fa-newspaper"></i></div>
                <div><div class="summary-value" id="sumTotal">0</div><div class="summary-label">Total</div></div>
            </div>
            <div class="summary-card">
                <div class="summary-icon green"><i class="fa-solid fa-circle-check"></i></div>
                <div><div class="summary-value" id="sumPublished">0</div><div class="summary-label">Dipublikasi</div></div>
            </div>
            <div class="summary-card">
                <div class="summary-icon yellow"><i class="fa-solid fa-file-pen"></i></div>
                <div><div class="summary-value" id="sumDraft">0</div><div class="summary-label">Draft</div></div>
            </div>
            <div class="summary-card">
                <div class="summary-icon purple"><i class="fa-solid fa-layer-group"></i></div>
                <div><div class="summary-value" id="sumCategories">0</div><div class="summary-label">Kategori</div></div>
            </div>
        </div>

        <div class="toolbar">
            <div class="search-box">
                <div class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                <input type="search" class="search-input" id="searchAnn" placeholder="Cari judul pengumuman...">
            </div>
            <select class="filter-select" id="filterCategory">
                <option value="all">Semua Kategori</option>
                <option value="pengumuman">📢 Pengumuman</option>
                <option value="pemberitahuan">📋 Pemberitahuan</option>
                <option value="himbauan">📣 Himbauan</option>
                <option value="undangan">💌 Undangan</option>
                <option value="edaran">📄 Edaran</option>
                <option value="lainnya">📝 Lainnya</option>
            </select>
            <select class="filter-select" id="filterStatus">
                <option value="all">Semua Status</option>
                <option value="published">✅ Dipublikasi</option>
                <option value="draft">📝 Draft</option>
            </select>
            <button class="btn-add" onclick="switchToForm()"><i class="fa-solid fa-plus"></i><span>Buat Pengumuman</span></button>
        </div>

        <div class="announcement-list" id="annList"></div>
    </div>

    <!-- ============ TAB: FORM ============ -->
    <div class="tab-content" id="tab-form">
        <div class="form-card">
            <div class="form-header">
                <h3 id="formTitle"><i class="fa-solid fa-pen-to-square"></i> Buat Pengumuman Baru</h3>
                <p id="formSubtitle">Lengkapi informasi pengumuman sebelum dipublikasikan</p>
            </div>
            <div class="form-body">
                <form id="annForm" enctype="multipart/form-data">
                    <input type="hidden" id="editId" value="">

                    <div class="form-section">
                        <div class="form-section-title"><i class="fa-solid fa-heading"></i><span>Informasi Utama</span></div>
                        <div class="form-group">
                            <label class="form-label-custom">Judul Pengumuman <span class="required">*</span></label>
                            <input type="text" class="form-control-custom" id="inputJudul" placeholder="Contoh: Pemberitahuan Pelaksanaan Ujian Praktik Kelas XII" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label-custom">Kategori <span class="required">*</span></label>
                                <select class="form-control-custom" id="inputKategori" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="pengumuman">📢 Pengumuman</option>
                                    <option value="pemberitahuan">📋 Pemberitahuan</option>
                                    <option value="himbauan">📣 Himbauan</option>
                                    <option value="undangan">💌 Undangan</option>
                                    <option value="edaran">📄 Edaran / Surat Edaran</option>
                                    <option value="lainnya">📝 Lainnya</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label-custom">Tanggal Publikasi <span class="required">*</span></label>
                                <input type="date" class="form-control-custom" id="inputTanggal" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label-custom">Penulis / Sumber <span class="hint">(opsional)</span></label>
                            <input type="text" class="form-control-custom" id="inputPenulis" placeholder="Contoh: Kepala Sekolah / Wakil Kurikulum">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label-custom">Gambar / Foto <span class="hint">(opsional)</span></label>
                            <input type="file" class="form-control-custom" id="inputFiles" enctype="multipart/form-data" accept="image/*">
                        </div>

                        <div class="form-group">
                           <img src="#" alt="preview" id="imagePreview" style="display:none; max-width:100%; margin-top:10px; border-radius:4px;">
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-section-title"><i class="fa-solid fa-align-left"></i><span>Isi Pengumuman</span></div>
                        <div class="form-group">
                            <label class="form-label-custom">Konten <span class="required">*</span></label>
                            <div class="editor-toolbar">
                                <button type="button" class="toolbar-btn" onclick="insertFormat('**','**')" title="Bold"><i class="fa-solid fa-bold"></i></button>
                                <button type="button" class="toolbar-btn" onclick="insertFormat('_','_')" title="Italic"><i class="fa-solid fa-italic"></i></button>
                                <button type="button" class="toolbar-btn" onclick="insertFormat('__','__')" title="Underline"><i class="fa-solid fa-underline"></i></button>
                                <div class="toolbar-divider"></div>
                                <button type="button" class="toolbar-btn" onclick="insertPrefix('- ')" title="List Item"><i class="fa-solid fa-list-ul"></i></button>
                                <button type="button" class="toolbar-btn" onclick="insertPrefix('1. ')" title="Numbered List"><i class="fa-solid fa-list-ol"></i></button>
                                <div class="toolbar-divider"></div>
                                <button type="button" class="toolbar-btn" onclick="insertBlock('⚠️ PERHATIAN:\n')" title="Warning Block"><i class="fa-solid fa-triangle-exclamation"></i></button>
                                <button type="button" class="toolbar-btn" onclick="insertBlock('ℹ️ INFORMASI:\n')" title="Info Block"><i class="fa-solid fa-circle-info"></i></button>
                                <div class="toolbar-divider"></div>
                                <button type="button" class="toolbar-btn" onclick="togglePreview()" title="Preview"><i class="fa-solid fa-eye"></i></button>
                            </div>
                            <textarea class="form-control-custom" id="inputKonten" placeholder="Tulis isi pengumuman di sini...&#10;&#10;Gunakan toolbar di atas untuk memformat teks." required></textarea>
                            <div class="preview-box" id="previewBox">
                                <div class="preview-label"><i class="fa-solid fa-eye"></i> Preview Konten</div>
                                <div class="preview-content" id="previewContent"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-section-title"><i class="fa-solid fa-sliders"></i><span>Pengaturan Publikasi</span></div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label-custom">Target Pembaca</label>
                                <select class="form-control-custom" id="inputTarget">
                                    <option value="semua">Semua (Siswa, Guru, Orang Tua)</option>
                                    <option value="siswa">Siswa Saja</option>
                                    <option value="guru">Guru & Staff</option>
                                    <option value="ortu">Orang Tua / Wali</option>
                                    <option value="kelas12">Kelas XII Saja</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label-custom">Prioritas</label>
                                <select class="form-control-custom" id="inputPrioritas">
                                    <option value="normal">Normal</option>
                                    <option value="penting">⭐ Penting</option>
                                    <option value="darurat">🔴 Darurat / Mendesak</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-secondary-custom" onclick="resetForm()"><i class="fa-solid fa-rotate-left"></i> Reset</button>
                        <button type="button" class="btn-draft-custom" onclick="saveAsDraft()"><i class="fa-solid fa-file-pen"></i> Simpan Draft</button>
                        <button type="submit" class="btn-primary-custom"><i class="fa-solid fa-paper-plane"></i> Publikasikan</button>
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
                <div style="position:relative;z-index:2;">
                    <div id="mCategoryBadge" style="margin-bottom:8px;"></div>
                    <h5 class="modal-title-custom" id="mTitle">-</h5>
                    <div style="font-size:12px;opacity:0.9;margin-top:4px;" id="mDate">-</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="position:absolute;top:20px;right:20px;z-index:3;"></button>
            </div>
            <div class="modal-body-custom">
                <div class="detail-meta-grid">
                    <div class="detail-meta-item"><i class="fa-solid fa-tag"></i><div><div class="detail-meta-label">Kategori</div><div class="detail-meta-value" id="mCat">-</div></div></div>
                    <div class="detail-meta-item"><i class="fa-solid fa-circle-info"></i><div><div class="detail-meta-label">Status</div><div class="detail-meta-value" id="mStatus">-</div></div></div>
                    <div class="detail-meta-item"><i class="fa-solid fa-user-pen"></i><div><div class="detail-meta-label">Penulis</div><div class="detail-meta-value" id="mAuthor">-</div></div></div>
                    <div class="detail-meta-item"><i class="fa-solid fa-users"></i><div><div class="detail-meta-label">Target</div><div class="detail-meta-value" id="mTarget">-</div></div></div>
                    <div class="detail-meta-item"><i class="fa-solid fa-flag"></i><div><div class="detail-meta-label">Prioritas</div><div class="detail-meta-value" id="mPriority">-</div></div></div>
                    <div class="detail-meta-item"><i class="fa-regular fa-clock"></i><div><div class="detail-meta-label">Dibuat</div><div class="detail-meta-value" id="mCreated">-</div></div></div>
                </div>
                <div style="margin-top:16px;">
                    <div style="font-size:11px;font-weight:700;color:var(--text-tertiary);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px;">Gambar / Foto Pendukung</div>
                    <img src="#" alt="preview" id="mImage" style="display:none; max-width:100%; margin-bottom:16px; border-radius:4px;" />
                </div>
                
                <div style="margin-top:16px;">
                    <div style="font-size:11px;font-weight:700;color:var(--text-tertiary);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px;">Isi Pengumuman</div>
                    <div class="detail-article-text" id="mContent">-</div>
                </div>
            </div>
            <div class="modal-footer-custom">
                <button class="modal-action-btn edit" onclick="openEditFromModal()"><i class="fa-solid fa-pen-to-square"></i><span>Ubah</span></button>
                <button class="modal-action-btn delete" onclick="openDeleteConfirm()"><i class="fa-solid fa-trash-can"></i><span>Hapus</span></button>
            </div>
        </div>
    </div>
</div>

<!-- ============ MODAL DELETE ============ -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body-custom" style="padding:30px 24px;text-align:center;">
                <input type="hidden" id="deleteId">
                <div class="confirm-icon danger"><i class="fa-solid fa-trash-can"></i></div>
                <h4 class="confirm-title">Hapus Pengumuman?</h4>
                <p class="confirm-desc">Anda akan menghapus<br><strong id="deleteName" style="color:var(--text-primary);">-</strong><br><br>Tindakan ini <strong>tidak dapat dibatalkan</strong>.</p>
            </div>
            <div class="modal-footer-custom">
                <button class="btn-secondary-custom" data-bs-dismiss="modal" style="flex:1;"><i class="fa-solid fa-xmark"></i> Batal</button>
                <button class="modal-action-btn delete" onclick="confirmDelete()" style="flex:1;"><i class="fa-solid fa-trash"></i><span>Ya, Hapus</span></button>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>