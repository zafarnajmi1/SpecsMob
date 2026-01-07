<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with(['user', 'commentable'])->latest();

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
        return view('admin-views.comments.index', compact('comments'));
    }

    public function approve(Comment $comment)
    {
        $comment->update(['is_approved' => true]);
        ToastMagic::success('Comment approved successfully.');
        return back();
    }

    public function reject(Comment $comment)
    {
        $comment->update(['is_approved' => false]);
        ToastMagic::success('Comment rejected/pending successfully.');
        return back();
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        ToastMagic::success('Comment deleted successfully.');
        return back();
    }
}
