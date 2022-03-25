<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\GithubInformation
 *
 * @property int $id
 * @property int $oauth_provider_id
 * @property string|null $name
 * @property string|null $company
 * @property string|null $blog
 * @property string|null $location
 * @property string|null $email
 * @property string|null $bio
 * @property int $followers
 * @property int $following
 * @property string $user_registered_at
 * @property string $user_updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\OAuthProvider $oauthProvider
 * @method static \Illuminate\Database\Eloquent\Builder|GithubInformation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GithubInformation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GithubInformation query()
 * @method static \Illuminate\Database\Eloquent\Builder|GithubInformation whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubInformation whereBlog($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubInformation whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubInformation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubInformation whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubInformation whereFollowers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubInformation whereFollowing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubInformation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubInformation whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubInformation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubInformation whereOauthProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubInformation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubInformation whereUserRegisteredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GithubInformation whereUserUpdatedAt($value)
 * @mixin \Eloquent
 */
class GithubInformation extends Model
{
    use HasFactory;

    protected $table = 'github_informations';

    protected $fillable = [
        'name',
        'company',
        'blog',
        'location',
        'email',
        'bio',
        'followers',
        'following',
        'user_registered_at',
        'user_updated_at',
    ];

    /**
     * @return string
     */
    public function getUserRegisteredAtAttribute(): string
    {
        return $this->convertDatetimeFormatToYmd($this->attributes['user_registered_at']);
    }

    /**
     * @param string $datetime
     * @return string
     */
    private function convertDatetimeFormatToYmd(string $datetime): string
    {
        return date('Y-m-d', strtotime($datetime));
    }

    /**
     * @return BelongsTo
     */
    public function oauthProvider(): BelongsTo
    {
        return $this->belongsTo(OAuthProvider::class);
    }
}
