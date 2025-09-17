@extends('layouts.admin')

@section('content')
    <!-- Header với hiệu ứng gradient -->
    <div class="chat-header">
        <div class="container-xxl">
            <div class="header-content">
                <div class="header-info">
                    <div class="ai-avatar">
                        <i class="fa-brands fa-airbnb"></i>
                    </div>
                    <div class="header-text">
                        <h1 class="chat-title">AI Assistant</h1>
                        <p class="chat-subtitle">Trợ lý thông minh cho quản trị</p>
                    </div>
                </div>

                <div class="header-controls">
                    <!-- Model Selector -->
                    <div class="control-item">
                        <div class="dropdown">
                            <button class="control-btn" type="button" id="modelDropdown" data-bs-toggle="dropdown">
                                <i class="ri-cpu-line"></i>
                                <span id="currentModel">GPT-4o Mini</span>
                                <i class="ri-arrow-down-s-line"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item model-option" href="#" data-model="gpt-4o-mini">
                                        <i class="ri-flashlight-line"></i> GPT-4o Mini
                                    </a></li>
                                <li><a class="dropdown-item model-option" href="#" data-model="gpt-4o">
                                        <i class="ri-rocket-line"></i> GPT-4o
                                    </a></li>
                                <li><a class="dropdown-item model-option" href="#" data-model="gpt-3.5-turbo">
                                        <i class="ri-speed-line"></i> GPT-3.5 Turbo
                                    </a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Memory Toggle -->
                    <div class="control-item">
                        <button id="memoryToggle" class="control-btn memory-active">
                            <i class="ri-brain-line"></i>
                            <span>Memory</span>
                        </button>
                    </div>

                    <!-- Clear Chat -->
                    <div class="control-item">
                        <button id="clearChat" class="control-btn control-danger">
                            <i class="ri-delete-bin-line"></i>
                            <span>Clear</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Container -->
    <div class="chat-container">
        <div class="container-xxl h-100">
            <div class="chat-wrapper">
                <!-- Messages Area -->
                <div class="messages-area" id="messagesArea">
                    <div class="messages-container" id="messagesContainer">
                        <!-- Welcome Message -->
                        <div class="message-wrapper ai-message">
                            <div class="message-avatar">
                                <div class="avatar ai-avatar-small">
                                    <i class="fa-brands fa-airbnb"></i>
                                </div>
                            </div>
                            <div class="message-content">
                                <div class="message-bubble welcome-message">
                                    <div class="welcome-header">
                                        <h4>🚀 Chào mừng đến với AI Assistant Pro!</h4>
                                        <p class="welcome-subtitle">Trợ lý thông minh cho chủ shop thời trang</p>
                                    </div>

                                    <div class="welcome-features">
                                        <!-- Business Intelligence -->
                                        <div class="feature-section">
                                            <h5><i class="ri-bar-chart-line"></i> Business Intelligence</h5>
                                            <div class="feature-grid">
                                                <span class="feature-tag">dashboard tổng quan</span>
                                                <span class="feature-tag">phân tích doanh thu theo mùa</span>
                                                <span class="feature-tag">lợi nhuận theo danh mục</span>
                                                <span class="feature-tag">kpi performance</span>
                                                <span class="feature-tag">so sánh cùng kỳ năm trước</span>
                                                <span class="feature-tag">cash flow analysis</span>
                                            </div>
                                        </div>

                                        <!-- Predictive Analytics -->
                                        <div class="feature-section">
                                            <h5><i class="ri-crystal-ball-line"></i> Dự báo thông minh</h5>
                                            <div class="feature-grid">
                                                <span class="feature-tag">dự báo doanh thu tháng tới</span>
                                                <span class="feature-tag">xu hướng theo mùa</span>
                                                <span class="feature-tag">nhu cầu sản phẩm</span>
                                                <span class="feature-tag">khách hàng có nguy cơ rời bỏ</span>
                                                <span class="feature-tag">cơ hội tăng trưởng</span>
                                            </div>
                                        </div>

                                        <!-- Smart Recommendations -->
                                        <div class="feature-section">
                                            <h5><i class="ri-lightbulb-flash-line"></i> Gợi ý thông minh</h5>
                                            <div class="feature-grid">
                                                <span class="feature-tag">tối ưu giá bán</span>
                                                <span class="feature-tag">chiến lược marketing</span>
                                                <span class="feature-tag">mix sản phẩm</span>
                                                <span class="feature-tag">reorder inventory</span>
                                                <span class="feature-tag">cross-selling opportunities</span>
                                            </div>
                                        </div>

                                        <!-- Customer Analytics -->
                                        <div class="feature-section">
                                            <h5><i class="ri-user-star-line"></i> Phân tích khách hàng</h5>
                                            <div class="feature-grid">
                                                <span class="feature-tag">rfm segmentation</span>
                                                <span class="feature-tag">customer lifetime value</span>
                                                <span class="feature-tag">hành vi mua hàng</span>
                                                <span class="feature-tag">personalization insights</span>
                                                <span class="feature-tag">retention strategy</span>
                                            </div>
                                        </div>

                                        <!-- Inventory Intelligence -->
                                        <div class="feature-section">
                                            <h5><i class="ri-box-3-line"></i> Quản lý kho thông minh</h5>
                                            <div class="feature-grid">
                                                <span class="feature-tag">inventory turnover</span>
                                                <span class="feature-tag">slow moving items</span>
                                                <span class="feature-tag">reorder alerts</span>
                                                <span class="feature-tag">size distribution</span>
                                                <span class="feature-tag">dead stock analysis</span>
                                            </div>
                                        </div>

                                        <!-- Competitive Intelligence -->
                                        <div class="feature-section">
                                            <h5><i class="ri-sword-line"></i> Phân tích cạnh tranh</h5>
                                            <div class="feature-grid">
                                                <span class="feature-tag">so sánh giá competitor</span>
                                                <span class="feature-tag">gap analysis</span>
                                                <span class="feature-tag">market positioning</span>
                                                <span class="feature-tag">trend opportunities</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Advanced Quick Actions -->
                                    <div class="quick-actions">
                                        <h6>⚡ Action nhanh - Business Intelligence:</h6>
                                        <div class="quick-buttons">
                                            <button class="quick-btn primary" data-message="dashboard tổng quan hôm nay">
                                                <i class="ri-dashboard-3-line"></i> Dashboard Overview
                                            </button>
                                            <button class="quick-btn success"
                                                data-message="phân tích doanh thu và lợi nhuận tháng này">
                                                <i class="ri-money-dollar-circle-line"></i> Revenue Analysis
                                            </button>
                                            <button class="quick-btn warning"
                                                data-message="dự báo doanh thu và xu hướng 30 ngày tới">
                                                <i class="ri-line-chart-line"></i> Forecast 30 Days
                                            </button>
                                            <button class="quick-btn info"
                                                data-message="phân tích khách hàng và rfm segmentation">
                                                <i class="ri-user-heart-line"></i> Customer Insights
                                            </button>
                                        </div>

                                        <h6>🎯 Action nhanh - Smart Recommendations:</h6>
                                        <div class="quick-buttons">
                                            <button class="quick-btn secondary"
                                                data-message="gợi ý tối ưu inventory và reorder">
                                                <i class="ri-box-1-line"></i> Inventory Optimization
                                            </button>
                                            <button class="quick-btn accent"
                                                data-message="gợi ý chiến lược pricing và marketing">
                                                <i class="ri-price-tag-3-line"></i> Pricing Strategy
                                            </button>
                                            <button class="quick-btn gradient"
                                                data-message="phân tích cạnh tranh và cơ hội thị trường">
                                                <i class="ri-focus-3-line"></i> Market Opportunities
                                            </button>
                                            <button class="quick-btn danger" data-message="cảnh báo và anomaly detection">
                                                <i class="ri-alarm-warning-line"></i> Smart Alerts
                                            </button>
                                        </div>

                                        <h6>💡 Try Advanced Queries:</h6>
                                        <div class="advanced-examples">
                                            <div class="example-query">"So sánh hiệu suất bán hàng Q3 với Q2, phân tích
                                                nguyên nhân và dự báo Q4"</div>
                                            <div class="example-query">"Khách hàng nào có nguy cơ churn cao và chiến lược
                                                retention như thế nào?"</div>
                                            <div class="example-query">"Tối ưu mix sản phẩm cho Black Friday dựa trên data
                                                năm trước"</div>
                                            <div class="example-query">"Phân tích competitor pricing và gợi ý dynamic
                                                pricing strategy"</div>
                                        </div>
                                    </div>

                                    <!-- AI Capabilities Badge -->
                                    <div class="ai-capabilities">
                                        <div class="capability-badge">
                                            <i class="ri-robot-line"></i>
                                            <span>Powered by Advanced AI</span>
                                        </div>
                                        <div class="capability-list">
                                            <span>🧠 Predictive Analytics</span>
                                            <span>📊 Real-time BI</span>
                                            <span>⚡ Smart Alerts</span>
                                            <span>🎯 Personalized Insights</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="message-time">{{ date('H:i d/m/Y') }}</div>
                            </div>

                            <style>
                                .welcome-message {
                                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                    color: white;
                                    padding: 25px;
                                    border-radius: 16px;
                                    margin-bottom: 15px;
                                    box-shadow: 0 8px 32px rgba(102, 126, 234, 0.3);
                                }

                                .welcome-header {
                                    text-align: center;
                                    margin-bottom: 25px;
                                    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
                                    padding-bottom: 15px;
                                }

                                .welcome-header h4 {
                                    margin: 0;
                                    font-size: 24px;
                                    font-weight: 700;
                                }

                                .welcome-subtitle {
                                    margin: 8px 0 0 0;
                                    opacity: 0.9;
                                    font-size: 14px;
                                    color: black
                                }

                                .feature-section {
                                    margin-bottom: 20px;
                                    background: rgba(255, 255, 255, 0.1);
                                    border-radius: 12px;
                                    backdrop-filter: blur(10px);
                                }

                                .feature-section h5 {
                                    margin: 0 0 12px 0;
                                    font-size: 16px;
                                    font-weight: 600;
                                    display: flex;
                                    align-items: center;
                                    gap: 8px;
                                }

                                .feature-grid {
                                    display: flex;
                                    flex-wrap: wrap;
                                    gap: 8px;
                                }

                                .feature-tag {
                                    background: rgba(255, 255, 255, 0.2);
                                    padding: 6px 12px;
                                    border-radius: 20px;
                                    font-size: 12px;
                                    cursor: pointer;
                                    transition: all 0.3s ease;
                                    border: 1px solid rgba(255, 255, 255, 0.1);
                                }

                                .feature-tag:hover {
                                    background: rgba(255, 255, 255, 0.3);
                                    transform: translateY(-2px);
                                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
                                }

                                .quick-actions {
                                    margin-top: 20px;
                                    padding-top: 20px;
                                    border-top: 1px solid rgba(255, 255, 255, 0.2);
                                }

                                .quick-actions h6 {
                                    margin: 15px 0 10px 0;
                                    font-size: 14px;
                                    font-weight: 600;
                                    opacity: 0.95;
                                }

                                .quick-buttons {
                                    display: grid;
                                    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                                    gap: 10px;
                                    margin-bottom: 15px;
                                }

                                .quick-btn {
                                    background: rgba(255, 255, 255, 0.15);
                                    color: white;
                                    border: 1px solid rgba(255, 255, 255, 0.2);
                                    padding: 12px 16px;
                                    border-radius: 8px;
                                    font-size: 13px;
                                    cursor: pointer;
                                    transition: all 0.3s ease;
                                    display: flex;
                                    align-items: center;
                                    gap: 8px;
                                    font-weight: 500;
                                }

                                .quick-btn:hover {
                                    background: rgba(255, 255, 255, 0.25);
                                    transform: translateY(-2px);
                                    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
                                }

                                .quick-btn.primary {
                                    background: rgba(52, 152, 219, 0.3);
                                    border-color: #3498db;
                                }

                                .quick-btn.success {
                                    background: rgba(39, 174, 96, 0.3);
                                    border-color: #27ae60;
                                }

                                .quick-btn.warning {
                                    background: rgba(243, 156, 18, 0.3);
                                    border-color: #f39c12;
                                }

                                .quick-btn.info {
                                    background: rgba(155, 89, 182, 0.3);
                                    border-color: #9b59b6;
                                }

                                .quick-btn.secondary {
                                    background: rgba(149, 165, 166, 0.3);
                                    border-color: #95a5a6;
                                }

                                .quick-btn.accent {
                                    background: rgba(231, 76, 60, 0.3);
                                    border-color: #e74c3c;
                                }

                                .quick-btn.gradient {
                                    background: linear-gradient(45deg, rgba(255, 107, 107, 0.3), rgba(255, 142, 83, 0.3));
                                    border-color: #ff6b6b;
                                }

                                .quick-btn.danger {
                                    background: rgba(192, 57, 43, 0.3);
                                    border-color: #c0392b;
                                }

                                .advanced-examples {
                                    background: rgba(0, 0, 0, 0.2);
                                    padding: 15px;
                                    border-radius: 8px;
                                    margin-top: 10px;
                                }

                                .example-query {
                                    background: rgba(255, 255, 255, 0.1);
                                    padding: 8px 12px;
                                    border-radius: 6px;
                                    font-size: 12px;
                                    margin-bottom: 6px;
                                    border-left: 3px solid rgba(255, 255, 255, 0.3);
                                    cursor: pointer;
                                    transition: all 0.2s ease;
                                }

                                .example-query:hover {
                                    background: rgba(255, 255, 255, 0.2);
                                    transform: translateX(5px);
                                }

                                .ai-capabilities {
                                    margin-top: 20px;
                                    padding-top: 15px;
                                    border-top: 1px solid rgba(255, 255, 255, 0.2);
                                    text-align: center;
                                    color: black;
                                }

                                .capability-badge {
                                    display: inline-flex;
                                    align-items: center;
                                    gap: 6px;
                                    background: rgba(255, 255, 255, 0.2);
                                    padding: 8px 16px;
                                    border-radius: 20px;
                                    font-size: 12px;
                                    font-weight: 600;
                                    margin-bottom: 10px;
                                }

                                .capability-list {
                                    display: flex;
                                    justify-content: center;
                                    flex-wrap: wrap;
                                    gap: 12px;
                                    font-size: 11px;
                                    opacity: 0.9;
                                }

                                .message-time {
                                    text-align: right;
                                    font-size: 12px;
                                    color: #999;
                                    margin-top: 10px;
                                }

                                @media (max-width: 768px) {
                                    .quick-buttons {
                                        grid-template-columns: 1fr;
                                    }

                                    .capability-list {
                                        flex-direction: column;
                                        gap: 6px;
                                    }

                                    .feature-grid {
                                        justify-content: center;
                                    }
                                }
                            </style>

                            <script>
                                // Enhanced click handlers for advanced features
                                document.addEventListener('DOMContentLoaded', function() {
                                    // Quick button handlers
                                    // document.querySelectorAll('.quick-btn').forEach(btn => {
                                    //     btn.addEventListener('click', function() {
                                    //         const message = this.getAttribute('data-message');
                                    //         if (message) {
                                    //             // Trigger chat with predefined message
                                    //             sendMessage(message);
                                    //         }
                                    //     });
                                    // });

                                    // Feature tag handlers - make them clickable
                                    document.querySelectorAll('.feature-tag').forEach(tag => {
                                        tag.addEventListener('click', function() {
                                            const query = this.textContent.trim();
                                            sendMessage(`Phân tích về: ${query}`);
                                        });
                                    });

                                    // Example query handlers
                                    document.querySelectorAll('.example-query').forEach(example => {
                                        example.addEventListener('click', function() {
                                            const query = this.textContent.trim();
                                            sendMessage(query);
                                        });
                                    });

                                    // Function to send message (integrate with your chat system)
                                    function sendMessage(message) {
                                        // Replace with your actual chat send function
                                        console.log('Sending message:', message);

                                        // Example integration:
                                        const messageInput = document.querySelector('#messageInput');
                                        if (messageInput) {
                                            messageInput.value = message;
                                            // Trigger send button or form submit
                                            const sendBtn = document.querySelector('#sendButton');
                                            if (sendBtn) sendBtn.click();
                                        }
                                    }

                                    // Add typing animation for examples
                                    const examples = document.querySelectorAll('.example-query');
                                    examples.forEach((example, index) => {
                                        example.style.animationDelay = `${index * 0.1}s`;
                                        example.style.animation = 'fadeInUp 0.6s ease forwards';
                                    });
                                });

                                // CSS Animation keyframes
                                const style = document.createElement('style');
                                style.textContent = `
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
                                document.head.appendChild(style);
                            </script>
                        </div>
                    </div>
                </div>

                <!-- Input Area -->
                <div class="input-area">
                    <div class="input-container">
                        <div class="input-wrapper">
                            <textarea id="messageInput" class="message-input" placeholder="Hỏi tôi về doanh thu, khách hàng, sản phẩm..."
                                rows="1"></textarea>
                            <button id="sendButton" class="send-button">
                                <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </div>
                        <div class="input-footer">
                            <span class="input-hint">
                                <i class="ri-lightbulb-flash-line"></i>
                                Nhấn Enter để gửi, Shift+Enter để xuống dòng
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div class="typing-indicator" id="typingIndicator" style="display: none;">
        <div class="message-wrapper ai-message">
            <div class="message-avatar">
                <div class="avatar ai-avatar-small">
                    <i class="fa-brands fa-airbnb"></i>
                </div>
            </div>
            <div class="message-content">
                <div class="message-bubble typing-bubble">
                    <div class="typing-dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Reset và Base Styles */
        .chat-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1.5rem 0;
            color: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .ai-avatar {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            backdrop-filter: blur(10px);
        }

        .header-text h1 {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 700;
        }

        .header-text p {
            margin: 0;
            opacity: 0.9;
            font-size: 0.9rem;
        }

        .header-controls {
            display: flex;
            gap: 0.75rem;
        }

        .control-btn {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .control-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-1px);
        }

        .control-btn.memory-active {
            background: rgba(34, 197, 94, 0.2);
            border-color: rgba(34, 197, 94, 0.3);
        }

        .control-btn.control-danger:hover {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.3);
        }

        /* Chat Container */
        .chat-container {
            flex: 1;
            height: calc(100vh - 200px);
            background: #f8fafc;
        }

        .chat-wrapper {
            height: 100%;
            display: flex;
            flex-direction: column;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .messages-area {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            scroll-behavior: smooth;
        }

        .messages-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* Message Styles */
        .message-wrapper {
            display: flex;
            gap: 0.75rem;
            max-width: 85%;
        }

        .message-wrapper.user-message {
            align-self: flex-end;
            flex-direction: row-reverse;
        }

        .message-wrapper.ai-message {
            align-self: flex-start;
        }

        .message-avatar {
            flex-shrink: 0;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .ai-avatar-small {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .user-avatar-small {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .message-content {
            flex: 1;
        }

        .message-bubble {
            padding: 1rem 1.25rem;
            border-radius: 1.25rem;
            position: relative;
            animation: messageSlideIn 0.3s ease-out;
        }

        /* .message-wrapper.ai-message .message-bubble {
                            background: #f1f5f9;
                            border-bottom-left-radius: 0.375rem;
                            border: 1px solid #e2e8f0;
                        } */

        .message-wrapper.user-message .message-bubble {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            border-bottom-right-radius: 0.375rem;
            box-shadow: 0 4px 12px rgba(79, 172, 254, 0.3);
        }

        .message-time {
            font-size: 0.75rem;
            color: #64748b;
            margin-top: 0.375rem;
            text-align: right;
        }

        .message-wrapper.user-message .message-time {
            text-align: left;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Welcome Message */
        .welcome-message {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%) !important;
            border: 1px solid #bae6fd !important;
            border-bottom-left-radius: 0.375rem !important;
        }

        .welcome-header h4 {
            margin: 0 0 1rem 0;
            color: #0369a1;
            font-weight: 600;
        }

        .feature-section {
            margin-bottom: 1.5rem;
        }

        .feature-section h5 {
            margin: 0 0 0.75rem 0;
            color: #0f172a;
            font-size: 0.9rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .feature-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .feature-tag {
            background: rgba(59, 130, 246, 0.1);
            color: #1d4ed8;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .quick-actions {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #cbd5e1;
        }

        .quick-actions h6 {
            margin: 0 0 0.75rem 0;
            color: #0f172a;
            font-weight: 600;
        }

        .quick-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .quick-btn {
            background: white;
            border: 1px solid #d1d5db;
            color: #374151;
            padding: 0.5rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .quick-btn:hover {
            background: #f9fafb;
            border-color: #9ca3af;
            transform: translateY(-1px);
        }

        /* Input Area */
        .input-area {
            padding: 1.5rem;
            background: white;
            border-top: 1px solid #e2e8f0;
        }

        .input-wrapper {
            display: flex;
            gap: 0.75rem;
            align-items: end;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 1.25rem;
            padding: 0.75rem;
            transition: all 0.2s ease;
        }

        .input-wrapper:focus-within {
            border-color: #3b82f6;
            background: white;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .message-input {
            flex: 1;
            border: none;
            outline: none;
            background: transparent;
            resize: none;
            font-size: 1rem;
            line-height: 2.5;
            max-height: 120px;
            font-family: inherit;
        }

        .send-button {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border: none;
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .send-button:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .send-button:disabled {
            opacity: 0.5;
            transform: none;
            box-shadow: none;
        }

        .input-footer {
            display: flex;
            justify-content: center;
            margin-top: 0.75rem;
        }

        .input-hint {
            font-size: 0.75rem;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        /* Typing Indicator */
        .typing-bubble {
            background: #f1f5f9 !important;
            border: 1px solid #e2e8f0 !important;
            padding: 1rem 1.25rem !important;
        }

        .typing-dots {
            display: flex;
            gap: 0.25rem;
        }

        .typing-dots span {
            width: 6px;
            height: 6px;
            background: #64748b;
            border-radius: 50%;
            animation: typingPulse 1.4s infinite;
        }

        .typing-dots span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-dots span:nth-child(3) {
            animation-delay: 0.4s;
        }

        /* Animations */
        @keyframes messageSlideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes typingPulse {

            0%,
            60%,
            100% {
                opacity: 0.4;
                transform: scale(1);
            }

            30% {
                opacity: 1;
                transform: scale(1.2);
            }
        }

        /* Scrollbar */
        .messages-area::-webkit-scrollbar {
            width: 6px;
        }

        .messages-area::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .messages-area::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .messages-area::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
            }

            .header-controls {
                justify-content: center;
                flex-wrap: wrap;
            }

            .message-wrapper {
                max-width: 95%;
            }

            .quick-buttons {
                justify-content: center;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sendButton = document.getElementById('sendButton');
            const messageInput = document.getElementById('messageInput');
            const messagesContainer = document.getElementById('messagesContainer');
            const messagesArea = document.getElementById('messagesArea');
            const clearChat = document.getElementById('clearChat');
            const memoryToggle = document.getElementById('memoryToggle');
            const typingIndicator = document.getElementById('typingIndicator');

            // Auto-resize textarea
            messageInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });

            // Handle Enter key
            messageInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });

            // Quick action buttons
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('quick-btn')) {
                    const message = e.target.dataset.message;
                    messageInput.value = message;
                    sendMessage();
                }
            });

            function addMessage(content, type = 'user') {
                const messageWrapper = document.createElement('div');
                messageWrapper.className = `message-wrapper ${type}-message`;

                const avatarClass = type === 'user' ? 'user-avatar-small' : 'ai-avatar-small';
                const avatarIcon = type === 'user' ? 'fa-solid fa-user-tie' : 'fa-brands fa-airbnb';

                // Nếu là AI thì parse markdown
                let renderedContent = content;
                if (type === 'ai') {
                    try {
                        renderedContent = marked.parse(content); // convert Markdown → HTML
                    } catch (err) {
                        console.error("Markdown parse error:", err);
                    }
                }

                messageWrapper.innerHTML = `
                <div class="message-avatar">
                    <div class="avatar ${avatarClass}">
                        <i class="${avatarIcon}"></i>
                    </div>
                </div>
                <div class="message-content">
                    <div class="message-bubble">
                        ${renderedContent}
                    </div>
                    <div class="message-time">${new Date().toLocaleTimeString('vi-VN')}</div>
                </div>
            `;

                messagesContainer.appendChild(messageWrapper);
                scrollToBottom();
            }

            function showTyping() {
                typingIndicator.style.display = 'block';
                messagesContainer.appendChild(typingIndicator);
                scrollToBottom();
            }

            function hideTyping() {
                typingIndicator.style.display = 'none';
                if (typingIndicator.parentNode) {
                    typingIndicator.parentNode.removeChild(typingIndicator);
                }
            }

            function scrollToBottom() {
                messagesArea.scrollTop = messagesArea.scrollHeight;
            }

            function sendMessage() {
                const message = messageInput.value.trim();
                if (!message) return;

                // Add user message
                addMessage(message, 'user');
                messageInput.value = '';
                messageInput.style.height = 'auto';

                // Show typing indicator
                showTyping();

                // Send to server
                fetch("{{ route('admin.aichat.ask') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            message: message
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        hideTyping();
                        addMessage(data.answer, 'ai'); // sẽ được parse Markdown
                    })
                    .catch(error => {
                        hideTyping();
                        addMessage("⚠️ Xin lỗi, đã có lỗi xảy ra. Vui lòng thử lại sau.", 'ai');
                        console.error('Error:', error);
                    });
            }

            // Event listeners
            sendButton.addEventListener('click', sendMessage);

            clearChat.addEventListener('click', function() {
                if (confirm('Bạn có chắc chắn muốn xóa toàn bộ cuộc trò chuyện?')) {
                    // Keep only the welcome message
                    const welcomeMessage = messagesContainer.querySelector('.welcome-message').closest(
                        '.message-wrapper');
                    messagesContainer.innerHTML = '';
                    messagesContainer.appendChild(welcomeMessage);
                }
            });

            // Memory toggle
            memoryToggle.addEventListener('click', function() {
                this.classList.toggle('memory-active');
                const isActive = this.classList.contains('memory-active');
                this.innerHTML = `
                <i class="ri-brain-line"></i>
                <span>Memory ${isActive ? 'ON' : 'OFF'}</span>
            `;
            });

            // Model selection
            document.querySelectorAll('.model-option').forEach(option => {
                option.addEventListener('click', function(e) {
                    e.preventDefault();
                    const modelName = this.textContent.trim();
                    document.getElementById('currentModel').textContent = modelName;
                });
            });
        });
    </script>
@endsection
