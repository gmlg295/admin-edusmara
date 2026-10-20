
(function(){
'use strict';

const STATUS_LABELS = {1:'Aktif',0:'Non-Aktif'};
let sanksiData = [];
let nextId = 100;
let detailModalInst = null;
let deleteModalInst = null;
let currentDetailId = null;

const ApiData =  new MyFetch('/sms/sanksi/getData', { method: 'GET'});

window.loadDataFromServer = async function() {
    try {
        const result = await ApiData.fetchData();
        if(result.status){
            sanksiData = result.data;
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
    const container = document.getElementById('sanksiList');
    if(!container) return;

    const search = document.getElementById('searchInput').value.toLowerCase().trim();
    const fStat = document.getElementById('filterStatus').value;

    let filtered = sanksiData.filter(a=>{
        const ms = !search || a.nama_sanksi.toLowerCase().includes(search) || a.kode_sanksi.toLowerCase().includes(search);
        const mst = fStat==='all' || a.is_aktif==fStat;
        return ms && mst;
    }).sort((a,b)=>a.urutan - b.urutan);

    if(filtered.length===0){
        container.innerHTML=`<div class="empty-state"><i class="fa-solid fa-gavel"></i><h4>Tidak Ada Data</h4><p>${sanksiData.length===0?'Belum ada sanksi. Klik "Tambah Sanksi" untuk memulai.':'Tidak ada data yang cocok dengan filter.'}</p></div>`;
        return;
    }

    container.innerHTML = filtered.map((a,idx)=>{
        const statusClass = a.is_aktif ? 'active' : 'inactive';
        const statusLabel = a.is_aktif ? 'Aktif' : 'Non-Aktif';

        return `<div class="sn-card" style="animation-delay:${idx*0.04}s" onclick="showDetail(${a.id_sanksi})">
            <div class="sn-body">
                <div class="sn-top">
                    <span class="sn-badge">${a.kode_sanksi}</span>
                    <span class="sn-status ${statusClass}">${statusLabel}</span>
                </div>
                <div class="sn-title">${a.nama_sanksi}</div>
                <div class="sn-meta">
                    <span><i class="fa-solid fa-list-ol"></i> Urutan: ${a.urutan}</span>
                    ${a.deskripsi ? `<span><i class="fa-solid fa-file-lines"></i> Deskripsi</span>` : ''}
                </div>
            </div>
            <div class="sn-actions" onclick="event.stopPropagation()">
                <button class="act-btn view" onclick="showDetail(${a.id_sanksi})" title="Detail"><i class="fa-solid fa-eye"></i></button>
                <button class="act-btn edit" onclick="openEditForm(${a.id_sanksi})" title="Edit"><i class="fa-solid fa-pen"></i></button>
                <button class="act-btn delete" onclick="openDeleteConfirm(${a.id_sanksi})" title="Hapus"><i class="fa-solid fa-trash"></i></button>
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

    if(sumTotal) sumTotal.textContent = sanksiData.length;
    if(sumActive) sumActive.textContent = sanksiData.filter(a=>a.is_aktif==1).length;

    const maxUrutan = sanksiData.length > 0 ? Math.max(...sanksiData.map(a=>a.urutan)) : 0;
    if(sumMaxUrutan) sumMaxUrutan.textContent = maxUrutan;

    const avgUrutan = sanksiData.length > 0 ? (sanksiData.reduce((sum,a)=>sum+a.urutan,0) / sanksiData.length).toFixed(1) : 0;
    if(sumAvgUrutan) sumAvgUrutan.textContent = avgUrutan;
};

// ============ SHOW DETAIL ============
window.showDetail = function(id){
    const a = sanksiData.find(x=>x.id_sanksi==id);
    if(!a) return;
    currentDetailId = id;

    document.getElementById('mBadge').innerHTML = `<span class="sn-badge" style="font-size:11px;padding:4px 12px">${a.kode_sanksi}</span>`;
    document.getElementById('mTitle').textContent = a.nama_sanksi;
    document.getElementById('mKode').textContent = `Kode: ${a.kode_sanksi}`;
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
    document.getElementById('formTitle').innerHTML = '<i class="fa-solid fa-gavel"></i> Tambah Sanksi Baru';
    document.getElementById('formSubtitle').textContent = 'Lengkapi informasi sanksi pelanggaran';
    switchTab('form');
};

// ============ FORM: EDIT ============
window.openEditForm = function(id){
    const a = sanksiData.find(x=>x.id_sanksi==id);
    if(!a) {
        console.error('Data tidak ditemukan dengan ID:', id);
        return;
    }
    resetForm();
    document.getElementById('editId').value = a.id_sanksi;
    document.getElementById('formTitle').innerHTML = '<i class="fa-solid fa-pen-to-square"></i> Edit Sanksi';
    document.getElementById('formSubtitle').textContent = `Mengedit: ${a.nama_sanksi}`;
    document.getElementById('fNamaSanksi').value = a.nama_sanksi;
    document.getElementById('fKodeSanksi').value = a.kode_sanksi;
    document.getElementById('fDeskripsi').value = a.deskripsi || '';
    document.getElementById('fUrutan').value = a.urutan;
    document.getElementById('fIsActive').value = a.is_aktif;
    switchTab('form');
};

window.resetForm = function(){
    document.getElementById('sanksiForm').reset();
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
document.getElementById('sanksiForm').addEventListener('submit',function(e){
    e.preventDefault();
    saveSanksi();
});

async function saveSanksi(){
    const editId = document.getElementById('editId').value;
    const namaSanksi = document.getElementById('fNamaSanksi').value.trim();
    const kodeSanksi = document.getElementById('fKodeSanksi').value.trim();
    const deskripsi = document.getElementById('fDeskripsi').value.trim();
    const urutan = document.getElementById('fUrutan').value;
    const isActive = document.getElementById('fIsActive').value;

    if(!namaSanksi||!kodeSanksi||!urutan){
        showToast('error','Form Belum Lengkap','Harap isi semua field yang wajib (*)');return;
    }

    const entry = {
        nama_sanksi: namaSanksi,
        kode_sanksi: kodeSanksi,
        deskripsi: deskripsi,
        urutan: parseInt(urutan),
        is_aktif: parseInt(isActive)
    };

    if(editId){
        const updateData =  new MyFetch('/sms/sanksi/update/' + editId,
            {
                method: 'POST',
                csrfHash: document.getElementById('csrf_edusmara').textContent,
                params : entry
            }
        );

        try {
            const result = await updateData.fetchData();
            if(result.status){
                showToast('success', 'Berhasil!', `Data ${entry.nama_sanksi} berhasil diperbarui.`);
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
        const submitdata =  new MyFetch('/sms/sanksi/create',
            {
                method: 'POST',
                csrfHash: document.getElementById('csrf_edusmara').textContent,
                params : entry
            }
        );

        try {
            const result = await submitdata.fetchData();
            if(result.status){
                showToast('success', 'Berhasil!', `Sanksi ${entry.nama_sanksi} berhasil ditambahkan.`);
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
    const a = sanksiData.find(x=>x.id_sanksi==id);
    if(!a) {
        console.error('Data tidak ditemukan dengan ID:', id);
        return;
    }
    document.getElementById('delId').value = a.id_sanksi;
    document.getElementById('delName').textContent = a.nama_sanksi;
    if(deleteModalInst) {
        deleteModalInst.show();
    }
};

window.confirmDelete = function(){
    const id = parseInt(document.getElementById('delId').value);
    const a = sanksiData.find(x=>x.id_sanksi==id);
    if(!a) return;

    const removeData =  new MyFetch('/sms/sanksi/delete/' + id, {method: 'GET', csrfHash: document.getElementById('csrf_edusmara').textContent});

    removeData.fetchData().then(result => {
        console.log('Delete Result:', result);
        if(result && result.status){
            showToast('warning', 'Dihapus', `Data ${a.nama_sanksi} telah dihapus permanen.`);
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
        showToast('error', 'Gagal', error.message || `Terjadi kesalahan saat menghapus ${a.nama_sanksi}.`);
    });
};

})();
