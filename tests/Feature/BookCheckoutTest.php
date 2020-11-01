<?php

namespace Tests\Feature;

use App\User;
use App\Book;
use Carbon\Carbon;
use App\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_book_can_be_checked_out_by_signed_in_user()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $book = factory(Book::class)->create();

        $this->actingAs($user)
            ->post('checkout/' . $book->id);

        $reservation = Reservation::first();

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, $reservation->user_id);
        $this->assertEquals($book->id, $reservation->book_id);
        $this->assertEquals(Carbon::now(), $reservation->checkout_at);
    }

    public function test_only_signed_in_user_can_checkout_books()
    {
        // $this->withoutExceptionHandling();

        $book = factory(Book::class)->create();

        $this->post('checkout/' . $book->id)
            ->assertRedirect('login');

        $this->assertCount(0, Reservation::all());
    }

    public function test_checkout_only_real_books()
    {
        // $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $response = $this->actingAs($user)
            ->post('checkout/123')
            ->assertStatus(404);

        $this->assertCount(0, Reservation::all());
    }

    public function test_a_book_can_be_checked_in_by_signed_in_user()
    {
        $this->withoutExceptionHandling();
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post('checkout/' . $book->id);


        $this->post('checkin/' . $book->id);

        $reservation = Reservation::first();

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($reservation->user_id, $user->id);
        $this->assertEquals($reservation->book_id, $book->id);
        $this->assertEquals(Carbon::now(), $reservation->checkin_at);
    }

    public function test_only_signed_in_user_can_check_in_book()
    {
        // $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $book = factory(Book::class)->create();

        $this->actingAs($user)
            ->post('checkout/' . $book->id);

        Auth::logout();

        $this->post('checkin/' . $book->id)
            ->assertRedirect('login');

        $this->assertCount(1, Reservation::all());
    }

    public function test_throw_404_if_book_is_not_checked_out_first()
    {
        // $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $book = factory(Book::class)->create();

        $response = $this->actingAs($user)
            ->post('checkin/' . $book->id);

        $response->assertStatus(404);
    }
}
