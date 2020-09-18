<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIpLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ip_location', function (Blueprint $table) {
            $table->bigInteger("ip_number")->default(0)->comment("IP地址转数字");
            $table->string('ip', 16)->default("")->comment("IP地址");
            $table->string('country', 20)->default("")->comment("所在国");
            $table->string('region', 20)->default("")->comment("地区");
            $table->string('city', 20)->default("")->comment("城市");
            $table->string('isp', 40)->default("")->comment("ISP");
            $table->primary("ip_number");
            $table->unique("ip");
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
        Schema::dropIfExists('ip_location');
    }
}
