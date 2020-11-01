<?php

namespace Tests\Unit;

use App\Book;
use App\User;
use Carbon\Carbon;
use App\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_book_can_be_reserved()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $book->checkout($user);
        $reservation = Reservation::first();
        $this->assertCount(1, Reservation::all());
        $this->assertEquals($reservation->book_id, $book->id);
        $this->assertEquals($reservation->user_id, $user->id);
        $this->assertEquals(Carbon::now(), $reservation->checkout_at);
    }

    public function test_book_can_checked_in()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $book->checkout($user);
        $book->checkin($user);

        $reservation = Reservation::first();

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($reservation->book_id, $book->id);
        $this->assertEquals($reservation->user_id, $user->id);
        $this->assertEquals(Carbon::now(), $reservation->checkin_at);
    }

    public function test_book_can_check_out_twice()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $book->checkout($user);
        $book->checkin($user);

        $book->checkout($user);
        $reservation = Reservation::find(2);

        $this->assertCount(2, Reservation::all());
        $this->assertEquals($reservation->book_id, $book->id);
        $this->assertEquals($reservation->user_id, $user->id);
        $this->assertEquals(Carbon::now(), $reservation->checkout_at);
    }

    public function test_book_must_be_checked_out_to_check_in()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $this->expectException(\Exception::class);
        $book->checkin($user);

        $this->assertCount(1, Reservation::all());
    }
}
