<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('migrations') && Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE migrations MODIFY id INT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        if (Schema::hasTable('users') && ! Schema::hasColumn('users', 'is_active')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('email');
            });
        }

        if (! Schema::hasTable('type')) {
            Schema::create('type', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('name');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->string('slug')->nullable();
                $table->string('meta_title_en')->nullable();
                $table->string('meta_title_ar')->nullable();
                $table->text('meta_description_en')->nullable();
                $table->text('meta_description_ar')->nullable();
                $table->string('meta_keywords_en')->nullable();
                $table->string('meta_keywords_ar')->nullable();
                $table->string('og_image_path')->nullable();
                $table->boolean('is_indexable')->default(true);
            });
        }

        if (! Schema::hasTable('cities')) {
            Schema::create('cities', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('name');
                $table->integer('price');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('admins')) {
            Schema::create('admins', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('username', 50)->unique();
                $table->string('password');
                $table->string('email', 100)->unique();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('guest_users')) {
            Schema::create('guest_users', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('name');
                $table->string('email');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('discount_codes')) {
            Schema::create('discount_codes', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('code', 50)->unique();
                $table->integer('discount_percentage')->nullable();
                $table->date('expiry_date')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('messages')) {
            Schema::create('messages', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('name');
                $table->string('email');
                $table->string('phone');
                $table->string('message');
                $table->timestamps();
                $table->timestamp('read_at')->nullable();
            });
        }

        if (! Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('type_id');
                $table->string('name');
                $table->timestamps();
                $table->boolean('is_active')->default(true);
                $table->string('slug')->nullable();
                $table->string('meta_title_en')->nullable();
                $table->string('meta_title_ar')->nullable();
                $table->text('meta_description_en')->nullable();
                $table->text('meta_description_ar')->nullable();
                $table->string('meta_keywords_en')->nullable();
                $table->string('meta_keywords_ar')->nullable();
                $table->string('og_image_path')->nullable();
                $table->boolean('is_indexable')->default(true);

                $table->foreign('type_id')->references('id')->on('type');
            });
        }

        if (! Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2);
                $table->integer('sale')->nullable()->default(0);
                $table->integer('type_id')->nullable();
                $table->integer('category_id')->nullable();
                $table->string('color');
                $table->boolean('is_active')->default(true);
                $table->boolean('is_highest')->nullable()->default(false);
                $table->timestamps();
                $table->unsignedBigInteger('guide_id')->nullable();
                $table->string('slug')->nullable();
                $table->string('meta_title_en')->nullable();
                $table->string('meta_title_ar')->nullable();
                $table->text('meta_description_en')->nullable();
                $table->text('meta_description_ar')->nullable();
                $table->string('meta_keywords_en')->nullable();
                $table->string('meta_keywords_ar')->nullable();
                $table->string('og_image_path')->nullable();
                $table->boolean('is_indexable')->default(true);

                $table->foreign('category_id')->references('id')->on('categories');
                $table->foreign('type_id')->references('id')->on('type');
            });
        }

        if (! Schema::hasTable('product_images')) {
            Schema::create('product_images', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('images');
                $table->integer('products_id');
                $table->timestamps();

                $table->foreign('products_id')->references('id')->on('products');
            });
        }

        if (! Schema::hasTable('product_items')) {
            Schema::create('product_items', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('products_id');
                $table->string('quantity')->nullable();
                $table->string('size');
                $table->timestamps();

                $table->foreign('products_id')->references('id')->on('products');
            });
        }

        if (! Schema::hasTable('addresses')) {
            Schema::create('addresses', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('address_line1');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->integer('guest_id')->nullable();
                $table->string('address_line2')->nullable();
                $table->string('city', 100);
                $table->string('state', 100)->nullable();
                $table->string('postal_code', 20)->nullable();
                $table->string('phone_number')->nullable();
                $table->string('country', 100)->nullable();
                $table->timestamps();
                $table->boolean('is_default')->default(false);

                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('guest_id')->references('id')->on('guest_users');
            });
        }

        if (! Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->integer('id', true);
                $table->unsignedBigInteger('user_id')->nullable();
                $table->integer('guest_id')->nullable();
                $table->integer('address_id')->nullable();
                $table->decimal('total_amount', 10, 2);
                $table->integer('discount_code_id')->nullable();
                $table->string('status')->default('pending');
                $table->integer('city_id')->nullable();
                $table->timestamps();
                $table->decimal('delivery_fee', 10, 2)->nullable();
                $table->string('meta_event_id', 64)->nullable();

                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('city_id')->references('id')->on('cities');
                $table->foreign('discount_code_id')->references('id')->on('discount_codes');
                $table->foreign('address_id')->references('id')->on('addresses');
                $table->foreign('guest_id')->references('id')->on('guest_users');
            });
        }

        if (! Schema::hasTable('order_discounts')) {
            Schema::create('order_discounts', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('order_id');
                $table->integer('discount_code_id');
                $table->decimal('discount_amount', 10, 2);
                $table->timestamps();

                $table->foreign('order_id')->references('id')->on('orders');
                $table->foreign('discount_code_id')->references('id')->on('discount_codes');
            });
        }

        if (! Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('orders_id');
                $table->integer('products_id');
                $table->integer('size_id')->nullable();
                $table->string('size');
                $table->integer('quantity');
                $table->decimal('price', 10, 2);
                $table->timestamps();

                $table->foreign('orders_id')->references('id')->on('orders');
                $table->foreign('products_id')->references('id')->on('products');
                $table->foreign('size_id')->references('id')->on('product_items');
            });
        }

        if (! Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('orders_id');
                $table->string('payment_method');
                $table->decimal('amount', 10, 2);
                $table->enum('status', ['pending', 'completed', 'failed'])->nullable()->default('pending');
                $table->timestamps();

                $table->foreign('orders_id')->references('id')->on('orders');
            });
        }

        if (! Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->integer('id', true);
                $table->unsignedBigInteger('user_id');
                $table->integer('product_id');
                $table->string('comment');
                $table->string('rating');
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->foreign('product_id')->references('id')->on('products');
                $table->foreign('user_id')->references('id')->on('users');
            });
        }

        if (! Schema::hasTable('shopping_cart')) {
            Schema::create('shopping_cart', function (Blueprint $table) {
                $table->integer('id', true);
                $table->unsignedBigInteger('user_id');
                $table->integer('products_id');
                $table->integer('size_id');
                $table->integer('quantity');
                $table->timestamps();

                $table->foreign('products_id')->references('id')->on('products');
                $table->foreign('size_id')->references('id')->on('product_items');
                $table->foreign('user_id')->references('id')->on('users');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('shopping_cart');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('order_discounts');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('product_items');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('discount_codes');
        Schema::dropIfExists('guest_users');
        Schema::dropIfExists('admins');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('type');

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'is_active')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }
    }
};
