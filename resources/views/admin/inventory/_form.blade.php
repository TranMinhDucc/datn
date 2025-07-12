<form action="{{ route('admin.inventory.adjust') }}" method="POST" class="d-flex gap-1">
    @csrf
    <input type="hidden" name="type_target" value="{{ $type }}">
    <input type="hidden" name="id" value="{{ $id }}">
    <select name="type" class="form-select form-select-sm" required>
        <option value="import">Nhập kho</option>
        <option value="export">Xuất kho</option>
        <option value="return">Hoàn hàng</option>
        <option value="adjust">Điều chỉnh</option>
    </select>
    <input type="number" name="quantity" class="form-control form-control-sm" placeholder="Số lượng" required>
    <input type="text" name="note" class="form-control form-control-sm" placeholder="Ghi chú">
    <button type="submit" class="btn btn-sm btn-primary">OK</button>
</form>
