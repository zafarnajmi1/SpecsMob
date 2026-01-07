<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeviceOpinion;
use Illuminate\Http\Request;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class OpinionController extends Controller
{
    public function index(Request $request)
    {
        $query = DeviceOpinion::with(['user', 'device'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('body', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            if ($request->status == 'pending') {
                $query->where('is_approved', false);
            } elseif ($request->status == 'approved') {
                $query->where('is_approved', true);
            }
        }

        $opinions = $query->paginate(20)->withQueryString();
        return view('admin-views.opinions.index', compact('opinions'));
    }

    public function approve(DeviceOpinion $opinion)
    {
        $opinion->update(['is_approved' => true]);
        ToastMagic::success('Opinion approved successfully.');
        return back();
    }

    public function reject(DeviceOpinion $opinion)
    {
        $opinion->update(['is_approved' => false]);
        ToastMagic::success('Opinion rejected/pending successfully.');
        return back();
    }

    public function destroy(DeviceOpinion $opinion)
    {
        $opinion->delete();
        ToastMagic::success('Opinion deleted successfully.');
        return back();
    }
}
