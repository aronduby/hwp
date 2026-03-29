<?php

namespace App\Http\Controllers;

use App\Models\ActiveSite;
use App\Http\Requests\Request;
use App\Providers\MediaServiceProvider;

class PickerController extends Controller
{
    public function index(Request $request, ActiveSite $picker)
    {
        $sites = $picker->sites;

        // have to temporarily disable landlord to be able to get the seasons properly
        \Landlord::disable();
        $sitesWithPhoto = $sites->map(function ($site) {
            $mediaService = MediaServiceProvider::getServiceForSeason($site->currentSeason());
            $featuredPhoto = $mediaService->forPicker();

            return [
                'site' => $site,
                'photo' => $featuredPhoto,
            ];
        });
        \Landlord::enable();

        $tld = $request->getTLD();

        return view('picker', compact('picker', 'sitesWithPhoto', 'tld'));
    }
}
