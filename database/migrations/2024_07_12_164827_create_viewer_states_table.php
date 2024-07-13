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
		Schema::create('viewer_states', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->string('name');
			$table->integer('version');
			$table->foreignUuid('authorization_id')
				->constrained('authorizations')
				->noActionOnUpdate()
				->nullOnDelete();
			$table->json('state');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('viewer_states');
	}
};
