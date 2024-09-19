<?php

use App\Models\Patient;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('street_address');
            $table->string('street_number');
            $table->string('district');
            $table->string('city');
            $table->string('zip_code')->nullable();
            $table->char('uf', 2)->nullable();
            $table->char('ddd', 2)->nullable();
            $table->foreignIdFor(Patient::class, 'belongs_to_patient')
                ->constrained('patients')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
