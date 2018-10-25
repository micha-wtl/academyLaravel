<?php

use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 100; $i++) {
            DB::table('comments')->insert([
                'post_id' => rand(1,100),
                'message' => str_random(150),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
