<?php

namespace App\Http\Controllers;

use App\Models\ActiveSite;
use App\Http\Requests\Request;

class PickerController extends Controller
{
    public function index(Request $request, ActiveSite $picker)
    {
        $sites = $picker->sites;
        $sites->load(['featuredPhotos' => function($query) {
            $query->take(1);
        }]);

        $tld = $request->getTLD();

        return view('picker', compact('picker', 'sites', 'tld'));
    }
}
