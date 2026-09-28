<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\IssueRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('q')->toString();

        $books = Book::query()->orderBy('title');

        if ($search !== '') {
            $books->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        return view('books.index', [
            'books' => $books->paginate(10)->withQueryString(),
            'q' => $search,
            'stats' => [
                'titles' => Book::count(),
                'onShelf' => (int) Book::sum('stock'),
                'onLoan' => IssueRecord::query()->whereNull('returned_at')->count(),
                'overdue' => IssueRecord::query()->whereNull('returned_at')->where('due_at', '<', today())->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('books.form', ['book' => new Book]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());

        Book::create($data);

        return back()->with('success', "{$data['title']} was added.");
    }

    public function edit(Book $book): View
    {
        return view('books.form', ['book' => $book]);
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $data = $request->validate($this->rules($book));

        $book->update($data);

        return back()->with('success', "{$data['title']} was updated.");
    }

    public function destroy(Book $book): RedirectResponse
    {
        abort_if($book->issueRecords()->exists(), 422, 'This book has issue history — it cannot be deleted.');

        $book->delete();

        return back()->with('success', "{$book->title} was deleted.");
    }

    /** The rules for adding and for editing a book (the ISBN stays unique). */
    private function rules(?Book $book = null): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'isbn' => ['required', 'string', 'max:32', Rule::unique('books', 'isbn')->ignore($book)],
            'published_year' => ['nullable', 'integer', 'min:1000', 'max:'.date('Y')],
            'stock' => ['required', 'integer', 'min:0', 'max:9999'],
            'description' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
