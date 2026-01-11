<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\DeviceOpinion;
use App\Models\User;
use Auth;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Str;
use Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function account()
    {
        $user = auth()->user();
        $stats = $this->getUserStats($user->id);

        $posts_count = $stats['posts_count'];
        $upvotes = $stats['upvotes'];

        return view('user-views.account.index', compact('posts_count', 'upvotes'));
    }

    public function posts($username)
    {
        // Get the user by username
        $user = User::where('username', $username)->firstOrFail();

        // Fetch all approved comments (device opinions, reviews, articles)
        $comments = Comment::with('commentable') // This will fetch the associated device opinions, reviews, or articles
            ->where('user_id', $user->id) // Filter by user
            ->where('is_approved', true) // Only approved comments
            ->latest() // Fetch latest first
            ->get();

        // Count the number of comments
        $posts_count = $comments->count();

        // Sum of upvotes (assuming 'likes_count' exists in your comments table)
        $upvotes = $comments->sum('likes_count');

        // Group comments by their `commentable_type` (device opinion, review, article)
        $groupedComments = $comments->groupBy(function ($comment) {
            return class_basename($comment->commentable_type); // Group by the model's name
        });

        return view('user-views.account.user-posts', compact(
            'user',
            'groupedComments',
            'posts_count',
            'upvotes'
        ));
    }


    public function account_manage($username)
    {
        abort_unless(
            auth()->user()->username === $username,
            403
        );

        $user = User::where('username', $username)->firstOrFail();

        $stats = $this->getUserStats($user->id);
        $posts_count = $stats['posts_count'];
        $upvotes = $stats['upvotes'];
        return view('user-views.account.user-accountManage', compact('user', 'posts_count', 'upvotes'));
    }

    public function updateAvatar(Request $request, $username)
    {
        abort_unless(auth()->user()->username === $username, 403);

        $request->validate([
            'avatar_file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();

        if ($request->hasFile('avatar_file')) {
            // Delete old image if exists
            if ($user->image && \Storage::disk('public')->exists($user->image)) {
                \Storage::disk('public')->delete($user->image);
            }

            $path = $request->file('avatar_file')->store('avatars', 'public');
            $user->image = $path;
            $user->avatar_type = 'none'; // Set back to 'none' as 'upload' is not in database ENUM
            $user->gravatar_email = null;
            $user->save();

            ToastMagic::success('Profile image updated successfully');
        }

        return back();
    }


    public function updateNickname(Request $request, $username)
    {
        // SECURITY: only owner can change
        abort_unless(auth()->user()->username === $username, 403);

        $request->validate([
            'nickname' => 'required|min:2|max:20|unique:users,username,' . auth()->id(),
        ]);

        $newUsername = Str::slug($request->nickname);

        auth()->user()->update([
            'username' => $newUsername,
        ]);

        ToastMagic::success('Nickname updated');

        //redirect to NEW username URL
        return redirect()->route('user.account.manage', $newUsername);
    }

    public function freeze($username)
    {
        abort_unless(auth()->user()->username === $username, 403);

        auth()->user()->update([
            'is_frozen' => true,
        ]);

        Auth::logout();

        return redirect('/')->with('success', 'Your account has been frozen');
    }


    public function destroy($username)
    {
        abort_unless(auth()->user()->username === $username, 403);

        $user = auth()->user();

        Auth::logout();

        // Optional: delete related data
        // $user->posts()->delete();

        $user->delete();

        return redirect('/')->with('success', 'Account deleted successfully');
    }

    public function downloadData($username)
    {
        abort_unless(auth()->user()->username === $username, 403);

        $user = auth()->user();

        $data = [
            [
                'Nickname' => $user->username,
                'Email' => $user->email,
                'Gravatar' => $user->gravatar_email ?? '',
                // 'TotalPosts' => $user->posts()->count() ?? 0,
                // 'TotalUpvotes' => $user->upvotes ?? 0,
                'TotalPosts' => 0,
                'TotalUpvotes' => 0,
                'TimeCreated' => $user->created_at->format('Y-m-d H:i:s'),
                'CountryWhenCreated' => $user->country ?? 'PK',
            ]
        ];

        $filename = 'user-data-' . $user->username . '.csv';

        $handle = fopen('php://output', 'w');
        fputcsv($handle, array_keys($data[0]));

        foreach ($data as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);

        return response()->streamDownload(function () use ($handle) {}, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function getUserStats($userId)
    {
        $comments = Comment::where('user_id', $userId)
            ->where('is_approved', true)
            ->get();

        return [
            'posts_count' => $comments->count(),
            'upvotes' => $comments->sum('likes_count'),
        ];
    }
}
