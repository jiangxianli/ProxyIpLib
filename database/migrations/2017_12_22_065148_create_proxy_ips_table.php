<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProxyIpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proxy_ips', function (Blueprint $table) {
            $table->string('unique_id', 32);
            $table->string('ip', 15)->comment('IP地址');
            $table->string('port', 5)->comment('端口');
            $table->string('ip_address', 100)->comment('IP定位地址');
            $table->tinyInteger('anonymity')->default(1)->comment('匿名度 1:透明 2:高匿');
            $table->enum('protocol', ['http', 'https'])->comment('协议');
            $table->string('isp', 20)->comment('ISP 运营商');
            $table->integer('speed', 10)->comment('响应速度 毫秒');
            $table->timestamp('validated_at')->comment('最新校验时间');

            $table->timestamps();

            $table->index('unique_id');
            $table->index('ip');
            $table->unique(['ip', 'port', 'protocol']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proxy_ips');
    }
}
