<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Blog;
use App\Models\Review;
use App\Models\Coupon;
use App\Models\Brand;
use App\Models\Tag;
use App\Models\Faq;
use App\Models\VariantAttribute;
use App\Models\EmailCampaign;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $module = $request->input('module');
        $keyword = $request->input('keyword');

        if (!$module || !$keyword) {
            return redirect()->back()->with('error', 'Vui lòng nhập từ khóa và chọn module.');
        }

        $keyword = trim($keyword);
        $escapedKeyword = addcslashes($keyword, '%_');

        switch ($module) {
            case 'products':
                $products = Product::where(function ($query) use ($escapedKeyword) {
                    $query->where('name', 'like', "%{$escapedKeyword}%")
                        ->orWhere('slug', 'like', "%{$escapedKeyword}%")
                        ->orWhere('description', 'like', "%{$escapedKeyword}%")
                        ->orWhere('import_price', 'like', "%{$escapedKeyword}%")
                        ->orWhere('base_price', 'like', "%{$escapedKeyword}%")
                        ->orWhere('sale_price', 'like', "%{$escapedKeyword}%");
                })
                    ->paginate(10);
                return view('admin.products.index', compact('products'))->with('keyword', $keyword);

            case 'categories':
                $categories = Category::where('name', 'like', "%{$escapedKeyword}%")
                    ->orWhere('description', 'like', "%{$escapedKeyword}%")
                    ->orWhere('image', 'like', "%{$escapedKeyword}%")
                    ->paginate(10);
                return view('admin.categories.index', compact('categories'))->with('keyword', $keyword);

            case 'blogs':
                $blogs = Blog::where('title', 'like', "%{$escapedKeyword}%")
                    ->orWhere('slug', 'like', "%{$escapedKeyword}%")
                    ->orWhere('content', 'like', "%{$escapedKeyword}%")
                    ->orWhereHas('category', function ($q) use ($escapedKeyword) {
                        $q->where('name', 'like', "%{$escapedKeyword}%");
                    })
                    ->paginate(10);
                return view('admin.blogs.index', compact('blogs'))->with('keyword', $keyword);

            case 'users':
                $users = User::where('username', 'like', "%{$escapedKeyword}%")
                    ->orWhere('fullname', 'like', "%{$escapedKeyword}%")
                    ->orWhere('email', 'like', "%{$escapedKeyword}%")
                    ->orWhere('phone', 'like', "%{$escapedKeyword}%")
                    ->orWhere('address', 'like', "%{$escapedKeyword}%")
                    ->paginate(10);
                return view('admin.users.index', compact('users'))->with('keyword', $keyword);

            case 'reviews':
                $reviews = Review::where('comment', 'like', "%{$escapedKeyword}%")
                    ->orWhere('rating', 'like', "%{$escapedKeyword}%")
                    ->orWhere('verified_purchase', $keyword === '1' ? 1 : 0)
                    ->orWhere('approved', $keyword === '1' ? 1 : 0)
                    ->paginate(10);
                return view('admin.reviews.index', compact('reviews'))->with('keyword', $keyword);

            case 'coupons':
                $coupons = Coupon::where('code', 'like', "%{$escapedKeyword}%")
                    ->orWhere('discount_type', 'like', "%{$escapedKeyword}%")
                    ->orWhere('discount_value', 'like', "%{$escapedKeyword}%")
                    ->orWhere('max_usage', 'like', "%{$escapedKeyword}%")
                    ->paginate(10);
                return view('admin.coupons.index', compact('coupons'))->with('keyword', $keyword);

            case 'brands':
                $brands = Brand::where('name', 'like', "%{$escapedKeyword}%")
                    ->paginate(10);
                return view('admin.brands.index', compact('brands'))->with('keyword', $keyword);

            case 'tags':
                $tags = Tag::where('name', 'like', "%{$escapedKeyword}%")
                    ->orWhere('slug', 'like', "%{$escapedKeyword}%")
                    ->paginate(10);
                return view('admin.tags.index', compact('tags'))->with('keyword', $keyword);

            case 'faqs':
                $faqs = Faq::where('question', 'like', "%{$escapedKeyword}%")
                    ->orWhere('answer', 'like', "%{$escapedKeyword}%")
                    ->paginate(10);
                return view('admin.faq.index', compact('faqs'))->with('keyword', $keyword);

            case 'variant_attributes':
                $attributes = VariantAttribute::where('id', $keyword)
                    ->paginate(10);
                return view('admin.variant_attributes.index', compact('attributes'))->with('keyword', $keyword);

            case 'email_campaigns':
                $campaigns = EmailCampaign::where('campaign_name', 'like', "%{$escapedKeyword}%")
                    ->orWhere('email_subject', 'like', "%{$escapedKeyword}%")
                    ->orWhere('email_body', 'like', "%{$escapedKeyword}%")
                    ->orWhere('status', 'like', "%{$escapedKeyword}%")
                    ->paginate(10);
                return view('admin.email_campaigns.index', compact('campaigns'))->with('keyword', $keyword);

            default:
                return redirect()->back()->with('error', 'Module không hợp lệ!');
        }
    }
}
