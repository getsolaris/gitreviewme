<?php

namespace App\Jobs\Github;

use App\Models\OAuthProvider;
use App\Services\GithubService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncUserInformation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var OAuthProvider $oauthProvider
     */
    protected OAuthProvider $oauthProvider;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(OAuthProvider $oauthProvider)
    {
        $this->oauthProvider = $oauthProvider;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(GithubService $githubService)
    {
        $githubService->authentication($this->oauthProvider->access_token);

        $me = $githubService->me();
        $this->oauthProvider->githubInformation()->updateOrCreate($me);
    }
}
