<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@library.test',
            'password' => 'password',
            'is_admin' => true,
        ]);

        $members = [
            ['Ram Kumar', 'user@library.test'],
            ['Sita Sharma', 'sita@library.test'],
            ['Amit Verma', 'amit@library.test'],
            ['Priya Singh', 'priya@library.test'],
            ['Rahul Yadav', 'rahul@library.test'],
            ['Anita Gupta', 'anita@library.test'],
            ['Vikram Rao', 'vikram@library.test'],
        ];

        foreach ($members as [$name, $email]) {
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => 'password',
            ]);
        }

        $books = [
            ['Dune', 'Frank Herbert', '9780441013593', 1965, 5],
            ['1984', 'George Orwell', '9780451524935', 1949, 6],
            ['Emma', 'Jane Austen', '9780141439587', 1815, 3],
            ['Pride and Prejudice', 'Jane Austen', '9780141439518', 1813, 4],
            ['The Hobbit', 'J. R. R. Tolkien', '9780547928227', 1937, 5],
            ['The Lord of the Rings', 'J. R. R. Tolkien', '9780547928210', 1954, 3],
            ['To Kill a Mockingbird', 'Harper Lee', '9780061120084', 1960, 4],
            ['The Catcher in the Rye', 'J. D. Salinger', '9780316769488', 1951, 3],
            ['Brave New World', 'Aldous Huxley', '9780060850524', 1932, 2],
            ['Moby-Dick', 'Herman Melville', '9781503280786', 1851, 2],
            ['The Great Gatsby', 'F. Scott Fitzgerald', '9780743273565', 1925, 4],
            ['Jane Eyre', 'Charlotte Brontë', '9780141441146', 1847, 3],
            ['Wuthering Heights', 'Emily Brontë', '9780141439556', 1847, 2],
            ['Crime and Punishment', 'Fyodor Dostoevsky', '9780140455145', 1866, 3],
            ['The Alchemist', 'Paulo Coelho', '9780062316097', 1988, 5],
            ['Atomic Habits', 'James Clear', '9780735211292', 2018, 4],
            ['The Psychology of Money', 'Morgan Housel', '9780857197689', 2020, 3],
            ['Sapiens', 'Yuval Noah Harari', '9780099529682', 2011, 3],
            ['Clean Code', 'Robert C. Martin', '9780132350884', 2008, 4],
            ['The Design of Everyday Things', 'Don Norman', '9780465050659', 2013, 2],
        ];

        foreach ($books as [$title, $author, $isbn, $year, $stock]) {
            Book::create([
                'title' => $title,
                'author' => $author,
                'isbn' => $isbn,
                'published_year' => $year,
                'stock' => $stock,
            ]);
        }

        $this->command->info(
            'Seeded 1 admin, '.count($members).' members and '.count($books).' books. Password for every account: password'
        );
        $this->command->line('Admin: admin@library.test | Member: user@library.test');
    }
}
