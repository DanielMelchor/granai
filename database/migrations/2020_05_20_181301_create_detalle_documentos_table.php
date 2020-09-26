<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleDocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_documentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('maestro_documento_id');
            $table->unsignedBigInteger('admision_cargo_detalle_id')->nullable();
            $table->unsignedBigInteger('producto_id');
            $table->string('descripcion');
            $table->Integer('signo',2);
            $table->decimal('cantidad',12,2);
            $table->decimal('precio_unitario',12,2);
            $table->decimal('precio_bruto',12,2);
            $table->decimal('descuento',12,2);
            $table->decimal('recargo',12,2);
            $table->decimal('precio_neto',12,2);
            $table->decimal('precio_base',12,2);
            $table->decimal('precio_impuesto',12,2);
            $table->string('estado',1);
            $table->timestamps();
            $table->string('created_by',50);
            $table->string('updated_by',50);
            $table->foreign('maestro_documento_id')->references('id')->on('maestro_documentos')->onDelete('cascade');
            $table->foreign('admision_cargo_detalle_id')->references('id')->on('admision_cargo_detalles');
            $table->foreign('producto_id')->references('id')->on('productos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_documentos');
    }
}
