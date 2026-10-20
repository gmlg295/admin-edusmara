<?php $this->extend('layout/main'); ?>
<?php $this->section('content'); ?>
<header class="page-header">
    <div class="custom-container">
        <div class="header-panel">
            <div class="header-left">
                <button class="back-btn" onclick="window.history.back()"><i class="fa-solid fa-arrow-left"></i></button>
                <div class="header-title">
                    <h1><i class="fa-solid fa-calendar-xmark" style="color:var(--primary);margin-right:6px"></i>Kelola Alasan Terlambat</h1>
                    <p>Tambah, edit, dan kelola alasan keterlambatan siswa</p>
                </div>
            </div>
            <div class="admin-badge"><i class="fa-solid fa-user-shield"></i><span>Mode Admin</span></div>
        </div>
    </div>
</header>

<div class="custom-container">
    <!-- TABS -->
    <div class="page-tabs">
        <button class="page-tab active" data-tab="list"><i class="fa-solid fa-list-check"></i><span>Data Alasan Terlambat</span></button>
        <button class="page-tab" data-tab="form"><i class="fa-solid fa-pen-to-square"></i><span>Tambah / Edit</span></button>
    </div>

    <!-- ===== TAB: LIST ===== -->
    <div class="tab-content active" id="tab-list">
        <div class="summary-grid">
            <div class="summary-card"><div class="summary-icon si-blue"><i class="fa-solid fa-calendar-xmark"></i></div><div><div class="summary-value" id="sumTotal">0</div><div class="summary-label">Total Alasan</div></div></div>
            <div class="summary-card"><div class="summary-icon si-green"><i class="fa-solid fa-check-circle"></i></div><div><div class="summary-value" id="sumActive">0</div><div class="summary-label">Aktif</div></div></div>
            <div class="summary-card"><div class="summary-icon si-yellow"><i class="fa-solid fa-circle-pause"></i></div><div><div class="summary-value" id="sumInactive">0</div><div class="summary-label">Non-Aktif</div></div></div>
            <div class="summary-card"><div class="summary-icon si-purple"><i class="fa-solid fa-list-ol"></i></div><div><div class="summary-value" id="sumMaxUrutan">0</div><div class="summary-label">Urutan Terakhir</div></div></div>
        </div>

        <div class="toolbar">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="search" class="search-input" id="searchInput" placeholder="Cari alasan terlambat...">
            </div>
            <select class="filter-select" id="filterStatus">
                <option value="all">Semua Status</option>
                <option value="1">Aktif</option>
                <option value="0">Non-Aktif</option>
            </select>
            <button class="btn-add" onclick="openAddForm()"><i class="fa-solid fa-plus"></i><span>Tambah Alasan</span></button>
        </div>

        <div class="alasanterlambat-list" id="alasanterlambatList"></div>
    </div>

    <!-- ===== TAB: FORM ===== -->
    <div class="tab-content" id="tab-form">
        <div class="form-card">
            <div class="form-header">
                <h3 id="formTitle"><i class="fa-solid fa-calendar-xmark"></i> Tambah Alasan Terlambat Baru</h3>
                <p id="formSubtitle">Lengkapi informasi alasan keterlambatan siswa</p>
            </div>
            <div class="form-body">
                <form id="alasanterlambatForm">
                    <input type="hidden" id="editId" value="">

                    <div class="form-section">
                        <div class="form-section-title"><i class="fa-solid fa-heading"></i><span>Informasi Utama</span></div>
                        <div class="fg">
                            <label class="fl">Nama Alasan <span class="req">*</span></label>
                            <input type="text" class="fc" id="fNamaAlasan" name="input-nama_alasan" maxlength="100" placeholder="Contoh: Kemacetan, Bangun kesiangan, Ban bocor" required>
                            <p class="form-note"></p>
                        </div>
                        <div class="fr">
                            <div class="fg">
                                <label class="fl">Urutan <span class="req">*</span></label>
                                <input type="number" class="fc" id="fUrutan" name="input-urutan" placeholder="Contoh: 1, 2, 3" min="0" max="127" required>
                                <p class="form-note"></p>
                            </div>
                            <div class="fg">
                                <label class="fl">Status</label>
                                <select class="fc" id="fIsActive" name="input-is_aktif">
                                    <option value="1">Aktif</option>
                                    <option value="0">Non-Aktif</option>
                                </select>
                                <p class="form-note"></p>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-reset" onclick="resetForm()"><i class="fa-solid fa-rotate-left"></i> Reset</button>
                        <button type="submit" class="btn-submit"><i class="fa-solid fa-paper-plane"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="panel-space"></div>

<!-- MODAL DETAIL -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-hdr">
                <div style="position:relative;z-index:2">
                    <div id="mBadge" style="margin-bottom:6px"></div>
                    <h5 class="modal-ttl" id="mTitle">-</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="position:absolute;top:16px;right:16px;z-index:3"></button>
            </div>
            <div class="modal-bdy">
                <div class="detail-grid">
                    <div class="detail-item"><i class="fa-solid fa-list-ol"></i><div><div class="dl">Urutan</div><div class="dv" id="mUrutan">-</div></div></div>
                    <div class="detail-item"><i class="fa-solid fa-circle-info"></i><div><div class="dl">Status</div><div class="dv" id="mStatus">-</div></div></div>
                    <div class="detail-item full"><i class="fa-solid fa-clock"></i><div><div class="dl">Dibuat</div><div class="dv" id="mCreatedAt">-</div></div></div>
                </div>
            </div>
            <div class="modal-ftr">
                <button class="btn-ms" data-bs-dismiss="modal">Tutup</button>
                <button class="btn-md" id="mBtnDel" onclick="deleteFromModal()"><i class="fa-solid fa-trash"></i> Hapus</button>
                <button class="btn-mp" id="mBtnEdit" onclick="editFromModal()"><i class="fa-solid fa-pen-to-square"></i> Edit</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DELETE CONFIRM -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-bdy" style="padding:30px 24px;text-align:center">
                <input type="hidden" id="delId">
                <div style="width:64px;height:64px;border-radius:50%;background:#fee2e2;color:var(--danger);display:flex;align-items:center;justify-content:center;font-size:24px;margin:0 auto 16px"><i class="fa-solid fa-trash-can"></i></div>
                <h4 style="font-size:16px;font-weight:800;margin-bottom:6px">Hapus Alasan Terlambat?</h4>
                <p style="font-size:12px;color:var(--text-secondary);line-height:1.6">Alasan <strong id="delName" style="color:var(--text-primary)">-</strong> akan dihapus permanen.</p>
            </div>
            <div class="modal-ftr">
                <button class="btn-ms" data-bs-dismiss="modal" style="flex:1">Batal</button>
                <button class="btn-md" onclick="confirmDelete()" style="flex:1"><i class="fa-solid fa-trash"></i> Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>
