@extends('layouts.client') {{-- hoặc layouts bạn đang dùng --}}
@section('content')
<div style="max-width:980px;margin:16px auto;padding:0 12px;">
  {{-- flash + errors --}}
  @if(session('success'))
  <div style="margin-bottom:12px;border:1px solid #d9f0d9;background:#f3fbf3;color:#2e7d32;padding:10px;border-radius:12px;">
    {{ session('success') }}
  </div>
  @endif
  @if ($errors->any())
  <div style="margin-bottom:12px;border:1px solid #fde7e7;background:#fff6f6;color:#c62828;padding:10px;border-radius:12px;">
    <b>Lỗi:</b>
    <ul style="margin:6px 0 0 18px;">
      @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
    </ul>
  </div>
  @endif

  <h2 style="margin:0 0 12px;">Tạo Phiếu Hỗ Trợ</h2>

  <div style="background:#fff;border-radius:16px;padding:16px;box-shadow:0 10px 24px rgba(0,0,0,.06);">
    <form id="ticket-form" method="POST" action="{{ route('support.tickets.store') }}" enctype="multipart/form-data">
      @csrf

      {{-- Dòng 1: Tiêu đề + Ưu tiên --}}
      <div style="display:grid;grid-template-columns:2fr 1fr;gap:12px;">
        <div>
          <label style="font-weight:700;">Tiêu đề *</label>
          <input name="subject" value="{{ old('subject') }}" required maxlength="120"
            style="width:100%;margin-top:6px;padding:10px;border:1px solid #eee;border-radius:10px;">
        </div>
        <div>
          <label style="font-weight:700;">Ưu tiên *</label>
          <select name="priority" required
            style="width:100%;margin-top:6px;padding:10px;border:1px solid #eee;border-radius:10px;">
            <option value="normal" {{ old('priority')==='normal'?'selected':'' }}>Bình thường</option>
            <option value="high" {{ old('priority')==='high'?'selected':'' }}>Cao</option>
            <option value="urgent" {{ old('priority')==='urgent'?'selected':'' }}>Khẩn cấp</option>
          </select>
        </div>
      </div>

      {{-- Dòng 2: Nhóm vấn đề + Mã đơn --}}
      {{-- Hàng: Nhóm vấn đề + Mã đơn --}}
<div id="rowCatOrder" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:10px;">
  <div id="catWrap">
    <label style="font-weight:700;">Nhóm vấn đề *</label>
    <select name="category" id="category"
            style="width:100%;margin-top:6px;padding:10px;border:1px solid #eee;border-radius:10px;" required>
      <option value="order"   {{ old('category')==='order'?'selected':'' }}>Đơn hàng & vận chuyển</option>
      <option value="product" {{ old('category')==='product'?'selected':'' }}>Sản phẩm & chất lượng</option>
      <option value="payment" {{ old('category')==='payment'?'selected':'' }}>Thanh toán & hoá đơn</option>
      <option value="account" {{ old('category')==='account'?'selected':'' }}>Tài khoản & đăng nhập</option>
      <option value="other"   {{ old('category')==='other'?'selected':'' }}>Khác</option>
    </select>
  </div>

  <div id="orderCodeWrap">
    <label style="font-weight:700;">Mã đơn (tuỳ chọn)</label>
    <input list="order-codes" name="order_code" id="order_code" value="{{ old('order_code') }}"
           style="width:100%;margin-top:6px;padding:10px;border:1px solid #eee;border-radius:10px;">
    <datalist id="order-codes">
      @foreach(($orders ?? []) as $o)
        <option value="{{ $o->code }}">#{{ $o->code }} — {{ \Illuminate\Support\Str::title($o->status) }} ({{ $o->created_at->format('d/m') }})</option>
      @endforeach
    </datalist>
  </div>
</div>


      {{-- Dòng 3: Mã vận đơn --}}
      <div style="margin-top:10px;">
        <label style="font-weight:700;">Mã vận đơn (nếu có)</label>
        <input name="carrier_code" value="{{ old('carrier_code') }}"
          style="width:100%;margin-top:6px;padding:10px;border:1px solid #eee;border-radius:10px;">
      </div>

      {{-- Mô tả --}}
      <div style="margin-top:10px;">
        <label style="font-weight:700;">Mô tả chi tiết *</label>
        <textarea name="body" required minlength="20" rows="6"
          style="width:100%;margin-top:6px;padding:10px;border:1px solid #eee;border-radius:10px;">{{ old('body') }}</textarea>
      </div>

      {{-- File --}}
      <div style="margin-top:12px;">
        <label style="font-weight:700;">Đính kèm (tối đa 5 tệp, 5MB/tệp)</label>
        <input type="file" id="attachments" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.webp,.mp4,.pdf"
          style="display:block;margin-top:6px;">
        {{-- vùng preview --}}
        <div id="attachments-previews"
          style="display:flex;flex-wrap:wrap;gap:10px;margin-top:8px;"></div>
        {{-- lỗi client-side (nếu có) --}}
        <div id="attachments-error" style="color:#c62828;margin-top:6px;"></div>
      </div>



      {{-- Nút --}}
      <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:14px;">
        <button type="reset" style="padding:10px 14px;border:1px solid #eee;border-radius:10px;background:#fafafa;cursor:pointer;">Làm lại</button>
        <button type="submit" style="padding:10px 16px;border:0;border-radius:10px;background:#c69c6d;color:#fff;font-weight:800;cursor:pointer;">Gửi phiếu</button>
      </div>
    </form>

  </div>
