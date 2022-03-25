<?php

namespace App\Http\Controllers;

use App\Models\GithubRepository;
use App\Models\OAuthProvider;
use App\Services\GithubService;

class TestController extends Controller
{
    protected GithubService $githubService;
    public function __construct(GithubService $githubService) {
        $this->githubService = $githubService;
    }

    public function gitAuth() {
        $gitAccessToken = auth()->user()->oauthProvider->access_token;
        $this->githubService->authentication($gitAccessToken);
//        print_r($this->githubService->me());
    }

    public function profileUpdateQueue() {
        $oauthProvider = OAuthProvider::find(24);
        $gitAccessToken = auth()->user()->oauthProvider->access_token;
        $this->githubService->authentication($gitAccessToken);

        $repositories = $this->githubService->myRepositories();
//        return $repositories;

        $checkDeletedRepositories = [];

        foreach ($repositories as $repository) {
//            echo $repository['repository_id'];
            GithubRepository::updateOrCreate(
                [
                    'repository_id' => $repository['repository_id'],
                    'oauth_provider_id' => $oauthProvider->id,
                ],
                $repository
            );
//            echo "<br/>\n";
        }

//        return $repositories;
    }
}
