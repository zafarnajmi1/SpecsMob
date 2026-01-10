<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArticleComment;
use Illuminate\Http\Request;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class ArticleCommentController extends Controller
{
    public function index(Request $request)
    {
        $query = ArticleComment::with(['user', 'article'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('body', 'like', "%{$search}%");
        }

        if ($request->has('status')) {
            if ($request->status == 'pending') {
                $query->where('is_approved', false);
            } elseif ($request->status == 'approved') {
                $query->where('is_approved', true);
            }
        }

        $comments = $query->paginate(20)->withQueryString();
        return view('admin-views.article-comments.index', compact('comments'));
    }

    public function approve(ArticleComment $comment)
    {
        $comment->update(['is_approved' => true]);
        ToastMagic::success('Article comment approved successfully.');
        return back();
    }

    public function destroy(ArticleComment $comment)
    {
        $comment->delete();
        ToastMagic::success('Article comment deleted successfully.');
        return back();
    }
}