</div>


<script>
(function attachPreview(inputSel, previewSel, errorSel, opts) {
  const input   = document.querySelector(inputSel);
  const wrap    = document.querySelector(previewSel);
  const errBox  = document.querySelector(errorSel);
  if (!input || !wrap) return;

  const MAX_FILES = (opts && opts.maxFiles) || 5;
  const MAX_SIZE  = (opts && opts.maxSize)  || 5 * 1024 * 1024; // 5MB
  let files = []; // mảng quản lý nội bộ

  function fileId(f){ return [f.name, f.size, f.lastModified].join('|'); }

  function render(){
    // clear preview
    wrap.innerHTML = '';
    errBox && (errBox.textContent = '');

    // rebuild FileList cho <input>
    const dt = new DataTransfer();
    files.forEach(f => dt.items.add(f));
    input.files = dt.files;

    // vẽ thẻ preview
    files.forEach(f => {
      const card = document.createElement('div');
      card.style.cssText = `
        position:relative;width:110px;height:110px;border:1px solid #eee;border-radius:10px;
        overflow:hidden;background:#fafafa;display:flex;align-items:center;justify-content:center;
      `;

      if (f.type.startsWith('image/')) {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(f);
        img.onload = () => URL.revokeObjectURL(img.src);
        img.style.cssText = 'width:100%;height:100%;object-fit:cover;';
        card.appendChild(img);
      } else {
        const box = document.createElement('div');
        box.style.cssText = 'text-align:center;padding:6px;font-size:12px;color:#444;';
        box.innerHTML = '📄<br>'+ (f.name.length>16? f.name.slice(0,13)+'…' : f.name);
        card.appendChild(box);
      }

      const close = document.createElement('button');
      close.type = 'button';
      close.textContent = '×';
      close.title = 'Xoá';
      close.style.cssText = `
        position:absolute;top:4px;right:4px;width:22px;height:22px;border:0;border-radius:50%;
        background:#00000080;color:#fff;cursor:pointer;line-height:22px;text-align:center;
      `;
      close.onclick = () => {
        files = files.filter(x => fileId(x) !== fileId(f));
        render();
      };
      card.appendChild(close);

      wrap.appendChild(card);
    });
  }

  input.addEventListener('change', (e) => {
    errBox && (errBox.textContent = '');
    const picked = Array.from(e.target.files);

    // cộng dồn (bỏ trùng), kiểm tra giới hạn
    for (const f of picked) {
      const id = fileId(f);
      if (files.some(x => fileId(x) === id)) continue;       // tránh trùng
      if (files.length >= MAX_FILES) {                        // quá số file
        errBox && (errBox.textContent = `Chỉ chọn tối đa ${MAX_FILES} tệp.`);
        break;
      }
      if (f.size > MAX_SIZE) {                                // quá dung lượng
        errBox && (errBox.textContent = `Tệp "${f.name}" vượt quá ${Math.round(MAX_SIZE/1024/1024)}MB.`);
        continue;
      }
      files.push(f);
    }
    render();

    // reset input.value để có thể chọn lại cùng file sau khi xoá
    input.value = '';
  });

  // lần đầu
  render();
})('#attachments', '#attachments-previews', '#attachments-error', {maxFiles:5, maxSize: 5*1024*1024});
</script>


<script>
(function () {
  const cat   = document.getElementById('category');
  const row   = document.getElementById('rowCatOrder');
  const catCol= document.getElementById('catWrap');
  const wrap  = document.getElementById('orderCodeWrap');
  const input = document.getElementById('order_code');

  function toggleOrderCode() {
    const v = (cat.value || '').toLowerCase();
    const hide = (v === 'account' || v === 'other'); // ẩn khi tài khoản/khác
    if (hide) {
      wrap.style.display = 'none';
      row.style.gridTemplateColumns = '1fr';  // hàng còn 1 cột, cột trái full width
      catCol.style.gridColumn = '1 / -1';

      input.value = '';
      input.disabled = true;                  // tránh gửi giá trị cũ
    } else {
      wrap.style.display = '';
      row.style.gridTemplateColumns = '1fr 1fr';
      catCol.style.gridColumn = '';
      input.disabled = false;
    }
  }

  cat.addEventListener('change', toggleOrderCode);
  toggleOrderCode(); // khởi tạo theo giá trị hiện tại
})();
</script>

@endsection