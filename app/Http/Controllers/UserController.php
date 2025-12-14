<?php

namespace App\Http\Controllers;

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
        $posts_count = 0; //dummy
        $upvotes = 0; //dummy
        return view('user-views.account.index', compact('posts_count', 'upvotes'));
    }

public function posts($username)
{
    // Resolve user by username (route-safe)
    $user = User::where('username', $username)->firstOrFail();

    // Fetch approved device opinions (latest first)
    $deviceOpinions = DeviceOpinion::with('device')
        ->where('user_id', $user->id)
        ->where('parent_id', null) // only main posts (not replies)
        ->where('is_approved', true)
        ->latest()
        ->get();

    // Counts
    $posts_count = $deviceOpinions->count();
    $upvotes = $deviceOpinions->sum('likes_count');

    return view('user-views.account.user-posts', compact(
        'user',
        'deviceOpinions',
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

        $posts_count = 0;
        $upvotes = 0;

        return view('user-views.account.user-accountManage', compact('posts_count', 'upvotes'));
    }

public function updateAvatar(Request $request, $username)
{
    abort_unless(auth()->user()->username === $username, 403);

    $request->validate([
        'avatar_type' => 'required|in:none,gravatar',
        'gravatar_email' => 'nullable|email',
    ]);

    $user = User::find(auth()->id());
    if ($request->avatar_type === 'gravatar') {
        $user->gravatar_email = $request->gravatar_email;
        $user->avatar_type = 'gravatar';
        $user->save();
    } else {
        $user->gravatar_email = null;
        $user->avatar_type = 'none';
        $user->save();
    }

    ToastMagic::success('Avatar updated');
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
            'TotalUpvotes' =>  0,
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

}
