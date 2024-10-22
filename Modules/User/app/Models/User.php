<?php

namespace Modules\User\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Exercise\Models\ExerciseSystemDefault;
use Modules\Exercise\Models\UserExercise;
use Modules\Exercise\Models\UserSystemExercise;

class User  extends Authenticatable implements JWTSubject,  MustVerifyEmail
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
        'email_otp_expires_at',
        'password_reset_otp',
        'password_reset_otp_expires_at'
    ];
    protected $hidden = [
        'password',
        'email_otp',
        'email_otp_expires_at'
    ];
    protected $casts = [
        'email_otp_expires_at' => 'datetime',
        'password_reset_otp_expires_at' => 'datetime',
        'birth_date' => 'datetime',
        'password' => 'hashed'
    ];
    public function generatePasswordResetOtp()
    {
        $this->password_reset_otp = rand(100000, 999999); // Generate a 6-digit OTP
        $this->password_reset_otp_expires_at = now()->addMinutes(10); // OTP expires in 10 minutes
        $this->save();
    }

    // Method to check if the OTP is valid
    public function hasValidPasswordResetOtp($otp)
    {
        return $this->password_reset_otp === $otp && $this->password_reset_otp_expires_at->isFuture();
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
        return $this->email_otp == $otp && $this->email_otp_expires_at->isFuture();
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

    public function exerciseSystems()
    {
        return $this->belongsToMany(ExerciseSystemDefault::class, 'user_system_exercises')
            ->whereNull('user_system_exercises.deleted_at') // Exclude soft-deleted records
            ->withTimestamps();
    }
    public function exerciseSystemsUser()
    {
        return $this->belongsToMany(UserSystemExercise::class, 'user_exercises')->withTimestamps();
    }

    public function userExercises()
    {
        return $this->hasMany(UserExercise::class, 'user_system_exercise_id');
    }
}
