<?php

namespace App\Console\Commands;

use App\Libs\ATConfig;
use Illuminate\Support\Facades\Log;
use AfricasTalking\SDK\AfricasTalking;
use Illuminate\Console\Command;
use App\ScheduledMessage;

class MessageAllUsersViaSMS extends Command
{
    use ATConfig;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'message:sms-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        User::SetSendingStatusTrue($users);

        $SMS = $this->AT->sms();

        $options = [
            'message' => "This Is The SMS, Your Device Should Explode In 5MIN",
            'enqueue' => true,
            'from' => $this->getATShortCode(),
            'to' => $phoneNumbers
        ];

        $SMS->send($options);

        log::info("SMS sent To These numbers At :${phoneNumbers}");
    }
}
