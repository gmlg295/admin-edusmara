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

    <div class="class-content">
        <div class="header-upload">
            <h1>📊 Import Data Siswa</h1>
            <p>Upload file Excel untuk mengimport data siswa secara massal</p>
        </div>

        <div class="content">
            <!-- Alert Messages -->
            <div id="alertSuccess" class="alert alert-success"></div>
            <div id="alertError" class="alert alert-error"></div>
            <div id="alertWarning" class="alert alert-warning"></div>

            <!-- Upload Section -->
            <div class="upload-section" id="dropZone">
                <div class="upload-icon">📁</div>
                <div class="upload-text">Klik atau drag & drop file Excel di sini</div>
                <div class="upload-hint">Format: .xlsx atau .xls (Maks: 5MB)</div>
                <input type="file" id="fileInput" accept=".xlsx,.xls">
                <br>
                <a href="#" class="template-link" onclick="downloadTemplate()">📥 Download Template Excel</a>
            </div>

            <!-- Loading -->
            <div class="loading" id="loading">
                <div class="spinner"></div>
                <p>Memproses file...</p>
            </div>

            <!-- Preview Section -->
            <div class="preview-section" id="previewSection">
                <div class="preview-header">
                    <div class="preview-info">
                        <strong>Total Data:</strong>
                        <span id="totalRows">0</span>
                        baris |
                        <strong>Status:</strong>
                        <span id="validationStatus">-</span>
                    </div>
                    <div>
                        <button class="btn btn-secondary" onclick="resetForm()">🔄 Reset</button>
                        <button
                            class="btn btn-success"
                            id="btnSave"
                            onclick="saveData()"
                            disabled="disabled">
                            💾 Simpan Data
                        </button>
                    </div>
                </div>

                <div class="table-container">
                    <table id="previewTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NISN *</th>
                                <th>NIS</th>
                                <th>Nama Lengkap *</th>
                                <th>Jenis Kelamin *</th>
                                <th>Tempat Lahir</th>
                                <th>Tanggal Lahir *</th>
                                <th>Agama</th>
                                <th>No HP</th>
                                <th>Email</th>
                                <th>Alamat</th>
                                <th>Nama Ayah</th>
                                <th>Nama Ibu</th>
                                <th>Pekerjaan Ayah</th>
                                <th>Pekerjaan Ibu</th>
                                <th>No HP Wali</th>
                                <th>Hubungan Wali</th>
                                <th>Tanggal Masuk *</th>
                                <th>Jalur Masuk</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody"></tbody>
                    </table>
                </div>

                <div class="action-buttons">
                    <p style="color: #718096; font-size: 13px; margin-top: 10px;">
                        * Kolom bertanda bintang adalah wajib diisi
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>