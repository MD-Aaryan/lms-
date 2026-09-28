<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class MyController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        return view('my.index', [
            'active' => $user->issueRecords()
                ->whereNull('returned_at')
                ->with('book')
                ->orderByDesc('issued_at')
                ->get(),
            'history' => $user->issueRecords()
                ->whereNotNull('returned_at')
                ->with('book')
                ->orderByDesc('returned_at')
                ->get(),
            'totalFine' => $user->issueRecords()->sum('fine'),
            'overdue' => $user->issueRecords()->whereNull('returned_at')->where('due_at', '<', today())->count(),
        ]);
    }
}
