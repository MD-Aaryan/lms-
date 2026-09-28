<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\IssueRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    #[Test]
    public function every_page_renders_for_the_right_role(): void
    {
        $book = Book::factory()->create(['title' => 'Dune', 'isbn' => '111', 'stock' => 2]);
        $member = User::factory()->create(['is_admin' => false]);

        // One overdue loan and one finished loan, so both tables have a row.
        IssueRecord::create([
            'book_id' => $book->id,
            'user_id' => $member->id,
            'issued_at' => today()->subDays(20),
            'due_at' => today()->subDays(6),
        ]);
        IssueRecord::create([
            'book_id' => $book->id,
            'user_id' => $member->id,
            'issued_at' => today()->subDays(30),
            'due_at' => today()->subDays(20),
            'returned_at' => today()->subDays(18),
            'fine' => 10,
        ]);

        $admin = $this->admin();

        $this->actingAs($admin)->get(route('books.index'))->assertOk();
        $this->actingAs($admin)->get(route('books.index', ['q' => 'Dun']))->assertOk();
        $this->actingAs($admin)->get(route('books.create'))->assertOk();
        $this->actingAs($admin)->get(route('books.edit', $book))->assertOk();
        $this->actingAs($admin)->get(route('users.index'))->assertOk();
        $this->actingAs($admin)->get(route('circulation.index'))->assertOk();
        $this->actingAs($admin)->get(route('my'))->assertOk();
        $this->actingAs($member)->get(route('my'))->assertOk();
    }

    #[Test]
    public function the_isbn_stays_unique_when_adding_and_editing_a_book(): void
    {
        $admin = $this->admin();
        $book = Book::factory()->create(['isbn' => '111']);

        $this->actingAs($admin)
            ->post(route('books.store'), ['title' => 'T', 'author' => 'A', 'isbn' => '111', 'stock' => 1])
            ->assertSessionHasErrors('isbn');

        $this->actingAs($admin)
            ->post(route('books.store'), ['title' => 'T2', 'author' => 'A', 'isbn' => '222', 'stock' => 1])
            ->assertSessionHasNoErrors();

        // A book may keep its own ISBN while being edited ...
        $this->actingAs($admin)
            ->put(route('books.update', $book), ['title' => 'T3', 'author' => 'A', 'isbn' => '111', 'stock' => 2])
            ->assertSessionHasNoErrors();

        Book::factory()->create(['isbn' => '333']);

        // ... but it cannot take the ISBN of another book.
        $this->actingAs($admin)
            ->put(route('books.update', $book), ['title' => 'T4', 'author' => 'A', 'isbn' => '333', 'stock' => 2])
            ->assertSessionHasErrors('isbn');
    }

    #[Test]
    public function a_user_added_by_an_admin_can_log_in(): void
    {
        $this->actingAs($this->admin())
            ->post(route('users.store'), [
                'name' => 'New Member',
                'email' => 'new@library.test',
                'password' => 'secret123',
                'password_confirmation' => 'secret123',
                'is_admin' => '1',
            ])
            ->assertSessionHasNoErrors();

        auth()->logout();

        $this->post('/login', ['email' => 'new@library.test', 'password' => 'secret123'])
            ->assertRedirect('/');

        $this->assertAuthenticated();
    }
}
