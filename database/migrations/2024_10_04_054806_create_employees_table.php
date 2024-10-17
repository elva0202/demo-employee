<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->foreignId('state_id')->constrained()->cascadeOnDelete();
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->string('first_name');   //名 字串
            $table->string('last_name');    //姓 字串
            $table->string('middle_name');  //中間名 字串
            $table->string('address');      //地址 字串
            $table->char('zip_code');       //郵遞區號 長字元欄位
            $table->date('date_of_birth');  //生日 日期
            $table->date('date_hired');     //雇用日期 日期
            $table->timestamps();                   //自動生成兩個時間戳欄位（創建/更新）
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
