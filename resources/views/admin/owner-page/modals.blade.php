<!-- Add Stat Modal -->
<div class="modal fade" id="addStatModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.owner-page.stats.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Thêm Stat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Số *</label>
                        <input type="text" name="number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nhãn *</label>
                        <input type="text" name="label" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ảnh</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thứ tự *</label>
                        <input type="number" name="order" class="form-control" value="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Stat Modal -->
<div class="modal fade" id="editStatModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editStatForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Sửa Stat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Số *</label>
                        <input type="text" name="number" id="edit_stat_number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nhãn *</label>
                        <input type="text" name="label" id="edit_stat_label" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ảnh mới</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thứ tự *</label>
                        <input type="number" name="order" id="edit_stat_order" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Benefit Modal -->
<div class="modal fade" id="addBenefitModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.owner-page.benefits.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Thêm Benefit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tiêu đề *</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả *</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ảnh</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thứ tự *</label>
                        <input type="number" name="order" class="form-control" value="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Benefit Modal -->
<div class="modal fade" id="editBenefitModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editBenefitForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Sửa Benefit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tiêu đề *</label>
                        <input type="text" name="title" id="edit_benefit_title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả *</label>
                        <textarea name="description" id="edit_benefit_description" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ảnh mới</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thứ tự *</label>
                        <input type="number" name="order" id="edit_benefit_order" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Step Modal -->
<div class="modal fade" id="addStepModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.owner-page.steps.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Thêm Bước</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Số bước *</label>
                        <input type="number" name="step_number" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tiêu đề *</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả *</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Step Modal -->
<div class="modal fade" id="editStepModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editStepForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Sửa Bước</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Số bước *</label>
                        <input type="number" name="step_number" id="edit_step_number" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tiêu đề *</label>
                        <input type="text" name="title" id="edit_step_title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả *</label>
                        <textarea name="description" id="edit_step_description" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Section Modal -->
<div class="modal fade" id="addSectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.owner-page.sections.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Thêm Section</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tiêu đề *</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nội dung *</label>
                        <textarea name="content" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ảnh</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Vị trí ảnh *</label>
                        <select name="image_position" class="form-control" required>
                            <option value="left">Trái</option>
                            <option value="right">Phải</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thứ tự *</label>
                        <input type="number" name="order" class="form-control" value="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Section Modal -->
<div class="modal fade" id="editSectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editSectionForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Sửa Section</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tiêu đề *</label>
                        <input type="text" name="title" id="edit_section_title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nội dung *</label>
                        <textarea name="content" id="edit_section_content" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ảnh mới</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Vị trí ảnh *</label>
                        <select name="image_position" id="edit_section_position" class="form-control" required>
                            <option value="left">Trái</option>
                            <option value="right">Phải</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thứ tự *</label>
                        <input type="number" name="order" id="edit_section_order" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editStat(id, number, label, order) {
    document.getElementById('edit_stat_number').value = number;
    document.getElementById('edit_stat_label').value = label;
    document.getElementById('edit_stat_order').value = order;
    document.getElementById('editStatForm').action = `/admin/owner-page/stats/${id}`;
    new bootstrap.Modal(document.getElementById('editStatModal')).show();
}

function editBenefit(id, title, description, order) {
    document.getElementById('edit_benefit_title').value = title;
    document.getElementById('edit_benefit_description').value = description;
    document.getElementById('edit_benefit_order').value = order;
    document.getElementById('editBenefitForm').action = `/admin/owner-page/benefits/${id}`;
    new bootstrap.Modal(document.getElementById('editBenefitModal')).show();
}

function editStep(id, title, description, stepNumber) {
    document.getElementById('edit_step_title').value = title;
    document.getElementById('edit_step_description').value = description;
    document.getElementById('edit_step_number').value = stepNumber;
    document.getElementById('editStepForm').action = `/admin/owner-page/steps/${id}`;
    new bootstrap.Modal(document.getElementById('editStepModal')).show();
}

function editSection(id, title, content, position, order) {
    document.getElementById('edit_section_title').value = title;
    document.getElementById('edit_section_content').value = content;
    document.getElementById('edit_section_position').value = position;
    document.getElementById('edit_section_order').value = order;
    document.getElementById('editSectionForm').action = `/admin/owner-page/sections/${id}`;
    new bootstrap.Modal(document.getElementById('editSectionModal')).show();
}
</script>
