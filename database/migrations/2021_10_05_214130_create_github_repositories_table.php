<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGithubRepositoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('github_repositories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oauth_provider_id');
            $table->integer('repository_id')->comment('id');
            $table->enum('type', ['User', 'Organization']);
            $table->integer('owner_id')->comment('repository owner id');
            $table->string('name')->comment('repository name');
            $table->string('description')->nullable()->comment('repository description');
            $table->string('full_name')->comment('user handle id + repository name');
            $table->string('node_id');
            $table->string('url')->comment('html_url');
            $table->json('data')->nullable()->comment('temporary');
            $table->json('owner');
            $table->json('license')->nullable();
            $table->json('topics')->nullable();
            $table->integer('stargazers_count')->default(0);
            $table->integer('watchers_count')->default(0);
            $table->integer('forks_count')->default(0);
            $table->integer('open_issues')->default(0);
            $table->string('default_branch');
            $table->string('language')->nullable();
            $table->enum('is_visibility', ['public', 'private']);
            $table->dateTimeTz('repository_created_at')->comment('created_at');
            $table->dateTimeTz('repository_updated_at')->comment('updated_at');
            $table->dateTimeTz('repository_pushed_at')->comment('pushed_at');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('github_repositories');
    }
}
