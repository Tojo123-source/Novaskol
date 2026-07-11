<?php

namespace App\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('Support/helpers.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        $connectedMode = filter_var((string) env('CONNECTED_MODE', false), FILTER_VALIDATE_BOOL);
        config(['app.connected_mode' => $connectedMode]);

        if ($connectedMode) {
            $databasePath = env('DB_DATABASE', storage_path('app/connected/database.sqlite'));
            config([
                'database.default' => 'sqlite',
                'database.connections.sqlite.database' => $databasePath,
            ]);
        }

        $this->repairMessageSchema();
    }

    private function repairMessageSchema(): void
    {
        try {
            if (! Schema::hasTable('messages')) {
                return;
            }

            Schema::table('messages', function (Blueprint $table) {
                if (! Schema::hasColumn('messages', 'sender_type')) {
                    $table->string('sender_type', 40)->default('admin')->after('conversation_id');
                }
                if (! Schema::hasColumn('messages', 'sender_id')) {
                    $table->unsignedBigInteger('sender_id')->default(0)->after('sender_type');
                }
                if (! Schema::hasColumn('messages', 'content')) {
                    $table->text('content')->nullable()->after('sender_id');
                }
                if (! Schema::hasColumn('messages', 'type')) {
                    $table->string('type', 50)->default('text')->after('content');
                }
                if (! Schema::hasColumn('messages', 'file_path')) {
                    $table->string('file_path', 255)->nullable()->after('type');
                }
                if (! Schema::hasColumn('messages', 'file_name')) {
                    $table->string('file_name', 255)->nullable()->after('file_path');
                }
                if (! Schema::hasColumn('messages', 'file_size')) {
                    $table->unsignedBigInteger('file_size')->nullable()->after('file_name');
                }
                if (! Schema::hasColumn('messages', 'is_read')) {
                    $table->boolean('is_read')->default(false)->after('file_size');
                }
                if (! Schema::hasColumn('messages', 'is_delivered')) {
                    $table->boolean('is_delivered')->default(false)->after('is_read');
                }
            });

            if (Schema::hasColumn('messages', 'user_id')) {
                DB::table('messages')->where('sender_id', 0)->update(['sender_id' => DB::raw('user_id')]);
            }
            if (Schema::hasColumn('messages', 'user_type')) {
                DB::table('messages')->where('sender_type', 'admin')->update(['sender_type' => DB::raw('user_type')]);
            }
            if (Schema::hasColumn('messages', 'contenu')) {
                DB::table('messages')->whereNull('content')->update(['content' => DB::raw('contenu')]);
            }
            if (Schema::hasColumn('messages', 'type_contenu')) {
                DB::table('messages')->where('type', 'text')->update(['type' => DB::raw('type_contenu')]);
            }
        } catch (\Throwable) {
            // The installer may boot before the database is ready; migrations will create the schema.
        }
    }
}
