<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\IssueRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class IssueReturnTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    /** Issue one copy of the book to a new member and return that member. */
    private function issueBook(Book $book): User
    {
        $member = User::factory()->create();

        $this->actingAs($this->admin())
            ->post(route('circulation.store'), ['book_id' => $book->id, 'user_id' => $member->id])
            ->assertRedirect();

        return $member;
    }

    /** Create a loan that is still with the member: issued N days ago, due in M days. */
    private function openLoan(Book $book, int $issuedDaysAgo = 3, int $dueInDays = 11): IssueRecord
    {
        return IssueRecord::create([
            'book_id' => $book->id,
            'user_id' => User::factory()->create()->id,
            'issued_at' => today()->subDays($issuedDaysAgo),
            'due_at' => today()->addDays($dueInDays),
        ]);
    }

    #[Test]
    public function issuing_a_book_takes_one_copy_off_the_shelf(): void
    {
        $book = Book::factory()->create(['stock' => 3]);

        $this->issueBook($book);

        $this->assertSame(2, $book->fresh()->stock);
    }

    #[Test]
    public function returning_a_book_puts_the_copy_back_on_the_shelf(): void
    {
        $book = Book::factory()->create(['stock' => 3]);
        $loan = $this->openLoan($book);

        $this->actingAs($this->admin())
            ->post(route('circulation.return', $loan))
            ->assertRedirect();

        $this->assertSame(4, $book->fresh()->stock);
        $this->assertNotNull($loan->fresh()->returned_at);
    }

    #[Test]
    public function the_last_copy_cannot_be_issued_twice(): void
    {
        $book = Book::factory()->create(['stock' => 1]);

        $this->issueBook($book);

        // The second attempt must be refused and must not push the stock below zero.
        $this->actingAs($this->admin())
            ->post(route('circulation.store'), ['book_id' => $book->id, 'user_id' => User::factory()->create()->id])
            ->assertStatus(422);

        $this->assertSame(0, $book->fresh()->stock);
    }

    #[Test]
    public function returning_the_same_copy_twice_does_not_double_the_stock(): void
    {
        $book = Book::factory()->create(['stock' => 1]);
        $loan = $this->openLoan($book);
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('circulation.return', $loan))->assertRedirect();
        $this->actingAs($admin)->post(route('circulation.return', $loan))->assertStatus(422);

        $this->assertSame(2, $book->fresh()->stock);
    }

    #[Test]
    public function an_overdue_return_earns_five_rupees_per_late_day(): void
    {
        $book = Book::factory()->create(['stock' => 1]);
        // 6 days late, so 6 × 5 = 30.
        $loan = $this->openLoan($book, issuedDaysAgo: 20, dueInDays: -6);

        $this->actingAs($this->admin())
            ->post(route('circulation.return', $loan))
            ->assertRedirect();

        $this->assertSame('30.00', $loan->fresh()->fine);
    }

    #[Test]
    public function an_on_time_return_earns_no_fine(): void
    {
        $book = Book::factory()->create(['stock' => 1]);
        $loan = $this->openLoan($book);

        $this->actingAs($this->admin())
            ->post(route('circulation.return', $loan))
            ->assertRedirect();

        $this->assertSame('0.00', $loan->fresh()->fine);
    }

    #[Test]
    public function a_book_due_today_is_not_yet_overdue(): void
    {
        $book = Book::factory()->create();
        $loan = $this->openLoan($book, issuedDaysAgo: 14, dueInDays: 0);

        $this->assertFalse($loan->isOverdue());
        $this->assertSame(0, $loan->daysLate());
        $this->assertSame(0, $loan->daysLeft());
    }

    #[Test]
    public function days_left_counts_down_and_never_goes_negative(): void
    {
        $book = Book::factory()->create();
        $loan = $this->openLoan($book, issuedDaysAgo: 0, dueInDays: 5);

        $this->assertSame(5, $loan->daysLeft());
        $this->assertSame(0, $loan->daysLate());
        $this->assertFalse($loan->isOverdue());
    }

    #[Test]
    public function a_normal_user_cannot_open_the_admin_pages(): void
    {
        $member = User::factory()->create(['is_admin' => false]);

        $this->actingAs($member)->get(route('books.index'))->assertForbidden();
        $this->actingAs($member)->get(route('my'))->assertOk();
    }

    #[Test]
    public function a_normal_user_lands_on_their_own_page_not_an_admin_page(): void
    {
        User::factory()->create(['email' => 'user@library.test', 'is_admin' => false]);

        $this->post('/login', [
            'email' => 'user@library.test',
            'password' => 'password',
        ])->assertRedirect('/');

        $this->assertAuthenticated();
        $this->get('/')->assertRedirect(route('my'));
        $this->get(route('my'))->assertOk();
    }
}
