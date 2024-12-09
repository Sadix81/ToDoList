<?php

namespace Database\Seeders\Group;

use App\Models\Group\Group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $group1 = Group::create([
            'name' => 'group_1',
            'owner_id' => 1,
        ]);

        $group2 = Group::create([
            'name' => 'group_2',
            'owner_id' => 1,
        ]);

        $group3 = Group::create([
            'name' => 'group_3',
            'owner_id' => 2,
        ]);

        $group4 = Group::create([
            'name' => 'group_4',
            'owner_id' => 2,
        ]);

        // $group1->userRoles()->attach([
        //     ['user_id' => 1, 'role_id' => 2], // Assuming role_id exists
        //     ['user_id' => 2, 'role_id' => 2],
        //     ['user_id' => 3, 'role_id' => 3],
        //     ['user_id' => 4, 'role_id' => 3],
        // ]);
        
        // $group2->userRoles()->attach([
        //     ['user_id' => 1, 'role_id' => 2],
        //     ['user_id' => 3, 'role_id' => 3],
        //     ['user_id' => 5, 'role_id' => 3],
        // ]);  
         // $group3->userRoles()->attach([2 ,4, 5]);
        // $group4->userRoles()->attach([1, 2 , 3]);
    }
}
