
(function(){
'use strict';

let alasanTerlambatData = [];
let detailModalInst = null;
let deleteModalInst = null;
let currentDetailId = null;

const ApiData =  new MyFetch('/sms/alasanterlambat/getData', { method: 'GET'});

window.loadDataFromServer = async function() {
    try {
        const result = await ApiData.fetchData();
        if(result.status){
            alasanTerlambatData = result.data;
            setTimeout(() => {
                renderList();
                updateSummary();
            }, 500);
        };
        if (!result.status) throw new Error(`Data gagal dimuat: ${result.status}`);

    } catch (error) {
        console.error(error);
    }
};

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

// ============ HELPERS ============
function escapeHtml(str){
    return String(str ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
}

function isAktif(a){
    return a.is_aktif == 1;
}

// ============ RENDER LIST ============
window.renderList = function(){
    const container = document.getElementById('alasanterlambatList');
    if(!container) return;

    const search = document.getElementById('searchInput').value.toLowerCase().trim();
    const fStat = document.getElementById('filterStatus').value;

    let filtered = alasanTerlambatData.filter(a=>{
        const ms = !search || a.nama_alasan.toLowerCase().includes(search);
        const mst = fStat==='all' || a.is_aktif==fStat;
        return ms && mst;
    }).sort((a,b)=>a.urutan - b.urutan);

    if(filtered.length===0){
        container.innerHTML=`<div class="empty-state"><i class="fa-solid fa-calendar-xmark"></i><h4>Tidak Ada Data</h4><p>${alasanTerlambatData.length===0?'Belum ada alasan terlambat. Klik "Tambah Alasan" untuk memulai.':'Tidak ada data yang cocok dengan filter.'}</p></div>`;
        return;
    }

    container.innerHTML = filtered.map((a,idx)=>{
        const statusClass = isAktif(a) ? 'active' : 'inactive';
        const statusLabel = isAktif(a) ? 'Aktif' : 'Non-Aktif';

        return `<div class="at-card" style="animation-delay:${idx*0.04}s" onclick="showDetail(${a.id_alasan_terlambat})">
            <div class="at-body">
                <div class="at-top">
                    <span class="at-badge">#${a.urutan}</span>
                    <span class="at-status ${statusClass}">${statusLabel}</span>
                </div>
                <div class="at-title">${escapeHtml(a.nama_alasan)}</div>
                <div class="at-meta">
                    <span><i class="fa-solid fa-list-ol"></i> Urutan: ${a.urutan}</span>
                    <span><i class="fa-solid fa-clock"></i> ${a.created_at ? new Date(a.created_at).toLocaleDateString('id-ID') : '-'}</span>
                </div>
            </div>
            <div class="at-actions" onclick="event.stopPropagation()">
                <button class="act-btn view" onclick="showDetail(${a.id_alasan_terlambat})" title="Detail"><i class="fa-solid fa-eye"></i></button>
                <button class="act-btn edit" onclick="openEditForm(${a.id_alasan_terlambat})" title="Edit"><i class="fa-solid fa-pen"></i></button>
                <button class="act-btn delete" onclick="openDeleteConfirm(${a.id_alasan_terlambat})" title="Hapus"><i class="fa-solid fa-trash"></i></button>
            </div>
        </div>`;
    }).join('');
};

// ============ SUMMARY ============
window.updateSummary = function(){
    const sumTotal = document.getElementById('sumTotal');
    const sumActive = document.getElementById('sumActive');
    const sumInactive = document.getElementById('sumInactive');
    const sumMaxUrutan = document.getElementById('sumMaxUrutan');

    const totalAktif = alasanTerlambatData.filter(isAktif).length;
    if(sumTotal) sumTotal.textContent = alasanTerlambatData.length;
    if(sumActive) sumActive.textContent = totalAktif;
    if(sumInactive) sumInactive.textContent = alasanTerlambatData.length - totalAktif;

    const maxUrutan = alasanTerlambatData.length > 0 ? Math.max(...alasanTerlambatData.map(a=>parseInt(a.urutan) || 0)) : 0;
    if(sumMaxUrutan) sumMaxUrutan.textContent = maxUrutan;
};

// ============ SHOW DETAIL ============
window.showDetail = function(id){
    const a = alasanTerlambatData.find(x=>x.id_alasan_terlambat==id);
    if(!a) return;
    currentDetailId = id;

    document.getElementById('mBadge').innerHTML = `<span class="at-badge" style="font-size:11px;padding:4px 12px">#${a.urutan}</span>`;
    document.getElementById('mTitle').textContent = a.nama_alasan;
    document.getElementById('mUrutan').textContent = a.urutan;
    document.getElementById('mStatus').textContent = isAktif(a) ? 'Aktif' : 'Non-Aktif';
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
    document.getElementById('formTitle').innerHTML = '<i class="fa-solid fa-calendar-xmark"></i> Tambah Alasan Terlambat Baru';
    document.getElementById('formSubtitle').textContent = 'Lengkapi informasi alasan keterlambatan siswa';
    switchTab('form');
};

// ============ FORM: EDIT ============
window.openEditForm = function(id){
    const a = alasanTerlambatData.find(x=>x.id_alasan_terlambat==id);
    if(!a) {
        console.error('Data tidak ditemukan dengan ID:', id);
        return;
    }
    resetForm();
    document.getElementById('editId').value = a.id_alasan_terlambat;
    document.getElementById('formTitle').innerHTML = '<i class="fa-solid fa-pen-to-square"></i> Edit Alasan Terlambat';
    document.getElementById('formSubtitle').textContent = `Mengedit: ${a.nama_alasan}`;
    document.getElementById('fNamaAlasan').value = a.nama_alasan;
    document.getElementById('fUrutan').value = a.urutan;
    document.getElementById('fIsActive').value = isAktif(a) ? '1' : '0';
    switchTab('form');
};

window.resetForm = function(){
    document.getElementById('alasanterlambatForm').reset();
    document.getElementById('editId').value = '';
    resetMsgError();
};

function resetMsgError(){
    document.querySelectorAll('#alasanterlambatForm .fc').forEach(input => input.classList.remove('is-invalid'));
    document.querySelectorAll('#alasanterlambatForm .form-note').forEach(note => note.textContent = '');
}

function showValidationErrors(errors){
    const errorMessages = Object.values(errors).flat().join('<br>');
    showToast('error', 'Validasi Gagal!', errorMessages);

    Object.keys(errors).forEach(field => {
        const input = document.getElementsByName(`input-${field}`)[0];
        if (input) {
            input.classList.add('is-invalid');
            if (input.nextElementSibling) input.nextElementSibling.textContent = `* ${errors[field]}`;
        }
    });
}

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
document.getElementById('alasanterlambatForm').addEventListener('submit',function(e){
    e.preventDefault();
    saveAlasanTerlambat();
});

async function saveAlasanTerlambat(){
    const editId = document.getElementById('editId').value;
    const namaAlasan = document.getElementById('fNamaAlasan').value.trim();
    const urutan = document.getElementById('fUrutan').value;
    const isActive = document.getElementById('fIsActive').value;

    if(!namaAlasan || urutan===''){
        showToast('error','Form Belum Lengkap','Harap isi semua field yang wajib (*)');return;
    }

    const entry = {
        nama_alasan: namaAlasan,
        urutan: parseInt(urutan),
        is_aktif: parseInt(isActive)
    };

    resetMsgError();

    const request = editId
        ? new MyFetch('/sms/alasanterlambat/update/' + editId, {
            method: 'POST',
            csrfHash: document.getElementById('csrf_edusmara').textContent,
            params : entry
        })
        : new MyFetch('/sms/alasanterlambat/create', {
            method: 'POST',
            csrfHash: document.getElementById('csrf_edusmara').textContent,
            params : entry
        });

    try {
        const result = await request.fetchData();
        if(result.status){
            showToast('success', 'Berhasil!', editId
                ? `Data ${entry.nama_alasan} berhasil diperbarui.`
                : `Alasan ${entry.nama_alasan} berhasil ditambahkan.`);
            reloadData();
        }
    } catch (error) {
        if(error.status === 422 && error.data && error.data.errors) {
            showValidationErrors(error.data.errors);
        } else {
            showToast('error', 'Gagal!', editId
                ? 'Terjadi kesalahan saat memperbarui data.'
                : 'Terjadi kesalahan saat menambahkan data.');
        }
    }

    function reloadData() {
        loadDataFromServer();
        resetForm();
        switchTab('list');
    }
}

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

// ============ DELETE ============
window.openDeleteConfirm = function(id){
    const a = alasanTerlambatData.find(x=>x.id_alasan_terlambat==id);
    if(!a) {
        console.error('Data tidak ditemukan dengan ID:', id);
        return;
    }
    document.getElementById('delId').value = a.id_alasan_terlambat;
    document.getElementById('delName').textContent = a.nama_alasan;
    if(deleteModalInst) {
        deleteModalInst.show();
    }
};

window.confirmDelete = function(){
    const id = parseInt(document.getElementById('delId').value);
    const a = alasanTerlambatData.find(x=>x.id_alasan_terlambat==id);
    if(!a) return;

    const removeData =  new MyFetch('/sms/alasanterlambat/delete/' + id, {method: 'GET', csrfHash: document.getElementById('csrf_edusmara').textContent});

    removeData.fetchData().then(result => {
        if(result && result.status){
            showToast('warning', 'Dihapus', `Data ${a.nama_alasan} telah dihapus permanen.`);
            loadDataFromServer();
            if(deleteModalInst) {
                deleteModalInst.hide();
            }
        } else {
            showToast('error', 'Gagal', 'Gagal menghapus data.');
        }
    }).catch(error => {
        console.error(error);
        showToast('error', 'Gagal', error.message || `Terjadi kesalahan saat menghapus ${a.nama_alasan}.`);
    });
};

})();
