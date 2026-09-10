
(function() {
    'use strict';

    const STORAGE_KEY = 'smantique_guru';
    let guruData = []; //JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
    let currentDetailId = null;
    let detailModalInst, deleteModalInst;
    const inputNamaKelas = document.getElementById('inputNamaKelas');
    const inputSiswa = document.getElementById('inputSiswa');

    const ApiDataGuru  =  new MyFetch('/mst/getDataGuru', { method: 'GET'});
    async function loadDataFromServer() {
        try {
            const result = await ApiDataGuru.fetchData();
                if(result.status){
                    guruData = result.data;
                    setTimeout(() => {
                        renderList();
                        updateSummary();
                    }, 500);

                };
                if (!result.status) throw new Error(`Device log gagal: ${result.status}`);
            
        } catch (error) {
            
            console.error(error);
        }
    }   
    // ============ INIT ============
    document.addEventListener('DOMContentLoaded', () => {
        detailModalInst = new bootstrap.Modal(document.getElementById('detailModal'));
        deleteModalInst = new bootstrap.Modal(document.getElementById('deleteModal'));
        loadDataFromServer();
        initTabs();
        updateSummary();
        renderList(); 
        loadDataKelas();
    });

    // ============ TABS ============
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

    window.switchToForm = function(editId = null) {
        document.querySelectorAll('.page-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        document.querySelector('[data-tab="form"]').classList.add('active');
        document.getElementById('tab-form').classList.add('active');

        if (editId) {
            loadEditForm(editId);
        } else {
            resetForm();
            document.getElementById('formTitle').innerHTML = '<i class="fa-solid fa-user-plus"></i> Tambah Data Guru Baru';
            document.getElementById('formSubtitle').textContent = 'Lengkapi profil guru dengan benar';
            document.getElementById('btnSubmit').innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Simpan Data Guru';
        }
    };

    // ============ RENDER LIST ============
    window.renderList = function() {
        const list = document.getElementById('teacherList');
        const search = document.getElementById('searchGuru').value.toLowerCase().trim();
        const filterStatus = document.getElementById('filterStatus').value;
        const filterJabatan = document.getElementById('filterJabatan').value;

        let filtered = guruData.filter(g => {
            const matchSearch = !search || g.nama_lengkap.toLowerCase().includes(search) || g.nip.includes(search);
            const matchStatus = filterStatus === 'all' || g.status_kepegawaian === filterStatus;
            const matchJabatan = filterJabatan === 'all' || g.jabatan_struktural === filterJabatan;
            return matchSearch && matchStatus && matchJabatan;
        });

        if (filtered.length === 0) {
            list.innerHTML = `<div class="empty-state"><i class="fa-solid fa-user-slash"></i><h4>Tidak Ada Data</h4><p>${guruData.length === 0 ? 'Belum ada data guru. Klik "Tambah Guru" untuk memulai.' : 'Tidak ada guru yang cocok dengan filter.'}</p></div>`;
            return;
        }

        list.innerHTML = filtered.map((g, idx) => {
            const initials = g.nama_lengkap.replace(/^(Drs\.|Dr\.|Prof\.|Ir\.)\s*/i, '').split(/[,\s]+/).filter(Boolean).map(n => n[0]).join('').substring(0, 2).toUpperCase();
            const avatarClass = g.jenis_kelamin === 'L' ? 'avatar-male' : 'avatar-female';
            const genderIcon = g.jenis_kelamin === 'L' ? '👨' : '👩';
            const statusClass = g.status_kepegawaian === 'PNS' ? 'pns' : g.status_kepegawaian === 'PPPK' ? 'p3k' : 'honorer';

            return `
                <div class="teacher-card" style="animation-delay:${idx * 0.04}s" onclick="showDetail(${g.id_guru})">
                    <div class="teacher-avatar ${avatarClass}">${initials}<div class="gender-dot">${genderIcon}</div></div>
                    <div class="teacher-info">
                        <div class="teacher-name">${g.nama_lengkap}</div>
                        <div class="teacher-nisn">NIP: ${g.nip}</div>
                        <div class="teacher-meta">
                            <span class="meta-badge jabatan"><i class="fa-solid fa-briefcase"></i> ${g.jabatan_struktural}</span>
                            <span class="meta-badge ${statusClass}"><i class="fa-solid fa-id-card"></i> ${g.status_kepegawaian}</span>
                            ${g.mapel ? `<span class="meta-badge pendidikan"><i class="fa-solid fa-book"></i> ${g.mapel}</span>` : ''}
                        </div>
                    </div>
                    <div class="teacher-actions" onclick="event.stopPropagation()">
                        <button class="action-btn view" onclick="showDetail(${g.id_guru})" title="Detail"><i class="fa-solid fa-eye"></i></button>
                        <button class="action-btn edit" onclick="switchToForm(${g.id_guru})" title="Edit"><i class="fa-solid fa-pen"></i></button>
                        <button class="action-btn delete" onclick="openDeleteDirect(${g.id_guru})" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </div>`;
        }).join('');
    };

    // Search & Filter
    document.getElementById('searchGuru').addEventListener('input', renderList);
    document.getElementById('filterStatus').addEventListener('change', renderList);
    document.getElementById('filterJabatan').addEventListener('change', renderList);

    // ============ SHOW DETAIL ============
    window.showDetail = function(id) {
        const g = guruData.find(x => x.id_guru == id);
        if (!g) return;
        currentDetailId = id;

        const initials = g.nama_lengkap.replace(/^(Drs\.|Dr\.|Prof\.|Ir\.)\s*/i, '').split(/[,\s]+/).filter(Boolean).map(n => n[0]).join('').substring(0, 2).toUpperCase();
        const avatarClass = g.jenis_kelamin === 'L' ? 'avatar-male' : 'avatar-female';

        document.getElementById('mAvatar').textContent = initials;
        document.getElementById('mAvatar').className = `modal-avatar ${avatarClass}`;
        document.getElementById('mName').textContent = g.nama_lengkap;
        document.getElementById('mJabatan').textContent = `${g.jabatan_struktural}${g.mapel ? ' • ' + g.mapel : ''}`;
        document.getElementById('mNip').textContent = g.nip;
        document.getElementById('mGender').textContent = g.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
        document.getElementById('mBirth').textContent = formatDateLong(g.tanggal_lahir);
        document.getElementById('mPhone').textContent = g.nomor_hp;
        document.getElementById('mEmail').textContent = g.email || '-';
        document.getElementById('mAddress').textContent = g.alamat_lengkap;
        document.getElementById('mEducation').textContent = `${g.jenjang_pendidikan} ${g.jurusan}`;
        document.getElementById('mUniv').textContent = g.universitas;
        document.getElementById('mYear').textContent = g.tahun_lulus || '-';
        document.getElementById('mMapel').textContent = g.mapel || '-';
        document.getElementById('mStatus').textContent = g.status_kepegawaian;
        document.getElementById('mGolongan').textContent = g.golongan_pangkat || '-';
        document.getElementById('mTmt').textContent = g.tmt ? formatDateLong(g.tmt) : '-';
        document.getElementById('mNotes').textContent = g.catatan || '-';

        detailModalInst.show();
    };

    // ============ EDIT FROM MODAL ============
    window.openEditFromModal = function() {
        detailModalInst.hide();
        setTimeout(() => switchToForm(currentDetailId), 300);
    };

    function loadEditForm(id) {
        const g = guruData.find(x => x.id_guru == id);
        if (!g) return;

        document.getElementById('editId').value = g.id;
        document.getElementById('formTitle').innerHTML = '<i class="fa-solid fa-pen-to-square"></i> Ubah Data Guru';
        document.getElementById('formSubtitle').textContent = `Mengedit data ${g.nama_lengkap}`;
        document.getElementById('btnSubmit').innerHTML = '<i class="fa-solid fa-check"></i> Simpan Perubahan';

        document.getElementById('inputNama').value = g.nama_lengkap;
        document.getElementById('inputNip').value = g.nip;
        document.getElementById('inputGender').value = g.jenis_kelamin;
        document.getElementById('inputTglLahir').value = g.tanggal_lahir;
        document.getElementById('inputHp').value = g.nomor_hp;
        document.getElementById('inputEmail').value = g.email || '';
        document.getElementById('inputAlamat').value = g.alamat_lengkap;
        document.getElementById('inputJenjang').value = g.jenjang_pendidikan;
        document.getElementById('inputJurusan').value = g.jurusan;
        document.getElementById('inputUniv').value = g.universitas;
        document.getElementById('inputTahunLulus').value = g.tahun_lulus || '';
        document.getElementById('inputJabatan').value = g.jabatan_struktural;
        document.getElementById('inputMapel').value = g.mapel || '';
        document.getElementById('inputStatus').value = g.status_kepegawaian;
        document.getElementById('inputGolongan').value = g.golongan_pangkat || '';
        document.getElementById('inputTmt').value = g.tmt || '';
        document.getElementById('inputCatatan').value = g.catatan || '';
    }

    // ============ FORM SUBMIT ============
    document.getElementById('guruForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const editId = document.getElementById('editId').value;

        const data = {
            nama_lengkap: document.getElementById('inputNama').value.trim(),
            nip: document.getElementById('inputNip').value.trim(),
            nuptk: document.getElementById('inputNuptk').value.trim(),
            jenis_kelamin: document.getElementById('inputGender').value,
            tempat_lahir: document.getElementById('inputTmpLahir').value,
            tanggal_lahir: document.getElementById('inputTglLahir').value,
            nomor_hp: document.getElementById('inputHp').value.trim(),
            email: document.getElementById('inputEmail').value.trim(),
            alamat_lengkap: document.getElementById('inputAlamat').value.trim(),
            jenjang_pendidikan: document.getElementById('inputJenjang').value,
            jurusan: document.getElementById('inputJurusan').value.trim(),
            universitas: document.getElementById('inputUniv').value.trim(),
            tahun_lulus: document.getElementById('inputTahunLulus').value,
            jabatan_struktural: document.getElementById('inputJabatan').value,
            mapel: document.getElementById('inputMapel').value,
            status_kepegawaian: document.getElementById('inputStatus').value,
            status_pegawai: document.getElementById('inputStatusPegawai').value,
            golongan_pangkat: document.getElementById('inputGolongan').value.trim(),
            tmt: document.getElementById('inputTmt').value,
            sertifikasi_guru: document.getElementById('inputSertifikasi').value,
            no_sertifikasi: document.getElementById('inputNoSertifikasi').value,
            bidang_sertifikasi: document.getElementById('inputBidangSertifikasi').value,
            tahun_sertifikasi: document.getElementById('inputTahunSertifikasi').value,
            catatan: document.getElementById('inputCatatan').value.trim()
        };
        const submitGuru =  new MyFetch('/mst/inputGuru', 
                            {
                                method: 'POST',
                                csrfHash: document.getElementById('csrf_edusmara').textContent,
                                params : data
                            }
                        );

        
        // Validate NIP unique
        const dup = guruData.find(g => g.nip === data.nip && g.id_guru != parseInt(editId));
        

        if (editId) {
            // const idx = guruData.findIndex(g => g.id_guru === parseInt(editId));
            // if (idx > -1) { guruData[idx] = { ...guruData[idx], ...data }; }
            // showToast('success', 'Data Diperbarui!', `Perubahan untuk ${data.nama_lengkap} telah disimpan`);
            resetMsgError();
             const updateGuru =  new MyFetch('/mst/updateGuru/' + editId, 
                                {
                                    method: 'POST',
                                    csrfHash: document.getElementById('csrf_edusmara').textContent,
                                    params : data
                                }
                            );

            try {
                const result = await updateGuru.fetchData();
                if(result.status){
                    showToast('success', 'Berhasil!', `Data ${data.nama_lengkap} berhasil diperbarui.`);
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

        } else {
            if (dup) { showToast('error', 'NIP Duplikat', `NIP sudah digunakan oleh ${dup.nama_lengkap}`); return; }

             resetMsgError();
            try {
                const result = await submitGuru.fetchData();
                if(result.status){

                    showToast('success', 'Berhasil!', `Guru ${data.nama_lengkap} berhasil ditambahkan.`);
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
                    showToast('error', 'Gagal!', `Terjadi kesalahan saat menambahkan data Guru.`);
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

    window.resetForm = function() {
        document.getElementById('guruForm').reset();
        document.getElementById('editId').value = '';
        document.getElementById('formTitle').innerHTML = '<i class="fa-solid fa-user-plus"></i> Tambah Data Guru Baru';
        document.getElementById('formSubtitle').textContent = 'Lengkapi profil guru dengan benar';
        document.getElementById('btnSubmit').innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Simpan Data Guru';
    };

    // ============ DELETE ============
    window.openDeleteConfirm = function() {
        const g = guruData.find(x => x.id_guru == currentDetailId);
        if (!g) return;
        detailModalInst.hide();
        document.getElementById('deleteId').value = g.id;
        document.getElementById('deleteName').textContent = g.nama_lengkap;
        setTimeout(() => deleteModalInst.show(), 300);
    };

    window.openDeleteDirect = function(id) {
        const g = guruData.find(x => x.id_guru == id);
        if (!g) return;
        document.getElementById('deleteId').value = g.id;
        document.getElementById('deleteName').textContent = g.nama_lengkap;
        deleteModalInst.show();
    };

    window.confirmDelete = function() {
        const id = parseInt(document.getElementById('deleteId').value);
        const k = guruData.find(x => x.id_guru == id);
        if (!k) return;

        const removeGuru =  new MyFetch('/mst/deleteGuru/' + id);

        try {
            removeGuru.fetchData().then(result => {
                if(result.status){
                    showToast('warning', 'Dihapus', `Data guru ${k.nama_lengkap} telah dihapus permanen.`);
                    guruData = guruData.filter(x => x.id_guru !== id);
                    loadDataFromServer();
                    updateSummary();
                    renderList();
                    deleteModalInst?.hide();
                }
            });

        } catch (error) {
            console.error(error);
            showToast('warning', 'Dihapus', error.message || `Terjadi kesalahan saat menghapus data guru ${k.nama_lengkap}.`);
        }
    };

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

    inputNamaKelas.addEventListener('change', function() {
        loadDataSiswaByKelas(this.value);
    })

    function loadDataSiswaByKelas(id_kelas = null) {

        const dataSiswa  =  new MyFetch('/prestasi/getDataSiswa', { method: 'GET', params: { kelas: id_kelas } });
        try {
            dataSiswa.fetchData().then(result => {
                console.log(' Result:', result);

                if(result.status){
                    const data = result.data;
                    inputSiswa.innerHTML = '';
                    data.forEach(siswa => {
                        const option = document.createElement('option');
                        option.value = siswa.id_siswa;
                        option.textContent = `${siswa.nisn} - ${siswa.nama_lengkap}`;
                        inputSiswa.appendChild(option);
                    });
                }
                
            });

        } catch (error) {
            console.error(error);
            
        }
        
    }
    
    
    function loadDataKelas() {
        const datas  =  new MyFetch('/prestasi/getDataKelas', { method: 'GET' });
        
        try {
            datas.fetchData().then(result => {
                console.log(' Result:', result);

                if(result.status){
                    const data = result.data;
                    inputNamaKelas.innerHTML = '';
                    data.forEach(r => {
                        const option = document.createElement('option');
                        option.value = r.id_kelas;
                        option.textContent = `Kelas ${r.tingkat} - ${r.nama_kelas}`;
                        inputNamaKelas.appendChild(option);
                    });
                }
                
            });

        } catch (error) {
            console.error(error);
            
        }
        
    }

    // ============ SUMMARY ============
    function updateSummary() {
        document.getElementById('sumTotal').textContent = guruData.length;
        document.getElementById('sumPNS').textContent = guruData.filter(g => g.status_kepegawaian === 'PNS').length;
        document.getElementById('sumP3K').textContent = guruData.filter(g => g.status_kepegawaian === 'PPPK').length;
        document.getElementById('sumHonorer').textContent = guruData.filter(g => g.status_kepegawaian === 'Honorer' || g.status === 'GTT').length;
    }

    // ============ HELPERS ============
    //function saveData() { localStorage.setItem(STORAGE_KEY, JSON.stringify(guruData)); }
    function formatDateLong(d) {
        if (!d) return '-';
        return new Date(d).toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
    }


})();