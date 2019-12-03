<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->increments('id');
            $table->string("title")->default("")->comment("标题");
            $table->enum("area", ["web_index", "blog_index", 'blog_detail'])->default("web_index")->comment("广告区域 web_index:首页 blog_index:文章列表页 blog_detail:文章详情页");
            $table->text("ad_content")->comment("广告内容");
            $table->enum("is_show", ["yes", "no"])->default("yes")->comment("是否显示 yes:显示 no:隐藏");
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
        Schema::dropIfExists('ads');
    }
}
