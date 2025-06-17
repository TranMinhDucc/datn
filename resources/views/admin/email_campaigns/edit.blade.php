@extends('layouts.admin')
@section('title', 'Chỉnh sửa chiến dịch Email')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Chỉnh sửa chiến dịch Email</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.email_campaigns.update', $campaign->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="campaign_name" class="form-label">Tên chiến dịch *</label>
                        <input type="text" class="form-control" id="campaign_name" name="campaign_name"
                            value="{{ $campaign->campaign_name }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email_subject" class="form-label">Tiêu đề Email *</label>
                        <input type="text" class="form-control" id="email_subject" name="email_subject"
                            value="{{ $campaign->email_subject }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email_body" class="form-label">Nội dung Email *</label>
                        <textarea name="email_body" class="form-control" id="email_body" rows="8"
                            required>{{ $campaign->email_body }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="cc" class="form-label">CC (nếu có)</label>
                        <input type="text" class="form-control" id="cc" name="cc" value="{{ $campaign->cc }}">
                    </div>

                    <div class="mb-3">
                        <label for="bcc" class="form-label">BCC (nếu có)</label>
                        <input type="text" class="form-control" id="bcc" name="bcc" value="{{ $campaign->bcc }}">
                    </div>

                    <div class="text-end">
                        <a href="{{ route('admin.email_campaigns.index') }}" class="btn btn-secondary">Hủy</a>
                        <button type="submit" class="btn btn-success">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection