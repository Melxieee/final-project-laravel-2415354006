<!-- Delete Subscription Modal -->
<div class="modal fade" id="deleteSubscriptionModal" tabindex="-1" aria-labelledby="deleteSubscriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header border-bottom px-4">
                <h5 class="modal-title font-weight-bold text-dark" id="deleteSubscriptionModalLabel">Delete Subscription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteSubscriptionForm" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body p-4 text-center">
                    <i class="bi bi-exclamation-triangle text-danger display-4 d-block mb-3"></i>
                    <p class="mb-0 font-weight-semibold">Are you sure you want to delete this subscription?</p>
                    <strong id="deleteSubscriptionName" class="text-danger"></strong>
                </div>
                <div class="modal-footer bg-light border-top px-4 py-3">
                    <button type="button" class="btn btn-outline-secondary px-4 rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger px-4 rounded-3">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
