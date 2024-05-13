<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $transactions = auth()->user()->transactions()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('dashboard', compact('transactions'));
    }
}
