@extends('layouts.app')

@section('title', 'Customers')
@section('page_title', 'Customers')

@section('content')
<div class="container-fluid p-0">
    <!-- Header Controls -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <!-- Filter Tabs -->
        <div class="btn-group shadow-sm bg-white p-1 rounded border" role="group">
            <a href="{{ route('web.customers.index') }}" class="btn btn-sm px-3 rounded {{ is_null($status) ? 'btn-primary text-white shadow-sm' : 'btn-light border-0' }}">
                All
            </a>
            <a href="{{ route('web.customers.index', ['status' => 'active']) }}" class="btn btn-sm px-3 rounded {{ $status === 'active' ? 'btn-primary text-white shadow-sm' : 'btn-light border-0' }}">
                Active
            </a>
            <a href="{{ route('web.customers.index', ['status' => 'inactive']) }}" class="btn btn-sm px-3 rounded {{ $status === 'inactive' ? 'btn-primary text-white shadow-sm' : 'btn-light border-0' }}">
                Inactive
            </a>
        </div>

        <!-- Add Customer Button -->
        <button type="button" class="btn btn-primary d-flex align-items-center shadow-sm" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
            <i class="bi bi-plus-lg me-2"></i> Add Data
        </button>
    </div>

    <!-- Error/Validation Alert -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <div class="font-weight-semibold text-sm mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Please correct the following errors:</div>
            <ul class="mb-0 text-xs ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Data Table Card -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3 text-muted text-sm" style="font-weight: 600;">Customer ID</th>
                            <th class="px-4 py-3 text-muted text-sm" style="font-weight: 600;">Customer Name</th>
                            <th class="px-4 py-3 text-muted text-sm" style="font-weight: 600;">Email</th>
                            <!-- <th class="px-4 py-3 text-muted text-sm" style="font-weight: 600;">Phone</th> -->
                            <th class="px-4 py-3 text-muted text-sm" style="font-weight: 600;">Address</th>
                            <th class="px-4 py-3 text-muted text-sm" style="font-weight: 600;">Status</th>
                            <th class="px-4 py-3 text-muted text-sm text-end" style="font-weight: 600;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="customers-table-body">
                        <tr>
                            <td colspan="6" class="px-4 py-5 text-center text-muted">
                                <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                Loading customers...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="customers-pagination" class="card-footer bg-white border-top py-3 d-none"></div>
    </div>
</div>

@include('customers.create')
@include('customers.edit')
@include('customers.delete')
@endsection

