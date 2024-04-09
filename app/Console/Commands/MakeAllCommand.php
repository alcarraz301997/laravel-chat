<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class MakeAllCommand extends Command
{
    protected $signature = 'make:all {name}';

    protected $description = 'Create migration, model, service, controller, resource, and request for the given name';

    public function handle()
    {
        $name = $this->argument('name');

        // Make migration
        $migrationName = 'create_tbl_' . Str::snake($name);
        Artisan::call('make:migration', ['name' => $migrationName]);

        // Make model
        Artisan::call('make:model', ['name' => $name]);

        // Make service
        Artisan::call('make:service', ['name' => $name . 'Service']);

        // Make controller
        Artisan::call('make:controller', ['name' => $name . 'Controller']);

        // Make resource
        Artisan::call('make:resource', ['name' => $name . 'Resource']);

        // Make request
        Artisan::call('make:request', ['name' => $name . 'Request']);

        $this->info('All files created successfully!');
    }
}
