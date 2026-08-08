<?php /** @noinspection PhpUnused */

namespace App\Console\Commands;

use App\Console\Commands\Traits\UsesCloudinary;
use App\Models\ActiveSeason;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Message;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;

/**
 * # Cloudinary - Tags Webhook
 * This command creates or updates the webhook for tagging photos. It's a bigger PITA because Cloudinary has chosen to
 * not include this functionality as part of the SDK.
 *
 * Probably want to use this with the {@see Tenanted} command to specify which domain/season
 */
#[Signature('cloudinary:tags-webhook')]
#[Description('Creates or updates the tagging webhook for the current season')]
class CloudinaryTagsWebhook extends LoggedCommand
{

    /**
     * We're keeping this for the easy verification, we can't use the actual SDK for these methods
     */
    use UsesCloudinary;

    /**
     * Execute the console command.
     *
     * @return int
     * @throws GuzzleException
     */
    public function handle(): int
    {
        /** @var ActiveSeason $season */
        $season = resolve(ActiveSeason::class);
        $cloudinary = $this->getCloudinaryForSeason($season);
        if (!$cloudinary) {
            return 1;
        }

        try {
            $settings = $season->settings->get('cloudinary');
            $eventType = 'resource_tags_changed';
            $client = new Client([
                'base_uri' => "https://api.cloudinary.com/api/v1_1/{$settings['cloud_name']}/",
                'auth' => [$settings['api_key'], $settings['api_secret']],
            ]);
            $rsp = $client->request('GET', "triggers", [
                'query' => [
                    'event_type' => $eventType,
                ]
            ]);
            $bodyStr = (string) $rsp->getBody();
            $body = json_decode($bodyStr);

            $domain = $season->site->domain;
            $seasonId = $season->id;
            $path = parse_url(route('cloudinary.tags'), PHP_URL_PATH);
            $idToUpdate = false;

            $alreadyHasWebhook = collect($body->triggers)
                ->contains(function ($trigger) use($domain, $seasonId, $path, &$idToUpdate) {
                    $urlParts = parse_url($trigger->uri);
                    // matching on domain and path, and then treating the query string to determine if we need to update
                    // vs create a totally new one

                    // remember that the site domain ignores the TLD
                    $matchesDomainAndPath = str_starts_with($urlParts['host'], $domain.'.')
                        && $urlParts['path'] === $path;

                    if ($matchesDomainAndPath) {
                        if ($urlParts['query'] !== "season_id={$seasonId}") {
                            // different season, need to mark the id to update but still return true to stop searching
                            $idToUpdate = $trigger->id;
                        }
                        return true;
                    } else {
                        return false;
                    }
                });

            // already exists and has the right season, all good!
            if ($alreadyHasWebhook && $idToUpdate === false) {
                $this->info("Cloudinary tags webhook already exists and has the proper season");
                return 0;
            }

            $webhookUrl = "https://{$domain}.com{$path}?season_id={$seasonId}";

            // already exists, but doesn't have the right season, update it
            if ($alreadyHasWebhook && $idToUpdate !== false) {
                $this->info("Updating Cloudinary tags webhook to latest season");
                $client->request('PUT', "triggers/{$idToUpdate}", [
                    'form_params' => [
                        'new_uri' => $webhookUrl,
                    ]
                ]);
                return 0;
            }

            // doesn't exist at all, have to create it
            if (!$alreadyHasWebhook) {
                $this->info("Creating Cloudinary tags webhook");
                $client->request('POST', "triggers", [
                    'json' => [
                        'uri' => $webhookUrl,
                        'event_type' => $eventType,
                    ]
                ]);
                return 0;
            }

        } catch (ClientException $e) {
            $this->error("Failed to create or update Cloudinary tags webhook:" . $e->getMessage());
            $this->info(Message::toString($e->getRequest()));
            $this->info($e->getResponse()->getStatusCode());
            return 1;
        }

        return 0;
    }
}
