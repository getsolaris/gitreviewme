<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\GithubRepository
 *
 * @property int $id
 * @property int $oauth_provider_id
 * @property int $repository_id id
 * @property string $name repository name
 * @property string|null $description repository description
 * @property string $full_name user handle id + repository name
 * @property string $node_id
 * @property string $url html_url
 * @property array|null $data temporary
 * @property array|null $license
 * @property int $stargazers_count
 * @property int $watchers_count
 * @property int $forks_count
 * @property int $open_issues
 * @property string $default_branch
 * @property string|null $language
 * @property string $repository_created_at created_at
 * @property string $repository_updated_at updated_at
 * @property string $repository_pushed_at pushed_at
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\OAuthProvider $oauthProvider
 * @method static \Illuminate\Database\Eloquent\Builder|GithubRepository newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GithubRepository newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GithubRepository query()
 * @method static \Illuminate\Database\Eloquent\Builder|GithubRepository whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubRepository whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubRepository whereDefaultBranch($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubRepository whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubRepository whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubRepository whereForksCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubRepository whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubRepository whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubRepository whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubRepository whereLicense($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubRepository whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubRepository whereNodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubRepository whereOauthProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubRepository whereOpenIssues($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubRepository whereRepositoryCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubRepository whereRepositoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubRepository whereRepositoryPushedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubRepository whereRepositoryUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubRepository whereStargazersCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubRepository whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubRepository whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubRepository whereWatchersCount($value)
 * @mixin \Eloquent
 * @property mixed $owner
 * @property string $is_visibility
 * @method static \Illuminate\Database\Eloquent\Builder|GithubRepository whereIsVisibility($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubRepository whereOwner($value)
 * @property string $type
 * @property int $owner_id repository owner id
 * @method static Builder|GithubRepository whereOwnerId($value)
 * @method static Builder|GithubRepository whereType($value)
 * @property array|null $topics
 * @property-read bool $has_project
 * @property-read bool $hide
 * @property-read \App\Models\Project $project
 * @method static Builder|GithubRepository whereTopics($value)
 */
class GithubRepository extends Model
{
    use HasFactory;

    protected $table = 'github_repositories';

    protected $fillable = [
        'oauth_provider_id',
        'repository_id',
        'type',
        'owner_id',
        'name',
        'description',
        'full_name',
        'node_id',
        'url',
        'owner',
        'owner_id',
        'data',
        'license',
        'topics',
        'stargazers_count',
        'watchers_count',
        'forks_count',
        'open_issues',
        'default_branch',
        'language',
        'is_visibility',
        'repository_created_at',
        'repository_updated_at',
        'repository_pushed_at',
    ];

    protected $casts = [
        'data' => 'json',
        'license' => 'json',
        'topics' => 'array',
    ];

    /**
     * @return BelongsTo
     */
    public function oauthProvider(): BelongsTo
    {
        return $this->belongsTo(OAuthProvider::class);
    }

    /**
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return bool
     */
    public function getOwnerAttribute(): bool
    {
        return $this->attributes['owner_id'] === (int) $this->oauthProvider->provider_user_id;
    }

    /**
     * @return bool
     */
    public function getHasProjectAttribute(): bool
    {
        return Project::whereRepositoryId($this->attributes['id'])->exists();
    }

    /**
     * @return bool
     */
    public function getHideAttribute(): bool
    {
        if (! auth()->user()) {
            if ($this->attributes['is_visibility'] === 'private') return true;
            else return false;
        }

        if ((int) auth()->user()->oauthProvider->id === $this->attributes['oauth_provider_id']) {
            return false;
        } else {
            if ($this->attributes['is_visibility'] === 'private') return true;
            else return false;
        }
    }
}
