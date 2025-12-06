<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InsertSmartwatchCategory extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Insert a 'Smartwatch' category if it doesn't already exist
		if (! DB::table('categories')->where('slug', 'smartwatch')->exists()) {
			DB::table('categories')->insert([
				'parent_id' => null,
				'order' => 1,
				'name' => 'Smartwatch',
				'slug' => Str::slug('Smartwatch'),
				'created_at' => now(),
				'updated_at' => now(),
			]);
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('categories')->where('slug', 'smartwatch')->delete();
	}
}

