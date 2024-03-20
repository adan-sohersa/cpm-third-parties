<?php

use App\Enums\Resource\ResourceTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function PHPUnit\Framework\callback;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create(table: 'resources', callback: function (Blueprint $table) {
			$table->uuid(column: 'id')->primary();
			$table->foreignUuid(column: 'authorization_id')->nullable()->constrained();
			$table->string(column: 'path_in_source')->nullable();
			$table->string(column: 'id_in_source')->nullable();
			$table->string(column: 'alias', length: 250);
			$table->enum(column: 'type', allowed: array_column(ResourceTypes::cases(), 'value'));
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists(table: 'resources');
	}
};
