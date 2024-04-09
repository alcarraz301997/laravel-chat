<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class TraitsCommand extends Command
{
    protected $signature = 'make:trait {name}';

    protected $description = 'Create global variable';

    public function handle()
    {
        $name = $this->argument('name');
        $filePath = app_path("Traits/{$name}.php");

        // Verificar si el directorio existe, si no, crearlo
        $directory = dirname($filePath);
        if (!File::isDirectory($directory)) File::makeDirectory($directory, 0755, true, true);

        // Verificar si el archivo ya existe
        if (File::exists($filePath)) {
            $this->error("The trait {$name} already exists!");
            return;
        }

        File::put($filePath, $this->traitTemplate($name));

        $this->info("Trait {$name} created successfully!");
    }

    protected function traitTemplate($name)
    {
        return "<?php\n\nnamespace App\Traits;\n\ntrait $name\n{\n    // Add your response methods here\n}\n";
    }
}
