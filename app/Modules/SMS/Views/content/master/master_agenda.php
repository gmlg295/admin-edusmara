<?php $this->extend('layout/main'); ?>
<?php $this->section('content'); ?>
<header class="page-header">
    <div class="custom-container">
        <div class="header-panel">
            <div class="header-left">
                <button class="back-btn" onclick="window.history.back()"><i class="fa-solid fa-arrow-left"></i></button>
                <div class="header-title">
                    <h1><i class="fa-solid fa-calendar-days" style="color:var(--primary);margin-right:6px"></i>Kelola Agenda</h1>
                    <p>Tambah, edit, dan kelola agenda sekolah</p>
                </div>
            </div>
            <div class="admin-badge"><i class="fa-solid fa-user-shield"></i><span>Mode Admin</span></div>
        </div>
    </div>
</header>

<div class="custom-container">
    <!-- TABS -->
    <div class="page-tabs">
        <button class="page-tab active" data-tab="list"><i class="fa-solid fa-list-check"></i><span>Data Agenda</span></button>
        <button class="page-tab" data-tab="form"><i class="fa-solid fa-pen-to-square"></i><span>Tambah / Edit</span></button>
    </div>

    <!-- ===== TAB: LIST ===== -->
    <div class="tab-content active" id="tab-list">
        <div class="summary-grid">
            <div class="summary-card"><div class="summary-icon si-blue"><i class="fa-solid fa-calendar-check"></i></div><div><div class="summary-value" id="sumTotal">0</div><div class="summary-label">Total Agenda</div></div></div>
            <div class="summary-card"><div class="summary-icon si-green"><i class="fa-solid fa-clock"></i></div><div><div class="summary-value" id="sumUpcoming">0</div><div class="summary-label">Mendatang</div></div></div>
            <div class="summary-card"><div class="summary-icon si-yellow"><i class="fa-solid fa-spinner"></i></div><div><div class="summary-value" id="sumOngoing">0</div><div class="summary-label">Berlangsung</div></div></div>
            <div class="summary-card"><div class="summary-icon si-purple"><i class="fa-solid fa-circle-check"></i></div><div><div class="summary-value" id="sumDone">0</div><div class="summary-label">Selesai</div></div></div>
        </div>

        <div class="toolbar">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="search" class="search-input" id="searchInput" placeholder="Cari judul agenda...">
            </div>
            <select class="filter-select" id="filterCat">
                <option value="all">Semua Kategori</option>
                <option value="akademik">📘 Akademik</option>
                <option value="ujian">📝 Ujian</option>
                <option value="ekskul">⚽ Ekstrakurikuler</option>
                <option value="rapat">👥 Rapat</option>
                <option value="libur">🏖️ Libur</option>
            </select>
            <select class="filter-select" id="filterStatus">
                <option value="all">Semua Status</option>
                <option value="upcoming">Mendatang</option>
                <option value="ongoing">Berlangsung</option>
                <option value="completed">Selesai</option>
                <option value="cancelled">Dibatalkan</option>
            </select>
            <button class="btn-add" onclick="openAddForm()"><i class="fa-solid fa-plus"></i><span>Tambah Agenda</span></button>
        </div>

        <div class="agenda-list" id="agendaList"></div>
    </div>

    <!-- ===== TAB: FORM ===== -->
    <div class="tab-content" id="tab-form">
        <div class="form-card">
            <div class="form-header">
                <h3 id="formTitle"><i class="fa-solid fa-calendar-plus"></i> Tambah Agenda Baru</h3>
                <p id="formSubtitle">Lengkapi informasi agenda sebelum dipublikasikan</p>
            </div>
            <div class="form-body">
                <form id="agendaForm">
                    <input type="hidden" id="editId" value="">

                    <div class="form-section">
                        <div class="form-section-title"><i class="fa-solid fa-heading"></i><span>Informasi Utama</span></div>
                        <div class="fg">
                            <label class="fl">Judul Agenda <span class="req">*</span></label>
                            <input type="text" class="fc" id="fTitle" placeholder="Contoh: Upacara HUT Kemerdekaan RI ke-81" required>
                        </div>
                        <div class="fr three">
                            <div class="fg">
                                <label class="fl">Kategori <span class="req">*</span></label>
                                <select class="fc" id="fCategory" required>
                                    <option value="">Pilih Kategori</option>
                                    
                                </select>
                            </div>
                            <div class="fg">
                                <label class="fl">Tanggal Mulai <span class="req">*</span></label>
                                <input type="date" class="fc" id="fDateStart" required>
                            </div>
                            <div class="fg">
                                <label class="fl">Tanggal Selesai</label>
                                <input type="date" class="fc" id="fDateEnd">
                            </div>
                        </div>
                        <div class="fr">
                            <div class="fg">
                                <label class="fl">Waktu Mulai</label>
                                <input type="time" class="fc" id="fTimeStart">
                            </div>
                            <div class="fg">
                                <label class="fl">Waktu Selesai</label>
                                <input type="time" class="fc" id="fTimeEnd">
                            </div>
                        </div>
                        <div class="fg">
                            <label class="fl">Full Day (Tanpa Jam Spesifik)</label>
                            <select class="fc" id="fFullDay">
                                <option value="0">Tidak — Ada jam mulai & selesai</option>
                                <option value="1">Ya — Acara seharian penuh</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-section-title"><i class="fa-solid fa-location-dot"></i><span>Lokasi & Peserta</span></div>
                        <div class="fg">
                            <label class="fl">Lokasi <span class="req">*</span></label>
                            <input type="text" class="fc" id="fLocation" placeholder="Contoh: Lapangan Utama, Aula Sekolah, Ruang Guru" required>
                        </div>
                        <div class="fr">
                            <div class="fg">
                                <label class="fl">Target Peserta <span class="req">*</span></label>
                                <select class="fc" id="fTarget" required>
                                    <option value="">Pilih Target</option>
                                    <option value="semua">Semua Warga Sekolah</option>
                                    <option value="siswa">Siswa Saja</option>
                                    <option value="guru">Guru & Staff</option>
                                    <option value="ortu">Orang Tua / Wali</option>
                                    <option value="kelas12">Kelas XII Saja</option>
                                    <option value="undangan">Tamu Undangan</option>
                                </select>
                            </div>
                            <div class="fg">
                                <label class="fl">Estimasi Peserta</label>
                                <input type="number" class="fc" id="fEstPeserta" placeholder="Contoh: 800" min="0">
                            </div>
                        </div>
                        <div class="fg">
                            <label class="fl">Daftar Peserta / Keterangan Peserta</label>
                            <input type="text" class="fc" id="fParticipants" placeholder="Contoh: Kepala Sekolah, Wakasek, Panitia HUT RI">
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-section-title"><i class="fa-solid fa-align-left"></i><span>Deskripsi & Pengaturan</span></div>
                        <div class="fg">
                            <label class="fl">Deskripsi Lengkap <span class="req">*</span></label>
                            <textarea class="fc" id="fDesc" placeholder="Jelaskan detail agenda, rundown, hal yang perlu disiapkan, dll..." required></textarea>
                        </div>
                        <div class="fr three">
                            <div class="fg">
                                <label class="fl">Prioritas</label>
                                <select class="fc" id="fPriority">
                                    <option value="normal">Normal</option>
                                    <option value="tinggi">⭐ Tinggi</option>
                                    <option value="darurat">🔴 Darurat / Mendesak</option>
                                </select>
                            </div>
                            <div class="fg">
                                <label class="fl">Tampilkan Countdown</label>
                                <select class="fc" id="fCountdown">
                                    <option value="0">Tidak</option>
                                    <option value="1">Ya — Tampilkan di homepage</option>
                                </select>
                            </div>
                            <div class="fg">
                                <label class="fl">Kirim Reminder H-1</label>
                                <select class="fc" id="fReminder">
                                    <option value="0">Tidak</option>
                                    <option value="1">Ya — Kirim notifikasi H-1</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-reset" onclick="resetForm()"><i class="fa-solid fa-rotate-left"></i> Reset</button>
                        <button type="button" class="btn-draft" onclick="saveDraft()"><i class="fa-solid fa-file-pen"></i> Simpan Draft</button>
                        <button type="submit" class="btn-submit"><i class="fa-solid fa-paper-plane"></i> Publikasikan</button>
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
                    <div style="font-size:12px;opacity:.9;margin-top:4px" id="mDate">-</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="position:absolute;top:16px;right:16px;z-index:3"></button>
            </div>
            <div class="modal-bdy">
                <div class="detail-grid">
                    <div class="detail-item"><i class="fa-regular fa-clock"></i><div><div class="dl">Waktu</div><div class="dv" id="mTime">-</div></div></div>
                    <div class="detail-item"><i class="fa-solid fa-location-dot"></i><div><div class="dl">Lokasi</div><div class="dv" id="mLoc">-</div></div></div>
                    <div class="detail-item"><i class="fa-solid fa-users"></i><div><div class="dl">Target Peserta</div><div class="dv" id="mTarget">-</div></div></div>
                    <div class="detail-item"><i class="fa-solid fa-flag"></i><div><div class="dl">Prioritas</div><div class="dv" id="mPriority">-</div></div></div>
                    <div class="detail-item"><i class="fa-solid fa-circle-info"></i><div><div class="dl">Status</div><div class="dv" id="mStatus">-</div></div></div>
                    <div class="detail-item"><i class="fa-solid fa-user-group"></i><div><div class="dl">Estimasi Peserta</div><div class="dv" id="mEst">-</div></div></div>
                    <div class="detail-item full"><i class="fa-solid fa-file-lines"></i><div><div class="dl">Deskripsi</div><div class="dv" id="mDesc" style="white-space:pre-wrap">-</div></div></div>
                    <div class="detail-item full" id="mPartRow" style="display:none"><i class="fa-solid fa-user-check"></i><div><div class="dl">Peserta</div><div class="dv" id="mPart">-</div></div></div>
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
                <h4 style="font-size:16px;font-weight:800;margin-bottom:6px">Hapus Agenda?</h4>
                <p style="font-size:12px;color:var(--text-secondary);line-height:1.6">Agenda <strong id="delName" style="color:var(--text-primary)">-</strong> akan dihapus permanen.</p>
            </div>
            <div class="modal-ftr">
                <button class="btn-ms" data-bs-dismiss="modal" style="flex:1">Batal</button>
                <button class="btn-md" onclick="confirmDelete()" style="flex:1"><i class="fa-solid fa-trash"></i> Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>