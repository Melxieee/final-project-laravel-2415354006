<!-- Edit Service Modal -->
<div class="modal fade" id="editServiceModal" tabindex="-1" aria-labelledby="editServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header border-bottom px-4">
                <h5 class="modal-title font-weight-bold text-dark" id="editServiceModalLabel">Edit Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editServiceForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label font-weight-semibold" for="edit_name">Service Name</label>
                        <input type="text" name="name" id="edit_name" required class="form-control rounded-3 py-2">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-semibold" for="edit_price">Price</label>
                        <input type="number" name="price" id="edit_price" required min="0" class="form-control rounded-3 py-2">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-semibold" for="edit_description">Description</label>
                        <textarea name="description" id="edit_description" rows="3" class="form-control rounded-3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-semibold" for="edit_status">Status</label>
                        <select name="status" id="edit_status" class="form-select rounded-3 py-2">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top px-4 py-3">
                    <button type="button" class="btn btn-outline-secondary px-4 rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-3">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
