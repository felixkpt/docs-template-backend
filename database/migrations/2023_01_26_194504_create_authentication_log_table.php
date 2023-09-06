<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if(!Schema::hasTable('authentication_log')){
            Schema::create('authentication_log', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('authenticatable_id');
                $table->string('authenticatable_type');
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamp('login_at')->nullable();
                $table->boolean('login_successful')->default(false);
                $table->timestamp('logout_at')->nullable();
                $table->boolean('cleared_by_user')->default(false);
            });
        }
    }
    
    public function down(){
        Schema::dropIfExists('authentication_log');
    }
};
