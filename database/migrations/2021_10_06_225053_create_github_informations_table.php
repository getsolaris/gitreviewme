<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGithubInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('github_informations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oauth_provider_id');
            $table->string('name')->nullable();
            $table->string('company')->nullable();
            $table->string('blog')->nullable();
            $table->string('location')->nullable();
            $table->string('email')->nullable();
            $table->string('bio')->nullable();
            $table->integer('followers')->default(0);
            $table->integer('following')->default(0);
            $table->dateTimeTz('user_registered_at');
            $table->dateTimeTz('user_updated_at');
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
        Schema::dropIfExists('user_github_informations');
    }
}
