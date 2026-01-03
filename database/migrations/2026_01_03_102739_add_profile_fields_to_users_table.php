<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->after('name');
            $table->string('full_name')->nullable()->after('username');
            $table->enum('gender', ['male', 'female'])->nullable()->after('email');
            $table->date('birth_date')->nullable()->after('gender');
            $table->string('phone')->nullable()->after('birth_date');
            $table->text('address')->nullable()->after('phone');
            $table->string('avatar')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'full_name', 'gender', 'birth_date', 'phone', 'address', 'avatar']);
        });
    }
};
