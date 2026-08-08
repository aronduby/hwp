<?php /** @noinspection PhpUnused */

namespace App\Console\Commands;

use App\Models\ActiveSeason;
use App\Models\ActiveSite;
use App\Models\Recent;
use App\Notifications\PhotosAdded;
use App\Services\MediaServices\CloudinaryMediaService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Throwable;

#[Signature('events:cloudinary-add-recent-photos {from?} {to?}')]
#[Description('Creates the entry in the recent listing for new cloudinary photos and handles the events')]
class CloudinaryAddRecentPhotosCommand extends LoggedCommand
{
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @throws Throwable
     */
    public function handle(): void
    {
        /**
         * @var ActiveSite $site
         * @var ActiveSeason $season
         */
        $site = resolve(ActiveSite::class);
        $season = resolve(ActiveSeason::class);

        if ($season->media_service !== CloudinaryMediaService::class) {
            $this->error('Active season must use cloudinary for this to work');
            return;
        }

        $from = $this->argument('from');
        $to = $this->argument('to');

        while (!$from) {
            $from = $this->ask('When should it start from? (uses strtotime)');
            $from = strtotime($from);
            if (!$from) {
                $this->error("Sorry, I don't know how to parse that");
            }
        }

        while (!$to) {
            $to = $this->ask('When should it end at? (uses strtotime)');
            $to = strtotime($to);
            if (!$to) {
                $this->error("Sorry, I don't know how to parse that");
            }
        }

        $this->table(['From', 'To'], [[$from, $to]]);

        $recent = new Recent();
        $recent->site_id = $site->id;
        $recent->season_id = $season->id;
        $recent->renderer = Recent::TYPE_PHOTOS;
        $recent->content = json_encode([
            'from' => (int) $from,
            'to' => (int) $to
        ]);
        $recent->saveOrFail();

        $notification = new PhotosAdded($recent);
        $site->notify($notification);
    }
}
