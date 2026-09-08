(function() {
    'use strict';

    // ============ KONFIGURASI & STATE ============
    const STORAGE_KEY = 'smantique_kelas';
    let kelasData = []; //JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
    let currentDetailId = null;
    let detailModalInst, deleteModalInst;

    const ApiDataKelas =  new MyFetch('/mst/getDataKelas', { method: 'GET'});

    // Seed Data Demo (Hanya jika localStorage kosong)
    // if (kelasData.length === 0) {
    //     kelasData = [
    //         { id: 1, nama_kelas: 'X IPA 1', tingkat: 10, tahun_pelajaran: '2025/2026', kapasitas: 36, jurusan: 'IPA', kurikulum: 'Kurikulum Merdeka', ruang_kelas: 'R-101', is_aktif: 1, urutan: 1 },
    //         { id: 2, nama_kelas: 'X IPS 2', tingkat: 10, tahun_pelajaran: '2025/2026', kapasitas: 36, jurusan: 'IPS', kurikulum: 'Kurikulum Merdeka', ruang_kelas: 'R-103', is_aktif: 1, urutan: 2 },
    //         { id: 3, nama_kelas: 'XI Bahasa 1', tingkat: 11, tahun_pelajaran: '2025/2026', kapasitas: 32, jurusan: 'Bahasa', kurikulum: 'Kurikulum Merdeka', ruang_kelas: 'R-205', is_aktif: 1, urutan: 3 },
    //         { id: 4, nama_kelas: 'XII IPA 3', tingkat: 12, tahun_pelajaran: '2024/2025', kapasitas: 34, jurusan: 'IPA', kurikulum: 'K13 Revisi', ruang_kelas: 'R-302', is_aktif: 0, urutan: 4 }
    //     ];
    //     saveData();
    // }

    async function loadDataFromServer() {
        try {
            const result = await ApiDataKelas.fetchData();
                if(result.status){
                    kelasData = result.data;
                    setTimeout(() => {
                        renderList();
                        updateSummary();
                    }, 500);

                    //saveData();
                   // console.log('Data fetched successfully.', result.data);
                };
                if (!result.status) throw new Error(`Device log gagal: ${result.status}`);
            
        } catch (error) {
            
            console.error(error);
        }
    }   



    // ============ INITIALIZATION ============
    document.addEventListener('DOMContentLoaded', () => {
        // Inisialisasi Bootstrap Modal (pastikan ID modal di HTML sesuai)
        if (typeof bootstrap !== 'undefined') {
            detailModalInst = new bootstrap.Modal(document.getElementById('detailModal'));
            deleteModalInst = new bootstrap.Modal(document.getElementById('deleteModal'));
        }
        loadDataFromServer();
        initTabs();
        updateSummary();
        
    });

    // ============ TAB NAVIGATION ============
    function initTabs() {
        document.querySelectorAll('.page-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.page-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                document.getElementById(`tab-${this.dataset.tab}`).classList.add('active');
            });
        });
    }

    // Fungsi Global untuk pindah ke form (Add/Edit)
    window.switchToForm = function(editId = null) {
        document.querySelectorAll('.page-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        
        const formTab = document.querySelector('[data-tab="form"]');
        const formContent = document.getElementById('tab-form');
        if(formTab && formContent) {
            formTab.classList.add('active');
            formContent.classList.add('active');
        }

        if (editId) {
            loadEditForm(editId);
        } else {
            resetForm();
            document.getElementById('formTitle').innerHTML = '<i class="fa-solid fa-school"></i> Tambah Kelas Baru';
            document.getElementById('formSubtitle').textContent = 'Isi data rombel dengan lengkap dan benar';
            document.getElementById('btnSubmit').innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Simpan Data Kelas';
        }
    };

    // ============ RENDER LIST (CARD VIEW) ============
    window.renderList = function() {
        const listContainer = document.getElementById('kelasList');
        const searchVal = document.getElementById('searchKelas')?.value.toLowerCase().trim() || '';
        const filterTingkat = document.getElementById('filterTingkat')?.value || 'all';
        const filterJurusan = document.getElementById('filterJurusan')?.value || 'all';

        // Filtering Logic
        let filtered = kelasData.filter(k => {
            const matchSearch = !searchVal || 
                k.nama_kelas.toLowerCase().includes(searchVal) || 
                k.ruang_kelas.toLowerCase().includes(searchVal) ||
                String(k.tahun_pelajaran).includes(searchVal);
            
            const matchTingkat = filterTingkat === 'all' || String(k.tingkat) === filterTingkat;
            const matchJurusan = filterJurusan === 'all' || (k.jurusan && k.jurusan.toLowerCase() === filterJurusan.toLowerCase());
            
            return matchSearch && matchTingkat && matchJurusan;
        });

        // Sort by urutan then nama
        filtered.sort((a, b) => (a.urutan || 0) - (b.urutan || 0) || a.nama_kelas.localeCompare(b.nama_kelas));

        if (filtered.length === 0) {
            listContainer.innerHTML = `
                <div class="empty-state">
                    <i class="fa-solid fa-school-circle-xmark"></i>
                    <h4>Tidak Ada Data</h4>
                    <p>${kelasData.length === 0 ? 'Belum ada data kelas. Klik "Tambah Kelas" untuk memulai.' : 'Tidak ada kelas yang cocok dengan filter pencarian.'}</p>
                </div>`;
            return;
        }

        // Generate Cards
        listContainer.innerHTML = filtered.map((k, idx) => {
            const statusClass = k.is_aktif == 1 ? 'status-active' : 'status-inactive';
            const statusText = k.is_aktif == 1 ? 'Aktif' : 'Non-Aktif';
            const jurusanBadge = k.jurusan ? `<span class="meta-badge jurusan"><i class="fa-solid fa-layer-group"></i> ${k.jurusan}</span>` : '';

            return `
            <div class="kelas-card" style="animation-delay:${idx * 0.04}s" onclick="showDetail(${k.id_kelas})">
            
                <div class="kelas-header">
                    <div class="kelas-icon"><i class="fa-solid fa-door-open"></i></div>
                    <div class="kelas-status ${statusClass}">${statusText}</div>
                </div>
                <div class="kelas-info">
                    <div class="kelas-name">Kelas : ${k.nama_kelas}</div>
                    <div class="kelas-nisn">Nama Ruang : ${k.ruang_kelas}</div>
                    <div class="kelas-meta">
                        <span class="meta-badge jabatan"><i class="fa-solid fa-briefcase"></i> ${k.tingkat}</span>
                        <span class="meta-badge ${statusClass}"><i class="fa-solid fa-id-card"></i> ${k.kapasitas}</span>
                        ${k.kurikulum ? `<span class="meta-badge pendidikan"><i class="fa-solid fa-book"></i> ${k.tahun_pelajaran}</span>` : ''}
                    </div>
                </div>

                <div class="kelas-actions" onclick="event.stopPropagation()">
                    <button class="action-btn view" onclick="showDetail(${k.id_kelas})" title="Detail"><i class="fa-solid fa-eye"></i></button>
                    <button class="action-btn edit" onclick="switchToForm(${k.id_kelas})" title="Edit"><i class="fa-solid fa-pen"></i></button>
                    <button class="action-btn delete" onclick="openDeleteDirect(${k.id_kelas})" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>`;
        }).join('');
    };

    // Event Listeners untuk Filter & Search
    const searchInput = document.getElementById('searchKelas');
    const filterTingkat = document.getElementById('filterTingkat');
    const filterJurusan = document.getElementById('filterJurusan');

    if(searchInput) searchInput.addEventListener('input', renderList);
    if(filterTingkat) filterTingkat.addEventListener('change', renderList);
    if(filterJurusan) filterJurusan.addEventListener('change', renderList);

    // ============ DETAIL MODAL ============
    window.showDetail = function(id) {
        const k = kelasData.find(x => x.id_kelas == id);
        if (!k) return;
        currentDetailId = id;

        // Populate Modal Elements
        setText('mAvatar', k.nama_kelas);
        setText('mName', k.ruang_kelas);
        setText('mNamaKelas', k.nama_kelas);
        setText('mTingkat', `Kelas ${k.tingkat}`);
        setText('mTahunAjaran', k.tahun_pelajaran);
        setText('mKapasitas', `${k.kapasitas} Siswa`);
        setText('mJurusan', k.jurusan || '-');
        setText('mKurikulum', k.kurikulum || '-');
        setText('mRuang', k.ruang_kelas || '-');
        setText('mStatus', k.is_aktif == 1 ? '✅ Aktif' : '⛔ Non-Aktif');
        setText('mUrutan', k.urutan || 0);

        detailModalInst?.show();
    };

    window.openEditFromModal = function() {
        detailModalInst?.hide();
        setTimeout(() => switchToForm(currentDetailId), 300);
    };

    // ============ FORM HANDLING ============
    function loadEditForm(id) {
        const k = kelasData.find(x => x.id_kelas == id);
        if (!k) return;

        document.getElementById('editId').value = k.id_kelas;
        document.getElementById('formTitle').innerHTML = '<i class="fa-solid fa-pen-to-square"></i> Ubah Data Kelas';
        document.getElementById('formSubtitle').textContent = `Mengedit data ${k.nama_kelas}`;
        document.getElementById('btnSubmit').innerHTML = '<i class="fa-solid fa-check"></i> Simpan Perubahan';

        // Map fields to inputs
        
        setVal('inputNamaKelas', k.nama_kelas);
        setVal('inputTingkat', k.tingkat);
        setVal('inputTahunPelajaran', k.tahun_pelajaran);
        setVal('inputKapasitas', k.kapasitas);
        setVal('inputJurusan', k.jurusan || '');
        setVal('inputKurikulum', k.kurikulum || '');
        setVal('inputRuangKelas', k.ruang_kelas || '');
        setVal('inputIsAktif', k.is_aktif);
        setVal('inputUrutan', k.urutan || 0);
    }

    document.getElementById('kelasForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const editId = document.getElementById('editId').value;

        const data = {
            nama_kelas: getVal('inputNamaKelas').trim(),
            tingkat: parseInt(getVal('inputTingkat')),
            tahun_pelajaran: getVal('inputTahunPelajaran').trim(),
            kapasitas: parseInt(getVal('inputKapasitas')),
            jurusan: getVal('inputJurusan').trim() || null,
            kurikulum: getVal('inputKurikulum').trim() || null,
            ruang_kelas: getVal('inputRuangKelas').trim() || null,
            is_aktif: parseInt(getVal('inputIsAktif')),
            urutan: parseInt(getVal('inputUrutan')) || 0
        };
        const submitKelas =  new MyFetch('/mst/inputKelas', 
            {
                method: 'POST',
                csrfHash: document.getElementById('csrf_edusmara').textContent,
                params : data
            }
        );
        
       
        //
        // Validasi Unik: Nama Kelas + Tahun Pelajaran tidak boleh duplikat
        const dup = kelasData.find(k => 
            k.nama_kelas.toLowerCase() === data.nama_kelas.toLowerCase() && 
            k.tahun_pelajaran === data.tahun_pelajaran && 
            k.id_kelas !== parseInt(editId)
        );

        if (editId) {
            resetMsgError();
             const updateKelas =  new MyFetch('/mst/updateKelas/' + editId, 
                                {
                                    method: 'POST',
                                    csrfHash: document.getElementById('csrf_edusmara').textContent,
                                    params : data
                                }
                            );

            try {
                const result = await updateKelas.fetchData();
                if(result.status){
                    showToast('success', 'Berhasil!', `Data ${data.nama_kelas} berhasil diperbarui.`);
                    reloadData();
                }
            } catch (error) {
                if(error.status === 422) {
                    const errors = error.data.errors;
                    console.log('Validation Errors:', errors);
                    const errorMessages = Object.values(errors).flat().join('<br>');
                    showToast('error', 'Validasi Gagal!', errorMessages);

                    const errorFields = Object.keys(errors);
                    errorFields.forEach(field => {
                        const input = document.getElementsByName(`input-${field}`)[0];
                        if (input) {
                            input.classList.add('is-invalid');
                            input.nextElementSibling.textContent = `* ${errors[field]}`;
                        }
                    });


                } else {
                    showToast('error', 'Gagal!', `Terjadi kesalahan saat menambahkan data kelas.`);
                }

                return;
            }

            // const idx = kelasData.findIndex(k => k.id_kelas === parseInt(editId));
            // if (idx > -1) kelasData[idx] = { ...kelasData[idx], ...data };
            // showToast('success', 'Berhasil!', `Data ${data.nama_kelas} berhasil diperbarui.`);
        } else {
            if (dup) {
                showToast('error', 'Data Duplikat', `Kelas "${data.nama_kelas}" untuk TA ${data.tahun_pelajaran} sudah ada!`);
                return;
            }

            resetMsgError();
            try {
                const result = await submitKelas.fetchData();
                if(result.status){

                    showToast('success', 'Berhasil!', `Kelas ${data.nama_kelas} berhasil ditambahkan.`);
                    reloadData();
                }
            } catch (error) {
               // console.error(error);
                if(error.status === 422) {
                    const errors = error.data.errors;
                    console.log('Validation Errors:', errors);
                    const errorMessages = Object.values(errors).flat().join('<br>');
                    showToast('error', 'Validasi Gagal!', errorMessages);

                    const errorFields = Object.keys(errors);
                    errorFields.forEach(field => {
                        const input = document.getElementsByName(`input-${field}`)[0];
                        if (input) {
                            input.classList.add('is-invalid');
                            input.nextElementSibling.textContent = `* ${errors[field]}`;
                        }
                    });


                } else {
                    showToast('error', 'Gagal!', `Terjadi kesalahan saat menambahkan data kelas.`);
                }

               return;
            }
        }
        function reloadData() {
            loadDataFromServer();
            updateSummary();
            renderList();
            resetForm();

            // Kembali ke Tab List
            const listTab = document.querySelector('[data-tab="list"]');
            const listContent = document.getElementById('tab-list');
            if(listTab && listContent) {
                document.querySelectorAll('.page-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                listTab.classList.add('active');
                listContent.classList.add('active');
        }
    }
    });

    window.closeForm = function() {
            resetForm();
            const listTab = document.getElementById('tab-list');
            const formTab = document.getElementById('tab-form');
            listTab.classList.add('active');
            formTab.classList.remove('active');
    }
    function resetMsgError() {
        // Reset form validation states
        document.querySelectorAll('.form-control-custom').forEach(input => {    
            input.classList.remove('is-invalid');
        });
        document.querySelectorAll('.form-note').forEach(note => note.textContent = '');
    }

    window.resetForm = function() {
        document.getElementById('kelasForm')?.reset();
        document.getElementById('editId').value = '';
        document.getElementById('formTitle').innerHTML = '<i class="fa-solid fa-school"></i> Tambah Kelas Baru';
        document.getElementById('formSubtitle').textContent = 'Isi data rombel dengan lengkap dan benar';
        document.getElementById('btnSubmit').innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Simpan Data Kelas';
    };

    // ============ DELETE LOGIC ============
    window.openDeleteConfirm = function() {
        const k = kelasData.find(x => x.id_kelas === currentDetailId);
        if (!k) return;
        detailModalInst?.hide();
        document.getElementById('deleteId').value = k.id_kelas;
        document.getElementById('deleteName').textContent = k.nama_kelas;
        setTimeout(() => deleteModalInst?.show(), 300);
    };

    window.openDeleteDirect = function(id) {
        const k = kelasData.find(x => x.id_kelas == id);
        if (!k) return;
        document.getElementById('deleteId').value = k.id_kelas;
        document.getElementById('deleteName').textContent = k.nama_kelas;
        deleteModalInst?.show();
    };

    window.confirmDelete = function() {
        const id = parseInt(document.getElementById('deleteId').value);
        const k = kelasData.find(x => x.id_kelas == id);
        if (!k) return;

        const removeKelas =  new MyFetch('/mst/deleteKelas/' + id);

        try {
            removeKelas.fetchData().then(result => {
                if(result.status){
                    showToast('warning', 'Dihapus', `Data kelas ${k.nama_kelas} telah dihapus permanen.`);
                    kelasData = kelasData.filter(x => x.id_kelas !== id);
                    loadDataFromServer();
                    updateSummary();
                    renderList();
                    deleteModalInst?.hide();
                }
            });

        } catch (error) {
            console.error(error);
            showToast('warning', 'Dihapus', error.message || `Terjadi kesalahan saat menghapus data kelas ${k.nama_kelas}.`);
        }
        
    };

    // ============ SUMMARY DASHBOARD ============
    function updateSummary() {
        setText('sumTotal', kelasData.length);
        setText('sumKelas10', kelasData.filter(k => k.tingkat == 10).length);
        setText('sumKelas11', kelasData.filter(k => k.tingkat == 11).length);
        setText('sumKelas12', kelasData.filter(k => k.tingkat == 12).length);
    }

    // ============ HELPER FUNCTIONS ============
    //function saveData() { localStorage.setItem(STORAGE_KEY, JSON.stringify(kelasData)); }
    
    function setText(id, text) { 
        const el = document.getElementById(id); 
        if(el) el.textContent = text; 
    }
    
    function setVal(id, val) { 
        const el = document.getElementById(id); 
        if(el) el.value = val; 
    }
    
    function getVal(id) { 
        const el = document.getElementById(id); 
        return el ? el.value : ''; 
    }

    function loadDataWaliKelas() {
        // Simulasi data guru untuk dropdown Wali Kelas
        const waliKelasSelect = document.getElementById('inputWaliKelas');
        if (!waliKelasSelect) return;

        const waliKelasOptions = document.createDocumentFragment();
        guruData.forEach(g => {
            const option = document.createElement('option');
            option.value = g.id;
            option.textContent = g.nama;
            waliKelasOptions.appendChild(option);
        });
        waliKelasSelect.appendChild(waliKelasOptions);
    }

    function loadDataTingkat() {
        const tingkatSelect = document.getElementById('filterTingkat');
        if (!tingkatSelect) return;

        const tingkatOptions = document.createDocumentFragment();
        tingkatData.forEach(t => {
            const option = document.createElement('option');
            option.value = t.id;
            option.textContent = t.tingkat;
            tingkatOptions.appendChild(option);
        });
        tingkatSelect.appendChild(tingkatOptions);
    }
   

})();