<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return view('main.index', [
                'balance' => 0,
                'income' => 0,
                'expense' => 0,
                'transactions' => collect(),
                'chartLabels' => collect(),
                'chartIncome' => collect(),
                'chartExpense' => collect(),
            ]);
        }

        $user = Auth::user();

        // Ambil semua bisnis milik user
        $bisnisList = $user->bisnis()->pluck('id');

        // Ambil semua transaksi baik dari bisnis maupun pribadi
        $transactionsQuery = Transaction::query()
            ->when($bisnisList->isNotEmpty(), function ($q) use ($bisnisList, $user) {
                $q->whereIn('bisnis_id', $bisnisList)
                    ->orWhere('user_id', $user->id);
            }, function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });

        // Clone query untuk perhitungan
        $transactions = (clone $transactionsQuery)->get();

        // Hitung income & expense
        $income = $transactions->where('type', 'income')->sum('total_amount');
        $expense = $transactions->where('type', 'expense')->sum('total_amount');
        $balance = $income - $expense;

        // Ambil 5 transaksi terakhir (gabungan)
        $recentTransactions = (clone $transactionsQuery)
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        // Ambil data grafik (group by tanggal)
        $chartData = (clone $transactionsQuery)
            ->selectRaw('DATE(created_at) as day, 
                         SUM(CASE WHEN type = "income" THEN total_amount ELSE 0 END) as income,
                         SUM(CASE WHEN type = "expense" THEN total_amount ELSE 0 END) as expense')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $chartLabels = $chartData->pluck('day')->map(fn($d) => Carbon::parse($d)->format('d M'));
        $chartIncome = $chartData->pluck('income');
        $chartExpense = $chartData->pluck('expense');

        return view('main.index', [
            'balance' => $balance,
            'income' => $income,
            'expense' => $expense,
            'transactions' => $recentTransactions,
            'chartLabels' => $chartLabels,
            'chartIncome' => $chartIncome,
            'chartExpense' => $chartExpense,
        ]);
    }
}
