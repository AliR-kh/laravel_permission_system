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
            $table->renameColumn('name', 'fname');
            $table->string('fname')->nullable()->change();
            $table->string('lname')->nullable()->after('fname');
            $table->string('phone')->unique()->nullable()->after('lname');
            $table->string('email')->nullable()->change();
            $table->timestamp('banned_at')->nullable()->after('email');
            $table->enum('status', ['no-login', 'login','deactive'])->default('no-login')->after('banned_at');
            $table->timestamp('email_verified_at')->nullable()->change();
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('fname', 'name');
            $table->string('name')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            $table->string('password')->nullable(false)->change();
            $table->dropColumn(["lname","phone","status","banned_at"]);
        });
    }
};
