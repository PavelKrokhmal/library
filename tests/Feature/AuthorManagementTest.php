<?php

namespace Tests\Feature;

use App\Models\Author;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_author_can_be_created()
    {
        $this->post('/authors', $this->data());

        $author = Author::all();

        $this->assertCount(1, $author);
        $this->assertInstanceOf(Carbon::class, $author->first()->dob);

        $this->assertEquals('1999/07/06', $author->first()->dob->format('Y/d/m'));
    }

    /** @test */
    public function a_name_is_required()
    {
        $this->post('/authors', array_merge($this->data(), ['name' => '']))
        ->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_dob_is_required()
    {
        $this->post('/authors', array_merge($this->data(), ['dob' => '']))
            ->assertSessionHasErrors('dob');
    }

    private function data()
    {
        return [
            'name' => 'Author Name',
            'dob' => '06/07/1999'
        ];
    }
}
