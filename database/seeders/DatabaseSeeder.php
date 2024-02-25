<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

            $this->call([
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            PermissionRoleTableSeeder::class,
            UsersTableSeeder::class,
            PlayerTableSeeder::class,
            RoleUserTableSeeder::class,
            BankTableSeeder::class,
            GameTypeTableSeeder::class,
            ProductTableSeeder::class,
            GameTypeProductTableSeeder::class,
            CQ9GameListTableSeeder::class,
            DragonSoftGameListTableSeeder::class,
            EvoPlayGameListTableSeeder::class,
            HabaneroGameListTableSeeder::class,
            JokerGameListTableSeeder::class,
            PlayStarGameListTableSeeder::class,
            PlayTechGameListSeeder::class,
            ReevoGameListTableSeeder::class,
            SkyWindGameListTableSeeder::class
        ]);
      
    }
}
