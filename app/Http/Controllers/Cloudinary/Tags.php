<?php

namespace App\Http\Controllers\Cloudinary;

use App\Services\MediaServices\CloudinaryMediaService;
use Cache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;

class Tags extends Controller
{
    /**
     * Handles the tagging webhook from cloudinary
     *
     * If the home tag has been added or removed we need to redo the cache
     * Same with the cover tag
     *
     */
    public function __invoke(Request $request): JsonResponse
    {
        $content = json_decode($request->getContent(), true);
        $seasonId = $request->get('season_id');

        $changedHome = false;
        $changedCover = false;
        foreach ($content['resources'] as $resource) {
            if (!$changedHome &&
                (
                    in_array(CloudinaryMediaService::TAG_FOR_HOME, $resource['added'] ?? [])
                    || in_array(CloudinaryMediaService::TAG_FOR_HOME, $resource['removed'] ?? [])
                )
            ) {
                $changedHome = true;
            }

            if (!$changedCover &&
                (
                    in_array(CloudinaryMediaService::TAG_FOR_COVER, $resource['added'] ?? [])
                    || in_array(CloudinaryMediaService::TAG_FOR_COVER, $resource['removed'] ?? [])
                )
            ) {
                $changedCover = true;
            }

            if ($changedHome && $changedCover) {
                break;
            }
        }


        $results = [
            [$changedHome, 'cloudinary home tagging changed, clearing cache', CloudinaryMediaService::CACHE_KEY_FOR_HOME],
            [$changedCover, 'cloudinary cover tagging changed, clearing cache', CloudinaryMediaService::CACHE_KEY_FOR_COVERS],
        ];
        foreach ($results as list($changed, $msg, $cacheKey)) {
            if ($changed) {
                Log::info($msg);
                Cache::forget(sprintf($cacheKey, $seasonId));
            }
        }

        return response()->json([$changedHome, $changedCover]);
    }

}
