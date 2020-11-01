<?php

namespace App;

use App\Author;
use Carbon\Carbon;
use App\Reservation;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $guarded = [];

    public function path()
    {
        // return '/books/' . Str::slug($this->title);
        return '/books/' . $this->id;
    }

    public function author()
    {
        $this->belongsTo(Author::class);
    }

    public function checkout($user)
    {
        $this->reservations()->create([
            'user_id' => $user->id,
            'checkout_at' => Carbon::now()
        ]);
    }

    public function checkin($user)
    {
        $reservation = $this->reservations()->where('user_id', $user->id)
            ->whereNotNull('checkout_at')
            ->whereNull('checkin_at')->first();

        if (is_null($reservation)) {
            throw new Exception();
        }

        $reservation->update([
            'checkin_at' => Carbon::now()
        ]);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function setAuthorIdAttribute($attribute)
    {
        $this->attributes['author_id'] = (Author::firstOrCreate([
            'name' => $attribute,
        ]))->id;
    }
}
