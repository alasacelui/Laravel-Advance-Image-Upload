<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
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
     * Adding image manipulation on this model App\Models\User
     */

    public function registerMediaCollections(): void
    {
        // create a thumbnail size image
        $this->addMediaConversion('thumbnail')
        ->width(100)
        ->height(100)
        ->sharpen(10);

        // create a card size image

        $this->addMediaConversion('card')
        ->width(230)
        ->height(200);
    }

    public function avatar()
    {
        return $this->hasOne(Media::class, 'id', 'avatar_id');
    }
    
    // create an accessor
    public function getAvatarUrlAttribute()
    {
        return optional($this->avatar)->getUrl('thumbnail');
    }

   
}
