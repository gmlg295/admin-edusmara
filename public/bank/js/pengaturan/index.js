(function(){
'use strict';

let detailModalInst;

document.addEventListener('DOMContentLoaded',()=>{
    detailModalInst = new bootstrap.Modal(document.getElementById('detailModal'));
});

// Atau dengan callback
const api =  new MyFetch('/pengaturan/getDataApp', 
    {
        method: 'GET',
        showLoading: true,
        elmLoading: '#loaderPreloader',
    }
);


window.navigateToPage = function(menu, title="") {
    switch(menu) {
        case 'menu':
           loadDataApp();
            break;
        case 'detail':
            detailModalInst.show();
            break;
        default:
            break;
    }
    
    
};

async function loadDataApp() {
    try {
        const result = await api.fetchData();

        const listAppContainer = document.getElementById('listApp');
        listAppContainer.innerHTML = '';

        result.data.forEach((item, index) => {
            const listItem = document.createElement('li');
            listItem.className = 'list-group-item';
            listItem.innerHTML = `<div class="detail-meta-item" onclick="setMenu('${item.id_license}', '${item.full_name}')">
                        <i class="fa-solid fa-school-circle-check"></i>
                        <div>
                            <div class="detail-meta-label">Client - ${item.produc_key} / ${item.product_type}</div>
                            <div class="detail-meta-value" id="mCat">${item.full_name}</div>
                        </div>
                    </div>
            `;
            listAppContainer.appendChild(listItem);
        });


        detailModalInst.show();
    } catch (error) {
        console.error(error);
    }

    window.setMenu = function(id, name) {
        // Simpan data ke localStorage
        showToast('info', 'Navigasi', `Atur menu di ${name}`);

        if (id) {
            window.location.href = '/menu/setMenu?id_license=' + encodeURIComponent(id);
        }else {
            showToast('error', 'Navigasi', 'ID License tidak ditemukan.');
        }

    };
};







})();