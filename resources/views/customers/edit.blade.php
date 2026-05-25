<!-- Edit Customer Modal -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header border-bottom px-4">
                <h5 class="modal-title font-weight-bold text-dark" id="editCustomerModalLabel">Edit Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCustomerForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label font-weight-semibold" for="edit_customer_id">Customer ID</label>
                        <input type="text" name="customer_id" id="edit_customer_id" required class="form-control rounded-3 py-2">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-semibold" for="edit_name">Customer Name</label>
                        <input type="text" name="name" id="edit_name" required class="form-control rounded-3 py-2">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-semibold" for="edit_email">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control rounded-3 py-2">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-semibold" for="edit_phone">Phone</label>
                        <input type="text" name="phone" id="edit_phone" class="form-control rounded-3 py-2">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-semibold" for="edit_address">Address</label>
                        <textarea name="address" id="edit_address" rows="3" class="form-control rounded-3"></textarea>
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
