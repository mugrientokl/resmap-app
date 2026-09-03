<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

#[Signature('app:restore-database-backup {filename : Nombre del backup JSON} {--force : Confirma la restauración}')]
#[Description('Restaura datos desde un backup JSON')]
class RestoreDatabaseBackup extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (! $this->option('force')) {
            $this->error('La restauración reemplazará datos actuales. Usa --force para confirmar.');

            return self::FAILURE;
        }

        $filename = $this->argument('filename');
        if (! preg_match('/^database-[0-9_-]+\.json$/', $filename)) {
            $this->error('Nombre de backup no válido.');

            return self::FAILURE;
        }

        $disk = Storage::disk('local');
        $path = 'backups/'.$filename;
        if (! $disk->exists($path)) {
            $this->error('El backup no existe.');

            return self::FAILURE;
        }

        $backup = json_decode($disk->get($path), true, 512, JSON_THROW_ON_ERROR);
        $tables = [
            'resmap_db.users', 'resmap_db.categorias', 'resmap_db.clientes',
            'resmap_db.productos', 'resmap_db.productos_importados', 'resmap_db.ventas',
            'resmap_db.detalle_ventas', 'resmap_db.solicitud_webs', 'resmap_db.notifications',
        ];

        DB::transaction(function () use ($backup, $tables): void {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            foreach (array_reverse($tables) as $table) {
                DB::table(str_replace('resmap_db.', '', $table))->delete();
            }
            foreach ($tables as $table) {
                $rows = $backup[$table] ?? [];
                foreach (array_chunk($rows, 250) as $chunk) {
                    DB::table(str_replace('resmap_db.', '', $table))->insert($chunk);
                }
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        });

        $this->info('Datos restaurados correctamente desde '.$filename.'.');

        return self::SUCCESS;
    }
}
