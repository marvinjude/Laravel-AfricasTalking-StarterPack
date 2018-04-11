<?php

namespace App\Libs;

trait ATConfig
{
    public function setAT(): void
    {
        // use 'sandbox' for development in the test environment
        $username = getenv('AFRICASTALKING_USERNAME');

        // use your sandbox app API key for development in the test environment
        $apiKey = getenv('AFRICASTALKING_API_KEY');

        $this->AT = new AfricasTalking($username, $apiKey);
    }


    public function getATShortCode()
    {
        return getenv('AFRICASTALKING_SHORT_CODE');
    }
}