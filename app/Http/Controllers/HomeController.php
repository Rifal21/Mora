<?php

namespace App\Http\Controllers;

use App\Models\Bisnis;
use App\Models\BlogPost;
use App\Models\Plan;
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
            $news = BlogPost::latest()->limit(6)->get();
            $billing = Plan::where('is_active', true)
                ->withCount(['transactions' => function ($q) {
                    $q->where('status', 'success');
                }])
                ->get();
            $bisnis = Bisnis::all();
            return view('main.index', [
                'balance' => 0,
                'income' => 0,
                'expense' => 0,
                'transactions' => collect(),
                'chartLabels' => collect(),
                'chartIncome' => collect(),
                'chartExpense' => collect(),
                'news' => $news,
                'billing' => $billing,
                'bisnis' => $bisnis
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
        $news = BlogPost::latest()->limit(6)->get();
        $billing = Plan::where('is_active', true)
            ->withCount(['transactions' => function ($q) {
                $q->where('status', 'success');
            }])
            ->get();
        $bisnis = Bisnis::all();
        

        return view('main.index', [
            'balance' => $balance,
            'income' => $income,
            'expense' => $expense,
            'transactions' => $recentTransactions,
            'chartLabels' => $chartLabels,
            'chartIncome' => $chartIncome,
            'chartExpense' => $chartExpense,
            'news' => $news,
            'billing' => $billing,
            'bisnis' => $bisnis
        ]);
    }
    public function cart()
    {
        $cart = session('cart', []);
        $total = collect($cart)->sum('price');
        return view('main.cart.index', compact('cart', 'total'));
    }
    // CartController.php
    public function add(Request $request)
    {
        $user = Auth::user();
        if($user->subscriptions()->where('status', 'active')->exists()) {
            return redirect()->route('billing.index')->with('error', 'Anda sudah memiliki paket aktif!');
        }
        $packageId = $request->input('package_id');
        $package = Plan::findOrFail($packageId);

        $cart = session()->get('cart', []);

        // Jika sudah ada di cart, jangan duplikasi
        if (!isset($cart[$packageId])) {
            $cart[$packageId] = [
                'id' => $packageId,
                'name' => $package->name,
                'price' => $package->price,
                'duration_days' => $package->duration_days,
            ];
        }

        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('success', 'Paket berhasil ditambahkan ke keranjang!');
    }
    public function remove($id)
    {
        $cart = session()->get('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('success', 'Item dihapus dari keranjang.');
    }
}
