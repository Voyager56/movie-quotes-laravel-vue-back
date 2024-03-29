<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
	use HasApiTokens, HasFactory, Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'username',
		'email',
		'password',
		'photo',
		'oauth',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	public function getJWTIdentifier()
	{
		return $this->getKey();
	}

	public function getJWTCustomClaims(): array
	{
		return [];
	}

	public function quotes()
	{
		return $this->hasMany(Quote::class);
	}

	public function movies()
	{
		return $this->hasMany(Movie::class);
	}

	public function comments()
	{
		return $this->hasMany(Comments::class);
	}

	public function likes()
	{
		return $this->hasMany(Likes::class);
	}

	public function notifications()
	{
		return $this->hasMany(Notification::class, 'to_user_id');
	}

	public function notificationsTo()
	{
		return $this->hasMany(Notification::class, 'from_user_id');
	}
}
