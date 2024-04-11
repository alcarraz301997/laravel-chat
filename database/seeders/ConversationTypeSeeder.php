<?php

namespace Database\Seeders;

use App\Models\ConversationTypes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConversationTypeSeeder extends Seeder
{
    public function run(): void
    {
        $this->runDataDefault();
        if (env('PROJECT_MODE', 'prod') === 'dev') {
            $this->runDataFake();
        }
    }

    public function runDataDefault(){
        ConversationTypes::create(['name' => 'Chat']);
        ConversationTypes::create(['name' => 'Grupo']);
    }

    public function runDataFake() {
    }
}
