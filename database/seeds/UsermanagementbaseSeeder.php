<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UsermanagementSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

        User::create([
            'name'       => 'Bob Bloom',
            'email'      => 'admin@example.org',
            'password'   => bcrypt('password'),
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);

        Group::create([
            'title'       => 'Registered',
            'description' => 'Basic front-end access only.',
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);

        Group::create([
            'title'       => 'Administrator',
            'description' => 'Basic back-end administration access only.',
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);

        Group::create([
            'title'       => 'Super Administrator',
            'description' => 'Highest back-end administration access.',
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);


        User_group::create([
            'user_id'   => 1,
            'group_id'  => 1,
        ]);

        User_group::create([
            'user_id'   => 1,
            'group_id'  => 3,
        ]);

	}
}
