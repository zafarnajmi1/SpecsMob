<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\Device;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DealController extends Controller
{
    public function index(Request $request)
    {
        $query = Deal::with('device')->latest();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('store_name', 'like', "%{$search}%")
                    ->orWhere('memory', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by region
        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        // Filter by featured
        if ($request->filled('featured') && $request->featured == 'yes') {
            // You can add is_featured column later if needed
        }

        $deals = $query->paginate(20)->withQueryString();

        // Get unique regions for filter
        $regions = Deal::distinct('region')->pluck('region');

        return view('admin-views.deals.index', compact('deals', 'regions'));
    }

    public function create()
    {
        $devices = Device::orderBy('name')->get();
        return view('admin-views.deals.create', compact('devices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'device_id' => 'nullable|exists:devices,id',
            'link' => 'required|url',
            'price' => 'required|string',
            'original_price' => 'nullable|string',
            'discount_percent' => 'nullable|string',
            'store_name' => 'required|string|max:255',
            'region' => 'required|string',
            'memory' => 'nullable|string',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            $validated['slug'] = Str::slug($request->title);
            $validated['is_active'] = $request->boolean('is_active', true);

            Deal::create($validated);

            ToastMagic::success('Deal created successfully!');
            return redirect()->route('admin.deals.index');
        } catch (\Exception $e) {
            ToastMagic::error('Error creating deal: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $deal = Deal::findOrFail($id);
        $devices = Device::orderBy('name')->get();
        return view('admin-views.deals.edit', compact('deal', 'devices'));
    }

    public function update(Request $request, $id)
    {
        $deal = Deal::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'device_id' => 'nullable|exists:devices,id',
            'link' => 'required|url',
            'price' => 'required|string',
            'original_price' => 'nullable|string',
            'discount_percent' => 'nullable|string',
            'store_name' => 'required|string|max:255',
            'region' => 'required|string',
            'memory' => 'nullable|string',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            $validated['slug'] = Str::slug($request->title);
            $validated['is_active'] = $request->boolean('is_active', true);

            $deal->update($validated);

            ToastMagic::success('Deal updated successfully!');
            return redirect()->route('admin.deals.index');
        } catch (\Exception $e) {
            ToastMagic::error('Error updating deal: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $deal = Deal::findOrFail($id);
            $deal->delete();

            ToastMagic::success('Deal deleted successfully!');
        } catch (\Exception $e) {
            ToastMagic::error('Error deleting deal: ' . $e->getMessage());
        }

        return redirect()->route('admin.deals.index');
    }

    public function featured(Request $request)
    {
        // Show only featured deals or deals with high discounts
        $deals = Deal::where('is_active', true)
            ->with('device')
            ->latest()
            ->paginate(20);

        return view('admin-views.deals.featured', compact('deals'));
    }
}

