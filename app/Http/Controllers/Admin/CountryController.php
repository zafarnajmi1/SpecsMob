<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Validator;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $countries = Country::latest()->get();
        return view('admin-views.pricing_setup.country', compact('countries'));
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
            'name' => 'required|string|max:255|unique:countries,name',
            'iso_code' => 'required|string|size:2|unique:countries,iso_code|uppercase',
        ]);

        if ($validator->fails()) {
            ToastMagic::error($validator->errors()->first());
            return redirect()->back();
        }
        try {
            Country::create([
                'name' => $request->name,
                'iso_code' => $request->iso_code,
            ]);

            ToastMagic::success('Country created successfully!');
            return redirect()->back();
        } catch (\Exception $e) {
            ToastMagic::error('Failed to create country: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /** Update the specified resource in storage. */

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
    public function update(Request $request, Country $country)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'iso_code' => 'string|size:2|uppercase',
        ]);

        if ($validator->fails()) {
            ToastMagic::error($validator->errors()->first());
            return redirect()->back();
        }

        try {
            $country->update([
                'name' => isset($request->name) ? $request->name : $country->name,
                'iso_code' => isset($request->iso_code) ? $request->iso_code : $country->iso_code,
            ]);

            ToastMagic::success('Country updated successfully!');
            return redirect()->back();
        } catch (\Exception $e) {
            ToastMagic::error('Failed to update country: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        try {
            // Check if country is being used in device offers
            // if ($country->deviceOffers()->exists()) {
            //     ToastMagic::error('Cannot delete country because it is being used in device offers.');
            //     return redirect()->back();
            // }

            $country->delete();

             ToastMagic::success('Country deleted successfully!');
            return redirect()->back();
        } catch (\Exception $e) {
            ToastMagic::error('Failed to delete country: '. $e->getMessage());
            return redirect()->back();
        }
    }
}
