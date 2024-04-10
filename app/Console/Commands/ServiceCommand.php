<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ServiceCommand extends Command
{
    protected $signature = 'make:service {name}';

    protected $description = 'Create service';

    public function handle()
    {
        $name = $this->argument('name');
        $filePath = app_path("Services/{$name}.php");

        // Verificar si el directorio existe, si no, crearlo
        $directory = dirname($filePath);
        if (!File::isDirectory($directory)) File::makeDirectory($directory, 0755, true, true);

        // Verificar si el archivo ya existe
        if (File::exists($filePath)) {
            $this->error("The service {$name} already exists!");
            return;
        }

        File::put($filePath, $this->traitTemplate($name));

        $this->info("Service {$name} created successfully!");
    }

    protected function traitTemplate($name)
    {
        return "<?php\n\nnamespace App\Services;\n\nuse App\Traits\HasResponse;\n\nclass $name\n{\n    use HasResponse;\n}\n";
    }
}
