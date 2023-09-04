<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Models\Settings
 *
 * @property int $id
 * @property string $has_settings_type
 * @property int $has_settings_id
 * @property array $settings
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $owner
 * @method static Builder|Settings whereCreatedAt($value)
 * @method static Builder|Settings whereHasSettingsId($value)
 * @method static Builder|Settings whereHasSettingsType($value)
 * @method static Builder|Settings whereId($value)
 * @method static Builder|Settings whereSettings($value)
 * @method static Builder|Settings whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Settings extends Model
{

    protected $casts = [
        'settings' => 'array',
    ];

    protected $fillable = ['settings'];

    /**
     * Get all the owning commentable models.
     */
    public function owner(): MorphTo
    {
        return $this->morphTo('has_settings');
    }

    /**
     * We're overriding a different get method that we don't care to be compatible with
     *
     * @noinspection PhpHierarchyChecksInspection
     * @noinspection PhpSignatureMismatchDuringInheritanceInspection
     * @noinspection PhpParameterNameChangedDuringInheritanceInspection
     */
    public function get(string $path = null, $defaultValue = null)
    {
        return data_get($this->settings, $path, $defaultValue);
    }

    public function set(string $path, $val)
    {
        return data_set($this->settings, $path, $val);
    }
}
