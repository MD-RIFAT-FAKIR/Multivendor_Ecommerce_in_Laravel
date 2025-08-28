<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;

class SiteSettingController extends Controller
{
    //site settings
    public function SiteSettings() {
        $setting = SiteSetting::find(1);

        return view('backend.setting.setting_update', compact('setting'));
    }
}
