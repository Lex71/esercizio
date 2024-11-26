<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    User::truncate();
    User::create([
      'name' => 'Root User',
      'email' => 'root@mail.com',
      'password' => Hash::make('password')
    ]);
  }
}
