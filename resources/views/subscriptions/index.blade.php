@extends('layouts.app')

@section('title', 'Subscriptions')
@section('page_title', 'Subscriptions')

@section('content')
@php
$statusMap = [
'active' => ['label' => 'Active', 'class' => 'bg-success-subtle text-success border-success-subtle'],
'inactive' => ['label' => 'Deactivate', 'class' => 'bg-secondary-subtle text-secondary border-secondary-subtle'],
'trial' => ['label' => 'Trial', 'class' => 'bg-info-subtle text-info border-info-subtle'],
'isolir' => ['label' => 'Isolir', 'class' => 'bg-warning-subtle text-warning border-warning-subtle'],
'dismantle' => ['label' => 'Dismantle', 'class' => 'bg-danger-subtle text-danger border-danger-subtle'],
];
@endphp

<div class="container-fluid p-0">
    <!-- Header Controls -->
    <div class="d-flex justify-content-end mb-4">
        <!-- Add Subscription Button -->
        <button type="button" class="btn btn-primary d-flex align-items-center shadow-sm" data-bs-toggle="modal" data-bs-target="#addSubscriptionModal">
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
                    <thead class="table-white">
                        <tr>
                            <th class="px-4 py-3 text-black text-sm" style="font-weight: 600;">Customer Name</th>
                            <th class="px-4 py-3 text-black text-sm" style="font-weight: 600;">Service Name</th>
                            <th class="px-4 py-3 text-black text-sm" style="font-weight: 600;">Start Date</th>
                            <th class="px-4 py-3 text-black text-sm" style="font-weight: 600;">End Date</th>
                            <th class="px-4 py-3 text-black text-sm" style="font-weight: 600;">Status</th>
                            <th class="px-4 py-3 text-black text-sm text-end" style="font-weight: 600;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="subscriptions-table-body">
                        <tr>
                            <td colspan="6" class="px-4 py-5 text-center text-muted">
                                <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                Loading subscriptions...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="subscriptions-pagination" class="card-footer bg-white border-top py-3 d-none"></div>
    </div>
</div>

@include('subscriptions.create')
@include('subscriptions.edit')
@include('subscriptions.delete')
@endsection

