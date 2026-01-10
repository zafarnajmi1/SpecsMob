<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::firstOrCreate(['id' => 1]);
        return view('admin-views.settings.general', compact('settings'));
    }

    public function social()
    {
        $settings = SystemSetting::firstOrCreate(['id' => 1]);
        return view('admin-views.settings.social', compact('settings'));
    }

    public function ads()
    {
        $settings = SystemSetting::firstOrCreate(['id' => 1]);
        return view('admin-views.settings.ads', compact('settings'));
    }

    public function mail()
    {
        $settings = SystemSetting::firstOrCreate(['id' => 1]);
        return view('admin-views.settings.mail', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = SystemSetting::firstOrCreate(['id' => 1]);

        $data = $request->all();

        // Handle File Uploads
        if ($request->hasFile('site_logo')) {
            if ($settings->site_logo) {
                Storage::disk('public')->delete($settings->site_logo);
            }
            $data['site_logo'] = $request->file('site_logo')->store('settings', 'public');
        }

        if ($request->hasFile('site_favicon')) {
            if ($settings->site_favicon) {
                Storage::disk('public')->delete($settings->site_favicon);
            }
            $data['site_favicon'] = $request->file('site_favicon')->store('settings', 'public');
        }

        $settings->update($data);

        ToastMagic::success('Settings updated successfully.');
        return back();
    }
}
