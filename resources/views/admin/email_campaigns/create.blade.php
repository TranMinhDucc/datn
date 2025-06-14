@extends('layouts.admin')
@section('title', 'Tạo chiến dịch Email')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" />
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tạo chiến dịch Email</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.email_campaigns.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="campaign_name" class="form-label">Tên chiến dịch *</label>
                        <input type="text" class="form-control" id="campaign_name" name="campaign_name" required>
                    </div>

                    <div class="mb-3">
                        <label for="email_subject" class="form-label">Tiêu đề Email *</label>
                        <input type="text" class="form-control" id="email_subject" name="email_subject" required>
                    </div>

                    <div class="mb-3">
                        <label for="email_body" class="form-label">Nội dung Email *</label>
                        <textarea name="email_body" id="email_body" required></textarea>


                    </div>

                    <div class="mb-3">
                        <label for="cc" class="form-label">CC (nếu có)</label>
                        <input type="text" class="form-control" id="cc" name="cc">
                    </div>

                    <div class="mb-3">
                        <label for="bcc" class="form-label">BCC (nếu có)</label>
                        <input type="text" class="form-control" id="bcc" name="bcc">
                    </div>


                    <div class="mb-3">

                        <label for="users" class="form-label">Người nhận *</label>
                        <div class="mb-2">
                            <button type="button" id="selectAllBtn" class="btn btn-outline-primary btn-sm">
                                Chọn tất cả người nhận
                            </button>
                        </div>
                        <input type="text" id="users" name="users" class="form-control"
                            placeholder="Nhập hoặc chọn người nhận..." required>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('admin.email_campaigns.index') }}" class="btn btn-secondary">Quay lại</a>
                        <button type="submit" class="btn btn-primary">Tạo chiến dịch</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

{{-- @section('scripts')
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script>
    setTimeout(() => {
        const input = document.querySelector('#users');
        if (!input) {
            console.warn("❌ Không tìm thấy #users");
            return;
        }

        const whitelist = [
            "tranthi6725@gmail.com",
            "cuthi090@gmail.com"
        ];

        const tagify = new Tagify(input, {
            whitelist: whitelist,
            dropdown: {
                enabled: 0,              // show on focus (not require typing)
                closeOnSelect: false,    // giữ dropdown mở khi chọn
                maxItems: 20,
                highlightFirst: true
            }
        });

        // Show dropdown when input is focused
        input.addEventListener('focus', () => {
            tagify.dropdown.show.call(tagify);
        });

        console.log("✅ Tagify đã khởi tạo và auto hiển thị gợi ý email khi click.");
    }, 300);
</script>
@endsection --}}
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('email_body', {
            height: 300,
            language: 'vi',
            // ✅ Ngăn CKEditor mã hóa HTML
            entities: false,
            basicEntities: false,
            htmlEncodeOutput: false,
            // ✅ Toolbar đầy đủ
            removeButtons: '',
            extraPlugins: 'colorbutton,font,justify,print,format,table,uploadimage',
            toolbarGroups: [
                { name: 'clipboard', groups: ['clipboard', 'undo'] },
                { name: 'editing', groups: ['find', 'selection', 'spellchecker'] },
                { name: 'insert' },
                { name: 'tools' },
                { name: 'document', groups: ['mode', 'document', 'doctools'] },
                '/',
                { name: 'basicstyles', groups: ['basicstyles', 'cleanup'] },
                { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align'] },
                { name: 'styles' },
                { name: 'colors' },
                { name: 'about' }
            ],
            on: {
                instanceReady: function (evt) {
                    const notification = evt.editor.container.findOne('.cke_notification_warning');
                    if (notification) notification.hide(); // Ẩn cảnh báo version
                }
            }
        });
    </script>



    <script>
        let tagify;

        setTimeout(() => {
            const input = document.querySelector('#users');
            if (!input) return;

            // Fetch danh sách email từ backend
            fetch('{{ route('admin.email_campaigns.recipients') }}')
                .then(res => res.json())
                .then(whitelist => {
                    tagify = new Tagify(input, {
                        whitelist: whitelist,
                        dropdown: {
                            enabled: 0,
                            closeOnSelect: false,
                            maxItems: 20,
                            highlightFirst: true
                        }
                    });

                    // Hiển thị dropdown khi focus
                    input.addEventListener('focus', () => {
                        tagify.dropdown.show.call(tagify);
                    });

                    // Gắn nút "Chọn tất cả"
                    document.getElementById('selectAllBtn').addEventListener('click', () => {
                        tagify.removeAllTags();
                        tagify.addTags(whitelist);
                    });

                    console.log("✅ Tagify và tính năng chọn tất cả đã sẵn sàng.");
                })
                .catch(err => console.error("❌ Lỗi khi tải danh sách người nhận:", err));
        }, 300);
    </script>
@endsection