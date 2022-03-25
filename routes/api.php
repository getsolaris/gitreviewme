<?php

use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\SkillController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\UserSkillController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('test/gitAuth', [TestController::class, 'gitAuth'])->middleware('auth');
Route::get('test/profileUpdateQueue', [TestController::class, 'profileUpdateQueue'])->middleware('auth');

// api/auth
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth');
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [UserController::class, 'me'])->middleware('auth');
});

// api/oauth
Route::group(['prefix' => 'oauth'], function () {
    Route::get('', [OAuthController::class, 'index'])->middleware('administrator');
    Route::post('{driver}', [OAuthController::class, 'redirect']);
    Route::get('{driver}/callback', [OAuthController::class, 'handleCallback'])->name('oauth.callback');
});

// api/profiles/{provider_handle_id}
Route::group(['prefix' => 'profiles'], function () {
    Route::group(['prefix' => '{handle_id}'], function () {
        Route::get('', [ProfileController::class, 'showUserProviderHandleId']);
    });
});

// api/projects
Route::group(['prefix' => 'projects'], function () {
    Route::get('', [ProjectController::class, 'index']);
    Route::get('{project_id}', [ProjectController::class, 'show']);
});

Route::group(['middleware' => 'auth'], function () {
    // api/users
    Route::group(['prefix' => 'users'], function () {
        Route::get('', [UserController::class, 'index']);

        // api/users/{user_id}
        Route::group(['prefix' => '{user_id}'], function () {
            Route::get('', [UserController::class, 'show']);
            Route::put('', [UserController::class, 'update']);

            // api/users/{user_id}/skills
            Route::group(['prefix' => 'skills'], function () {
                Route::get('', [UserSkillController::class, 'index']);
                Route::post('', [UserSkillController::class, 'store']);

                // api/users/{user_id}/skills/{skill_id}
                Route::group(['prefix' => '{skill_id}'], function () {
                    Route::get('', [UserSkillController::class, 'show']);
                    Route::put('', [UserSkillController::class, 'update']);
                    Route::delete('', [UserSkillController::class, 'destroy']);
                });
            });
        });
    });

    // api/profiles
    Route::group(['prefix' => 'profiles'], function () {
        Route::get('', [ProfileController::class, 'index']);
        Route::get('test', [ProfileController::class, 'test']); // TODO: TEST

        // api/profiles/{user_id}
//        Route::group(['prefix' => '{user_id}'], function () {
//            Route::get('', [ProfileController::class, 'show']);
//        });

        // api/profiles/{provider_handle_id}
//        Route::group(['prefix' => '{handle_id}'], function () {
//            Route::get('', [ProfileController::class, 'showUserProviderHandleId']);
//        });
    });

    // api/skills
    Route::group(['prefix' => 'skills'], function () {
        Route::get('', [SkillController::class, 'index']);
        Route::get('{skill_id}', [SkillController::class, 'show']);
    });

    // api/projects
    Route::group(['prefix' => 'projects'], function () {
        Route::post('', [ProjectController::class, 'store']);

        // api/projects/{project_id}
        Route::group(['prefix' => '{id}'], function () {

            // api/projects/{project_id}/skills
            Route::group(['prefix' => 'skills'], function () {

            });
        });
    });
});
