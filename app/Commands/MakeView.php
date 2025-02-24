<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class MakeView extends BaseCommand
{
    protected $group = 'custom';
    protected $name = 'make:view';
    protected $description = 'Generate a new view file';

    public function run(array $params)
    {
        $viewName = $params[0] ?? null;

        if (!$viewName) {
            CLI::error('Please provide a view name. Example: php spark make:view admin.dashboard');
            return;
        }

        // Konversi titik (.) menjadi slash (/) untuk mendukung subfolder
        $viewPath = APPPATH . "Views/" . str_replace('.', '/', $viewName) . ".php";

        // Buat folder jika belum ada
        $directory = dirname($viewPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true); // true untuk recursive directory creation
        }

        // Cek apakah file sudah ada
        if (file_exists($viewPath)) {
            CLI::error("View '{$viewName}.php' already exists!");
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

        CLI::write("View '{$viewName}.php' created successfully!", 'green');
    }
}
