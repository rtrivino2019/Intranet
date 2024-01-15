<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;

class UsersRolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        // reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Misc
        $miscPermission = Permission::create(['name' => 'Consultant']);

        // USER MODEL
        $userPermission1 = Permission::create(['name' => 'Create: Employee']);
        $userPermission2 = Permission::create(['name' => 'Read: Employee']);
        $userPermission3 = Permission::create(['name' => 'Update: Employee']);
        $userPermission4 = Permission::create(['name' => 'Delete: Employee']);

        // ROLE MODEL
        $rolePermission1 = Permission::create(['name' => 'Create: Role']);
        $rolePermission2 = Permission::create(['name' => 'Read: Role']);
        $rolePermission3 = Permission::create(['name' => 'Update: Role']);
        $rolePermission4 = Permission::create(['name' => 'Delete: Role']);

        // PERMISSION MODEL
        $permission1 = Permission::create(['name' => 'Create: Permission']);
        $permission2 = Permission::create(['name' => 'Read: Permission']);
        $permission3 = Permission::create(['name' => 'Update: Permission']);
        $permission4 = Permission::create(['name' => 'Delete: Permission']);

        // ADMINS
        $adminPermission1 = Permission::create(['name' => 'Read: Admin']);
        $adminPermission2 = Permission::create(['name' => 'Update: Admin']);

        // CREATE ROLES
        $userRole = Role::create(['name' => 'Employee'])->syncPermissions([
            $miscPermission,
        ]);

        $superAdminRole = Role::create(['name' => 'Owner'])->syncPermissions([
            $userPermission1,
            $userPermission2,
            $userPermission3,
            $userPermission4,
            $rolePermission1,
            $rolePermission2,
            $rolePermission3,
            $rolePermission4,
            $permission1,
            $permission2,
            $permission3,
            $permission4,
            $adminPermission1,
            $adminPermission2,
            $userPermission1,
        ]);
        $adminRole = Role::create(['name' => 'Admin'])->syncPermissions([
            $userPermission1,
            $userPermission2,
            $userPermission3,
            $userPermission4,
            $rolePermission1,
            $rolePermission2,
            $rolePermission3,
            $rolePermission4,
            $permission1,
            $permission2,
            $permission3,
            $permission4,
            $adminPermission1,
            $adminPermission2,
            $userPermission1,
        ]);
        $managerRole = Role::create(['name' => 'Manager'])->syncPermissions([
            $userPermission2,
            $rolePermission2,
            $permission2,
            $adminPermission1,
        ]);
        $assistantRole = Role::create(['name' => 'Assistant Manager'])->syncPermissions([
            $adminPermission1,
        ]);

        // CREATE ADMINS & USERS

        $faker = Faker::create();

        $userRobert = User::create([
            'name' => 'Robert',
            'lastname' => 'Trivino',
            //'is_admin' => 1,
            'email' => 'rtrivinoc@cielobluecantina.com',
            //'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
            'status' => true,
            'phone' => $faker->unique()->phoneNumber(),
            'address'=> $faker->address(),
            'w2w4_path' => $faker->imageUrl(640, 480),
            'position_id' => '2',
        ]);
        // Attach the user to restaurant with id = 1
        $userRobert->restaurants()->attach(1);
        $userRobert->restaurants()->attach(2);
        // Assign a role to the user
        $userRobert->assignRole($superAdminRole);

        // $userNoah = User::create([
        //     'name' => 'Noah',
        //     'lastname' => 'Trivino',
        //     //'is_admin' => 1,
        //     'email' => 'noah@gmail.com',
        //     //'email_verified_at' => now(),
        //     'password' => Hash::make('12345678'),
        //     'remember_token' => Str::random(10),
        //     'status' => true,
        //     'phone' => $faker->unique()->phoneNumber(),
        //     'address'=> $faker->address(),
        //     'w2w4_path' => $faker->imageUrl(640, 480),
        //     'position_id' => '2',
        // ]);
        // // Attach the user to restaurant with id = 1
        // $userNoah->restaurants()->attach(1);
        // $userNoah->restaurants()->attach(2);
        // // Assign a role to the user
        // $userNoah->assignRole($superAdminRole);



        // $userDiego = User::create([
        //     'name' => 'Diego',
        //     'lastname' => 'Trivino',
        //     //'is_admin' => 1,
        //     'email' => 'diego@gmail.com',
        //     //'email_verified_at' => now(),
        //     'password' => Hash::make('12345678'),
        //     'remember_token' => Str::random(10),
        //     'status' => true,
        //     'phone' => $faker->unique()->phoneNumber(),
        //     'address'=> $faker->address(),
        //     'w2w4_path' => $faker->imageUrl(640, 480),
        //     'position_id' => '2',
        // ]);
        // // Attach the user to restaurant with id =
        // $userDiego->restaurants()->attach(1);
        // // Assign a role to the user
        // $userDiego->assignRole($adminRole);

        // $userLulu = User::create([
        //     'name' => 'Lulu',
        //     'lastname' => 'Trivino',
        //     //'is_admin' => 1,
        //     'email' => 'lulu@gmail.com',
        //     //'email_verified_at' => now(),
        //     'password' => Hash::make('12345678'),
        //     'remember_token' => Str::random(10),
        //     'status' => true,
        //     'phone' => $faker->unique()->phoneNumber(),
        //     'address'=> $faker->address(),
        //     'w2w4_path' => $faker->imageUrl(640, 480),
        //     'position_id' => '2',
        // ]);
        // // Attach the user to restaurant with id =
        // $userLulu->restaurants()->attach(2);
        // // Assign a role to the user
        // $userLulu->assignRole($adminRole);

        // $userGorda = User::create([
        //     'name' => 'Gorda',
        //     'lastname' => 'Harris',
        //     //'is_admin' => 1,
        //     'email' => 'gorda@gmail.com',
        //     //'email_verified_at' => now(),
        //     'password' => Hash::make('12345678'),
        //     'remember_token' => Str::random(10),
        //     'status' => true,
        //     'phone' => $faker->unique()->phoneNumber(),
        //     'address'=> $faker->address(),
        //     'w2w4_path' => $faker->imageUrl(640, 480),
        //     'position_id' => '2',
        // ]);
        // // Attach the user to restaurant with id =
        // $userGorda->restaurants()->attach(3);
        // // Assign a role to the user
        // $userGorda->assignRole($adminRole);

        // $userSebastian = User::create([
        //     'name' => 'Sebastian',
        //     'lastname' => 'Harris',
        //     //'is_admin' => 1,
        //     'email' => 'sebastian@gmail.com',
        //     //'email_verified_at' => now(),
        //     'password' => Hash::make('12345678'),
        //     'remember_token' => Str::random(10),
        //     'status' => true,
        //     'phone' => $faker->unique()->phoneNumber(),
        //     'address'=> $faker->address(),
        //     'w2w4_path' => $faker->imageUrl(640, 480),
        //     'position_id' => '2',
        // ]);
        // // Attach the user to restaurant with id =
        // $userSebastian->restaurants()->attach(4);
        // // Assign a role to the user
        // $userSebastian->assignRole($adminRole);

        // $userMessy = User::create([
        //     'name' => 'Messy',
        //     'lastname' => 'Harris',
        //     //'is_admin' => 1,
        //     'email' => 'messy@gmail.com',
        //     //'email_verified_at' => now(),
        //     'password' => Hash::make('12345678'),
        //     'remember_token' => Str::random(10),
        //     'status' => true,
        //     'phone' => $faker->unique()->phoneNumber(),
        //     'address'=> $faker->address(),
        //     'w2w4_path' => $faker->imageUrl(640, 480),
        //     'position_id' => '2',
        // ]);
        // // Attach the user to restaurant with id =
        // $userMessy->restaurants()->attach(5);
        // // Assign a role to the user
        // $userMessy->assignRole($adminRole);




for ($i = 0; $i < 399; $i++) {
    $user = User::create([
        'name' => $faker->firstName,
        'lastname' => $faker->lastName,
        //'is_admin' => 0,  // Assuming these random users are not admins
        'email' => $faker->unique()->safeEmail,

        //'email' => $faker->unique()->firstName . '@gmail.com',

        //'email_verified_at' => now(),
        'password' => Hash::make('12345678'),
        'remember_token' => Str::random(10),
        'status' => true,
        'phone' => $faker->unique()->phoneNumber(),
        'address'=> $faker->address(),
        'w2w4_path' => $faker->imageUrl(640, 480),
        'position_id' => $faker->numberBetween(2, 6),

    ]);

    //Assign the userRole to the user
    $user->assignRole($userRole);

    //Attach the user to a random restaurant between 1 and 14
    $randomRestaurantId = rand(1, 14);
    $user->restaurants()->attach($randomRestaurantId);
    }

    }
}
