
(function(){
'use strict';

const STATUS_LABELS = {1:'Aktif',0:'Non-Aktif'};
let kategoriPrestasiData = [];
let nextId = 100;
let detailModalInst = null;
let deleteModalInst = null;
let currentDetailId = null;

const ApiData =  new MyFetch('/sms/kategoriprestasi/getData', { method: 'GET'});

window.loadDataFromServer = async function() {
    try {
        const result = await ApiData.fetchData();
        if(result.status){
            kategoriPrestasiData = result.data;
            setTimeout(() => {
                renderList();
                updateSummary();
            }, 500);
        };
        if (!result.status) throw new Error(`Data gagal dimuat: ${result.status}`);

    } catch (error) {
        console.error(error);
    }
}

// ============ INIT ============
document.addEventListener('DOMContentLoaded',()=>{
    detailModalInst = new bootstrap.Modal(document.getElementById('detailModal'));
    deleteModalInst = new bootstrap.Modal(document.getElementById('deleteModal'));
    loadDataFromServer();
    initTabs();
    renderList();
    updateSummary();
    document.getElementById('searchInput').addEventListener('input',renderList);
    document.getElementById('filterStatus').addEventListener('change',renderList);
});

window.initTabs = function(){
    document.querySelectorAll('.page-tab').forEach(tab=>{
        tab.addEventListener('click',function(){
            document.querySelectorAll('.page-tab').forEach(t=>t.classList.remove('active'));
            this.classList.add('active');
            document.querySelectorAll('.tab-content').forEach(c=>c.classList.remove('active'));
            document.getElementById(`tab-${this.dataset.tab}`).classList.add('active');
        });
    });
};

// ============ RENDER LIST ============
window.renderList = function(){
    const container = document.getElementById('kategoriprestasiList');
    if(!container) return;

    const search = document.getElementById('searchInput').value.toLowerCase().trim();
    const fStat = document.getElementById('filterStatus').value;

    let filtered = kategoriPrestasiData.filter(a=>{
        const ms = !search || a.nama_kategori.toLowerCase().includes(search) || a.kode_kategori.toLowerCase().includes(search);
        const mst = fStat==='all' || a.is_aktif==fStat;
        return ms && mst;
    }).sort((a,b)=>a.urutan - b.urutan);

    if(filtered.length===0){
        container.innerHTML=`<div class="empty-state"><i class="fa-solid fa-trophy"></i><h4>Tidak Ada Data</h4><p>${kategoriPrestasiData.length===0?'Belum ada kategori prestasi. Klik "Tambah Kategori" untuk memulai.':'Tidak ada data yang cocok dengan filter.'}</p></div>`;
        return;
    }

    container.innerHTML = filtered.map((a,idx)=>{
        const statusClass = a.is_aktif ? 'active' : 'inactive';
        const statusLabel = a.is_aktif ? 'Aktif' : 'Non-Aktif';

        return `<div class="kp-card" style="animation-delay:${idx*0.04}s" onclick="showDetail(${a.id_kategori_prestasi})">
            <div class="kp-body">
                <div class="kp-top">
                    <span class="kp-badge">${a.kode_kategori}</span>
                    <span class="kp-status ${statusClass}">${statusLabel}</span>
                </div>
                <div class="kp-title">${a.nama_kategori}</div>
                <div class="kp-meta">
                    <span><i class="fa-solid fa-list-ol"></i> Urutan: ${a.urutan}</span>
                    ${a.deskripsi ? `<span><i class="fa-solid fa-file-lines"></i> Deskripsi</span>` : ''}
                </div>
            </div>
            <div class="kp-actions" onclick="event.stopPropagation()">
                <button class="act-btn view" onclick="showDetail(${a.id_kategori_prestasi})" title="Detail"><i class="fa-solid fa-eye"></i></button>
                <button class="act-btn edit" onclick="openEditForm(${a.id_kategori_prestasi})" title="Edit"><i class="fa-solid fa-pen"></i></button>
                <button class="act-btn delete" onclick="openDeleteConfirm(${a.id_kategori_prestasi})" title="Hapus"><i class="fa-solid fa-trash"></i></button>
            </div>
        </div>`;
    }).join('');
};

// ============ SUMMARY ============
window.updateSummary = function(){
    const sumTotal = document.getElementById('sumTotal');
    const sumActive = document.getElementById('sumActive');
    const sumMaxUrutan = document.getElementById('sumMaxUrutan');
    const sumAvgUrutan = document.getElementById('sumAvgUrutan');

    if(sumTotal) sumTotal.textContent = kategoriPrestasiData.length;
    if(sumActive) sumActive.textContent = kategoriPrestasiData.filter(a=>a.is_aktif==1).length;

    const maxUrutan = kategoriPrestasiData.length > 0 ? Math.max(...kategoriPrestasiData.map(a=>a.urutan)) : 0;
    if(sumMaxUrutan) sumMaxUrutan.textContent = maxUrutan;

    const avgUrutan = kategoriPrestasiData.length > 0 ? (kategoriPrestasiData.reduce((sum,a)=>sum+a.urutan,0) / kategoriPrestasiData.length).toFixed(1) : 0;
    if(sumAvgUrutan) sumAvgUrutan.textContent = avgUrutan;
};

// ============ SHOW DETAIL ============
window.showDetail = function(id){
    const a = kategoriPrestasiData.find(x=>x.id_kategori_prestasi==id);
    if(!a) return;
    currentDetailId = id;

    document.getElementById('mBadge').innerHTML = `<span class="kp-badge" style="font-size:11px;padding:4px 12px">${a.kode_kategori}</span>`;
    document.getElementById('mTitle').textContent = a.nama_kategori;
    document.getElementById('mKode').textContent = `Kode: ${a.kode_kategori}`;
    document.getElementById('mUrutan').textContent = a.urutan;
    document.getElementById('mStatus').textContent = a.is_aktif ? 'Aktif' : 'Non-Aktif';
    document.getElementById('mDeskripsi').textContent = a.deskripsi || '-';
    document.getElementById('mCreatedAt').textContent = a.created_at ? new Date(a.created_at).toLocaleDateString('id-ID') : '-';

    if(detailModalInst) {
        detailModalInst.show();
    }
};

window.editFromModal = function(){
    if(detailModalInst) {
        detailModalInst.hide();
    }
    setTimeout(()=>openEditForm(currentDetailId),300);
};

window.deleteFromModal = function(){
    if(detailModalInst) {
        detailModalInst.hide();
    }
    setTimeout(()=>openDeleteConfirm(currentDetailId),300);
};

// ============ FORM: ADD ============
window.openAddForm = function(){
    resetForm();
    document.getElementById('formTitle').innerHTML = '<i class="fa-solid fa-trophy"></i> Tambah Kategori Prestasi Baru';
    document.getElementById('formSubtitle').textContent = 'Lengkapi informasi kategori prestasi';
    switchTab('form');
};

// ============ FORM: EDIT ============
window.openEditForm = function(id){
    const a = kategoriPrestasiData.find(x=>x.id_kategori_prestasi==id);
    if(!a) {
        console.error('Data tidak ditemukan dengan ID:', id);
        return;
    }
    resetForm();
    document.getElementById('editId').value = a.id_kategori_prestasi;
    document.getElementById('formTitle').innerHTML = '<i class="fa-solid fa-pen-to-square"></i> Edit Kategori Prestasi';
    document.getElementById('formSubtitle').textContent = `Mengedit: ${a.nama_kategori}`;
    document.getElementById('fNamaKategori').value = a.nama_kategori;
    document.getElementById('fKodeKategori').value = a.kode_kategori;
    document.getElementById('fDeskripsi').value = a.deskripsi || '';
    document.getElementById('fUrutan').value = a.urutan;
    document.getElementById('fIsActive').value = a.is_aktif;
    switchTab('form');
};

window.resetForm = function(){
    document.getElementById('kategoriprestasiForm').reset();
    document.getElementById('editId').value = '';
};

window.switchTab = function(tab){
    document.querySelectorAll('.page-tab').forEach(t=>t.classList.remove('active'));
    const tabElement = document.querySelector(`[data-tab="${tab}"]`);
    if(tabElement) {
        tabElement.classList.add('active');
    }
    document.querySelectorAll('.tab-content').forEach(c=>c.classList.remove('active'));
    const contentElement = document.getElementById(`tab-${tab}`);
    if(contentElement) {
        contentElement.classList.add('active');
    }
};

// ============ SAVE ============
document.getElementById('kategoriprestasiForm').addEventListener('submit',function(e){
    e.preventDefault();
    saveKategoriPrestasi();
});

async function saveKategoriPrestasi(){
    const editId = document.getElementById('editId').value;
    const namaKategori = document.getElementById('fNamaKategori').value.trim();
    const kodeKategori = document.getElementById('fKodeKategori').value.trim();
    const deskripsi = document.getElementById('fDeskripsi').value.trim();
    const urutan = document.getElementById('fUrutan').value;
    const isActive = document.getElementById('fIsActive').value;

    if(!namaKategori||!kodeKategori||!urutan){
        showToast('error','Form Belum Lengkap','Harap isi semua field yang wajib (*)');return;
    }

    const entry = {
        nama_kategori: namaKategori,
        kode_kategori: kodeKategori,
        deskripsi: deskripsi,
        urutan: parseInt(urutan),
        is_aktif: parseInt(isActive)
    };

    if(editId){
        const updateData =  new MyFetch('/sms/kategoriprestasi/update/' + editId,
            {
                method: 'POST',
                csrfHash: document.getElementById('csrf_edusmara').textContent,
                params : entry
            }
        );

        try {
            const result = await updateData.fetchData();
            if(result.status){
                showToast('success', 'Berhasil!', `Data ${entry.nama_kategori} berhasil diperbarui.`);
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
                showToast('error', 'Gagal!', `Terjadi kesalahan saat memperbarui data.`);
            }

            return;
        }
    } else {
        const submitdata =  new MyFetch('/sms/kategoriprestasi/create',
            {
                method: 'POST',
                csrfHash: document.getElementById('csrf_edusmara').textContent,
                params : entry
            }
        );

        try {
            const result = await submitdata.fetchData();
            if(result.status){
                showToast('success', 'Berhasil!', `Kategori prestasi ${entry.nama_kategori} berhasil ditambahkan.`);
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
                showToast('error', 'Gagal!', `Terjadi kesalahan saat menambahkan data.`);
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
}

// ============ DELETE ============
window.openDeleteConfirm = function(id){
    const a = kategoriPrestasiData.find(x=>x.id_kategori_prestasi==id);
    if(!a) {
        console.error('Data tidak ditemukan dengan ID:', id);
        return;
    }
    document.getElementById('delId').value = a.id_kategori_prestasi;
    document.getElementById('delName').textContent = a.nama_kategori;
    if(deleteModalInst) {
        deleteModalInst.show();
    }
};

window.confirmDelete = function(){
    const id = parseInt(document.getElementById('delId').value);
    const a = kategoriPrestasiData.find(x=>x.id_kategori_prestasi==id);
    if(!a) return;

    const removeData =  new MyFetch('/sms/kategoriprestasi/delete/' + id, {method: 'GET', csrfHash: document.getElementById('csrf_edusmara').textContent});

    removeData.fetchData().then(result => {
        console.log('Delete Result:', result);
        if(result && result.status){
            showToast('warning', 'Dihapus', `Data ${a.nama_kategori} telah dihapus permanen.`);
            loadDataFromServer();
            updateSummary();
            renderList();
            if(deleteModalInst) {
                deleteModalInst.hide();
            }
        } else {
            showToast('error', 'Gagal', 'Gagal menghapus data.');
        }
    }).catch(error => {
        console.error(error);
        showToast('error', 'Gagal', error.message || `Terjadi kesalahan saat menghapus ${a.nama_kategori}.`);
    });
};

})();
