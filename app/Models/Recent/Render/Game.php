<?php

namespace App\Models\Recent\Render;

use App\Models\Game as GameModel;

class Game extends Renderer
{

    /**
     * The blade template to use
     *
     * @var string
     */
    protected string $view = 'recent.game';

    /**
     * Process the content and save to $this->data
     *
     * @param $content string
     */
    public function process(string $content): void
    {
        $ids = json_decode($content);
        $id = array_pop($ids);
        $bg = null;

        $game = GameModel::withCount(['album', 'stats', 'updates'])
            ->findOrFail($id);

        $route = 'schedule';
        if ($game->album_count) {
            $route = 'game.photos';
            $bg = isset($game->album->cover) ? $game->album->cover->photo : null;

        } elseif ($game->stats_count) {
            $route = 'game.stats';
        } elseif ($game->updates_count) {
            $route = 'game.recap';
        }

        $this->data = [
            'game' => $game,
            'route' => $route,
            'recent' => $this->recent,
            'bg' => $bg
        ];
    }

}
