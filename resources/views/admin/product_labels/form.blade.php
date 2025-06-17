<div class="mb-3">
    <label for="product_id" class="form-label">Sản phẩm</label>
    <select name="product_id" id="product_id" class="form-select">
        @foreach($products as $product)
            <option value="{{ $product->id }}" {{ isset($label) && $label->product_id == $product->id ? 'selected' : '' }}>
                {{ $product->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="position" class="form-label">Vị trí</label>
    <input type="text" name="position" id="position" class="form-control" value="{{ old('position', $label->position ?? '') }}">
</div>

<div class="mb-3">
    <label for="image" class="form-label">Hình ảnh</label>
    <input type="file" name="image" id="image" class="form-control">
    @if (isset($label) && $label->image)
        <img src="{{ asset('storage/' . $label->image) }}" width="100" class="mt-2">
    @endif
</div>
