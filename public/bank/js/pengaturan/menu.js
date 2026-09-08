let arrayEdit=[];
let arrayMenus=[];
let id_edit_aktif =0;
const btnSimpanMenu = document.getElementById('btn-simpan-menu');
//const formsAddItem = document.getElementById('add-item');
const formSaveMenu = document.getElementById('form-saveMenu');

let updateOutput = function () {
    $('#nestable-output').val(JSON.stringify($('#nestable').nestable('serialize')));
};

$(document).ready(function () {

    $('#nestable').nestable().on('change', updateOutput);

    updateOutput();
    $("body").delegate(".item-delete", "click", function (e) {
        $(this).closest(".dd-item").remove();
        updateOutput();
    });

});

formSaveMenu.onsubmit = (e)=>{
    updateOutput();
    let postData = new FormData(formSaveMenu);
    var nameUrl = $("#form-saveMenu").attr("action");
    e.preventDefault();
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN':$("#csrf_sdi").text(),
        }
    });
    $.ajax({
    type: 'POST',
    url: nameUrl,
    data: postData,
    dataType:'JSON',
    processData: false,
    contentType: false, 
        success: function(response){
            $("#csrf_sdi").text(response.csrf_hash);
           // alert(response.pesan+', '+response.status);
            swal.fire("Oke", response.pesan, "success");
        },
    });
};

// formsAddItem.onsubmit = (e)=>{
//     let postData = new FormData(formsAddItem);
//     var nameUrl = $("#add-item").attr("action");
//     e.preventDefault();
//     $.ajaxSetup({
//         headers: {
//           'X-CSRF-TOKEN':$("#csrf_sdi").text(),
//         }
//     });
//     $.ajax({
//     type: 'POST',
//     url: nameUrl,
//     data: postData,
//     dataType:'JSON',
//     processData: false,
//     contentType: false, 
//         success: function(response){
//             $("#csrf_sdi").text(response.csrf_hash);
//            // alert(response.pesan+', '+response.status);
//             swal.fire("Oke", response.pesan, "success");
//             location.reload();
//         },
//     });
// };

const nestableList = document.getElementById('nestable');

let listContent = nestableList.querySelectorAll('.dd3-content .item-edit');
    for (let index = 0; index < listContent.length; index++) {
        let btnAktif = listContent[index];

        btnAktif.onclick = ()=>{
            showFieldHidden(btnAktif.getAttribute('data-Editid'),btnAktif.getAttribute('data-Editlabel'),btnAktif.getAttribute('data-Editurl'),btnAktif.getAttribute('data-Editicon'));
           alert("aku diklik" + btnAktif.getAttribute('data-Editid') + " - " + btnAktif.getAttribute('data-Editlabel') + " - " + btnAktif.getAttribute('data-Editurl') + " - " + btnAktif.getAttribute('data-Editicon'));
            return false;
        }
    }

    function showFieldHidden(id, label, url,icon) {
        btnSimpanMenu.disabled = true;
        let field = document.getElementById('fieldEdit-'+id);
       if (id_edit_aktif==0 ) {
         field.classList.remove("d-none");
         field.innerHTML = `
         <div class="form-group row">
            <label for="" class="col-sm-3 col-form-label">Label</label>
            <div class="col-sm-9">
            <input type="text" class="form-control" name="navigation_label" value="` + label + `" id="inputEdit-label-`+ id +`">
            </div>
        </div>
        
        <div class="form-group row">
            <label for="" class="col-sm-3 col-form-label">URL</label>
            <div class="col-sm-9">
            <input type="text" class="form-control" name="navigation_label" value="` + url + `" id="inputEdit-url-`+ id +`">
            </div>
        </div>
        
        <div class="form-group row">
            <label for="" class="col-sm-3 col-form-label">Icon Menu</label>
            <div class="col-sm-9">
            <input type="text" class="form-control" name="icon" value="` + icon + `" id="inputEdit-icon-`+ id +`">
            </div>
        </div>
        <div class="card-footer">
            <a href="javascript:simpanUpdateField(`+id+`);" id="btn_update" class="btn btn-info float-right btn-sm"><i class="fas fa-save"></i> Simpan</a> | <a href="javascript:hapusMenu(`+id+`);" id="btn_update" class="btn btn-danger float-right btn-sm mr-2"><i class="fas fa-trash-alt"></i> Hapus</a>
            <a href="javascript:hiddenField(`+id+`);" id="" class="btn btn-warning float-right btn-sm mr-1"><i class="fas fa-times-circle"></i> Tutup</a>
        </div>
        `;
         
         id_edit_aktif = id;
       }else{
        swal.fire("Maaf", "form lain sedang terbuka. Tutup dulu form yang terbuka!", "warning");
       }
    }

    function hiddenField(id) {
        let field = document.getElementById('fieldEdit-'+id);
        field.classList.add("d-none");
        field.innerHTML = '';
        btnSimpanMenu.disabled = false;
        id_edit_aktif =0;
    }

    function simpanUpdateField(id) {
        Swal.fire({
            title: 'Simpan perubahan?',
            showCancelButton: true,
            icon: 'warning',
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak",
            closeOnConfirm: false,
            closeOnCancel: false
        }).then(result => {
            if (result.value) {

                let icon = document.getElementById('inputEdit-icon-'+id).value;
                let label = document.getElementById('inputEdit-label-'+id).value;
                let url = document.getElementById('inputEdit-url-'+id).value;
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN':$("#csrf_sdi").text(),
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: baseUrl+'setting/menu/updateDataMenu',
                    data: {icon:icon, label:label, url:url, id_data:id},
                    dataType:'JSON',
                    success: function (response) {
                    $("#csrf_sdi").text(response.csrf_hash);
                    if (response.status) {
                        swal.fire("Berhasil", response.pesan, "success");
                        location.reload();
                    } else {
                        swal.fire("Gagal", response.pesan, "warning");
                    }
                    },
                    error: function( xhr, errorType, exception) {
                        var pesan = JSON.parse(xhr.responseText);
                        swal.fire("Gagal", "Error : "+pesan.code +", Title : "+pesan.title + ", Msg : "+pesan.message, "error");
                    },
        
                });
            }
        });
    }

    function hapusMenu(id) {
        Swal.fire({
            title: 'Hapus menu ini?',
            showCancelButton: true,
            icon: 'warning',
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak",
            closeOnConfirm: false,
            closeOnCancel: false
        }).then(result => {
            if (result.value) {
                $.ajax({
                    type: 'GET',
                    url: baseUrl+'setting/menu/hapusDataMenu/'+id,
                    dataType:'JSON',
                    success: function (response) {
                        if (response.status) {
                            swal.fire("Berhasil", response.pesan, "success");
                            location.reload();
                          } else {
                            swal.fire("Gagal", response.pesan, "warning");
                          }
                      
                    },
                    error: function( xhr, errorType, exception) {
                        var pesan = JSON.parse(xhr.responseText);
                        swal.fire("Gagal", "Error : "+pesan.code +", Title : "+pesan.title + ", Msg : "+pesan.message, "error");
                    },
          
                });
            }
          });
    }