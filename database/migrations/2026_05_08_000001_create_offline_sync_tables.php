<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sync_devices', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 64)->unique();
            $table->string('nom', 160);
            $table->string('type_appareil', 40)->default('pc');
            $table->string('role_sync', 40)->default('appareil_connecte');
            $table->string('plateforme', 80)->nullable();
            $table->string('adresse_ip', 80)->nullable();
            $table->string('code_appairage', 20)->nullable()->index();
            $table->boolean('autorise')->default(false);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('utilisateur_id')->nullable()->index();
            $table->string('utilisateur_role', 40)->nullable()->index();
            $table->timestamp('paired_at')->nullable();
            $table->timestamp('dernier_contact_at')->nullable();
            $table->timestamp('last_bootstrap_at')->nullable();
            $table->timestamps();
        });

        Schema::create('sync_batches', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 64)->unique();
            $table->string('device_uuid', 64)->index();
            $table->string('direction', 20)->default('push');
            $table->string('statut', 30)->default('en_attente')->index();
            $table->unsignedInteger('total_changements')->default(0);
            $table->unsignedInteger('total_appliques')->default(0);
            $table->unsignedInteger('total_conflits')->default(0);
            $table->longText('resume_json')->nullable();
            $table->text('message_erreur')->nullable();
            $table->timestamp('demarre_at')->nullable();
            $table->timestamp('termine_at')->nullable();
            $table->timestamps();
        });

        Schema::create('sync_changes', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 64)->unique();
            $table->string('batch_uuid', 64)->nullable()->index();
            $table->string('device_uuid', 64)->index();
            $table->unsignedBigInteger('utilisateur_id')->nullable()->index();
            $table->string('module', 80)->nullable()->index();
            $table->string('table_name', 100)->index();
            $table->string('record_uuid', 64)->index();
            $table->string('operation', 20)->index();
            $table->longText('payload_json')->nullable();
            $table->string('checksum', 128)->nullable();
            $table->string('statut', 30)->default('en_attente')->index();
            $table->text('message_erreur')->nullable();
            $table->timestamp('action_at')->nullable()->index();
            $table->timestamp('applique_at')->nullable();
            $table->timestamps();
        });

        Schema::create('sync_conflicts', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 64)->unique();
            $table->string('change_uuid', 64)->nullable()->index();
            $table->string('device_uuid', 64)->index();
            $table->string('table_name', 100)->index();
            $table->string('record_uuid', 64)->index();
            $table->string('type_conflit', 80)->default('modification_concurrente');
            $table->longText('donnees_locales_json')->nullable();
            $table->longText('donnees_entrantes_json')->nullable();
            $table->string('resolution', 40)->nullable();
            $table->unsignedBigInteger('resolu_par')->nullable();
            $table->timestamp('resolu_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sync_conflicts');
        Schema::dropIfExists('sync_changes');
        Schema::dropIfExists('sync_batches');
        Schema::dropIfExists('sync_devices');
    }
};
