<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use function PHPUnit\Framework\isEmpty;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'user_room');
    }

    public function online()
    {
        return DB::table("sessions")->where('user_id', $this->id)->exists();
    }

    public function privateRoomId()
    {

        $rooms = DB::select("SELECT count(*) as count, room_id as id from user_room where user_id =". $this->id ." or user_id = ". Auth::id() ." group by room_id having count = 2");
        foreach ($rooms as $room){
            $room_count = DB::table('user_room')->where("room_id",$room->id)->count();
            if($room_count == 2){
                return $room->id;
            }
        }
        return  0 ;
    }
}
