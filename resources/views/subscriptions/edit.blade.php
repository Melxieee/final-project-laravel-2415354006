<!-- Edit Subscription Modal -->
<div class="modal fade" id="editSubscriptionModal" tabindex="-1" aria-labelledby="editSubscriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header border-bottom px-4">
                <h5 class="modal-title font-weight-bold text-dark" id="editSubscriptionModalLabel">Edit Subscription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSubscriptionForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label font-weight-semibold" for="edit_customer_id">Customer Name</label>
                        <select name="customer_id" id="edit_customer_id" required class="form-select rounded-3 py-2">
                            <option value="">Loading customers...</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-semibold" for="edit_service_id">Service Name</label>
                        <select name="service_id" id="edit_service_id" required class="form-select rounded-3 py-2">
                            <option value="">Loading services...</option>
                        </select>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label font-weight-semibold" for="edit_start_date">Start Date</label>
                            <input type="date" name="start_date" id="edit_start_date" class="form-control rounded-3 py-2">
                        </div>
                        <div class="col-6">
                            <label class="form-label font-weight-semibold" for="edit_end_date">End Date</label>
                            <input type="date" name="end_date" id="edit_end_date" class="form-control rounded-3 py-2">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-semibold" for="edit_status">Status</label>
                        <!-- <select name="status" id="edit_status" required class="form-select rounded-3 py-2">
                            <option value="active">Active</option>
                            <option value="inactive">Deactivate</option>
                            <option value="trial">Trial</option>
                            <option value="isolir">Isolir</option>
                            <option value="dismantle">Dismantle</option>
                        </select> -->
                        <select name="status" id="edit_status" class="form-select">
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
