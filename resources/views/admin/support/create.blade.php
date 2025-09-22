@extends('layouts.admin') {{-- layout admin của bạn --}}

@section('title','Tạo phiếu hỗ trợ')

@section('content')




<div class="container-xxl">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
    <div class="alert alert-danger">
        <b>Lỗi:</b>
        <ul class="mb-0">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.support.tickets.store') }}" method="post" enctype="multipart/form-data" class="card shadow-sm">
        @csrf
        <div class="card-header">
            <h3 class="card-title">Tạo phiếu hỗ trợ</h3>
        </div>

        <div class="card-body row g-4">
            <div class="col-md-6">
                <label class="form-label">Khách hàng <span class="text-danger">*</span></label>
                <select id="user_id" name="user_id" class="form-select" required></select>
                @error('user_id') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                <input type="text" name="subject" value="{{ old('subject') }}" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Nhóm</label>
                <select name="category" class="form-select" required>
                    @foreach(['order'=>'Đơn hàng & vận chuyển','shipping'=>'Vận chuyển','refund'=>'Hoàn/đổi','product'=>'Sản phẩm','other'=>'Khác'] as $val=>$text)
                    <option value="{{ $val }}" @selected(old('category')==$val)>{{ $text }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Độ ưu tiên</label>
                <select name="priority" class="form-select">
                    @foreach(['low'=>'Thấp','normal'=>'Bình thường','high'=>'Cao','urgent'=>'Khẩn'] as $val=>$text)
                    <option value="{{ $val }}" @selected(old('priority','normal')==$val)>{{ $text }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Mã đơn (nếu có)</label>
                <input type="text" name="order_code" value="{{ old('order_code') }}" class="form-control" placeholder="ORD...">
            </div>

            <div class="col-md-4">
                <label class="form-label">Mã vận đơn (nếu có)</label>
                <input type="text" name="carrier_code" value="{{ old('carrier_code') }}" class="form-control" placeholder="VD: GHN...">
            </div>

            

            <div class="col-md-4">
                <label class="form-label">Thời điểm liên hệ</label>
                <input type="datetime-local" name="contact_time" value="{{ old('contact_time') }}" class="form-control">
            </div>

            

            <div class="col-12">
                <label class="form-label">Nội dung ban đầu (tuỳ chọn)</label>
                <textarea name="body" class="form-control" rows="4" placeholder="Mô tả vấn đề...">{{ old('body') }}</textarea>
            </div>

            <div class="col-12">
                <label class="form-label">Đính kèm (nhiều file)</label>
                <input type="file" name="attachments[]" class="form-control" multiple
                    accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx">
                <div class="form-text">Tối đa 10 file, 4MB/file.</div>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-end gap-2">
            <a href="{{ url()->previous() }}" class="btn btn-light">Huỷ</a>
            <button class="btn btn-primary">Tạo phiếu</button>
        </div>
    </form>
</div>

{{-- CSS/JS Select2 (CDN) --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">

<style>
/* ép chiều cao Select2 khớp input 48px (đổi số nếu form bạn cao 44px) */
#user_id + .select2-container .select2-selection--single{
  height:48px; display:flex; align-items:center;
  border:1px solid var(--bs-border-color);
  border-radius:.625rem; padding-left:12px; background:#fff;
}
#user_id + .select2-container .select2-selection__rendered{ line-height:48px; padding-left:0; }
#user_id + .select2-container .select2-selection__arrow{ height:48px; right:12px; }
#user_id + .select2-container--default.select2-container--focus .select2-selection--single{
  border-color:var(--bs-primary);
  box-shadow:0 0 0 .2rem rgba(var(--bs-primary-rgb), .25);
}
</style>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<script>
document.addEventListener('DOMContentLoaded', function () {
  const $el = $('#user_id');

  $el.select2({
    placeholder: '-- Chọn khách --',
    allowClear: true,
    width: '100%',
    ajax: {
      url: '{{ route("admin.ajax.users.search") }}',
      dataType: 'json',
      delay: 250,
      data: params => ({ q: params.term || '', page: params.page || 1 }),
      processResults: (data, params) => ({
        results: data.results,
        pagination: { more: data.pagination?.more }
      }),
      cache: true
    },
    minimumInputLength: 1,
    templateResult: formatUser,       // dropdown item
    templateSelection: formatSelected, // text khi đã chọn
    escapeMarkup: m => m
  });

  function formatUser(item){
    if(item.loading) return item.text;
    const name  = item.fullname ?? item.text ?? '';
    const email = item.email ? `<div class="text-muted small">${item.email}</div>` : '';
    return `<div><div class="fw-semibold">${name}</div>${email}</div>`;
  }
  function formatSelected(item){
    if(!item.id) return item.text;
    const name  = item.fullname ?? item.text ?? '';
    const email = item.email ? ` — ${item.email}` : '';
    return `${name}${email}`;
  }

  @if(!empty($selectedUser))
    // Giữ lại lựa chọn sau khi validate fail
    const opt = new Option('{{ $selectedUser->fullname." — ".$selectedUser->email }}', '{{ $selectedUser->id }}', true, true);
    $el.append(opt).trigger('change');
  @endif
});
</script>



@endsection