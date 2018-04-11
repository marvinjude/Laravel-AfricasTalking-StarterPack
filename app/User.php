<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'lastSMSDate', 'phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * Returns An Array Of Users That Are Due To Get SMS , This Array Doesn't Contain
     * User Whose SMS Delivery Is Pending
     * @param $attributes : An Array Of Attributes To Pull Out, Pass No Argument If
     * All Columns Should Be Fetched
     * @return Collection
     */
    public static function getUsersDueToGetSMS(array $attributes = null)
    {
        $dueUsers = self::all()->where('is_sending', '0')->filter(self::closure());

        if ($attributes)
            return $dueUsers->pluck(...$attributes);

        return $dueUsers;
    }


    /** Fetchs The Interval At Which You Want The SMS To Be Sent In Days
     * @return mixed
     */
    private static function getSMSInterval()
    {
        return getenv('SMS_INTERVAL_IN_DAYS');
    }


    /** The Closure To Be Applied To Every Record Before They Are Returned
     * @return \Closure
     */
    public static function closure()
    {
        return function ($value, $key) {
            // The Difference Between Today And The SMS_INTERVAL_IN_HOURS in your .env File
            $diffInDays = Carbon::now()->diffInDays(Carbon::createFromTimeString($value->lastSMSDate));

            /*In cases Where SMS Fails The Diff In Days May Be Higher Than getSMSInterval,
            *Hence >= operator  is Appropriate Here
            */
            if (self::getSMSInterval() >= $diffInDays)
                return $value;
        };
    }


    /** Set is_sending = true For Each User Record In This Collection
     * @param Collection $users
     */
    public static function SetSendingStatusTrue(Collection $users)
    {
        $users->each(function ($item) {
            $item->is_sending = '1';
            $item->save();
        });
    }

    /** Set is_sending = false For Each User Record In This Collection
     * @param Collection $users
     */
    public static function SetSendingStatusFalse(Collection $users)
    {
        $users->each(function ($item) {
            $item->is_sending = '0';
            $item->save();
        });
    }
}

