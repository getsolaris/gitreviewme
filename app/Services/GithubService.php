<?php

namespace App\Services;

use App\Models\GithubRepository;
use App\Models\OAuthProvider;
use Github\Client as Client;

/**
 * Class GithubService
 * @package App\Services
 */
class GithubService
{
    /**
     * @var Client $client
     */
    protected Client $client;

    /**
     * GithubService constructor.
     * @param Client $client
     */
    public function __construct(Client $client) {
        $this->client = $client;
    }

    /**
     * @return array
     */
    public function me(): array
    {
        return $this->githubMeDto($this->client->currentUser()->show());
    }

    /**
     * @param string $handleId
     * @return array
     */
    public function getGithubHandleByRepositories(string $handleId): array
    {
        return $this->userHandleByRepositories($handleId);
    }

    /**
     * @param string $date
     * @return string
     */
    public function convertDatetimeTzToTimestamp(string $date): string
    {
        return date('Y-m-d H:i:s', strtotime($date));
    }

    /**
     * TODO: Construct
     * @param string $token
     */
    public function authentication(string $token): void
    {
        $this->client->authenticate($token, null, $this->client::AUTH_ACCESS_TOKEN);
    }

    /**
     * @param array $params
     * @return array
     */
    public function myRepositories(array $params = []): array
    {
        $repositories = $this->client->user()->myRepositories($params);

        return $this->githubRepositoriesDto($repositories);
    }

    /**
     * @param OAuthProvider $oauthProvider
     */
    public function syncRepositories(OAuthProvider $oauthProvider) {
        $repositories = $this->myRepositories();

        foreach ($repositories as $repository) {
            GithubRepository::updateOrCreate(
                [
                    'repository_id' => $repository['repository_id'],
                    'oauth_provider_id' => $oauthProvider->id,
                ],
                $repository,
            );
        }
    }

    /**
     * @param string $username
     * @param string $repository
     * @return array
     */
    public function readme(string $username, string $repository): array
    {
        return $this->client->repos()->contents()->readme($username, $repository);
    }

    /**
     * @param string $handleId
     * @return array
     */
    private function userHandleByRepositories(string $handleId): array
    {
        $repositories = $this->client->user()->repositories($handleId, 'owner', 'pushed', 'desc', 5);

        return $this->githubRepositoriesDto($repositories);
    }

    /**
     * TODO: DTO
     * @param array $user
     * @return array
     */
    private function githubMeDto(array $user): array
    {
        return [
            'name' => $user['name'],
            'company' => $user['company'],
            'blog' => $user['blog'],
            'location' => $user['location'],
            'email' => $user['email'],
            'bio' => $user['bio'],
            'followers' => $user['followers'],
            'following' => $user['following'],
            'user_registered_at' => $this->convertDatetimeTzToTimestamp($user['created_at']),
            'user_updated_at' => $this->convertDatetimeTzToTimestamp($user['updated_at']),
        ];
    }

    /**
     * TODO: DTO
     * @param array $repositories
     * @return array
     */
    private function githubRepositoriesDto(array $repositories): array
    {
        $repos = [];
        foreach ($repositories as $repository) {
            $repos[] = [
                'repository_id' => $repository['id'],
                'type' => $repository['owner']['type'],
                'owner_id' => $repository['owner']['id'],
                'name' => $repository['name'],
                'description' => $repository['description'],
                'full_name' => $repository['full_name'],
                'node_id' => $repository['node_id'],
                'url' => $repository['html_url'],
                'data' => json_encode([]),
                'owner' => json_encode($repository['owner']),
                'license' => json_encode($repository['license']),
                'topics' => json_encode($repository['topics']),
                'stargazers_count' => $repository['stargazers_count'],
                'watchers_count' => $repository['watchers_count'],
                'forks_count' => $repository['forks_count'],
                'open_issues' => $repository['open_issues'],
                'default_branch' => $repository['default_branch'],
                'language' => $repository['language'],
                'is_visibility' => $repository['visibility'],
                'repository_created_at' => $this->convertDatetimeTzToTimestamp($repository['created_at']),
                'repository_updated_at' => $this->convertDatetimeTzToTimestamp($repository['updated_at']),
                'repository_pushed_at' => $this->convertDatetimeTzToTimestamp($repository['pushed_at']),
            ];
        }

        return $repos;
    }
}
