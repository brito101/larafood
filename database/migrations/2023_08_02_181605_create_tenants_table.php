<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->uuid('uuid')->nullable();
            $table->string('cnpj')->nullable();
            $table->string('name')->nullable();
            $table->string('url')->nullable();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();

            // Status tenant (se inativar 'N' ele perde o acesso ao sistema)
            $table->enum('active', ['Y', 'N'])->default('Y');

            // Subscription
            $table->date('subscription')->nullable(); // Data que se inscreveu
            $table->date('expires_at')->nullable(); // Data que expira o acesso
            $table->string('subscription_id', 255)->nullable(); // Identificado do Gateway de pagamento
            $table->boolean('subscription_active')->default(false); // Assinatura ativa (porque pode cancelar)
            $table->boolean('subscription_suspended')->default(false); // Assinatura cancelada

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenants');
    }
}
