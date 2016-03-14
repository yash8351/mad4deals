<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gl_images', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('gallery_id');
			$table->string('name');
			$table->string('url');
			$table->string('extension');
			$table->string('hash');
			$table->string('path');
			$table->string('available_sizes');
			$table->string('avail_sizes');
			$table->string('caption');
			$table->text('description');
			$table->string('author');
			$table->integer('views');
			$table->integer('active');
			$table->integer('display_order');
			//$table->text('position_tags');
			$table->text('meta_tags');
			$table->text('attributes');
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
		Schema::drop('gl_images');
	}

}