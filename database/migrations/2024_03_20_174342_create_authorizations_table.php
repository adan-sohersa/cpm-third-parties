<?php

use App\Enums\Authorization\ThirdPartyProviders;
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
		Schema::create('authorizations', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->enum('provider', array_column(ThirdPartyProviders::cases(), 'value'));
			$table->string('access_token');
			$table->string('refresh_token');
			$table->integer('expires_at')->nullable();
			$table->string('usarname_at_provider', 50)->nullable();
			$table->string('authorizable_class', 50);
			$table->string('authorizable_id', 50);
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('authorizations');
	}
};
