<?php

namespace Modules\User\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Model implements JWTSubject,  MustVerifyEmail
{
    use HasFactory;
    use SoftDeletes;
    use Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'slug',
        'email',
        'password',
        'phone',
        'birth_date',
        'gender',
        'status',
        'email_verified_at',
        'email_otp',
        'email_otp_expires_at'
    ];
    protected $hidden = [
        'password',
        'email_otp',
        'email_otp_expires_at'
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function generateOtp()
    {
        $otp = rand(100000, 999999);
        $this->email_otp = $otp;
        $this->email_otp_expires_at = now()->addMinutes(10); // OTP valid for 10 minutes
        $this->save();

        return $otp;
    }

    public function hasValidOtp($otp)
    {
        return $this->email_otp === $otp && $this->email_otp_expires_at->isFuture();
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function hasVerifiedEmail()
    {
        return ! is_null($this->email_verified_at);
    }
    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \Modules\User\Notifications\SendEmailOtpNotification($this->email_otp));
    }
    public function getEmailForVerification()
    {
        return $this->email; // The 'email' attribute is used for verification
    }
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = ucfirst(strtolower($value));
    }
    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = ucfirst(strtolower($value));
    }
}
