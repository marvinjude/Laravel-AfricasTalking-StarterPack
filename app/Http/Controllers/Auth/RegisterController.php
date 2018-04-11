<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use AfricasTalking\SDK\AfricasTalking;
use Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /*Africa's Talking Object*/

    private $AT;


    public function __construct()
    {
        $this->middleware('guest');
        $this->setAT();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        //TODO: "validate phone number using phplibphone package";
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone'=> $data['phone'],
            'password' => Hash::make($data['password']),
            'lastSMSDate' => Carbon::now(),
        ]);

        if ($user)
            $this->sendSMSToUser($data);

        return $user;
    }


    /** Sends SMS To The Newly Created User
     * @param $data
     */
    private function sendSMSToUser($data)
    {
        $SMS = $this->AT->sms();

        $options = [
            'message' => $this->getSMS($data['name']),
            "to" => $data['phone'],
            "from" => ""
        ];

        $SMS->send($options);
    }


    /**Generates the Message To Be Sent To The Newly registered User
     * @param $name
     * @return string
     */
    private function getSMS($name)
    {
        return "Hello ${name}! We Are Glad To Have You On" . config('app.name');
    }

    /**
     * Sets The Africa's Talking Object
     */
    private function setAT(): void {
        // use 'sandbox' for development in the test environment
        $username = getenv('AFRICASTALKING_USERNAME');

        // use your sandbox app API key for development in the test environment
        $apiKey = getenv('AFRICASTALKING_API_KEY');

        $this->AT = new AfricasTalking($username, $apiKey);
    }

}