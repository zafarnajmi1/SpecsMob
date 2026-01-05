<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeReviewSlider;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HomeReviewSliderController extends Controller
{
    public function index()
    {
        $sliders = HomeReviewSlider::latest()->get();
        return view('admin-views.homereview-slider.list', compact('sliders'));
    }

    public function create()
    {
        return view('admin-views.homereview-slider.add');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'review_link' => 'required|url',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            ToastMagic::error($validator->errors()->first());
            return redirect()->back()->withInput();
        }

        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('homereview-sliders', 'public');
            }

            HomeReviewSlider::create([
                'review_link' => $request->review_link,
                'image' => $imagePath,
            ]);

            ToastMagic::success('Slider added successfully!');
            return redirect()->route('admin.homereview-slider.index');
        } catch (\Exception $e) {
            if (isset($imagePath) && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            ToastMagic::error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $slider = HomeReviewSlider::findOrFail($id);
        return view('admin-views.homereview-slider.edit', compact('slider'));
    }

    public function update(Request $request, $id)
    {
        $slider = HomeReviewSlider::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'review_link' => 'required|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            ToastMagic::error($validator->errors()->first());
            return redirect()->back();
        }

        try {
            $data = [
                'review_link' => $request->review_link,
            ];

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('homereview-sliders', 'public');
                $data['image'] = $imagePath;

                if ($slider->image && Storage::disk('public')->exists($slider->image)) {
                    Storage::disk('public')->delete($slider->image);
                }
            }

            $slider->update($data);

            ToastMagic::success('Slider updated successfully!');
            return redirect()->route('admin.homereview-slider.index');
        } catch (\Exception $e) {
            ToastMagic::error('Failed to update slider.');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $slider = HomeReviewSlider::findOrFail($id);
            if ($slider->image && Storage::disk('public')->exists($slider->image)) {
                Storage::disk('public')->delete($slider->image);
            }
            $slider->delete();
            ToastMagic::success('Slider deleted successfully!');
            return redirect()->route('admin.homereview-slider.index');
        } catch (\Exception $e) {
            ToastMagic::error('Failed to delete slider.');
            return redirect()->back();
        }
    }
}
