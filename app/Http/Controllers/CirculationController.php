<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\IssueRecord;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CirculationController extends Controller
{
    public function index(): View
    {
        return view('circulation.index', [
            'books' => Book::query()
                ->where('stock', '>', 0)
                ->orderBy('title')
                ->get(),
            'users' => User::query()
                ->where('is_admin', false)
                ->orderBy('name')
                ->get(),
            'pending' => IssueRecord::query()
                ->whereNull('returned_at')
                ->with(['book', 'user'])
                ->orderBy('due_at')
                ->get(),
            'overdue' => IssueRecord::query()
                ->whereNull('returned_at')
                ->where('due_at', '<', today())
                ->count(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'book_id' => ['required', 'exists:books,id'],
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $book = Book::findOrFail($data['book_id']);
        $user = User::findOrFail($data['user_id']);

        abort_if($book->stock < 1, 422, 'This book is out of stock.');
        abort_if($user->isAdmin(), 422, 'A book cannot be issued to an admin.');

        // One copy leaves the shelf.
        $book->decrement('stock');

        $issue = IssueRecord::create([
            'book_id' => $book->id,
            'user_id' => $user->id,
            'issued_at' => today(),
            'due_at' => today()->addDays(IssueRecord::LOAN_DAYS),
        ]);

        return back()->with('success',
            "{$book->title} was issued to {$user->name} — due {$issue->due_at->format('d M Y')}.");
    }

    public function returnBook(IssueRecord $issue): RedirectResponse
    {
        abort_if($issue->returned_at !== null, 422, 'This book has already been returned.');

        // Work out the fine before the record is marked as returned.
        $fine = $issue->currentFine();

        $issue->book->increment('stock');
        $issue->update([
            'returned_at' => today(),
            'fine' => $fine,
        ]);

        $message = "{$issue->book->title} was returned.";

        if ($fine > 0) {
            $message .= " Fine: रू{$fine} ({$issue->daysLate()} days late).";
        }

        return back()->with('success', $message);
    }
}