@section('scripts')
<script>
    // Hydrate the Edit Customer Modal dynamically on show event
    const editCustomerModal = document.getElementById('editCustomerModal');
    if (editCustomerModal) {
        editCustomerModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const customerId = button.getAttribute('data-customer-id');
            const name = button.getAttribute('data-name');
            const email = button.getAttribute('data-email');
            const phone = button.getAttribute('data-phone');
            const address = button.getAttribute('data-address');
            const status = button.getAttribute('data-status');

            const form = editCustomerModal.querySelector('#editCustomerForm');
            form.action = `/customers/${id}`;

            document.getElementById('edit_customer_id').value = customerId;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = (email === 'null' || email === '' || email === 'undefined') ? '' : email;
            document.getElementById('edit_phone').value = (phone === 'null' || phone === '' || phone === 'undefined') ? '' : phone;
            document.getElementById('edit_address').value = (address === 'null' || address === '' || address === 'undefined') ? '' : address;
            document.getElementById('edit_status').value = status;
        });
    }

    // Hydrate the Delete Customer Modal dynamically on show event
    const deleteCustomerModal = document.getElementById('deleteCustomerModal');
    if (deleteCustomerModal) {
        deleteCustomerModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const form = deleteCustomerModal.querySelector('#deleteCustomerForm');
            form.action = `/customers/${id}`;
            document.getElementById('deleteCustomerName').textContent = name;
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        fetchCustomers();
    });

    async function fetchCustomers(apiUrl = null) {
        const tableBody = document.getElementById('customers-table-body');
        if (!tableBody) return;

        if (!apiUrl) {
            const currentStatus = @json($status);
            apiUrl = "{{ url('/api/customers') }}";
            if (currentStatus) {
                apiUrl += '?status=' + currentStatus;
            }
        }

        try {
            const res = await fetch(apiUrl);
            if (!res.ok) {
                throw new Error("Failed to retrieve customers from API");
            }
            const json = await res.json();
            
            // Handle Laravel paginator object structure
            const customers = (json.data && Array.isArray(json.data.data)) ? json.data.data : (json.data || []);

            if (customers.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-4 py-5 text-center text-muted">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i> No customers found.
                        </td>
                    </tr>
                `;
                const pagContainer = document.getElementById('customers-pagination');
                if (pagContainer) {
                    pagContainer.innerHTML = '';
                    pagContainer.classList.add('d-none');
                }
                return;
            }

            const csrfToken = '{{ csrf_token() }}';
            let html = '';
            customers.forEach(customer => {
                const statusBadge = customer.status
                    ? `<span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 rounded-pill font-weight-medium">Active</span>`
                    : `<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1.5 rounded-pill font-weight-medium">Inactive</span>`;

                const toggleAction = customer.status
                    ? `
                        <form action="/customers/${customer.id}/deactivate" method="POST" style="display:inline;">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="PATCH">
                            <button type="submit" class="dropdown-item d-flex align-items-center py-2">
                                <i class="bi bi-x-circle text-warning me-2.5"></i> Deactivate
                            </button>
                        </form>
                    `
                    : `
                        <form action="/customers/${customer.id}/activate" method="POST" style="display:inline;">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="PATCH">
                            <button type="submit" class="dropdown-item d-flex align-items-center py-2">
                                <i class="bi bi-check-circle text-success me-2.5"></i> Activate
                            </button>
                        </form>
                    `;

                html += `
                    <tr>
                        <td class="px-4 py-3 font-weight-bold text-dark">${customer.customer_id}</td>
                        <td class="px-4 py-3 text-slate-700">${customer.name}</td>
                        <td class="px-4 py-3 text-muted">${customer.email || '-'}</td>
                    <!-- <td class="px-4 py-3 text-muted">${customer.phone || '-'}</td> -->
                        <td class="px-4 py-3 text-muted max-w-xs text-truncate">${customer.address || '-'}</td>
                        <td class="px-4 py-3">${statusBadge}</td>
                        <td class="px-4 py-3 text-end">
                            <div class="dropdown">
                                <button class="btn btn-link text-secondary p-1 border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical" style="font-size: 1.15rem;"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-light py-1">
                                    <li>
                                        <button class="dropdown-item d-flex align-items-center py-2" type="button" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editCustomerModal" 
                                                data-id="${customer.id}" 
                                                data-customer-id="${customer.customer_id}"
                                                data-name="${customer.name}"
                                                data-email="${customer.email || ''}"
                                                data-phone="${customer.phone || ''}"
                                                data-address="${customer.address || ''}"
                                                data-status="${customer.status ? '1' : '0'}">
                                            <i class="bi bi-pencil-square text-muted me-2.5"></i> Edit
                                        </button>
                                    </li>
                                    <li>${toggleAction}</li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <button class="dropdown-item d-flex align-items-center py-2 text-danger" type="button"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteCustomerModal"
                                                data-id="${customer.id}"
                                                data-name="${customer.name}">
                                                
                                            <i class="bi bi-trash3 text-danger me-2.5"></i> Delete
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                `;
            });
            tableBody.innerHTML = html;

            // Render pagination controls
            if (window.renderPagination) {
                window.renderPagination('customers-pagination', json.data, fetchCustomers);
            }
        } catch (err) {
            console.error(err);
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-4 py-5 text-center text-danger">
                        <i class="bi bi-exclamation-triangle fs-4 d-block mb-2"></i>
                        Failed to retrieve customers from API
                    </td>
                </tr>
            `;
            const pagContainer = document.getElementById('customers-pagination');
            if (pagContainer) {
                pagContainer.innerHTML = '';
                pagContainer.classList.add('d-none');
            }
            if (window.showToast) {
                window.showToast("Failed to retrieve customers from API", "error");
            }
        }
    }
</script>
@endsection
