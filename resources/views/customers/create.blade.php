<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header border-bottom px-4">
                <h5 class="modal-title font-weight-bold text-dark" id="addCustomerModalLabel">Add Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('web.customers.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label font-weight-semibold" for="customer_id">Customer ID</label>
                        <input type="text" name="customer_id" id="customer_id" required placeholder="Enter customer ID (e.g. CUST-01)" class="form-control rounded-3 py-2">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-semibold" for="name">Customer Name</label>
                        <input type="text" name="name" id="name" required placeholder="Enter customer name" class="form-control rounded-3 py-2">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-semibold" for="email">Email</label>
                        <input type="email" name="email" id="email" placeholder="Enter customer email" class="form-control rounded-3 py-2">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-semibold" for="phone">Phone</label>
                        <input type="text" name="phone" id="phone" placeholder="Enter phone number" class="form-control rounded-3 py-2">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-semibold" for="address">Address</label>
                        <textarea name="address" id="address" placeholder="Enter customer address" rows="3" class="form-control rounded-3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-semibold" for="status">Status</label>
                        <select name="status" id="status" class="form-select rounded-3 py-2">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
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
