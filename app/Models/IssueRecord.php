<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['book_id', 'user_id', 'issued_at', 'due_at', 'returned_at', 'fine'])]
class IssueRecord extends Model
{
    use HasFactory;

    public const LOAN_DAYS = 14;

    public const FINE_PER_DAY = 5;

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** A book only becomes overdue after its due date has passed (due today is fine). */
    public function isOverdue(): bool
    {
        return $this->returned_at === null && $this->due_at->isBefore(today());
    }

    /** How many days late the book is: counted up to the return date, or up to today. */
    public function daysLate(): int
    {
        $end = $this->returned_at ?? today();

        if (! $this->due_at->isBefore($end)) {
            return 0;
        }

        return (int) $this->due_at->diffInDays($end);
    }

    /** How many days are left before the book is due (never negative). */
    public function daysLeft(): int
    {
        $end = $this->returned_at ?? today();

        if (! $end->isBefore($this->due_at)) {
            return 0;
        }

        return (int) $end->diffInDays($this->due_at);
    }

    /** The fine this book would cost if it were returned today. */
    public function currentFine(): int
    {
        return $this->daysLate() * self::FINE_PER_DAY;
    }

    protected function casts(): array
    {
        return [
            'issued_at' => 'date',
            'due_at' => 'date',
            'returned_at' => 'date',
            'fine' => 'decimal:2',
        ];
    }
}
