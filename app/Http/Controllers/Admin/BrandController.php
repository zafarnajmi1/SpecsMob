<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Faker\Provider\ar_EG\Company;
use Illuminate\Http\Request;
use Storage;
use Str;
use Validator;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::latest()->get();
        return view('admin-views.brands.list', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin-views.brands.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:brands,name',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 2MB max
        ], [
            'name.required' => 'The brand name is required.',
            'name.unique' => 'A brand with this name already exists.',
            'slug.required' => 'The slug is required.',
            'slug.unique' => 'This slug is already in use.',
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
                $logoPath = $logoFile->store('brands/logos', 'public');
            }
            $coverImgPath = null;
            if ($request->hasFile('cover_image')) {
                $coverImgFile = $request->file('cover_image');
                $coverImgPath = $coverImgFile->store('brands/covers', 'public');
            }

            Brand::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'logo' => $logoPath,
                'cover_img' => $coverImgPath,
                'description' => $request->description,
                'status' => $request->status,
            ]);
            
            ToastMagic::success('Brand created successfully!');
            return redirect()->route('admin.brands.index');
        } catch (\Exception $e) {
            if (isset($logoPath) && Storage::disk('public')->exists($logoPath)) {
                Storage::disk('public')->delete($logoPath);
                Storage::disk('public')->delete($coverImgPath);
            }

            ToastMagic::error($e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        return view('admin-views.brands.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'description' => 'nullable|string',
            'status' => 'required|boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'The brand name is required.',
            'name.unique' => 'A brand with this name already exists.',
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
                'description' => $request->description,
                'status' => $request->status,
            ];

            // handle new logo
            if ($request->hasFile('logo')) {
                $logoFile = $request->file('logo');
                $logoPath = $logoFile->store('brands/logos', 'public');
                $updateData['logo'] = $logoPath;

                // Delete old logo
                if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
                    Storage::disk('public')->delete($brand->logo);
                }
            }
            if ($request->hasFile('cover_image')) {
                $logoFile = $request->file('cover_image');
                $logoPath = $logoFile->store('brands/covers', 'public');
                $updateData['cover_img'] = $logoPath;

                // Delete old cover
                if ($brand->cover_img && Storage::disk('public')->exists($brand->cover_img)) {
                    Storage::disk('public')->delete($brand->cover_img);
                }
            }

            $brand->update($updateData);

            ToastMagic::success('Brand updated successfully!');
            return redirect()->route('admin.brands.index');
        } catch (\Exception $e) {
            ToastMagic::error('Failed to update brand. Please try again.');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        try {
            // Check if brand has any devices before deletion
            // if ($brand->devices()->exists()) {
            //     return redirect()->route('admin.brands.index')
            //     ->with('error', 'Cannot delete brand. It has associated devices.');
            // }

            if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
                Storage::disk('public')->delete($brand->logo);
            }
            if ($brand->cover_img && Storage::disk('public')->exists($brand->cover_img)) {
                Storage::disk('public')->delete($brand->cover_img);
            }

            $brand->delete();

            ToastMagic::success('Brand deleted successfully!');
            return redirect()->route('admin.brands.index');
        } catch (\Exception $e) {

            ToastMagic::error('Failed to delete brand. Please try again.');
            return redirect()->back();
        }
    }
}
