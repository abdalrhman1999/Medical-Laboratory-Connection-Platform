<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('appointments')->delete();
        $roles=[
            'admin',
            'staff',
            'laboratory',
            'Nurse',
            'user',
        ];
        foreach ($roles as $role) {
            Role::create([
                'name'=>$role
            ]);
        }
    }
}
