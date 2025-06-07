@extends('layouts.admin')

@section('title', 'Thêm mới danh mục')
@section('content')
  <!--begin::Content wrapper-->
                <div class="d-flex flex-column flex-column-fluid">
                                            
<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar  py-3 py-lg-6 " 
     
         >

            <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container  container-xxl d-flex flex-stack ">
            
    

<!--begin::Page title-->
<div  class="page-title d-flex flex-column justify-content-center flex-wrap me-3 ">
    <!--begin::Title-->
    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
        Thêm danh mục sản phẩm
            </h1>
    <!--end::Title-->
  @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
            
        <!--begin::Breadcrumb-->
       
    </div>
<!--end::Page title-->
<!--begin::Actions-->
<div class="d-flex align-items-center gap-2 gap-lg-3">
            <!--begin::Filter menu-->
        <div class="m-0">
            <!--begin::Menu toggle-->
            
            <!--end::Menu toggle-->
            
            

<!--begin::Menu 1-->
<div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_683db6ea12dcf">
    <!--begin::Header-->
    <div class="px-7 py-5">
        <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
    </div>
    <!--end::Header-->

    <!--begin::Menu separator-->
    <div class="separator border-gray-200"></div>
    <!--end::Menu separator-->
    

    <!--begin::Form-->
    <div class="px-7 py-5">
        <!--begin::Input group-->
     <div class="mb-10">
    <!--begin::Label-->
    <label class="form-label fw-semibold">Trạng thái:</label>
    <!--end::Label-->

    <!--begin::Input-->
    <select name="status" class="form-select form-select-solid" data-placeholder="Chọn trạng thái">
        <option value="1" {{ isset($category) && $category->status == 1 ? 'selected' : '' }}>Hiện</option>
        <option value="0" {{ isset($category) && $category->status == 0 ? 'selected' : '' }}>Ẩn</option>
    </select>
    <!--end::Input-->
</div>

        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="mb-10">
            <!--begin::Label-->
            <label class="form-label fw-semibold">Member Type:</label>
            <!--end::Label-->

            <!--begin::Options-->
            <div class="d-flex">
                <!--begin::Options-->    
                <label class="form-check form-check-sm form-check-custom form-check-solid me-5">
                    <input class="form-check-input" type="checkbox" value="1"/>
                    <span class="form-check-label">
                        Author
                    </span>
                </label>
                <!--end::Options-->    

                <!--begin::Options-->    
                <label class="form-check form-check-sm form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" value="2" checked="checked"/>
                    <span class="form-check-label">
                        Customer
                    </span>
                </label>
                <!--end::Options-->    
            </div>        
            <!--end::Options-->    
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="mb-10">
            <!--begin::Label-->
            <label class="form-label fw-semibold">Notifications:</label>
            <!--end::Label-->

            <!--begin::Switch-->
            <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                <input class="form-check-input" type="checkbox" value="" name="notifications" checked />
                <label class="form-check-label">
                    Enabled
                </label>
            </div>
            <!--end::Switch-->
        </div>
        <!--end::Input group-->

        <!--begin::Actions-->
        <div class="d-flex justify-content-end">
            <button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Reset</button>

            <button type="submit" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">Apply</button>
        </div>
        <!--end::Actions-->
    </div>
    <!--end::Form-->
</div>
<!--end::Menu 1-->        </div>
        <!--end::Filter menu-->
    
    
    <!--begin::Secondary button-->
        <!--end::Secondary button-->
    
    <!--begin::Primary button-->
          
        <!--end::Primary button-->
</div>
<!--end::Actions-->
        </div>
        <!--end::Toolbar container-->
    </div>
<!--end::Toolbar-->                                        
       @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif             
<!--begin::Content-->
<div id="kt_app_content" class="app-content  flex-column-fluid " >
    
           
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container  container-xxl ">
            
           <form id="kt_ecommerce_add_category_form" class="form d-flex flex-column flex-lg-row" action="{{ route('admin.categories.update' , $category->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
                @method('PUT')

    <!--begin::Aside column-->
    <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
        <!--begin::Thumbnail settings-->
<div class="card card-flush py-4">
    <!--begin::Card header-->
    <div class="card-header">
        <!--begin::Card title-->
        <div class="card-title">
            <h2>Thumbnail</h2>
        </div>
        <!--end::Card title-->
    </div>
    <!--end::Card header-->

    <!--begin::Card body-->
    <div class="card-body text-center pt-0">
        <!--begin::Image input-->
                    <!--begin::Image input placeholder-->
            <style>
                .image-input-placeholder {
                    background-image: url('../../../assets/media/svg/files/blank-image.svg');
                }

                [data-bs-theme="dark"] .image-input-placeholder {
                    background-image: url('../../../assets/media/svg/files/blank-image-dark.svg');
                }                
            </style>
            <!--end::Image input placeholder-->
        
        <!--begin::Image input-->
        <div  class="image-input image-input-outline {{ $category->icon  ? '' : 'image-input-empty' }}" 
        data-kt-image-input="true" 
        style="background-image: url('{{ $category->icon ? asset('storage/' . $category->icon) : '' }}')">
            <!--begin::Preview existing avatar-->
                            <div  class="image-input-wrapper w-150px h-150px" 
            style="background-image: url('{{ $category->icon ? asset('storage/' . $category->icon) : '/assets/media/svg/files/blank-image.svg' }}')"></div>
                        <!--end::Preview existing avatar-->

            <!--begin::Label-->
            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                <!--begin::Icon-->
                <i class="fa-solid fa-image"><span class="path1"></span><span class="path2"></span></i>                <!--end::Icon-->

                <!--begin::Inputs-->
                <input type="file" name="icon" accept=".png, .jpg, .jpeg" />
                <input type="hidden" name="img_old "  value="{{ $category->icon }}" />
                <!--end::Inputs-->
            </label>
            <!--end::Label-->

            <!--begin::Cancel-->
            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
           <i class="fa-solid fa-image"><span class="path1"></span><span class="path2"></span></i>            </span>
            <!--end::Cancel-->

            <!--begin::Remove-->
            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
               <i class="fa-solid fa-trash"><span class="path1"></span><span class="path2"></span></i>            </span>
            <!--end::Remove-->
        </div>
        <!--end::Image input-->

        <!--begin::Description-->
        <div class="text-muted fs-7">Set the category thumbnail image. Only *.png, *.jpg and *.jpeg image files are accepted</div>
        <!--end::Description-->
    </div>
    <!--end::Card body-->
