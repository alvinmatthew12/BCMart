<?php

use Illuminate\Database\Seeder;
use App\Models\Wallet;

class WalletTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Wallet::create([
            "balance" => 0,
            "user_id" => 1
        ]);
        Wallet::create([
            "balance" => 0,
            "user_id" => 2
        ]);
        Wallet::create([
            "balance" => 0,
            "user_id" => 3
        ]);
    }
}
