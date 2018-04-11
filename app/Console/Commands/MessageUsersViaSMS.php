<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use AfricasTalking\SDK\AfricasTalking;
use App\User;
use Illuminate\Support\Facades\Log;
use App\Libs\ATConfig;

class MessageUsersViaSMS extends Command
{
    use ATConfig;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'message:sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Send SMS TO USERS WHOSE WHO HAV\'NT RECEIVED IN THE LAST X DAYS";

    private $AT;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->setAT();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::getUsersDueToGetSMS();

        $phoneNumbers = $users->pluck('phone');


        $SMS = $this->AT->sms();

        $options = [
            'message' => "This Is The SMS, Your Device Should Explode In 5MIN",
            'enqueue' => true,
            'from' => $this->getATShortCode(),
            'to' => $phoneNumbers
        ];

        // SMS Is Being Sent To the Following Users
        User::setSendingStatusTrue($users);

        $SMS->send($options);

        log::info("SMS sent To These numbers At :${phoneNumbers}");
    }

}
