<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Validator;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currencies = Currency::latest()->get();
        return view('admin-views.pricing_setup.currency', compact('currencies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:currencies,name',
            'iso_code' => 'required|string|size:3|unique:currencies,iso_code|uppercase',
            'symbol' => 'nullable|string|max:8',
        ]);

        if ($validator->fails()) {
            ToastMagic::error($validator->errors()->first());
            return redirect()->back();
        }
        
        try {
            Currency::create([
                'name' => $request->name,
                'iso_code' => $request->iso_code,
                'symbol' => $request->symbol,
            ]);

            ToastMagic::success('Currency created successfully!');
            return redirect()->back();
        } catch (\Exception $e) {
            ToastMagic::error('Failed to create currency: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Currency $currency)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:currencies,name,' . $currency->id,
            'iso_code' => 'required|string|size:3|unique:currencies,iso_code,' . $currency->id . '|uppercase',
            'symbol' => 'nullable|string|max:8',
        ]);

        if ($validator->fails()) {
            ToastMagic::error($validator->errors()->first());
            return redirect()->back();
        }

        try {
            $currency->update([
                'name' => $request->name,
                'iso_code' => $request->iso_code,
                'symbol' => $request->symbol,
            ]);

            ToastMagic::success('Currency updated successfully!');
            return redirect()->back();
        } catch (\Exception $e) {
            ToastMagic::error('Failed to update currency: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Currency $currency)
    {
        try {
            // Check if currency is being used in device offers
            // if ($currency->deviceOffers()->exists()) {
            //     ToastMagic::error('Cannot delete currency because it is being used in device offers.');
            //     return redirect()->back();
            // }

            $currency->delete();

            ToastMagic::success('Currency deleted successfully!');
            return redirect()->back();
        } catch (\Exception $e) {
            ToastMagic::error('Failed to delete currency: '. $e->getMessage());
            return redirect()->back();
        }
    }
}