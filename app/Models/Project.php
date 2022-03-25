<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Project
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $url
 * @property int $view
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|ProjectSkill[] $skills
 * @property-read int|null $skills_count
 * @property-read User $user
 * @method static Builder|Project newModelQuery()
 * @method static Builder|Project newQuery()
 * @method static Builder|Project query()
 * @method static Builder|Project whereContent($value)
 * @method static Builder|Project whereCreatedAt($value)
 * @method static Builder|Project whereDeletedAt($value)
 * @method static Builder|Project whereDescription($value)
 * @method static Builder|Project whereId($value)
 * @method static Builder|Project whereTitle($value)
 * @method static Builder|Project whereUpdatedAt($value)
 * @method static Builder|Project whereUrl($value)
 * @method static Builder|Project whereUserId($value)
 * @method static Builder|Project whereView($value)
 * @mixin Eloquent
 * @method static \Illuminate\Database\Query\Builder|Project onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|Project withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Project withoutTrashed()
 * @property int $repository_id
 * @property string|null $base64_content
 * @property string|null $language
 * @property string|null $license
 * @method static Builder|Project whereBase64Content($value)
 * @method static Builder|Project whereLanguage($value)
 * @method static Builder|Project whereLicense($value)
 * @method static Builder|Project whereRepositoryId($value)
 * @property-read \App\Models\GithubRepository|null $githubRepository
 */
class Project extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'repository_id',
        'title',
        'description',
        'content',
        'base64_content',
        'url',
        'language',
        'license',
        'view',
    ];

    protected $guarded = [];

    protected $attributes = [
        'view' => 0,
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasOne
     */
    public function githubRepository(): HasOne
    {
        return $this->hasOne(GithubRepository::class, 'id', 'repository_id');
    }

    /**
     * @return morphToMany
     */
    public function skills(): morphToMany
    {
        return $this->morphToMany(Skill::class, 'skillable');
    }
}
