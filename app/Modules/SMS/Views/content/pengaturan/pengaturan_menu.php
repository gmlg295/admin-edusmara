
<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?php echo base_url('public/bank/menus/style.css') ?>">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/4.0.0/jquery.min.js" integrity="sha512-8LENNbXmzI/Gbj+OwXmqR6V4QaUAw0/porPzy1+dQoJqC0JPHedWoe0DDOTL2uHA5XXJyIsPtiMHH86pVlay6A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js"></script>
<div style="display:none;" id="<?= csrf_token() ?>"><?= csrf_hash() ?></div>
<style>
    
        /* Form Input Area */
        .input-card {
            background: var(--bg-card);
            padding: 20px;
            border-radius: var(--radius-sm);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-light);
            margin-bottom: 24px;
            display: flex;
            gap: 12px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .form-group { flex: 1; min-width: 150px; }
        .form-group label { display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
        
        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-family: inherit;
            font-size: 14px;
            transition: all 0.2s;
            box-sizing: border-box;
        }
        .form-control:focus { outline: none; border-color: var(--primary-light); box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }

        .btn-add {
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
            height: 42px;
        }
        .btn-add:hover { background: var(--primary-light); transform: translateY(-1px); }

        /* Nestable List Styling */
        .dd { position: relative; display: block; margin: 0; padding: 0; list-style: none; }
        .dd-list { display: block; position: relative; margin: 0; padding: 0; list-style: none; }
        .dd-list .dd-list { padding-left: 30px; } /* Indentation for children */
        
        .dd-item { display: block; position: relative; margin: 0; padding: 0; min-height: 20px; }
        
        /* Custom Handle & Item Look */
        .dd-handle {
            display: flex !important;
            align-items: center;
            justify-content: space-between;
            background: var(--bg-card) !important;
            border: 1px solid var(--border) !important;
            border-radius: 8px !important;
            padding: 12px 16px !important;
            margin-bottom: 8px !important;
            font-weight: 500 !important;
            color: var(--text-main) !important;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02) !important;
            transition: all 0.2s !important;
            height: auto !important;
            line-height: normal !important;
        }
        
        .dd-handle:hover { border-color: var(--primary-light); box-shadow: 0 4px 6px rgba(0,0,0,0.05) !important; }
        .dd-item > .dd-handle { border-left: 4px solid var(--primary) !important; } /* Indicator for root items */
        .dd-list .dd-list .dd-item > .dd-handle { border-left: 4px solid var(--secondary) !important; } /* Indicator for child items */

        .dd-handle-content { display: flex; align-items: center; gap: 12px; }
        .menu-icon-preview { 
            width: 32px; height: 32px; 
            background: #f1f5f9; 
            border-radius: 6px; 
            display: flex; align-items: center; justify-content: center;
            color: var(--primary);
            font-size: 14px;
        }
        
        .dd-actions { display: flex; gap: 8px; }
        .btn-action {
            background: transparent; border: 1px solid var(--border);
            color: var(--text-muted); width: 32px; height: 32px;
            border-radius: 6px; cursor: pointer; transition: 0.2s;
            display: flex; align-items: center; justify-content: center;
        }
        .btn-action:hover { background: var(--bg-body); color: var(--primary); border-color: var(--primary); }
        .btn-action.delete:hover { background: #fef2f2; color: #ef4444; border-color: #ef4444; }

        /* Placeholder & Dragging State */
        .dd-placeholder { margin: 5px 0; padding: 0; height: 30px; background: #e2e8f0; border: 1px dashed #cbd5e1; border-radius: 8px; }
        .dd-dragel { position: absolute; pointer-events: none; z-index: 9999; opacity: 0.8; }
        .dd-dragel > .dd-item .dd-handle { margin-top: 0 !important; box-shadow: 0 10px 15px rgba(0,0,0,0.1) !important; }

        /* Footer Action */
        .footer-action { margin-top: 24px; display: flex; justify-content: flex-end; }
        .btn-save {
            background: #10b981; color: white; border: none;
            padding: 12px 24px; border-radius: 8px; font-weight: 600;
            cursor: pointer; display: flex; align-items: center; gap: 8px;
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2);
            transition: 0.2s;
        }
        .btn-save:hover { background: #059669; transform: translateY(-1px); }

</style>
<?php
 
function renderMenuItem($id, $label, $url, $icon)
{
    //   <ol class="dd-list">
    //         <li class="dd-item" data-id="1">
    //             <div class="dd-handle">
    //                 <div class="dd-handle-content">
    //                     <div class="menu-icon-preview"><i class="fa-solid fa-chart-line"></i></div>
    //                     <span>Dashboard Utama</span>
    //                 </div>
    //                 <div class="dd-actions">
    //                     <button class="btn-action" title="Edit"><i class="fa-solid fa-pen"></i></button>
    //                     <button class="btn-action delete" title="Hapus"><i class="fa-solid fa-trash"></i></button>
    //                 </div>
    //             </div>
    //         </li>

    return  '<li class="dd-item dd3-item" data-id="' . $id . '" data-label="' . $label . '" data-url="' . $url . '" data-icon="' . $icon . '" >' .
        '<div class="dd-handle dd3-handle" > Drag</div>' .
        '<div class="dd3-content"><span> <i class="' . $icon . '"></i> &nbsp;' . $label . '</span>' .
        '<div class="item-edit" data-Editid="' . $id . '" data-Editlabel="' . $label . '" data-Editurl="' . $url . '" data-Editicon="' . $icon . '">Edit</div>' .
        '</div>' .
        '<div class="item-settings d-none" id="fieldEdit-'.$id.'"></div>';
}

function menuTree($parent_id = 0)
{
  
    $items = '';
    $data_menus = getDataMenus($parent_id);
        $items .= '<ol class="dd-list">';
        foreach ($data_menus as $row) {
            $items .= renderMenuItem($row->id_menu, $row->label, $row->url, $row->icon);
            $items .= menuTree($row->id_menu);
            $items .= '</li>';
        }
        $items .= '</ol>';
    return $items;
}

?>


<div class="custom-container">
    <div class="hero-banner">
        <div class="hero-content">
            <h1 class="hero-title">
                <i class="fa-solid fa-arrow-left"></i>
                Menu Pengaturan</h1>
            <p class="hero-subtitle">Pengaturan Menu, Pengaturan Pelanggan, Pengaturan User, Pengaturan Aplikasi</p>
        </div>
    </div>

     <!-- Form Tambah Menu -->
    <div class="input-card">
        <div class="form-group" style="flex: 2;">
            <label>Nama Menu</label>
            <input type="text" id="inputName" class="form-control" placeholder="Contoh: Dashboard">
        </div>
        <div class="form-group" style="flex: 2;">
            <label>URL / Route</label>
            <input type="text" id="inputUrl" class="form-control" placeholder="Contoh: /admin/dashboard">
        </div>
        <div class="form-group" style="flex: 1;">
            <label>Icon (FontAwesome)</label>
            <input type="text" id="inputIcon" class="form-control" placeholder="fa-home">
        </div>
        <button class="btn-add" onclick="addMenuItem()">
            <i class="fa-solid fa-plus"></i> Tambah
        </button>
    </div>


           
        <hr />

        <div class="dd" id="nestable" style="padding-top:15px;padding-bottom:15px;">
            <?php
                $html_menu = menuTree(0);
                echo (empty($html_menu)) ? '<ol class="dd-list"></ol>' : $html_menu;
            ?>
        </div>
        <hr />
        <form action="<?= base_url('setting/menu/postData');?>" method="POST" id="form-saveMenu"> 
            <input type="hidden" id="nestable-output" name="menu">
            <button type="submit" id="btn-simpan-menu" class="btn btn-success btn-sm"><i class="fas fa-edit"></i> Simpan Perubahan</button>
        </form>

</div>



<?= $this->endSection() ?>