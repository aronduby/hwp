<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Note
 *
 * @property int $id
 * @property int $site_id
 * @property int $season_id
 * @property string $title
 * @property string $content
 * @property string|null $photo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Note whereContent($value)
 * @method static Builder|Note whereCreatedAt($value)
 * @method static Builder|Note whereId($value)
 * @method static Builder|Note wherePhoto($value)
 * @method static Builder|Note whereSeasonId($value)
 * @method static Builder|Note whereSiteId($value)
 * @method static Builder|Note whereTitle($value)
 * @method static Builder|Note whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Note extends Model
{
    //
}
