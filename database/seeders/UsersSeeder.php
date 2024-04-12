<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $this->runDataDefault();
        if (env('PROJECT_MODE', 'prod') === 'dev') {
            $this->runDataFake();
        }
    }

    public function runDataDefault(){
        User::create([
            'name' => 'Junior Guillermo',
            'surname' => 'Alcarraz Montes',
            'email' => 'junior@gmail.com',
            'password' => bcrypt('Alcarraz30.'),
            'address' => 'El pinar',
            'phone' => '(+51) 921457841',
            'birthday' => '1997-05-30',
            'img_profile' => 'public/profile/default/user.png'
        ]);

        User::create([
            'name' => 'Liseth',
            'surname' => 'Contreras Mendizabal',
            'email' => 'liseth@gmail.com',
            'password' => bcrypt('Liseth02.'),
            'address' => 'Jose Olaya',
            'phone' => '(+51) 985632158',
            'birthday' => '1994-08-02',
            'img_profile' => 'public/profile/default/user.png'
        ]);
    }

    public function runDataFake() {
    }
}
