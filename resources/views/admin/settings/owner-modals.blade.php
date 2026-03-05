<style>
.modal-owner {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-owner-content {
    background-color: #fff;
    margin: 5% auto;
    padding: 20px;
    border-radius: 10px;
    width: 90%;
    max-width: 600px;
    max-height: 80vh;
    overflow-y: auto;
}

.modal-owner-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.modal-owner-close {
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}
</style>

<!-- Add Owner Stat Modal -->
<div id="ownerStatModal" class="modal-owner">
    <div class="modal-owner-content">
        <div class="modal-owner-header">
            <h3>Thêm Stat</h3>
            <span class="modal-owner-close" onclick="closeModal('ownerStatModal')">&times;</span>
        </div>
        <form action="{{ route('admin.settings.owner-stats.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Số *</label>
                <input type="text" name="number" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Nhãn *</label>
                <input type="text" name="label" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Thêm</button>
        </form>
    </div>
</div>

<!-- Edit Owner Stat Modal -->
<div id="ownerStatEditModal" class="modal-owner">
    <div class="modal-owner-content">
        <div class="modal-owner-header">
            <h3>Sửa Stat</h3>
            <span class="modal-owner-close" onclick="closeModal('ownerStatEditModal')">&times;</span>
        </div>
        <form id="editOwnerStatForm" method="POST">
            @csrf
            <input type="hidden" id="edit_owner_stat_id" name="id">
            <div class="form-group">
                <label>Số *</label>
                <input type="text" name="number" id="edit_owner_stat_number" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Nhãn *</label>
                <input type="text" name="label" id="edit_owner_stat_label" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary" onclick="event.preventDefault(); submitOwnerStatEdit();">Cập nhật</button>
        </form>
    </div>
</div>

<!-- Add Owner Benefit Modal -->
<div id="ownerBenefitModal" class="modal-owner">
    <div class="modal-owner-content">
        <div class="modal-owner-header">
            <h3>Thêm Benefit</h3>
            <span class="modal-owner-close" onclick="closeModal('ownerBenefitModal')">&times;</span>
        </div>
        <form action="{{ route('admin.settings.owner-benefits.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Tiêu đề *</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Mô tả *</label>
                <textarea name="description" class="form-control" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>Ảnh</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Thêm</button>
        </form>
    </div>
</div>

<!-- Edit Owner Benefit Modal -->
<div id="ownerBenefitEditModal" class="modal-owner">
    <div class="modal-owner-content">
        <div class="modal-owner-header">
            <h3>Sửa Benefit</h3>
            <span class="modal-owner-close" onclick="closeModal('ownerBenefitEditModal')">&times;</span>
        </div>
        <form id="editOwnerBenefitForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="edit_owner_benefit_id" name="id">
            <div class="form-group">
                <label>Tiêu đề *</label>
                <input type="text" name="title" id="edit_owner_benefit_title" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Mô tả *</label>
                <textarea name="description" id="edit_owner_benefit_description" class="form-control" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>Ảnh mới</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary" onclick="event.preventDefault(); submitOwnerBenefitEdit();">Cập nhật</button>
        </form>
    </div>
</div>

<!-- Add Owner Step Modal -->
<div id="ownerStepModal" class="modal-owner">
    <div class="modal-owner-content">
        <div class="modal-owner-header">
            <h3>Thêm Bước</h3>
            <span class="modal-owner-close" onclick="closeModal('ownerStepModal')">&times;</span>
        </div>
        <form action="{{ route('admin.settings.owner-steps.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Số bước *</label>
                <input type="number" name="step_number" class="form-control" min="1" required>
            </div>
            <div class="form-group">
                <label>Tiêu đề *</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Mô tả *</label>
                <textarea name="description" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Thêm</button>
        </form>
    </div>
</div>

<!-- Edit Owner Step Modal -->
<div id="ownerStepEditModal" class="modal-owner">
    <div class="modal-owner-content">
        <div class="modal-owner-header">
            <h3>Sửa Bước</h3>
            <span class="modal-owner-close" onclick="closeModal('ownerStepEditModal')">&times;</span>
        </div>
        <form id="editOwnerStepForm" method="POST">
            @csrf
            <input type="hidden" id="edit_owner_step_id" name="id">
            <div class="form-group">
                <label>Số bước *</label>
                <input type="number" name="step_number" id="edit_owner_step_number" class="form-control" min="1" required>
            </div>
            <div class="form-group">
                <label>Tiêu đề *</label>
                <input type="text" name="title" id="edit_owner_step_title" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Mô tả *</label>
                <textarea name="description" id="edit_owner_step_description" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" onclick="event.preventDefault(); submitOwnerStepEdit();">Cập nhật</button>
        </form>
    </div>
</div>

<!-- Add Owner Section Modal -->
<div id="ownerSectionModal" class="modal-owner">
    <div class="modal-owner-content">
        <div class="modal-owner-header">
            <h3>Thêm Section</h3>
            <span class="modal-owner-close" onclick="closeModal('ownerSectionModal')">&times;</span>
        </div>
        <form action="{{ route('admin.settings.owner-sections.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Tiêu đề *</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Nội dung *</label>
                <textarea name="content" class="form-control" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label>Ảnh</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            <div class="form-group">
                <label>Vị trí ảnh *</label>
                <select name="image_position" class="form-control" required>
                    <option value="left">Trái</option>
                    <option value="right">Phải</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Thêm</button>
        </form>
    </div>
</div>

<!-- Edit Owner Section Modal -->
<div id="ownerSectionEditModal" class="modal-owner">
    <div class="modal-owner-content">
        <div class="modal-owner-header">
            <h3>Sửa Section</h3>
            <span class="modal-owner-close" onclick="closeModal('ownerSectionEditModal')">&times;</span>
        </div>
        <form id="editOwnerSectionForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="edit_owner_section_id" name="id">
            <div class="form-group">
                <label>Tiêu đề *</label>
                <input type="text" name="title" id="edit_owner_section_title" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Nội dung *</label>
                <textarea name="content" id="edit_owner_section_content" class="form-control" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label>Ảnh mới</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            <div class="form-group">
                <label>Vị trí ảnh *</label>
                <select name="image_position" id="edit_owner_section_position" class="form-control" required>
                    <option value="left">Trái</option>
                    <option value="right">Phải</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" onclick="event.preventDefault(); submitOwnerSectionEdit();">Cập nhật</button>
        </form>
    </div>
</div>

<script>
function submitOwnerStatEdit() {
    const id = document.getElementById('edit_owner_stat_id').value;
    const form = document.getElementById('editOwnerStatForm');
    const formData = new FormData(form);

    fetch(`/admin/settings/owner-stats/${id}`, {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: formData
    }).then(() => location.reload());
}

function submitOwnerBenefitEdit() {
    const id = document.getElementById('edit_owner_benefit_id').value;
    const form = document.getElementById('editOwnerBenefitForm');
    const formData = new FormData(form);

    fetch(`/admin/settings/owner-benefits/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Có lỗi: ' + (data.message || 'Unknown'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra!');
    });
}

function submitOwnerStepEdit() {
    const id = document.getElementById('edit_owner_step_id').value;
    const form = document.getElementById('editOwnerStepForm');
    const formData = new FormData(form);

    fetch(`/admin/settings/owner-steps/${id}`, {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: formData
    }).then(() => location.reload());
}

function submitOwnerSectionEdit() {
    const id = document.getElementById('edit_owner_section_id').value;
    const form = document.getElementById('editOwnerSectionForm');
    const formData = new FormData(form);

    fetch(`/admin/settings/owner-sections/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Có lỗi: ' + (data.message || 'Unknown'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra!');
    });
}
</script>
