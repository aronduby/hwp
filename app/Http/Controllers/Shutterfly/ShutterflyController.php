<?php

namespace App\Http\Controllers\Shutterfly;

use App\Models\ActiveSite;
use App\Models\PlayerSeason;
use App\Services\PlayerListService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ShutterflyController extends Controller
{
    /**
     * @var PlayerListService
     */
    protected $playerList;

    /**
     * @var ActiveSite
     */
    protected $activeSite;

    public function __construct(PlayerListService $playerList, ActiveSite $activeSite)
    {
        $this->playerList = $playerList;
        $this->activeSite = $activeSite;
    }

    public function listPlayers() {
        return response()->json($this->playerList->all());
    }

    public function syncPlayers(Request $request) {
        $items = $request->json()->all();

        DB::beginTransaction();
        try {
            foreach($items as $ps) {
                DB::table('player_season')
                    ->where([
                        ['site_id', '=', $this->activeSite->id],
                        ['id', '=', $ps['playerSeasonId']]
                    ])
                    ->update([
                        'shutterfly_tag' => $ps['tagId']
                    ]);
            }
            DB::commit();

            // have to clear the playerlist cache now
            Artisan::call('cache:clear');

            $rsp = [
                'success' => true,
                'message' => 'Sync Successful!'
            ];

        } catch (\Exception $e) {
            DB::rollback();
            $rsp = [
                'success' => true,
                'message' => 'Error saving the sync result'
            ];
        }

        return response()->json($rsp);
    }

    public function saveTags(Request $request) {
        $tags = $request->json()->all();

        $success = file_put_contents(config('bridge.tags_path') . '/' . $this->activeSite->domain . '.tags.json', json_encode($tags, JSON_PRETTY_PRINT));
        if ($success === false) {
            $rsp = [
                'succes' => false,
                'message' => 'Tags could not write to file.'
            ];
        } else {
            $rsp = [
                'success' => true,
                'message' => 'Tags written to file. Do things to trigger import.'
            ];
        }

        return response()->json($rsp);
    }
}
