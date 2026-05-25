@extends('layouts.app')

@section('title', 'Services')
@section('page_title', 'Services')

@section('content')
<div class="container-fluid p-0">
    <!-- Header Controls -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <!-- Filter Tabs -->
        <div class="btn-group shadow-sm bg-white p-1 rounded border" role="group">
            <a href="{{ route('web.services.index') }}" class="btn btn-sm px-3 rounded {{ is_null($status) ? 'btn-primary text-white shadow-sm' : 'btn-light border-0' }}">
                All
            </a>
            <a href="{{ route('web.services.index', ['status' => 'active']) }}" class="btn btn-sm px-3 rounded {{ $status === 'active' ? 'btn-primary text-white shadow-sm' : 'btn-light border-0' }}">
                Active
            </a>
            <a href="{{ route('web.services.index', ['status' => 'inactive']) }}" class="btn btn-sm px-3 rounded {{ $status === 'inactive' ? 'btn-primary text-white shadow-sm' : 'btn-light border-0' }}">
                Inactive
            </a>
        </div>

        <!-- Add Service Button -->
        <button type="button" class="btn btn-primary d-flex align-items-center shadow-sm" data-bs-toggle="modal" data-bs-target="#addServiceModal">
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
                    <thead class="table-white" >
                        <tr>
                            <th class="px-4 py-3 text-black text-sm" style="font-weight: 600;">Service Name</th>
                            <th class="px-4 py-3 text-black text-sm" style="font-weight: 600;">Price</th>
                            <th class="px-4 py-3 text-black text-sm" style="font-weight: 600;">Description</th>
                            <th class="px-4 py-3 text-black text-sm" style="font-weight: 600;">Status</th>
                            <th class="px-4 py-3 text-black text-sm text-end" style="font-weight: 600;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="services-table-body">
                        <tr>
                            <td colspan="5" class="px-4 py-5 text-center text-muted">
                                <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                Loading services...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="services-pagination" class="card-footer bg-white border-top py-3 d-none"></div>
    </div>
</div>

@include('services.create')
@include('services.edit')
@include('services.delete')
@endsection

@section('scripts')
<script>
    // Hydrate the Edit Service Modal dynamically on show event
    const editServiceModal = document.getElementById('editServiceModal');
    if (editServiceModal) {
        editServiceModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const price = button.getAttribute('data-price');
            const description = button.getAttribute('data-description');
            const status = button.getAttribute('data-status');

            const form = editServiceModal.querySelector('#editServiceForm');
            form.action = `/services/${id}`;

            document.getElementById('edit_name').value = name;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_description').value = (description === 'null' || description === '' || description === 'undefined') ? '' : description;
            document.getElementById('edit_status').value = status;
        });
    }

    // Hydrate the Delete Service Modal dynamically on show event
    const deleteServiceModal = document.getElementById('deleteServiceModal');
    if (deleteServiceModal) {
        deleteServiceModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const form = deleteServiceModal.querySelector('#deleteServiceForm');
            form.action = `/services/${id}`;
            document.getElementById('deleteServiceName').textContent = name;
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        fetchServices();
    });

    async function fetchServices(apiUrl = null) {
        const tableBody = document.getElementById('services-table-body');
        if (!tableBody) return;

        if (!apiUrl) {
            const currentStatus = @json($status);
            apiUrl = "{{ url('/api/services') }}";
            if (currentStatus) {
                apiUrl += '?status=' + currentStatus;
            }
        }

        try {
            const res = await fetch(apiUrl);
            if (!res.ok) {
                throw new Error("Failed to retrieve services from API");
            }
            const json = await res.json();
            
            // Handle Laravel paginator object structure
            const services = (json.data && Array.isArray(json.data.data)) ? json.data.data : (json.data || []);

            if (services.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-4 py-5 text-center text-muted">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i> No services found.
                        </td>
                    </tr>
                `;
                const pagContainer = document.getElementById('services-pagination');
                if (pagContainer) {
                    pagContainer.innerHTML = '';
                    pagContainer.classList.add('d-none');
                }
                return;
            }

            const csrfToken = '{{ csrf_token() }}';
            let html = '';
            services.forEach(service => {
                const statusBadge = service.status
                    ? `<span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 rounded-pill font-weight-medium">Active</span>`
                    : `<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1.5 rounded-pill font-weight-medium">Inactive</span>`;

                const priceFormatted = 'Rp' + Number(service.price).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                const toggleAction = service.status
                    ? `
                        <form action="/services/${service.id}/deactivate" method="POST" style="display:inline;">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="PATCH">
                            <button type="submit" class="dropdown-item d-flex align-items-center py-2">
                                <i class="bi bi-x-circle text-warning me-2.5"></i> Deactivate
                            </button>
                        </form>
                    `
                    : `
                        <form action="/services/${service.id}/activate" method="POST" style="display:inline;">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="PATCH">
                            <button type="submit" class="dropdown-item d-flex align-items-center py-2">
                                <i class="bi bi-check-circle text-success me-2.5"></i> Activate
                            </button>
                        </form>
                    `;

                const descriptionText = service.description ? service.description : '-';
                html += `
                    <tr>
                        <td class="px-4 py-3 font-weight-bold text-dark">${service.name}</td>
                        <td class="px-4 py-3 text-dark font-weight-semibold">${priceFormatted}</td>
                        <td class="px-4 py-3 text-muted max-w-xs text-truncate">${descriptionText}</td>
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
                                                data-bs-target="#editServiceModal" 
                                                data-id="${service.id}" 
                                                data-name="${service.name}"
                                                data-price="${service.price}"
                                                data-description="${service.description || ''}"
                                                data-status="${service.status ? '1' : '0'}">
                                            <i class="bi bi-pencil-square text-muted me-2.5"></i> Edit
                                        </button>
                                    </li>
                                    <li>${toggleAction}</li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <button class="dropdown-item d-flex align-items-center py-2 text-danger" type="button"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteServiceModal"
                                                data-id="${service.id}"
                                                data-name="${service.name}">
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
                window.renderPagination('services-pagination', json.data, fetchServices);
            }
        } catch (err) {
            console.error(err);
            tableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="px-4 py-5 text-center text-danger">
                        <i class="bi bi-exclamation-triangle fs-4 d-block mb-2"></i>
                        Failed to retrieve services from API
                    </td>
                </tr>
            `;
            const pagContainer = document.getElementById('services-pagination');
            if (pagContainer) {
                pagContainer.innerHTML = '';
                pagContainer.classList.add('d-none');
            }
            if (window.showToast) {
                window.showToast("Failed to retrieve services from API", "error");
            }
        }
    }
</script>
@endsection
