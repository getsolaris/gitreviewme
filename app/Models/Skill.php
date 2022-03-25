<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Skill
 *
 * @property int $id
 * @property int $user_id
 * @property string $skill
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 * @method static Builder|Skill newModelQuery()
 * @method static Builder|Skill newQuery()
 * @method static Builder|Skill query()
 * @method static Builder|Skill whereCreatedAt($value)
 * @method static Builder|Skill whereId($value)
 * @method static Builder|Skill whereSkill($value)
 * @method static Builder|Skill whereUpdatedAt($value)
 * @method static Builder|Skill whereUserId($value)
 * @mixin Eloquent
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $projects
 * @property-read int|null $projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static Builder|Skill whereName($value)
 * @property bool $is_represent 대표 언어: true / default: false
 * @method static Builder|Skill whereIsRepresent($value)
 */
class Skill extends Model
{
    use HasFactory;

    public const REDIS_KEY = 'REDIS_SKILLABLES';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'is_represent',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $attributes = [
        'is_represent' => false,
    ];

    protected $casts = [
        'is_represent' => 'boolean',
    ];

    protected $guarded = [];

    /**
     * @return MorphToMany
     */
    public function users(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'skillable');
    }

    /**
     * @return MorphToMany
     */
    public function projects(): MorphToMany
    {
        return $this->morphedByMany(Project::class, 'skillable');
    }
}
