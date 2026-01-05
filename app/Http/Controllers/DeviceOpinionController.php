<?php
namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Comment;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Validator;

class DeviceOpinionController extends Controller
{
    public function device_opinion_post($slug, $id)
    {
        $device = Device::where('id', $id)
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        if ($slug !== $device->slug) {
            return redirect()
                ->route('device.opinions.post', [
                    'slug' => $device->slug,
                    'id' => $device->id,
                ], 301);
        }

        $popularReviews = [
            [
                'title' => 'OnePlus 15 review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oneplus-15/-347x151/gsmarena_000.jpg',
                'url' => '/review/oneplus-15',
            ],
            [
                'title' => 'Realme GT 8 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/realme-gt-8-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/realme-gt-8-pro',
            ],
            [
                'title' => 'Oppo Find X9 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oppo-find-x9-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/oppo-find-x9-pro',
            ],
        ];

        return view('user-views.pages.device-opinionPost', compact('device', 'popularReviews'));
    }

    public function store(Request $request, $slug, $id)
    {
        // 1️⃣ Find the device
        $device = Device::where('id', $id)
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // 2️⃣ Check if opinions are allowed
        if (!$device->allow_opinions) {
            abort(403, 'Opinions are disabled for this device.');
        }

        // 3️⃣ Block frozen users
        if (auth()->user()->is_frozen ?? false) {
            abort(403, 'Your account is frozen.');
        }

        // 4️⃣ Validate input
        $validator = Validator::make($request->all(), [
            'opinion' => 'required|string|min:5|max:2000',
        ]);
        if ($validator->fails()) {
            ToastMagic::error($validator->errors()->first('opinion'));
            return redirect()->back();
        }

        // 5️⃣ Store the opinion as a comment
        Comment::create([
            'user_id' => auth()->id(),
            'commentable_id' => $device->id, // Device ID
            'commentable_type' => Device::class, // Device model class
            'parent_id' => $request->parent_id, // For replies
            'body' => $request->opinion,
            'is_approved' => true, // Auto-approve for simplicity
        ]);

        ToastMagic::success('Your opinion posted.');

        // 7️⃣ Redirect to user's posts page
        return redirect()->route('device.opinions', ['slug' => $slug, 'id' => $id]);
    }
}