@section('scripts')
<script>
    // Hydrate the Edit Subscription Modal dynamically on show event
    const editSubscriptionModal = document.getElementById('editSubscriptionModal');
    if (editSubscriptionModal) {
        editSubscriptionModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const customerId = button.getAttribute('data-customer-id');
            const serviceId = button.getAttribute('data-service-id');
            const startDate = button.getAttribute('data-start-date');
            const endDate = button.getAttribute('data-end-date');
            const status = button.getAttribute('data-status');

            const form = editSubscriptionModal.querySelector('#editSubscriptionForm');
            form.action = `/subscriptions/${id}`;

            document.getElementById('edit_customer_id').value = customerId;
            document.getElementById('edit_service_id').value = serviceId;

            // Extract YYYY-MM-DD from date string if present
            const cleanDate = (str) => {
                if (!str || str === 'null' || str === 'undefined') return '';
                return str.split('T')[0];
            };

            document.getElementById('edit_start_date').value = cleanDate(startDate);
            document.getElementById('edit_end_date').value = cleanDate(endDate);
            const statusOptions = [{
                    value: 'active',
                    label: 'Active'
                },
                {
                    value: 'inactive',
                    label: 'Deactivate'
                },
                {
                    value: 'trial',
                    label: 'Trial'
                },
                {
                    value: 'isolir',
                    label: 'Isolir'
                },
                {
                    value: 'dismantle',
                    label: 'Dismantle'
                }
            ];

            const statusSelect = document.getElementById('edit_status');

            statusSelect.innerHTML = '';

            statusOptions.forEach(item => {
                if (item.value !== status) {
                    statusSelect.innerHTML += `
            <option value="${item.value}">
                ${item.label}
            </option>
        `;
                }
            });
        });
    }

    // Hydrate the Delete Subscription Modal dynamically on show event
    const deleteSubscriptionModal = document.getElementById('deleteSubscriptionModal');
    if (deleteSubscriptionModal) {
        deleteSubscriptionModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const form = deleteSubscriptionModal.querySelector('#deleteSubscriptionForm');
            form.action = `/subscriptions/${id}`;
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadDropdowns().then(() => {
            fetchSubscriptions();
        });
    });

    async function loadDropdowns() {
        try {
            const [custRes, servRes] = await Promise.all([
                fetch("{{ url('/api/customers?status=active&all=true') }}"),
                fetch("{{ url('/api/services?status=active&all=true') }}")
            ]);
            if (!custRes.ok || !servRes.ok) throw new Error("Failed to load active customers/services options");
            const customersJson = await custRes.json();
            const servicesJson = await servRes.json();

            const customers = (customersJson.data && Array.isArray(customersJson.data.data)) ? customersJson.data.data : (customersJson.data || []);
            const services = (servicesJson.data && Array.isArray(servicesJson.data.data)) ? servicesJson.data.data : (servicesJson.data || []);

            // Populate Add/Edit Customer Selects
            const addCustSelect = document.getElementById('customer_id');
            const editCustSelect = document.getElementById('edit_customer_id');

            let custHtml = '<option value="">Select Customer</option>';
            customers.forEach(c => {
                custHtml += `<option value="${c.id}">${c.name} (${c.customer_id})</option>`;
            });
            if (addCustSelect) addCustSelect.innerHTML = custHtml;
            if (editCustSelect) editCustSelect.innerHTML = custHtml;

            // Populate Add/Edit Service Selects
            const addServSelect = document.getElementById('service_id');
            const editServSelect = document.getElementById('edit_service_id');

            let servHtml = '<option value="">Select Service</option>';
            services.forEach(s => {
                const formattedPrice = Number(s.price).toLocaleString('id-ID', {
                    maximumFractionDigits: 0
                });
                servHtml += `<option value="${s.id}">${s.name} - Rp${formattedPrice}</option>`;
            });
            if (addServSelect) addServSelect.innerHTML = servHtml;
            if (editServSelect) editServSelect.innerHTML = servHtml;
        } catch (err) {
            console.error(err);
            if (window.showToast) {
                window.showToast("Failed to load active dropdown options from API", "error");
            }
        }
    }

    async function fetchSubscriptions(apiUrl = null) {
        const tableBody = document.getElementById('subscriptions-table-body');
        if (!tableBody) return;

        if (!apiUrl) {
            apiUrl = "{{ url('/api/subscriptions') }}";
        }

        try {
            const res = await fetch(apiUrl);
            if (!res.ok) {
                throw new Error("Failed to retrieve subscriptions from API");
            }
            const json = await res.json();

            // Handle Laravel paginator object structure
            const subscriptions = (json.data && Array.isArray(json.data.data)) ? json.data.data : (json.data || []);

            if (subscriptions.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-4 py-5 text-center text-muted">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i> No subscriptions found.
                        </td>
                    </tr>
                `;
                const pagContainer = document.getElementById('subscriptions-pagination');
                if (pagContainer) {
                    pagContainer.innerHTML = '';
                    pagContainer.classList.add('d-none');
                }
                return;
            }

            const statusMap = {
                'active': {
                    label: 'Active',
                    class: 'bg-success-subtle text-success border border-success-subtle'
                },
                'inactive': {
                    label: 'Deactivate',
                    class: 'bg-secondary-subtle text-secondary border-secondary-subtle'
                },
                'trial': {
                    label: 'Trial',
                    class: 'bg-info-subtle text-info border-info-subtle'
                },
                'isolir': {
                    label: 'Isolir',
                    class: 'bg-warning-subtle text-warning border-warning-subtle'
                },
                'dismantle': {
                    label: 'Dismantle',
                    class: 'bg-danger-subtle text-danger border-danger-subtle'
                }
            };

            const formatDate = (dateStr) => {
                if (!dateStr) return '-';
                const d = new Date(dateStr);
                if (isNaN(d.getTime())) return '-';
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                const day = String(d.getDate()).padStart(2, '0');
                return day + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
            };

            const csrfToken = '{{ csrf_token() }}';
            let html = '';
            subscriptions.forEach(sub => {
                const customerName = sub.customer ? sub.customer.name : 'Unknown Customer';
                const serviceName = sub.service ? sub.service.name : 'Unknown Service';
                const startDateFormatted = formatDate(sub.start_date);
                const endDateFormatted = formatDate(sub.end_date);

                const statusData = statusMap[sub.status] || {
                    label: sub.status,
                    class: 'bg-secondary-subtle text-secondary border-secondary-subtle'
                };
                const statusBadge = `<span class="badge border ${statusData.class} px-2.5 py-1.5 rounded-pill font-weight-medium">${statusData.label}</span>`;

                html += `
                    <tr>
                        <td class="px-4 py-3 font-weight-bold text-dark">${customerName}</td>
                        <td class="px-4 py-3 text-slate-700">${serviceName}</td>
                        <td class="px-4 py-3 text-muted">${startDateFormatted}</td>
                        <td class="px-4 py-3 text-muted">${endDateFormatted}</td>
                        <td class="px-4 py-3">${statusBadge}</td>
                        <td class="px-4 py-3 text-end">
                            <div class="dropdown">
                                <button class="btn btn-link text-secondary p-1 border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical" style="font-size: 1.15rem;"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-light py-1">
                                   ${sub.status !== 'dismantle' ? `
<li>
    <button class="dropdown-item d-flex align-items-center py-2" type="button" 
            data-bs-toggle="modal" 
            data-bs-target="#editSubscriptionModal" 
            data-id="${sub.id}" 
            data-customer-id="${sub.customer_id}"
            data-service-id="${sub.service_id}"
            data-start-date="${sub.start_date || ''}"
            data-end-date="${sub.end_date || ''}"
            data-status="${sub.status}">
        <i class="bi bi-pencil-square text-muted me-2.5"></i> Edit
    </button>
</li>
` : ''}



                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <button class="dropdown-item d-flex align-items-center py-2 text-danger" type="button"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteSubscriptionModal"
                                                data-id="${sub.id}">
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
                window.renderPagination('subscriptions-pagination', json.data, fetchSubscriptions);
            }
        } catch (err) {
            console.error(err);
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-4 py-5 text-center text-danger">
                        <i class="bi bi-exclamation-triangle fs-4 d-block mb-2"></i>
                        Failed to retrieve subscriptions from API
                    </td>
                </tr>
            `;
            const pagContainer = document.getElementById('subscriptions-pagination');
            if (pagContainer) {
                pagContainer.innerHTML = '';
                pagContainer.classList.add('d-none');
            }
            if (window.showToast) {
                window.showToast("Failed to retrieve subscriptions from API", "error");
            }
        }
    }
</script>
@endsection