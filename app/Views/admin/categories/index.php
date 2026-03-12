<?= view('admin/includes/header', ['title' => $title]) ?>

<style>
    /* Layout & Sidebar */
    .sticky-sidebar { top: 100px; z-index: 100; }
    .glass-card { 
        background: #ffffff; 
        border-radius: 1rem; 
        border: 1px solid #e2e8f0; 
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    
    /* Active/Inactive Status Styling */
    .status-inactive { opacity: 0.6; grayscale: 1; border-left: 4px solid #ef4444 !important; }
    .status-active { border-left: 4px solid #10b981 !important; }
    .badge-inactive { background-color: #fee2e2; color: #b91c1c; }
    .badge-active { background-color: #d1fae5; color: #065f46; }

    /* Professional Select UI */
    .form-select { 
        width: 100% !important; 
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        padding: 8px 12px;
        background-color: #f8fafc;
        transition: 0.2s;
    }
    .form-select:focus { border-color: #0d6efd; box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1); }

    /* Tree Styling with Lines */
    .tree-child-container { 
        border-left: 2px dashed #cbd5e1; 
        margin-left: 1.2rem; 
        padding-left: 1rem; 
        position: relative;
    }
    .tree-node { margin-bottom: 0.5rem; }
    .tree-item-wrapper {
        transition: all 0.2s ease-in-out;
        border: 1px solid #edf2f7;
    }
    .tree-item-wrapper:hover {
        background-color: #f1f5f9 !important;
        border-color: #cbd5e1;
        transform: translateX(5px);
    }

    /* Tabs Styling */
    .nav-pills-custom .nav-link {
        color: #64748b;
        font-weight: 700;
        padding: 10px 20px;
        border-radius: 10px;
    }
    .nav-pills-custom .nav-link.active {
        background: #0d6efd;
        color: white;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
    }
</style>

<div class="container-fluid px-4 py-4">
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success border-0 shadow-sm rounded-4 d-flex align-items-center mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="sticky-sidebar">
                <div class="card border-0 glass-card p-4 mb-4">
                    <h6 class="fw-bold mb-4 text-primary"><i class="bi bi-grid-fill me-2"></i>Category Manager</h6>
                    <form action="<?= base_url('admin/categories/store') ?>" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="type" id="cat_type" value="main">
                        <input type="hidden" name="parent_id" id="cat_real_parent_id" value="0">
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Select Parent</label>
                            <select id="main_cat_dropdown" class="form-select">
                                <option value="0">-- Root Category --</option>
                                <?php foreach($main_categories as $mc): ?>
                                    <option value="<?= $mc['id'] ?>"><?= $mc['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3 d-none" id="sub_cat_wrapper">
                            <label class="form-label small fw-bold">Sub Category</label>
                            <select id="sub_cat_dropdown" class="form-select border-primary">
                                <option value="">-- Add Layer Under This --</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <input type="text" name="name" class="form-control" placeholder="Name..." required>
                            <button type="submit" class="btn btn-primary px-3">ADD</button>
                        </div>
                    </form>
                </div>

                <div class="card border-0 p-4 shadow-lg text-white" style="background: #0f172a; border-radius: 1rem;">
                    <h6 class="fw-bold mb-4 text-success"><i class="bi bi-geo-alt-fill me-2"></i>Expand Territory</h6>
                    <form action="<?= base_url('admin/categories/store') ?>" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="type" id="loc_type" value="state">
                        <input type="hidden" name="parent_id" id="real_parent_id" value="0">

                        <div class="mb-3">
                            <label class="form-label small fw-bold opacity-75">State</label>
                            <select id="state_dropdown" class="form-select bg-dark border-secondary text-white">
                                <option value="0">New State</option>
                                <?php foreach($states as $st): ?>
                                    <option value="<?= $st['id'] ?>"><?= $st['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3 d-none" id="district_wrapper">
                            <label class="form-label small fw-bold text-success">District</label>
                            <select id="district_dropdown" class="form-select bg-dark border-success text-white">
                                <option value="">New District</option>
                            </select>
                        </div>
                        <div class="mb-3 d-none" id="mandal_wrapper">
                            <label class="form-label small fw-bold text-info">Mandal</label>
                            <select id="mandal_dropdown" class="form-select bg-dark border-info text-white">
                                <option value="">New Mandal</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <input type="text" name="name" class="form-control bg-dark border-secondary text-white" placeholder="Location Name..." required>
                            <button type="submit" class="btn btn-success px-3 border-0">SAVE</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm glass-card overflow-hidden">
                <div class="card-header bg-white border-0 p-3">
                    <ul class="nav nav-pills nav-pills-custom gap-2" id="pills-tab" role="tablist">
                        <li class="nav-item flex-fill">
                            <button class="nav-link active w-100" data-bs-toggle="pill" data-bs-target="#tab-news">Categories Tree</button>
                        </li>
                        <li class="nav-item flex-fill">
                            <button class="nav-link w-100" data-bs-toggle="pill" data-bs-target="#tab-loc">Regions Tree</button>
                        </li>
                    </ul>
                </div>

                <div class="tab-content p-4">
                    <div class="tab-pane fade show active" id="tab-news">
                        <div class="list-group list-group-flush gap-2">
                            <?php foreach($main_categories as $cat): ?>
                            <div class="tree-node">
                                <div class="d-flex align-items-center justify-content-between p-3 rounded-3 tree-item-wrapper <?= $cat['IsActice'] == 1 ? 'status-active bg-light' : 'status-inactive bg-white' ?>" style="cursor:pointer" onclick="expandTree(<?= $cat['id'] ?>, 'cat', this)">
                                    <div class="fw-bold d-flex align-items-center">
                                        <i class="bi bi-folder-fill text-warning me-2"></i>
                                        <?= esc($cat['name']) ?>
                                        <span class="badge ms-2 <?= $cat['IsActice'] == 1 ? 'badge-active' : 'badge-inactive' ?>" style="font-size: 0.6rem;">
                                            <?= $cat['IsActice'] == 1 ? 'ACTIVE' : 'HIDDEN' ?>
                                        </span>
                                    </div>
                                    <div class="btn-group" onclick="event.stopPropagation()">
                                        <button onclick="toggleStatus(<?= $cat['id'] ?>, <?= $cat['IsActice'] ?>)" class="btn btn-sm btn-white border rounded-circle me-1" title="<?= $cat['IsActice'] == 1 ? 'Hide' : 'Unhide' ?>">
                                            <i class="bi <?= $cat['IsActice'] == 1 ? 'bi-eye-fill text-success' : 'bi-eye-slash-fill text-muted' ?>"></i>
                                        </button>
                                        <button onclick="editItem(<?= $cat['id'] ?>, '<?= esc($cat['name']) ?>')" class="btn btn-sm btn-white border rounded-circle me-1"><i class="bi bi-pencil-fill text-primary"></i></button>
                                        <button onclick="deleteItem(<?= $cat['id'] ?>)" class="btn btn-sm btn-white border rounded-circle text-danger"><i class="bi bi-trash-fill"></i></button>
                                    </div>
                                </div>
                                <div id="cat-child-<?= $cat['id'] ?>" class="tree-child-container" style="display:none;"></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tab-loc">
                        <div id="treeSpinner" class="spinner-border spinner-border-sm text-primary d-none mb-3"></div>
                        <div class="list-group list-group-flush gap-2">
                            <?php foreach($states as $st): ?>
                            <div class="tree-node">
                                <div class="d-flex align-items-center justify-content-between p-3 rounded-3 tree-item-wrapper border <?= $st['IsActice'] == 1 ? 'status-active shadow-xs' : 'status-inactive' ?>" style="cursor:pointer" onclick="expandTree(<?= $st['id'] ?>, 'loc', this)">
                                    <div class="fw-bold text-dark d-flex align-items-center">
                                        <i class="bi bi-map-fill text-success me-2"></i>
                                        <?= esc($st['name']) ?>
                                        <?php if($st['IsActice'] == 0): ?>
                                            <span class="badge badge-inactive ms-2" style="font-size: 0.6rem;">HIDDEN</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="btn-group" onclick="event.stopPropagation()">
                                        <button onclick="toggleStatus(<?= $st['id'] ?>, <?= $st['IsActice'] ?>)" class="btn btn-sm btn-white border rounded-circle me-1">
                                            <i class="bi <?= $st['IsActice'] == 1 ? 'bi-eye-fill text-success' : 'bi-eye-slash-fill text-muted' ?>"></i>
                                        </button>
                                        <button onclick="editItem(<?= $st['id'] ?>, '<?= esc($st['name']) ?>')" class="btn btn-sm btn-white border rounded-circle me-1"><i class="bi bi-pencil-fill text-primary"></i></button>
                                        <button onclick="deleteItem(<?= $st['id'] ?>)" class="btn btn-sm btn-white border rounded-circle text-danger"><i class="bi bi-trash-fill"></i></button>
                                    </div>
                                </div>
                                <div id="loc-child-<?= $st['id'] ?>" class="tree-child-container" style="display:none;"></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <form action="<?= base_url('admin/categories/update') ?>" method="POST" class="modal-content border-0 shadow-lg rounded-4">
            <?= csrf_field() ?>
            <div class="modal-body p-4 text-center">
                <h6 class="fw-bold mb-3 text-dark">Rename Item</h6>
                <input type="hidden" name="id" id="edit_id">
                <input type="text" name="name" id="edit_name" class="form-control text-center rounded-pill border-2 mb-4 shadow-none" required>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold">Update Now</button>
                    <button type="button" class="btn btn-light rounded-pill py-2 border" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function toggleStatus(id, currentStatus) {
        const newStatus = currentStatus === 1 ? 0 : 1;
        if(confirm(`దీనిని ${newStatus === 1 ? 'Unhide' : 'Hide'} చేయాలా?`)) {
            window.location.href = `<?= base_url('admin/categories/toggleStatus') ?>/${id}/${newStatus}`;
        }
    }

    function expandTree(parentId, context, btn) {
        const container = $(`#${context}-child-${parentId}`);
        if (container.children().length > 0) {
            container.slideToggle(300);
            return;
        }

        $('#treeSpinner').removeClass('d-none');
        $.ajax({
            url: '<?= base_url('admin/categories/getLocationDetails') ?>/' + parentId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                let html = '';
                if (data.children && data.children.length > 0) {
                    data.children.forEach(item => {
                        let levelIcon = context === 'loc' ? (item.type === 'village' ? 'bi-house-door' : 'bi-geo-alt') : 'bi-arrow-return-right';
                        let canExpand = (item.type !== 'village');
                        let colorClass = context === 'loc' ? 'text-success' : 'text-primary';
                        let statusClass = item.IsActice == 1 ? 'status-active bg-white' : 'status-inactive bg-light-subtle';
                        let eyeIcon = item.IsActice == 1 ? 'bi-eye-fill text-success' : 'bi-eye-slash-fill text-muted';

                        html += `
                        <div class="tree-node mt-2 animate__animated animate__fadeIn">
                            <div class="d-flex align-items-center justify-content-between p-2 px-3 rounded-3 border shadow-xs ${statusClass}" 
                                 style="cursor:pointer" 
                                 onclick="${canExpand ? `expandTree(${item.id}, '${context}', this)` : ''}">
                                <div class="small fw-bold text-dark">
                                    <i class="bi ${levelIcon} me-2 ${colorClass}"></i>${item.name}
                                    <span class="badge bg-light text-muted ms-1" style="font-size:0.55rem">${item.type.toUpperCase()}</span>
                                    ${item.IsActice == 0 ? '<span class="badge badge-inactive ms-1" style="font-size:0.55rem">HIDDEN</span>' : ''}
                                </div>
                                <div class="btn-group" onclick="event.stopPropagation()">
                                    <button onclick="toggleStatus(${item.id}, ${item.IsActice})" class="btn btn-link btn-sm p-0 me-2"><i class="bi ${eyeIcon}"></i></button>
                                    <button onclick="editItem(${item.id}, '${item.name}')" class="btn btn-link btn-sm p-0 text-dark me-2"><i class="bi bi-pencil-fill"></i></button>
                                    <button onclick="deleteItem(${item.id})" class="btn btn-link btn-sm p-0 text-danger"><i class="bi bi-trash-fill"></i></button>
                                </div>
                            </div>
                            <div id="${context}-child-${item.id}" class="tree-child-container" style="display:none;"></div>
                        </div>`;
                    });
                } else {
                    html = '<div class="p-2 small text-muted italic ms-3">No further data.</div>';
                }
                container.html(html).slideDown(400);
                $('#treeSpinner').addClass('d-none');
            },
            error: function() {
                $('#treeSpinner').addClass('d-none');
            }
        });
    }

    function editItem(id, name) {
        $('#edit_id').val(id);
        $('#edit_name').val(name);
        new bootstrap.Modal(document.getElementById('editModal')).show();
    }

    function deleteItem(id) {
        if (confirm('ఖచ్చితంగా తొలగించాలా? దీని కింద ఉన్న సబ్-డేటా కూడా తొలగిపోతుంది.')) {
            window.location.href = '<?= base_url('admin/categories/delete') ?>/' + id;
        }
    }

    $(document).ready(function() {
        // Category Logic: Root -> main, Any selected parent -> sub
        $('#main_cat_dropdown').change(function() {
            const mainId = $(this).val();
            $('#cat_real_parent_id').val(mainId);
            if (mainId != "0") {
                $('#cat_type').val('sub'); // Update type to sub
                $('#sub_cat_wrapper').removeClass('d-none');
                fetchOptions(mainId, '#sub_cat_dropdown', 'sub-layer');
            } else {
                $('#cat_type').val('main'); // Reset to main
                $('#sub_cat_wrapper').addClass('d-none');
            }
        });

        // Category Sub-layer selection also sets parent_id
        $('#sub_cat_dropdown').change(function() {
            const subId = $(this).val();
            if(subId != "") {
                $('#cat_real_parent_id').val(subId);
            } else {
                $('#cat_real_parent_id').val($('#main_cat_dropdown').val());
            }
        });

        // Territory Logic: Cascading types
        $('#state_dropdown').change(function() {
            const sid = $(this).val();
            $('#real_parent_id').val(sid);
            $('#district_wrapper, #mandal_wrapper').addClass('d-none');
            if (sid != "0") {
                $('#loc_type').val('district'); // State selected -> Adding District
                $('#district_wrapper').removeClass('d-none');
                fetchOptions(sid, '#district_dropdown', 'District');
            } else {
                $('#loc_type').val('state'); // No state -> Adding State
            }
        });

        $('#district_dropdown').change(function() {
            const did = $(this).val();
            if (did != "") {
                $('#real_parent_id').val(did);
                $('#loc_type').val('mandal'); // District selected -> Adding Mandal
                $('#mandal_wrapper').removeClass('d-none');
                fetchOptions(did, '#mandal_dropdown', 'Mandal');
            } else {
                $('#real_parent_id').val($('#state_dropdown').val());
                $('#loc_type').val('district');
            }
        });

        $('#mandal_dropdown').change(function() {
            const mid = $(this).val();
            if (mid != "") {
                $('#real_parent_id').val(mid);
                $('#loc_type').val('village'); // Mandal selected -> Adding Village
            } else {
                $('#real_parent_id').val($('#district_dropdown').val());
                $('#loc_type').val('mandal');
            }
        });

        function fetchOptions(pid, target, label) {
            $.get('<?= base_url('admin/categories/getLocationDetails') ?>/' + pid, function(data) {
                let h = `<option value="">-- Add New ${label} --</option>`;
                if (data.children) data.children.forEach(i => { h += `<option value="${i.id}">${i.name}</option>`; });
                $(target).html(h);
            });
        }
    });
</script>

<?= view('admin/includes/footer') ?>