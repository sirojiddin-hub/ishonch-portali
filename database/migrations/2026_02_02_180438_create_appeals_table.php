<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('appeals', function (Blueprint $table) {
                $table->id();
                $table->string('track_code')->unique(); // ID raqam
                $table->boolean('is_anonymous')->default(true); // Anonimlik
                $table->string('full_name')->nullable();
                $table->string('phone')->nullable();
                $table->string('type'); // Muammo turi
                $table->string('region'); // Viloyat
                $table->string('district')->nullable();
                $table->string('organization')->nullable();
                $table->text('description'); // Matn
                $table->json('files')->nullable(); // Rasm/Video
                $table->enum('status', ['new', 'processing', 'closed', 'rejected'])->default('new');
                $table->timestamps();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('appeals');
        }
    };