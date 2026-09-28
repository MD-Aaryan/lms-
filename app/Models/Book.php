<?php

namespace App\Models;

use Database\Factories\BookFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'author', 'isbn', 'published_year', 'stock', 'description'])]
class Book extends Model
{
    /** @use HasFactory<BookFactory> */
    use HasFactory;

    public function issueRecords(): HasMany
    {
        return $this->hasMany(IssueRecord::class);
    }

    protected function casts(): array
    {
        return [
            'published_year' => 'integer',
            'stock' => 'integer',
        ];
    }
}
