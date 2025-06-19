@extends('layouts.admin')
@section('title', isset($shippingFee) ? 'Cập nhật phí vận chuyển' : 'Thêm phí vận chuyển')
@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">

        <div class="card card-flush">
            <!--begin::Card header-->
            <div class="card-header pt-8">
                <div class="card-title">
                    <h4 class="mb-0">{{ isset($shippingFee) ? 'Cập nhật' : 'Thêm mới' }} phí vận chuyển</h4>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body">
                <form method="POST" action="{{ isset($shippingFee) ? route('admin.shipping-fees.update', $shippingFee->id) : route('admin.shipping-fees.store') }}" id="shippingFeeForm">
                    @csrf
                    @if(isset($shippingFee)) @method('PUT') @endif

                    <!-- Khu vực giao hàng -->
                    <div class="mb-5">
                        <h5 class="mb-3">Khu vực giao hàng</h5>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="province" class="form-label">Tỉnh/Thành phố</label>
                                <input type="text" class="form-control" id="province" list="provinceList" placeholder="Nhập tỉnh/thành phố">
                                <datalist id="provinceList">

                                </datalist>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="district" class="form-label">Quận/Huyện</label>
                                <input type="text" class="form-control" id="district" list="districtList" placeholder="Nhập quận/huyện">
                                <datalist id="districtList">
                                    <!-- Sẽ được cập nhật động qua JavaScript -->
                                </datalist>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="ward" class="form-label">Phường/Xã</label>
                                <input type="text" class="form-control" id="ward" list="wardList" placeholder="Nhập phường/xã">
                                <datalist id="wardList">
                                    <!-- Sẽ được cập nhật động qua JavaScript -->
                                </datalist>
                            </div>

                            <div class="col-md-3 mb-3 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-primary w-100" onclick="addLocation()">
                                    <i class="fas fa-plus"></i> Thêm khu vực
                                </button>
                            </div>
                        </div>

                        <!-- Danh sách khu vực đã chọn -->
                        <div id="selectedLocations" class="mt-3">
                            <h6>Khu vực đã chọn:</h6>
                            <div id="locationList" class="d-flex flex-wrap gap-2">
                                <!-- Các tag khu vực sẽ hiển thị ở đây -->
                            </div>
                        </div>
                    </div>

                    <!-- Bảng phương thức giao hàng -->
                    <div class="mb-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Phương thức giao hàng</h5>
                            <button type="button" class="btn btn-primary btn-sm" onclick="addMethodRow()">
                                <i class="fas fa-plus"></i> Thêm phương thức
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="methodsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 40%">Phương thức giao hàng</th>
                                        <th style="width: 25%">Phí giao hàng (VNĐ)</th>
                                        <th style="width: 25%">Miễn phí từ (VNĐ)</th>
                                        <th style="width: 10%">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody id="methodsTableBody">
                                    <!-- Hàng đầu tiên mặc định -->
                                    <tr>
                                        <td>
                                            <select name="shipping_methods[0][method_id]" class="form-select method-select" required>
                                                <option value="">-- Chọn phương thức --</option>
                                                @foreach($methods as $method)
                                                <option value="{{ $method->id }}">{{ $method->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="shipping_methods[0][price]" class="form-control" placeholder="0" required min="0">
                                        </td>
                                        <td>
                                            <input type="number" name="shipping_methods[0][free_shipping_minimum]" class="form-control" placeholder="0" min="0">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger" onclick="removeMethodRow(this)" disabled>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Hidden input để lưu danh sách khu vực -->
                    <input type="hidden" name="locations" id="locationsData">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">{{ isset($shippingFee) ? 'Cập nhật' : 'Thêm mới' }}</button>
                        <a href="{{ route('admin.shipping-fees.index') }}" class="btn btn-secondary">Quay lại</a>
                    </div>
                </form>
            </div>
            <!--end::Card body-->
        </div>
    </div>
</div>

<!-- Modal thêm phương thức nhanh -->
<div class="modal fade" id="quickAddMethodModal" tabindex="-1" aria-labelledby="quickAddMethodLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickAddMethodLabel">Thêm phương thức giao hàng mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="newMethodName" class="form-control" placeholder="Tên phương thức giao hàng">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" onclick="addNewShippingMethod()">Thêm</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Hàm khởi tạo autocomplete cho địa chỉ
    function initAddressAutocomplete() {
        const provinceInput = document.getElementById('province');
        const districtInput = document.getElementById('district');
        const wardInput = document.getElementById('ward');

        const provinceList = document.getElementById('provinceList');
        const districtList = document.getElementById('districtList');
        const wardList = document.getElementById('wardList');

        // Load tỉnh
        fetch('https://provinces.open-api.vn/api/p/')
            .then(res => res.json())
            .then(provinces => {
                provinces.forEach(p => {
                    const option = document.createElement('option');
                    option.value = p.name;
                    option.setAttribute('data-code', p.code);
                    provinceList.appendChild(option);
                });

                // Khi chọn tỉnh
                provinceInput.addEventListener('input', () => {
                    const selected = provinces.find(p => p.name === provinceInput.value);
                    if (!selected) return;

                    fetch(`https://provinces.open-api.vn/api/p/${selected.code}?depth=2`)
                        .then(res => res.json())
                        .then(data => {
                            districtList.innerHTML = '';
                            districtInput.value = '';
                            wardList.innerHTML = '';
                            wardInput.value = '';

                            data.districts.forEach(d => {
                                const option = document.createElement('option');
                                option.value = d.name;
                                option.setAttribute('data-code', d.code);
                                districtList.appendChild(option);
                            });

                            // Khi chọn quận
                            districtInput.addEventListener('input', () => {
                                const district = data.districts.find(d => d.name === districtInput.value);
                                if (!district) return;

                                fetch(`https://provinces.open-api.vn/api/d/${district.code}?depth=2`)
                                    .then(res => res.json())
                                    .then(detail => {
                                        wardList.innerHTML = '';
                                        wardInput.value = '';
                                        detail.wards.forEach(w => {
                                            const option = document.createElement('option');
                                            option.value = w.name;
                                            wardList.appendChild(option);
                                        });
                                    });
                            });
                        });
                });
            });
    }
    document.addEventListener('DOMContentLoaded', function() {
        initAddressAutocomplete();
    });

    let selectedLocations = [];
    let methodRowIndex = 1;

    // Thêm khu vực vào danh sách
    function addLocation() {
        const province = document.getElementById('province').value.trim();
        const district = document.getElementById('district').value.trim();
        const ward = document.getElementById('ward').value.trim();

        if (!province) {
            alert('Vui lòng chọn tỉnh/thành phố');
            return;
        }

        // Tạo địa chỉ đầy đủ
        let fullAddress = province;
        if (district) fullAddress += ', ' + district;
        if (ward) fullAddress += ', ' + ward;

        // Kiểm tra trùng lặp
        if (selectedLocations.some(loc => loc.fullAddress === fullAddress)) {
            alert('Khu vực này đã được thêm');
            return;
        }

        // Thêm vào danh sách
        const location = {
            province: province,
            district: district || '',
            ward: ward || '',
            fullAddress: fullAddress
        };

        selectedLocations.push(location);
        updateLocationDisplay();

        // Reset form
        document.getElementById('province').value = '';
        document.getElementById('district').value = '';
        document.getElementById('ward').value = '';
        document.getElementById('districtList').innerHTML = '';
        document.getElementById('wardList').innerHTML = '';
    }

    // Cập nhật hiển thị danh sách khu vực
    function updateLocationDisplay() {
        const locationList = document.getElementById('locationList');
        locationList.innerHTML = '';

        selectedLocations.forEach((location, index) => {
            const tag = document.createElement('span');
            tag.className = 'badge bg-primary me-2 mb-2 p-2';
            tag.innerHTML = `
                ${location.fullAddress}
                <button type="button" class="btn-close btn-close-white ms-2" onclick="removeLocation(${index})" style="font-size: 0.75em;"></button>
            `;
            locationList.appendChild(tag);
        });

        // Cập nhật hidden input
        document.getElementById('locationsData').value = JSON.stringify(selectedLocations);
    }

    // Xóa khu vực
    function removeLocation(index) {
        selectedLocations.splice(index, 1);
        updateLocationDisplay();
    }

    // Thêm hàng phương thức mới
    function addMethodRow() {
        const tbody = document.getElementById('methodsTableBody');
        const newRow = document.createElement('tr');

        newRow.innerHTML = `
            <td>
                <div class="d-flex gap-2">
                    <select name="shipping_methods[${methodRowIndex}][method_id]" class="form-select method-select" required>
                        <option value="">-- Chọn phương thức --</option>
                        @foreach($methods as $method)
                        <option value="{{ $method->id }}">{{ $method->name }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="showQuickAddMethod(this)" title="Thêm phương thức mới">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </td>
            <td>
                <input type="number" name="shipping_methods[${methodRowIndex}][price]" class="form-control" placeholder="0" required min="0">
            </td>
            <td>
                <input type="number" name="shipping_methods[${methodRowIndex}][free_shipping_minimum]" class="form-control" placeholder="0" min="0">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger" onclick="removeMethodRow(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;

        tbody.appendChild(newRow);
        methodRowIndex++;
        updateRemoveButtons();
    }

    // Xóa hàng phương thức
    function removeMethodRow(button) {
        button.closest('tr').remove();
        updateRemoveButtons();
    }

    // Cập nhật trạng thái button xóa (không cho xóa nếu chỉ có 1 hàng)
    function updateRemoveButtons() {
        const rows = document.querySelectorAll('#methodsTableBody tr');
        rows.forEach((row, index) => {
            const removeBtn = row.querySelector('.btn-danger');
            removeBtn.disabled = rows.length === 1;
        });
    }

    // Hiển thị modal thêm phương thức
    function showQuickAddMethod(button) {
        window.currentMethodSelect = button.previousElementSibling;
        new bootstrap.Modal(document.getElementById('quickAddMethodModal')).show();
    }

    // Thêm phương thức mới qua modal
    function addNewShippingMethod() {
        const name = document.getElementById('newMethodName').value.trim();
        if (!name) {
            alert('Vui lòng nhập tên phương thức');
            return;
        }

        fetch('{{ route('admin.shipping-methods.quick-add') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        name
                    })
                })
            .then(res => res.json())
            .then(method => {
                // Thêm vào tất cả select box
                document.querySelectorAll('.method-select').forEach(select => {
                    const option = document.createElement('option');
                    option.value = method.id;
                    option.textContent = method.name;
                    select.appendChild(option);
                });

                // Chọn phương thức mới tạo cho select hiện tại
                if (window.currentMethodSelect) {
                    window.currentMethodSelect.value = method.id;
                }

                // Đóng modal và reset
                document.getElementById('newMethodName').value = '';
                bootstrap.Modal.getInstance(document.getElementById('quickAddMethodModal')).hide();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi thêm phương thức');
            });
    }

    // Khởi tạo
    document.addEventListener('DOMContentLoaded', function() {
        updateRemoveButtons();
    });

    // Validation form trước khi submit
    document.getElementById('shippingFeeForm').addEventListener('submit', function(e) {
        if (selectedLocations.length === 0) {
            e.preventDefault();
            alert('Vui lòng chọn ít nhất một khu vực giao hàng');
            return false;
        }
    });
</script>
@endpush
@endsection