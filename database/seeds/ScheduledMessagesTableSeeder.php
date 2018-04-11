<?php

use Illuminate\Database\Seeder;

class ScheduledMessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\ScheduledMessage::class,100)->create();
    }
}
