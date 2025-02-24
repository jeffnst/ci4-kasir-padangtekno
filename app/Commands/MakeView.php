<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class MakeView extends BaseCommand
{
    protected $group = 'custom';
    protected $name = 'make:view';
    protected $description = 'Membuat file view baru di folder app/Views';

    public function run(array $params)
    {
        // Ambil nama view dari parameter
        $viewName = $params[0] ?? null;

        if (!$viewName) {
            CLI::error('Silakan berikan nama view! Contoh: php spark make:view home');
            return;
        }

        // Path file view yang akan dibuat
        // $viewPath = APPPATH . "Views/{$viewName}.php";
        $viewPath = APPPATH . "Views/" . str_replace('.', '/', $viewName) . ".php";


        if (file_exists($viewPath)) {
            CLI::error("View '{$viewName}.php' sudah ada!");
            return;
        }

        // Template default untuk view baru
        $template = <<<EOT
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{$viewName}</title>
    </head>

    <body>
        <h1>Ini adalah view {$viewName}</h1>
    </body>

    </html>
    EOT;

        // Simpan file baru
        file_put_contents($viewPath, $template);

        CLI::write("View '{$viewName}.php' berhasil dibuat di app/Views/", 'green');
    }
}
