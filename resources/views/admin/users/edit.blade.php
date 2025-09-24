@extends('layouts.admin')
@section('title', 'Chỉnh sửa người dùng')
@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">Chỉnh sửa người dùng</h3>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                data-bs-target="#adjustBalanceModal">
                                <i class="fas fa-plus-circle"></i> Cộng / Trừ số dư
                            </button>
                            <a href="{{ route('admin.users.balance-log', $user->id) }}" class="btn btn-sm btn-warning">Biến
                                động số dư</a>
                            <a href="{{ route('admin.users.activity-log', $user->username) }}"
                                class="btn btn-sm btn-danger">Nhật kí hoạt động</a>
                        </div>
                    </div>
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')

                        @if ($errors->any() && !$errors->has('amount') && !$errors->has('type') && !$errors->has('description'))
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif


                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label"><i class="fas fa-user"></i> Tên đăng nhập</label>
                                    <input type="text" name="username"
                                        class="form-control @error('username') is-invalid @enderror"
                                        value="{{ old('username', $user->username) }}"
                                        placeholder="Chỉ chữ cái, số, gạch dưới. Tối đa 30 ký tự">
                                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><i class="fas fa-id-card"></i> Họ tên</label>
                                    <input type="text" name="fullname"
                                        class="form-control @error('fullname') is-invalid @enderror"
                                        value="{{ old('fullname', $user->fullname) }}">
                                    @error('fullname') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $user->email) }}" readonly>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label"><i class="fas fa-lock"></i> Mật khẩu mới</label>
                                    <input type="password" name="password" placeholder="Để trống nếu không thay đổi"
                                        class="form-control @error('password') is-invalid @enderror">
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label"><i class="fas fa-phone"></i> Số điện thoại</label>
                                    <input type="text" name="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', $user->phone) }}">
                                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label"><i class="fas fa-home"></i> Địa chỉ</label>
                                    <input type="text" name="address"
                                        class="form-control @error('address') is-invalid @enderror"
                                        value="{{ old('address', $user->address) }}">
                                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label"><i class="fas fa-venus-mars"></i> Giới tính</label>
                                    <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                        <option disabled>-- Chọn giới tính --</option>
                                        <option value="Nam" {{ old('gender', $user->gender) == 'Nam' ? 'selected' : '' }}>Nam
                                        </option>
                                        <option value="Nữ" {{ old('gender', $user->gender) == 'Nữ' ? 'selected' : '' }}>Nữ
                                        </option>
                                        <option value="Khác" {{ old('gender', $user->gender) == 'Khác' ? 'selected' : '' }}>
                                            Khác</option>
                                    </select>
                                    @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label"><i class="fas fa-user-tag"></i> Vai trò</label>

                                    <select name="role" class="form-select @error('role') is-invalid @enderror">
                                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>



                            <div class="row mb-3">
                                
                                <div class="col-md-4">
                                    <label class="form-label"><i class="fas fa-wallet text-gray-600"></i> Số dư ví</label>
                                    <input type="text" class="form-control" value="{{ number_format($user->balance) }}đ"
                                        readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label"><i class="fas fa-shield-alt"></i> Trạng thái</label>
                                    <select name="banned" class="form-select @error('banned') is-invalid @enderror">
                                        <option value="0" {{ $user->banned == 0 ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="1" {{ $user->banned == 1 ? 'selected' : '' }}>Bị khoá</option>
                                    </select>
                                    @error('banned') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label"><i class="fas fa-wifi"></i> Địa chỉ IP dùng để đăng
                                        nhập</label>
                                    <input type="text" class="form-control" value="{{ $user->last_login_ip ?? 'Chưa có' }}"
                                        readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><i class="fas fa-desktop"></i> Thiết bị đăng nhập</label>

                                    <input type="text" class="form-control"
                                        value="{{ $user->last_login_device ?? 'Chưa có' }}" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label"><i class="fas fa-sign-in-alt"></i> Đăng nhập gần nhất vào
                                        lúc</label>

                                    <input type="text" class="form-control" value="{{ $user->last_login_at ?? 'Chưa có' }}"
                                        readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><i class="fas fa-calendar-plus"></i> Đăng ký tài khoản vào lúc
                                    </label>
                                    <input type="text" class="form-control" value="{{ $user->created_at }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-light">Huỷ</a>

                        </div>
                    </form>
                </div>
            </div>
            <!-- Modal Cộng/Trừ số dư -->
            <div class="modal fade" id="adjustBalanceModal" tabindex="-1" aria-labelledby="adjustBalanceModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form id="adjustBalanceForm" action="{{ route('admin.users.adjustBalance', $user->id) }}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="adjustBalanceModalLabel">
                                    <i class="fas fa-wallet me-2"></i>Điều chỉnh số dư
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                            </div>
                            <div class="modal-body">
                                @if (session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif

                                <div class="mb-3">
                                    <label><i class="fas fa-exchange-alt me-1"></i>Loại thao tác</label>
                                    <select name="type" class="form-select" required>
                                        <option value="add">+ Cộng số dư</option>
                                        <option value="subtract">− Trừ số dư</option>
                                    </select>
                                    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label><i class="fas fa-coins me-1"></i>Số tiền</label>
                                    <input type="number" name="amount" value="{{ old('amount') }}" class="form-control">
                                    @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label><i class="fas fa-comment-dots me-1"></i>Lý do</label>
                                    <textarea name="description" rows="2" class="form-control"
                                        placeholder="Ví dụ: hoàn tiền, phạt...">{{ old('description') }}</textarea>
                                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i>Huỷ
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check-circle me-1"></i>Xác nhận
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>



        </div>
    </div>
@endsection
@if ($errors->has('amount') || $errors->has('type') || $errors->has('description') || session('error'))
    <script>
        var adjustModal = new bootstrap.Modal(document.getElementById('adjustBalanceModal'));
        adjustModal.show();
    </script>
@endif
@section('js')
    <!-- Thêm thư viện SweetAlert2 nếu chưa có -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('adjustBalanceForm');

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(form);

                // Xoá lỗi cũ
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                    .then(async res => {
                        const data = await res.json();

                        if (res.ok && data.success) {
                            const modal = bootstrap.Modal.getInstance(document.getElementById('adjustBalanceModal'));
                            modal.hide();

                            // Hiển thị thông báo SweetAlert2
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công',
                                text: data.message,
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.reload(); // reload sau khi ấn OK
                            });

                        } else {
                            // Hiển thị lỗi validation
                            if (data.errors) {
                                for (const key in data.errors) {
                                    const input = form.querySelector(`[name="${key}"]`);
                                    if (input) {
                                        input.classList.add('is-invalid');
                                        const feedback = document.createElement('div');
                                        feedback.classList.add('invalid-feedback');
                                        feedback.innerText = data.errors[key][0];
                                        input.parentNode.appendChild(feedback);
                                    }
                                }
                            } else if (data.error) {
                                const amountInput = form.querySelector('[name="amount"]');
                                if (amountInput) {
                                    amountInput.classList.add('is-invalid');
                                    const feedback = document.createElement('div');
                                    feedback.classList.add('invalid-feedback');
                                    feedback.innerText = data.error;
                                    amountInput.parentNode.appendChild(feedback);
                                }
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi gửi request:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Không thể kết nối tới server.',
                        });
                    });
            });
        });
    </script>

@endsection