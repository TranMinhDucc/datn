@extends('layouts.admin')

@section('title', 'Chỉnh sửa FAQ')

@section('content')
<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">

        <div class="card card-flush">
            <!--begin::Card header-->
            <div class="card-header pt-8">
                <div class="card-title">
                    <h2 class="fw-bold">Chỉnh sửa câu hỏi</h2>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body">
                <form action="{{ route('admin.faq.store') }}" method="POST">
                    @csrf
                    <!--begin::Input group-->
                    <div class="mb-10">
                        <label class="form-label fw-semibold">Câu hỏi</label>
                        <input type="text" name="question" class="form-control form-control-solid" value="{{ old('question',$faq->question) }}" required>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="mb-10">
                        <label class="form-label fw-semibold">Trả lời</label>
                        <textarea name="answer" class="form-control form-control-solid" rows="6" required>{{ old('answer',$faq->answer) }}</textarea>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Actions-->
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.faq.index') }}" class="btn btn-light btn-active-light-primary me-2">Quay lại</a>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                    <!--end::Actions-->
                </form>
            </div>
            <!--end::Card body-->
        </div>
    </div>
</div>
<!--end::Content-->
</div>
@endsection