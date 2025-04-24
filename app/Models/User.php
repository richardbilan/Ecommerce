<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\DeliveryAddress;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'profile_image',
        'password',
        'remember_token',
        'last_name',
        'birthdate',
        'phone_number',
        'gender',
        'location',
        'type'
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
        'birthdate' => 'date',
    ];

    /**
     * Get the delivery addresses for the user.
     */
    public function deliveryAddresses(): HasMany
    {
        return $this->hasMany(DeliveryAddress::class);
    }

    /**
     * Get the default delivery address for the user.
     */
    public function defaultDeliveryAddress(): ?DeliveryAddress // Return type hint added
    {
        // Added null return possibility if no default address exists
        return $this->deliveryAddresses()->where('is_default', true)->first();
    }

    /**
     * Set the location coordinates.
     */
    public function setLocationCoordinates(float $latitude, float $longitude): void
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->save();
    }
}
