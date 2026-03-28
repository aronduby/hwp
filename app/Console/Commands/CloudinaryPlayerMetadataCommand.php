<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\UsesCloudinary;
use App\Models\ActiveSeason;
use App\Models\PlayerSeason;
use Cloudinary\Api\Exception\NotFound;
use Cloudinary\Api\Metadata\SetMetadataField;
use Illuminate\Console\Command;
use phpDocumentor\Reflection\Types\Self_;

/**
 * # Cloudinary - Update Players Metadata Field
 * This command creates or updates the players metadata field for the supplied seasons player list.
 *
 * Probably want to use this with the {@see Tenanted} command to specify which domain/season
 */
class CloudinaryPlayerMetadataCommand extends Command
{

    use UsesCloudinary;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cloudinary:player-metadata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the cloudinary players metadata field with all the players for the current season';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /** @var ActiveSeason $season */
        $season = resolve(ActiveSeason::class);
        $cloudinary = $this->getCloudinaryForSeason($season);
        if (!$cloudinary) {
            return 1;
        }

        $this->info("Fetching players for season: {$season->title}");

        $playerSeasons = PlayerSeason::with('player')
            ->where('season_id', $season->id)
            ->get();

        if ($playerSeasons->isEmpty()) {
            $this->warn('No players found for the current season.');
            return 0;
        }

        $playerNames = $playerSeasons->map(function (PlayerSeason $ps) {
            return $ps->player->name;
        })->values()->toArray();
        $this->info("Found " . count($playerNames) . " unique players.");

        try {
            $fieldId = 'players';

            // Check if field exists
            try {
                $existingField = $cloudinary->adminApi()->metadataFieldByFieldId($fieldId);
                $this->info("'{$fieldId}' metadata field exists, checking for differences");

                $existingDatasource = $existingField['datasource']['values'];
                $existingNames = [];
                $entriesToRemove = [];
                foreach ($existingDatasource as $entry) {
                    if ($entry['state'] === 'active') {
                        $existingNames[] = $entry['value'];
                        if (!in_array($entry['value'], $playerNames)) {
                            $entriesToRemove[] = $entry;
                        }
                    }
                }
                $namesToAdd = array_diff($playerNames, $existingNames);

                $this->info("Found " . count($namesToAdd) . " players to add.");
                $this->info("Found " . count($entriesToRemove) . " players to remove.");

                if (!empty($namesToAdd)) {
                    $cloudinary->adminApi()->updateMetadataFieldDatasource(
                        $fieldId,
                        array_map([self::class, 'convertToDatasourceEntry'], $namesToAdd)
                    );
                    $this->info("Added ".count($namesToAdd)." players to the field");
                }

                if (!empty($entriesToRemove)) {
                    $externalIdsToRemove = array_column($entriesToRemove, 'external_id');
                    $cloudinary->adminApi()->deleteDatasourceEntries($fieldId, $externalIdsToRemove);
                    $this->info("Removed " . count($entriesToRemove)." players");
                }

            } catch (NotFound $e) {
                $this->info("Creating new '{$fieldId}' metadata field of type 'set'...");

                $field = new SetMetadataField($fieldId, array_map([self::class, 'convertToDatasourceEntry'], $playerNames));
                $field->setLabel('Players');
                $cloudinary->adminApi()->addMetadataField($field);
            }

            $this->info("Successfully updated '{$fieldId}' metadata field.");
        } catch (\Exception $e) {
            $this->error("Failed to update Cloudinary metadata: " . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Used with array_map to be able to go from an array of names to an array of datasource entries
     * @param string $name
     * @return string[]
     */
    static protected function convertToDatasourceEntry(string $name) {
        return [ 'value' => $name ];
    }
}
