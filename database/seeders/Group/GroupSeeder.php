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

        $group1->users()->attach([1, 2 , 3 , 4]);
        $group2->users()->attach([1 , 3 ,5]);
        $group3->users()->attach([2 ,4, 5]);
        $group4->users()->attach([1, 2 , 3]);
    }
}
