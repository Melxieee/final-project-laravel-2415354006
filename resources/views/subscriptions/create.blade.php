<!-- Add Subscription Modal -->
<div class="modal fade" id="addSubscriptionModal" tabindex="-1" aria-labelledby="addSubscriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header border-bottom px-4">
                <h5 class="modal-title font-weight-bold text-dark" id="addSubscriptionModalLabel">Add Subscription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('web.subscriptions.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label font-weight-semibold" for="customer_id">Customer Name</label>
                        <select name="customer_id" id="customer_id" required class="form-select rounded-3 py-2">
                            <option value="">Loading customers...</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-semibold" for="service_id">Service Name</label>
                        <select name="service_id" id="service_id" required class="form-select rounded-3 py-2">
                            <option value="">Loading services...</option>
                        </select>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label font-weight-semibold" for="start_date">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control rounded-3 py-2">
                        </div>
                        <div class="col-6">
                            <label class="form-label font-weight-semibold" for="end_date">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control rounded-3 py-2">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-semibold" for="status">Status</label>
                        <select name="status" id="status" required class="form-select rounded-3 py-2">
                            <option value="active">Active</option>
                            <option value="inactive">Deactivate</option>
                            <option value="trial">Trial</option>
                            <option value="isolir">Isolir</option>
                            <option value="dismantle">Dismantle</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top px-4 py-3">
                    <button type="button" class="btn btn-outline-secondary px-4 rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-3">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
