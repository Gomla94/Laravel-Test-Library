<?php

namespace Tests\Unit;

use App\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_name_is_required_to_add_an_author()
    {
        Author::firstOrCreate([
            'name' => 'Ahmed',
        ]);

        $this->assertCount(1, Author::all());
    }
}
