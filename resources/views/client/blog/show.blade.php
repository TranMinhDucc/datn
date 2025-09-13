@extends('layouts.client')

@section('title', 'Blog Details')

@push('css')
<style>
    /* Comment */
    .comments-container {
        margin: 2rem 0;
        padding: 1.5rem;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .comments-container h5 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
    }

    .comments-wrapper {
        max-height: 600px;
        overflow-y: auto;
        padding-right: 0.5rem;
    }

    /* Scrollbar styling */
    .comments-wrapper::-webkit-scrollbar {
        width: 6px;
    }

    .comments-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .comments-wrapper::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .comments-wrapper::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* Comment Thread */
    .comment-thread {
        margin-bottom: 1.5rem;
        position: relative;
    }

    .comment-root {
        border-left: 3px solid transparent;
    }

    .comment-reply {
        margin-left: 2rem;
        margin-top: 1rem;
        border-left: 3px solid #3498db;
        padding-left: 1rem;
        background: rgba(52, 152, 219, 0.02);
        border-radius: 0 8px 8px 0;
    }

    /* Comment Item */
    .comment-item {
        display: flex;
        gap: 1rem;
        align-items: flex-start;
    }

    .comment-avatar {
        flex-shrink: 0;
    }

    .avatar-img {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e9ecef;
        transition: transform 0.2s ease;
    }

    .avatar-img:hover {
        transform: scale(1.05);
    }

    /* Comment Content */
    .comment-content {
        flex: 1;
        min-width: 0;
    }

    .comment-header {
        margin-bottom: 0.5rem;
    }

    .comment-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .comment-author {
        font-size: 0.95rem;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .comment-author i {
        color: #3498db;
        font-size: 0.9rem;
    }

    .comment-date {
        font-size: 0.85rem;
        color: #6c757d;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .comment-date i {
        font-size: 0.8rem;
    }

    .comment-body {
        margin: 0.75rem 0;
    }

    .comment-body p {
        margin: 0;
        line-height: 1.6;
        color: #495057;
        font-size: 0.95rem;
    }

    /* Comment Actions */
    .comment-actions {
        margin-top: 0.75rem;
    }

    .reply-button {
        background: none;
        border: none;
        color: #3498db;
        font-size: 0.85rem;
        cursor: pointer;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .reply-button:hover {
        background: rgba(52, 152, 219, 0.1);
        color: #2980b9;
        transform: translateX(2px);
    }

    .reply-button i {
        font-size: 0.8rem;
        transition: transform 0.2s ease;
    }

    .reply-button:hover i {
        transform: rotate(-10deg);
    }

    /* Comment Replies */
    .comment-replies {
        margin-top: 1rem;
        position: relative;
    }

    .comment-replies::before {
        content: '';
        position: absolute;
        left: -1rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #3498db, transparent);
    }

    /* No Comments State */
    .no-comments {
        text-align: center;
        padding: 2rem;
        color: #6c757d;
    }

    .no-comments .comment-item {
        justify-content: center;
    }

    .no-comments p {
        font-style: italic;
        margin: 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .comments-container {
            padding: 1rem;
            margin: 1rem 0;
        }

        .comment-reply {
            margin-left: 1rem;
            padding-left: 0.75rem;
        }

        .comment-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
        }

        .avatar-img {
            width: 40px;
            height: 40px;
        }

        .comment-item {
            gap: 0.75rem;
        }

        .comment-replies::before {
            left: -0.75rem;
        }
    }

    @media (max-width: 480px) {
        .comment-reply {
            margin-left: 0.5rem;
            padding-left: 0.5rem;
        }

        .avatar-img {
            width: 35px;
            height: 35px;
        }

        .comment-author {
            font-size: 0.9rem;
        }

        .comment-body p {
            font-size: 0.9rem;
        }
    }

    /* Animation */
    .comment-thread {
        animation: fadeInUp 0.3s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Comment form */
    .comment-form-container {
        margin: 2rem 0;
        padding: 1.5rem;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .comment-form-container h5 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 1.5rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        resize: vertical;
        min-height: 44px;
    }

    .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        outline: none;
    }

    .form-control-sm {
        padding: 0.5rem;
        font-size: 0.875rem;
        min-height: 38px;
    }

    .guest-info {
        margin-bottom: 1rem;
    }

    .guest-info-inline {
        margin: 0.75rem 0;
    }

    .btn {
        padding: 0.5rem 1.5rem;
        border: none;
        border-radius: 6px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-primary {
        background: #3498db;
        color: white;
    }

    .btn-primary:hover:not(:disabled) {
        background: #2980b9;
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
    }

    .btn-sm {
        padding: 0.375rem 1rem;
        font-size: 0.875rem;
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .reply-form-container {
        margin: 1rem 0 0 3rem;
        padding: 1rem;
        background: rgba(52, 152, 219, 0.05);
        border-radius: 8px;
        border-left: 3px solid #3498db;
        animation: slideDown 0.3s ease-out;
    }

    .reply-form-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.75rem;
    }

    .spinner {
        display: inline-block;
        width: 12px;
        height: 12px;
        border: 2px solid #ffffff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .reply-form-container {
            margin-left: 1rem;
        }

        .reply-form-actions {
            flex-direction: column;
        }

        .btn {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<section class="section-b-space pt-0">
    <div class="heading-banner">
        <div class="custom-container container">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h4>Tin tức</h4>
                </div>
                <div class="col-sm-6">
                    <ul class="breadcrumb float-end">
                        <li class="breadcrumb-item"> <a href="{{ route('client.home') }}">Trang chủ </a></li>
                        <li class="breadcrumb-item active"> <a href="{{ route('client.blog.index') }}">Tin tức</a></li>
                    </ul>
                </div>
            </div>
            </div>
        </div>
    </div>
</section>
<section class="section-b-space pt-0">
    <div class="custom-container container blog-page">
        <div class="row gy-4">
            <div class="col-xl-9 col-lg-8 col-12 ratio50_2">
                <div class="row">
                    <div class="col-12">
                        <div class="blog-main-box blog-details">
                            <div>
                                <div class="blog-img"> <img class="img-fluid bg-img"
                                        src="{{ asset('storage/' . $blog->thumbnail) }}"
                                        alt=""></div>
                            </div>
                            <div class="blog-content"><span class="blog-date">{{$blog->published_at->format('l, d m Y')}}</span><a
                                    href="#">
                                    <h4>{{$blog->title}}</h4>
                                </a>
                                {!! $blog->content !!}
                                <h5 class="pt-3">Có thể bạn sẽ quan tâm</h5>
                                <ul>
                                    @foreach ($relatedBlogs as $related)
                                    <li>
                                        <a href="{{ route('client.blog.show', $related->slug) }}">
                                            {{ $related->title }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                                <div class="comments-container">
                                    <h5>Bình luận</h5>
                                    <div class="comments-wrapper">
                                        @forelse($blog->comments->whereNull('parent_id') as $comment)
                                        @include('client.blog.comment', ['comment' => $comment])
                                        @empty
                                        <div class="no-comments">
                                            <div class="comment-item">
                                                <div class="comment-content">
                                                    <p>No comments yet. Be the first to comment!</p>
                                                </div>
                                            </div>
                                        </div>
                                        @endforelse
                                    </div>
                                </div>
                                <h5 class="pt-3">Để lại bình luận</h5>

                                <form action="{{ route('client.blog.comment.store', $blog) }}" method="POST" class="comment-form">
                                    @csrf
                                    <div class="row gy-3 message-box">
                                        @guest
                                        <div class="col-md-6">
                                            <label class="form-label">Tên Hiển Thị</label>
                                            <input type="text" name="guest_name" class="form-control" placeholder="Your Name"
                                                value="{{ session('guest_name', '') }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="guest_email" class="form-control" placeholder="Your Email"
                                                value="{{ session('guest_email', '') }}" required>
                                        </div>
                                        @endguest
                                        <div class="col-12">
                                            <label class="form-label">Nội dung bình luận</label>
                                            <textarea class="form-control" name="content" id="message" rows="6" placeholder="Viết bình luận của bạn ở đây..." required></textarea>
                                        </div>
                                        <div class="col-12"><button type="submit" class="btn btn_black rounded sm">Đăng bình luận</button></div>
                                    </div>


                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 order-lg-first col-12">
                <div class="blog-sidebar sticky">
                    <div class="row gy-4">
                        <div class="col-12">
                            <div class="blog-search">
                                <input type="search" placeholder="Search Here..."><i class="iconsax"
                                    data-icon="search-normal-2"></i>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="sidebar-box">
                                <div class="sidebar-title">
                                    <div class="loader-line"></div>
                                    <h5>Danh mục</h5>
                                </div>
                                <ul class="categories">
                                    @foreach ($categories as $category)
                                    <li>
                                        <p>{{$category->name}}<span>{{$category->blogs_count}}</span></p>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="sidebar-box">
                                <div class="sidebar-title">
                                    <div class="loader-line"></div>
                                    <h5>Bài viết nổi bật</h5>
                                </div>
                                <ul class="top-post">
                                    @foreach($topViewedBlogs as $top)
                                    <li>
                                        <img class="img-fluid" src="{{ asset('storage/' . $top->thumbnail) }}" alt="{{ $top->title }}">
                                        <div>
                                            <a href="{{ route('client.blog.show', $top->slug) }}">
                                                <h6>{{ \Illuminate\Support\Str::limit($top->title, 60) }}</h6>
                                            </a>
                                            <p>{{ $top->published_at->format('d/m/Y') }}</p>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <!-- <div class="col-12">
                            <div class="sidebar-box">
                                <div class="sidebar-title">
                                    <div class="loader-line"></div>
                                    <h5> Popular Tags</h5>
                                </div>
                                <ul class="popular-tag">
                                    <li>
                                        <p>T-shirt</p>
                                    </li>
                                    <li>
                                        <p>Handbags </p>
                                    </li>
                                    <li>
                                        <p>Trends </p>
                                    </li>
                                    <li>
                                        <p>Fashion</p>
                                    </li>
                                    <li>
                                        <p>Designer</p>
                                    </li>
                                </ul>
                            </div>
                        </div> -->
                        <div class="col-12">
                            <div class="sidebar-box">
                                <div class="sidebar-title">
                                    <div class="loader-line"></div>
                                    <h5>Theo dõi chúng tôi</h5>
                                </div>
                                <ul class="social-icon">
                                    <li> <a href="https://www.facebook.com/" target="_blank">
                                            <div class="icon"><i class="fa-brands fa-facebook-f"></i></div>
                                            <h6>Facebook</h6>
                                        </a></li>
                                    <li> <a href="https://www.instagram.com/" target="_blank">
                                            <div class="icon"><i class="fa-brands fa-instagram"> </i></div>
                                            <h6>Instagram</h6>
                                        </a></li>
                                    <li> <a href="https://twitter.com/" target="_blank">
                                            <div class="icon"><i class="fa-brands fa-x-twitter"></i></div>
                                            <h6>Twitter</h6>
                                        </a></li>
                                    <li> <a href="https://www.youtube.com/" target="_blank">
                                            <div class="icon"><i class="fa-brands fa-youtube"></i></div>
                                            <h6>Youtube</h6>
                                        </a></li>
                                    <li> <a href="https://www.whatsapp.com/" target="_blank">
                                            <div class="icon"><i class="fa-brands fa-whatsapp"></i></div>
                                            <h6>Whatsapp</h6>
                                        </a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 d-none d-lg-block">
                            <div class="blog-offer-box"> <img class="img-fluid"
                                    src="{{ asset('assets/client/images/other-img/blog-offer.jpg') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle reply button clicks
        document.querySelectorAll('.reply-button').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                const commentId = this.dataset.commentId;
                const parentAuthor = this.dataset.parentAuthor;
                const replyForm = document.getElementById(`reply-form-${commentId}`);
                const textarea = replyForm.querySelector('textarea[name="content"]');

                // Hide all other reply forms
                document.querySelectorAll('.reply-form-container').forEach(form => {
                    if (form.id !== `reply-form-${commentId}`) {
                        form.style.display = 'none';
                    }
                });

                // Toggle current reply form
                if (replyForm.style.display === 'none' || replyForm.style.display === '') {
                    replyForm.style.display = 'block';
                    textarea.focus();

                    // Add smooth scroll to reply form
                    replyForm.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                } else {
                    replyForm.style.display = 'none';
                }
            });
        });

        // Handle cancel reply buttons
        document.querySelectorAll('.cancel-reply').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                const replyForm = this.closest('.reply-form-container');
                replyForm.style.display = 'none';

                // Clear the textarea
                const textarea = replyForm.querySelector('textarea[name="content"]');
                textarea.value = '';
            });
        });

        // Auto-resize textareas
        document.querySelectorAll('textarea').forEach(textarea => {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        });

        // Handle form submissions with loading states
        document.querySelectorAll('.comment-form, .reply-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner"></span> Posting...';

                // Re-enable after 3 seconds as fallback
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }, 3000);
            });
        });
    });
</script>

@endsection