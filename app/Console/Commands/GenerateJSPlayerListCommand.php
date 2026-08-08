<?php /** @noinspection PhpUnused */

namespace App\Console\Commands;

use App\Models\ActiveSite;
use App\Models\Player;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

#[Signature('generate:js-player-list {--domain= : The domain to pull players from}')]
#[Description('Creates the javascript file with the player list')]
class GenerateJSPlayerListCommand extends Command
{

    /**
     * The dot notation path to the template to use for the generated js file
     *
     * @var string
     */
    protected string $templatePath = 'partials.js-player-list';

    /**
     * The base path to where we're saving the files
     *
     * @var string
     */
    protected string $basePath = 'js/playerlist/';

    /**
     * The final file location
     *
     * @var string
     */
    protected string $filePath;

    /**
     * The site we are running as
     *
     * @var ActiveSite
     */
    protected ActiveSite $site;

    /**
     * Create a new command instance.
     *
     * @param ActiveSite $site
     */
    public function __construct(ActiveSite $site)
    {
        parent::__construct();

        $this->site = $site;
        $this->filePath = $this->basePath . $site->domain . '.js';
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        // don't need to handle domain, it's already handled in App\Providers\TenantServiceProvider
        /** @noinspection PhpUndefinedMethodInspection */
        $players = Player::orderBy('name_key')->get();

        $byName = [];
        $byNameKey = [];
        $players->each(function($player) use (&$byName, &$byNameKey) {
            $byName[$player->name] = route('players', ['nameKey' => $player->name_key], false);
            $byNameKey[$player->name_key] = route('players', ['nameKey' => $player->name_key], false);
        });

        $content = view($this->templatePath, compact('byName', 'byNameKey'));

        Storage::disk('public')->put($this->filePath, $content);
    }
}
