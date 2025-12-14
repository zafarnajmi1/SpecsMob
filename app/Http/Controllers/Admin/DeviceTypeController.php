<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeviceType;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Str;
use Validator;

class DeviceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deviceTypes = DeviceType::latest()->get();
        return view('admin-views.devices.deviceType', compact('deviceTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:device_types,name'
        ]);

        if ($validator->fails()) {
            ToastMagic::error($validator->errors()->first());
            return redirect()->back();
        }

        DeviceType::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'status' => $request->status
        ]);

        ToastMagic::success('DeviceType created successfully!');
        return redirect()->route('admin.devicetypes.index');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DeviceType $devicetype)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:device_types,name'
        ]);

        if ($validator->fails()) {
            ToastMagic::error($validator->errors()->first());
            return redirect()->back();
        }

        $devicetype->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'status' => $request->status,
        ]);

        ToastMagic::success('DeviceType updated successfully!');
        return redirect()->route('admin.devicetypes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeviceType $devicetype)
    {
        // Check if brand has any devices before deletion
        // if ($devicetype->devices()->exists()) {
        // ToastMagic::error('Cannot delete deviceType. It has associated devices.');
        // return redirect()->route('admin.devicetypes.index');
        // }

        $devicetype->delete();

        ToastMagic::success('DeviceType deleted successfully!');
        return redirect()->route('admin.devicetypes.index');
    }
}
