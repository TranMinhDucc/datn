@csrf
<div class="row">
  <div class="col-md-6">
    <label class="form-label">Title small</label>
    <input name="title_small" class="form-control" value="{{ old('title_small', $item->title_small ?? '') }}">
  </div>
  <div class="col-md-6">
    <label class="form-label">Title main</label>
    <input name="title_main" class="form-control" value="{{ old('title_main', $item->title_main ?? '') }}">
  </div>
  <div class="col-md-6 mt-2">
    <label class="form-label">Subtitle</label>
    <input name="subtitle" class="form-control" value="{{ old('subtitle', $item->subtitle ?? '') }}">
  </div>
  <div class="col-md-3 mt-2">
    <label class="form-label">Button text</label>
    <input name="btn_text" class="form-control" value="{{ old('btn_text', $item->btn_text ?? 'Shop Collection') }}">
  </div>
  <div class="col-md-3 mt-2">
    <label class="form-label">Button URL</label>
    <input name="btn_url" class="form-control" value="{{ old('btn_url', $item->btn_url ?? '') }}">
  </div>

  <div class="col-md-6 mt-3">
    <label class="form-label">Left Image</label>
    <input type="file" name="left_image" class="form-control">
    @if(!empty($item?->left_image))
      <img src="{{ asset('storage/'.$item->left_image) }}" class="img-fluid mt-2" style="max-height:120px">
    @endif
  </div>
  <div class="col-md-6 mt-3">
    <label class="form-label">Right Image</label>
    <input type="file" name="right_image" class="form-control">
    @if(!empty($item?->right_image))
      <img src="{{ asset('storage/'.$item->right_image) }}" class="img-fluid mt-2" style="max-height:120px">
    @endif
  </div>

  <div class="col-md-12 mt-3">
    <label class="form-label">Side title</label>
    <input name="side_title" class="form-control" value="{{ old('side_title', $item->side_title ?? '') }}">
  </div>
  <div class="col-md-6 mt-2">
    <label class="form-label">Offer title</label>
    <input name="side_offer_title" class="form-control" value="{{ old('side_offer_title', $item->side_offer_title ?? '') }}">
  </div>
  <div class="col-md-6 mt-2">
    <label class="form-label">Offer code</label>
    <input name="side_offer_code" class="form-control" value="{{ old('side_offer_code', $item->side_offer_code ?? '') }}">
  </div>
  <div class="col-md-12 mt-2">
    <label class="form-label">Offer description</label>
    <textarea name="side_offer_desc" class="form-control" rows="3">{{ old('side_offer_desc', $item->side_offer_desc ?? '') }}</textarea>
  </div>

  <div class="col-md-3 mt-3">
    <label class="form-label">Active?</label>
    <select name="is_active" class="form-select">
      <option value="1" @selected(old('is_active', $item->is_active ?? 1)==1)>Yes</option>
      <option value="0" @selected(old('is_active', $item->is_active ?? 1)==0)>No</option>
    </select>
  </div>
</div>

<button class="btn btn-primary mt-3">Save</button>
