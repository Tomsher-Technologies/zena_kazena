<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('parent_id')->default(0);
            $table->integer('level')->default(0);
            $table->string('name', 50);
            $table->integer('order_level')->default(0);
            $table->double('commision_rate', 8, 2)->default(0.00);
            $table->string('banner', 100)->nullable();
            $table->string('icon', 100)->nullable();
            $table->integer('featured')->default(0);
            $table->integer('top')->default(0);
            $table->integer('digital')->default(0);
            $table->string('slug', 255)->nullable()->index('slug');
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->mediumText('meta_keyword')->nullable();
            $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
