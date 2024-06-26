<?php

namespace Database\Seeders;

use App\Models\ContentTypes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $this->runDataDefault();
        if (env('PROJECT_MODE', 'prod') === 'dev') {
            $this->runDataFake();
        }
    }

    public function runDataDefault(){
        ContentTypes::create(['name' => 'Texto']);
        ContentTypes::create(['name' => 'Imagen/Video']);
        ContentTypes::create(['name' => 'Archivo']);
        // ContentTypes::create(['name' => 'Eliminado']);
    }

    public function runDataFake() {
    }
}
