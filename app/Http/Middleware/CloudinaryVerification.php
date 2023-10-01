<?php

namespace App\Http\Middleware;

use App\Models\Season;
use App\Providers\MediaServiceProvider;
use App\Services\MediaServices\CloudinaryMediaService;
use Closure;
use Exception;
use Illuminate\Http\Request;

class CloudinaryVerification
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws Exception
     */
    public function handle(Request $request, Closure $next)
    {
        // get the season from season_id and make sure it has cloudinary media service
        // get the seasons cloudinary media service so that we get the cloudinary service setup properly
        // verify the signature

        if (!$request->has('season_id')) {
            throw new Exception('no season id specified');
        }

        $seasonId = $request->get('season_id');

        /**
         * @var Season $season
         */
        $season = Season::findOrFail($seasonId);
        if ($season->media_service !== CloudinaryMediaService::class) {
            throw new Exception('specified season is not set to use the cloudinary service');
        }

        /**
         * This part is necessary to load the config into memory for signature verification
         *
         * @var CloudinaryMediaService $cloudinaryMediaService
         */
        $cloudinaryMediaService = MediaServiceProvider::getServiceForSeason($season);
        $cloudinary = $cloudinaryMediaService->cloudinary;

        $body = $request->getContent();
        $timestamp = $request->header('X-Cld-Timestamp');
        $signature = $request->header('X-Cld-Signature');

        if (self::verifyNotificationSignature($cloudinary->configuration->cloud->apiSecret, $body, $timestamp, $signature)) {
            return $next($request);
        } else {
            throw new Exception('invalid signature');
        }
    }

    /**
     * We're doing this custom here because the built-in verification requires global configuration,
     * which we're not doing on this project.
     *
     * @param string $apiSecret
     * @param string $body
     * @param string $timestamp
     * @param string $signature
     * @param int $validFor
     * @return bool
     * @noinspection PhpSameParameterValueInspection
     */
    private static function verifyNotificationSignature(string $apiSecret, string $body, string $timestamp, string $signature, int $validFor = 7200): bool
    {

        if (time() - $timestamp > $validFor) {
            return false;
        }

        $payloadToSign = $body . $timestamp;
        $hmac = sha1($payloadToSign . $apiSecret);
        return $hmac === $signature;
    }
}