</div>
<!--end::Thumbnail settings-->
        <!--begin::Status-->
<div class="card card-flush py-4">
    <!--begin::Card header-->
    <div class="card-header">
        <!--begin::Card title-->
        <div class="card-title">
            <h2>Trạng thái</h2>
        </div>
        <!--end::Card title-->

        <!--begin::Card toolbar-->
        <div class="card-toolbar">
            <div class="rounded-circle {{ old('status', $category->status ?? 1) == 1 ? 'bg-success' : 'bg-danger' }} w-15px h-15px" id="kt_ecommerce_add_category_status"></div>
        </div>
        <!--end::Card toolbar-->
    </div>
    <!--end::Card header-->

    <!--begin::Card body-->
    <div class="card-body pt-0">
        <!--begin::Select-->
        <select name="status" class="form-select mb-2" data-control="select2" data-hide-search="true" data-placeholder="Chọn trạng thái" id="kt_ecommerce_add_category_status_select">
            <option></option>
            <option value="1" {{ old('status', $category->status ?? 1) == 1 ? 'selected' : '' }}>Hiện</option>
            <option value="0" {{ old('status', $category->status ?? 1) == 0 ? 'selected' : '' }}>Ẩn</option>
        </select>
        <!--end::Select-->

        <!--begin::Description-->
        <div class="text-muted fs-7">Chọn trạng thái hiển thị danh mục.</div>
        <!--end::Description-->
    </div>
    <!--end::Card body-->
</div>

<!--end::Status-->
        <!--begin::Template settings-->
{{-- <div class="card card-flush py-4">
    <!--begin::Card header-->
    <div class="card-header">
        <!--begin::Card title-->
        <div class="card-title">
            <h2>Store Template</h2>
        </div>
        <!--end::Card title-->
    </div>
    <!--end::Card header-->

    <!--begin::Card body-->
    <div class="card-body pt-0">
        <!--begin::Select store template-->
        <label for="kt_ecommerce_add_category_store_template" class="form-label">Select a store template</label>
        <!--end::Select store template-->

        <!--begin::Select2-->
        <select class="form-select mb-2" data-control="select2" data-hide-search="true" data-placeholder="Select an option" id="kt_ecommerce_add_category_store_template">
            <option></option>
            <option value="default" selected>Default template</option>
            <option value="electronics">Electronics</option>
            <option value="office">Office stationary</option>
            <option value="fashion">Fashion</option>
        </select>
        <!--end::Select2-->

        <!--begin::Description-->
        <div class="text-muted fs-7">Assign a template from your current theme to define how the category products are displayed.</div>
        <!--end::Description-->
    </div>
    <!--end::Card body-->
</div> --}}
<!--end::Template settings-->    </div>
    <!--end::Aside column-->

    <!--begin::Main column-->
    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
        <!--begin::General options-->
<div class="card card-flush py-4">
    <!--begin::Card header-->
    <div class="card-header">
       
    </div>
    <!--end::Card header-->

    <!--begin::Card body-->
    <div class="card-body pt-0">
        <!--begin::Input group-->
   

        <div class="mb-10 fv-row">
            <!--begin::Label-->
            <label class="required form-label">Tên danh mục </label>
            <!--end::Label-->

            <!--begin::Input-->
                        <input type="text" name="name" class="form-control mb-2" placeholder=" Nhập tên danh mục " value="{{ old('name' , $category->name) }}"/>
                        @error('name')
        <div class="text-danger">{{ $message }}</div>
    @enderror
            <!--end::Input-->

            <!--begin::Description-->
           
            <!--end::Description-->
        </div>
        <!--end::Input group-->
   
        <!--begin::Input group-->
   <div class="mb-10 fv-row">
       
            <label class="required form-label">Nội dung  </label>
           
                        <input type="text" name="description" class="form-control mb-2" placeholder="Nhập nội dung" value="{{ old('description' , $category->description) }}"/>
           
        </div>
         
        <div class="mb-10 fv-row">
    <label class="form-label">Đường dẫn (tự sinh nếu bỏ trống)</label>
    <input type="text" name="slug" class="form-control mb-2" placeholder="Tự động tạo từ tên nếu để trống" value="{{ old('slug' , $category->slug) }}" />
    @error('slug')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
         

        <!--end::Input group-->
    </div>
    <!--end::Card header-->
</div>

<!--end::Automation-->
        <div class="d-flex justify-content-end">
             <!--begin::Button-->
             
            <!--end::Button-->

            <!--begin::Button-->
            <button type="submit" id="kt_ecommerce_add_category_submit" class="btn btn-primary">
                <span class="indicator-label">
                 Sửa 
                </span>
                {{-- <span class="indicator-progress">
                    Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span> --}}
            </button>
            <!--end::Button-->
        </div>
    </div>
    <!--end::Main column-->
</form>        </div>
        <!--end::Content container-->
    </div>
<!--end::Content-->	

                                    </div>
                                    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('content');
</script>
                <!--end::Content wrapper-->
@endsection