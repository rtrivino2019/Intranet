<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Report;
use App\Models\Product;
use App\Models\Position;
use App\Models\Supplier;
use App\Models\Restaurant;
use App\Models\CategoryFood;
use Illuminate\Database\Seeder;
use Database\Seeders\UsersRolesAndPermissionsSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            UsersRolesAndPermissionsSeeder::class

        ]);
         //User::factory(100)->create();

         Restaurant::create(['name' => 'Bamba Acworth']);//,'slug' => 'bamba-acworth']);
         Restaurant::create(['name' => 'Bamba Dallas']);//,'slug' => 'bamba-dallas']);
         Restaurant::create(['name' => 'Bamba Kennesaw']);//,'slug' => 'bamba-kennesaw']);
         Restaurant::create(['name' => 'Bamba Loganville']);//,'slug' => 'bamba-loganville']);
         Restaurant::create(['name' => 'Cielo Acworth']);//,'slug' => 'cielo-acworth']);
         Restaurant::create(['name' => 'Cielo Dallas']);//,'slug' => 'cielo-dallas']);
         Restaurant::create(['name' => 'Cielo Loganville']);//,'slug' => 'cielo-loganville']);
         Restaurant::create(['name' => 'Cielo Smyrna']);//,'slug' => 'cielo-smyrna']);
         Restaurant::create(['name' => 'Luna Canton']);//,'slug' => 'cielo-canton']);
         Restaurant::create(['name' => 'Luna Kennesaw']);//,'slug' => 'cielo-kennesaw']);
         Restaurant::create(['name' => 'Luna Smyrna']);//,'slug' => 'cielo-smyrna']);
         Restaurant::create(['name' => 'San Lucas Grayson']);//,'slug' => 'san-lucas-grayson']);
         Restaurant::create(['name' => 'San Lucas Hiram']);//,'slug' => 'san-lucas-hiram']);
         Restaurant::create(['name' => 'San Andres Monroe']);//,'slug' => 'san-andres-monroe']);


         Supplier::create(['name' => 'All American']);
         Supplier::create(['name' => 'PFG']);
         Supplier::create(['name' => 'Savannah']);
         Supplier::create(['name' => 'Coffee']);

         CategoryFood::create(['name' => 'Vegetales']);
         CategoryFood::create(['name' => 'Carnes']);
         CategoryFood::create(['name' => 'Papeleria']);
         CategoryFood::create(['name' => 'Cleaners']);
         CategoryFood::create(['name' => 'Nevera']);
         CategoryFood::create(['name' => 'Despensa']);
         CategoryFood::create(['name' => 'Sodas/Otros']);

     
         Position::create(['p_name' => 'Owner']);
         Position::create(['p_name' => 'Manager']);
         Position::create(['p_name' => 'Kitchen']);
         Position::create(['p_name' => 'Server']);
         Position::create(['p_name' => 'Food Runner']);
         Position::create(['p_name' => 'Bar']);
         Position::create(['p_name' => 'Hostess']);


         //Report::factory(100)->create();
    }
}
