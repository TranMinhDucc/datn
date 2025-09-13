@extends('layouts.admin')

@section('content')
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    <i class="ri-robot-line me-2 text-primary fs-2"></i> AI Chat Assistant
                </h1>
            </div>

            <div class="d-flex align-items-center gap-2">
                <!-- Model Selection Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-sm btn-light dropdown-toggle d-flex align-items-center" type="button"
                        id="modelDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ri-cpu-line me-2"></i>
                        <span id="currentModelSpan">gpt-4o-mini</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="modelDropdown">
                        <li><a class="dropdown-item model-select-item" href="#" data-model="gpt-4o-mini">GPT-4o
                                Mini</a></li>
                        <li><a class="dropdown-item model-select-item" href="#" data-model="gpt-4o">GPT-4o</a></li>
                        <li><a class="dropdown-item model-select-item" href="#" data-model="gpt-3.5-turbo">GPT-3.5
                                Turbo</a></li>
                    </ul>
                </div>

                <!-- Memory Toggle -->
                <div id="memoryToggle" class="btn btn-sm btn-success d-flex align-items-center memory-toggle">
                    <i class="ri-brain-line me-2"></i>
                    <span id="memoryStatus">Memory ON</span>
                </div>

                <!-- Clear Chat Button -->
                <button id="clearChatBtn" class="btn btn-sm btn-danger d-flex align-items-center">
                    <i class="ri-delete-bin-line me-2"></i>
                    Xóa chat
                </button>
            </div>
        </div>
    </div>

    <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="card card-flush">
            <!-- Chat body -->
            <div class="card-body" style="height: 70vh; overflow-y: auto; padding: 20px;">
                <div id="chatMessages" class="d-flex flex-column" style="min-height: 100%;">

                    <!-- Welcome Message -->
                    <div class="d-flex justify-content-start mb-5">
                        <div class="d-flex flex-column align-items-start">
                            <div class="p-4 bg-white rounded shadow-sm" style="max-width: 600px;">
                                <h5>🚀 Chào mừng bạn đến với AI Chat Assistant!</h5>

                                <div class="mt-3">
                                    <strong>📊 THỐNG KÊ & PHÂN TÍCH DỮ LIỆU:</strong><br>
                                    Tôi có thể trực tiếp truy vấn database và phân tích dữ liệu của bạn:
                                </div>

                                <ul class="mt-2 mb-0">
                                    <li>💰 <b>Doanh thu:</b> "doanh thu hôm nay", "lợi nhuận tháng này"</li>
                                    <li>👥 <b>Người dùng:</b> "user nào nạp tiền nhiều nhất", "top 10 khách hàng VIP"</li>
                                    <li>🛒 <b>Đơn hàng:</b> "đơn hàng hôm nay", "dịch vụ bán chạy nhất"</li>
                                    <li>🔄 <b>Giao dịch:</b> "lịch sử nạp tiền của User", "nhật ký số dư user abc"</li>
                                </ul>

                                <div class="mt-3">🎯 <b>TÍNH NĂNG KHÁC:</b> Tư vấn kỹ thuật, debug code, chiến lược kinh
                                    doanh</div>
                                <div class="mt-2">🧠 <b>Memory AI:</b> Tôi sẽ nhớ 5 cuộc trò chuyện gần nhất của bạn.
                                </div>

                                <div class="mt-3 p-3 bg-light rounded">
                                    <strong>Hãy thử hỏi:</strong> "doanh thu hôm nay" hoặc "user nào nạp tiền nhiều nhất"
                                </div>
                            </div>
                            <small class="text-muted mt-2">08:41 13/09/2025</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat input -->
            <div class="card-footer border-top p-3 bg-white">
                <div class="d-flex align-items-center">
                    <!-- Ô nhập tin nhắn -->
                    <div class="flex-grow-1 me-3">
                        <div class="input-group">
                            <textarea id="messageInput" class="form-control border rounded-pill px-4 py-2" rows="1"
                                placeholder="Nhập tin nhắn..." style="resize: none; overflow:hidden;"></textarea>
                        </div>
                    </div>

                    <!-- Nút gửi -->
                    <button id="sendButton"
                        class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 42px; height: 42px;">
                        <i class="ri-send-plane-line fs-5"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        #chatMessages {
            scroll-behavior: smooth;
        }

        #messageInput {
            max-height: 120px;
        }

        .chat-bubble {
            padding: 12px 16px;
            border-radius: 16px;
            max-width: 70%;
            word-wrap: break-word;
        }

        .chat-user {
            background: #0d6efd;
            color: #fff;
            border-bottom-right-radius: 0;
        }

        .chat-ai {
            background: #f1f1f1;
            color: #333;
            border-bottom-left-radius: 0;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sendButton = document.getElementById('sendButton');
            const messageInput = document.getElementById('messageInput');
            const chatMessages = document.getElementById('chatMessages');
            const clearChatBtn = document.getElementById('clearChatBtn');

            function addMessage(message, sender = 'user') {
                const wrapper = document.createElement('div');
                wrapper.className =
                    `d-flex ${sender === 'user' ? 'justify-content-end' : 'justify-content-start'} mb-3`;

                const bubble = document.createElement('div');
                bubble.className = `chat-bubble ${sender === 'user' ? 'chat-user' : 'chat-ai'}`;
                bubble.innerHTML = message;

                wrapper.appendChild(bubble);
                chatMessages.appendChild(wrapper);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            sendButton.addEventListener('click', function() {
                const msg = messageInput.value.trim();
                if (!msg) return;

                addMessage(msg, 'user');
                messageInput.value = '';

                fetch("{{ route('admin.aichat.ask') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            message: msg
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        addMessage(data.answer, 'ai');
                    })
                    .catch(() => {
                        addMessage("⚠️ Lỗi kết nối server!", 'ai');
                    });
            });

            // Clear chat
            clearChatBtn.addEventListener('click', function() {
                if (confirm("Bạn có chắc chắn muốn xóa toàn bộ cuộc trò chuyện?")) {
                    chatMessages.innerHTML = '';
                }
            });
        });
    </script>
@endsection
