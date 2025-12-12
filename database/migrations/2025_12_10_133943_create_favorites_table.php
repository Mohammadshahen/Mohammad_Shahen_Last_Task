<?php
// database/migrations/xxxx_create_favorites_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('blog_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->timestamps();
            
            // منع التكرار - يمكن للمستخدم إضافة المدونة للمفضلة مرة واحدة فقط
            $table->unique(['user_id', 'blog_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('favorites');
    }
};