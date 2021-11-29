<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['Admin', 'User'];
        return $this->setRoles($roles);
    }

    private function setRoles($roles)
    {
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }
    }
}
