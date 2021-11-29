<?php

use App\Models\UserType;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userTypes = ['Student', 'Teacher', 'Parent', 'Socially', 'At Work'];
        return $this->setUserType($userTypes);
    }

    private function setUserType($userTypes) {
        foreach ($userTypes as $userType) {
            UserType::create(['type' => $userType]);
        }
    }
}
