<?php

namespace Tests\Feature;

use App\Author;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorManagementTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_author_can_be_added()
    {
        $this->withoutExceptionHandling();

        $this->post('/authors', [
            'name' => 'Ahmed',
            'dob' => '05/14/1988'
        ]);

        $author = Author::first();

        $this->assertCount(1, Author::all());
        $this->assertInstanceOf(Carbon::class, $author->dob);
        $this->assertEquals('1988/14/05', $author->dob->format('Y/d/m'));
    }

    public function test_author_name_is_validated()
    {
        $author = $this->post('/authors', [
            'name' => '',
            'dob' => '05/14/1988',
        ]);

        $author->assertSessionHasErrors('name');
    }

    public function test_author_dob_is_validated()
    {
        $author = $this->post('authors', [
            'name' => 'Ahmed Gamal',
            'dob' => '',
        ]);

        $author->assertSessionHasErrors('dob');
    }
}
