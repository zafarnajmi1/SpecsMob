<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Storage;
use Str;
use Validator;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stores = Store::latest()->get();
        return view('admin-views.pricing_setup.store', compact('stores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:stores,name',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'logo.image' => 'The logo must be an image file.',
            'logo.mimes' => 'The logo must be a JPEG, PNG, JPG, GIF, or SVG file.',
            'logo.max' => 'The logo must not exceed 2MB in size.',
        ]);

        if ($validator->fails()) {
            ToastMagic::error($validator->errors()->first());
            return redirect()->back();
        }

        try {
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoFile = $request->file('logo');
                $logoPath = $logoFile->store('pricing_setup/stores', 'public');
            }

            Store::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'logo_url' => $logoPath,
            ]);

            ToastMagic::success('Store created successfully!');
            return redirect()->back();
        } catch (\Exception $e) {
            ToastMagic::error('Failed to create store: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Store $store)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:stores,name,' . $store->id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'logo.image' => 'The logo must be an image file.',
            'logo.mimes' => 'The logo must be a JPEG, PNG, JPG, GIF, or SVG file.',
            'logo.max' => 'The logo must not exceed 2MB in size.',
        ]);

        if ($validator->fails()) {
            ToastMagic::error($validator->errors()->first());
            return redirect()->back();
        }

        try {
            $updateData = [
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ];

            // Handle new logo
            if ($request->hasFile('logo')) {
                $logoFile = $request->file('logo');
                $logoPath = $logoFile->store('pricing_setup/stores', 'public');
                $updateData['logo_url'] = $logoPath;

                // Delete old logo
                $path = $store->logo_path;
                if ($path && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            $store->update($updateData);

            ToastMagic::success('Store updated successfully!');
            return redirect()->back();
        } catch (\Exception $e) {
            ToastMagic::error('Failed to update store: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Store $store)
    {
        try {
            // Check if store is being used in device offers
            // if ($store->deviceOffers()->exists()) {
            //     ToastMagic::error('Cannot delete store because it is being used in device offers.');
            //     return redirect()->back();
            // }

            // Delete logo file if exists
            $path = $store->logo_path;
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            $store->delete();

            ToastMagic::success('Store deleted successfully!');
            return redirect()->back();
        } catch (\Exception $e) {
            ToastMagic::error('Failed to delete store: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
