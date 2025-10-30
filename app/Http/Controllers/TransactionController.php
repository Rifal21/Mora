<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Bisnis;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(Auth::user()->bisnis()->count() == 0){
            return redirect()->route('bisnis.index')->with('warning', 'Anda belum memiliki bisnis. Silahkan tambahkan bisnis terlebih dahulu.');
        }
        $bisnis = Bisnis::all();
        $bisnis_set = session('bisnis_id');
        $products = Product::where('bisnis_id', $bisnis_set)->where('stock', '>', 0)->latest()->get();
        // $transactions = Transaction::where('bisnis_id', $bisnis_set)->latest()->get();
        $user = Auth::user();
        $bisnisList = $user->bisnis()->pluck('id');
        $transactions = Transaction::with(['bisnis', 'user'])
            ->where(function ($q) use ($bisnisList) {
                    $q->whereIn('bisnis_id', $bisnisList);                       
            })
            ->latest()
            ->paginate(20);
        $categories = ProductCategory::where('bisnis_id', $bisnis_set)->where('status', 'active')->latest()->get();
        return view('main.transaction.index', compact('bisnis', 'products', 'transactions', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        if(Auth::user()->profile->user_type === 'free'){
            if(auth()->user()->profile->quota_trx <= 0){
                return redirect()->route('billing.index')->with('error', 'Kuota transaksi habis. Silahkan upgrade ke pro!');
            }
            auth()->user()->profile->decrement('quota_trx', 1);
        }
        if($request->trx_type === 'catkeu') {
            $validate = $request->validate([
                'user_id' => 'required|exists:users,id',
                'type' => 'required|in:income,expense',
                'total_amount' => 'required|numeric',
                'notes' => 'nullable|string',
            ]);
            $validate['invoice_number'] = 'TRX-' . time();
    
            $transaction = Transaction::create([
                'user_id' => $request->user_id,
                'type' => $request->type,
                'total_amount' => $request->total_amount,
                'notes' => $request->notes,
                'invoice_number' => $validate['invoice_number'],
                'status' => 'success',
            ]);
    
            return redirect()->route('transactions.list')->with('success', 'Transaction created successfully');
        } else {

            // Decode JSON items menjadi array
            $items = json_decode($request->items, true);
    
            if (!$items || count($items) === 0) {
                return back()->withErrors(['items' => 'Cart is empty'])->withInput();
            }
    
            // Validasi request
            $request->validate([
                'customer_name' => 'nullable|string',
                'payment_method' => 'required|string|in:cash,qris',
                'notes' => 'nullable|string',
            ]);
    
            // Validasi setiap item
            foreach ($items as $index => $item) {
                if (!isset($item['product_id'], $item['quantity'], $item['price'], $item['total'])) {
                    return back()->withErrors(['items' => 'Invalid item data'])->withInput();
                }
                if (!\App\Models\Product::find($item['product_id'])) {
                    return back()->withErrors(['items' => "Product not found: {$item['product_id']}"])->withInput();
                }
                if ($item['quantity'] < 1 || $item['price'] < 0 || $item['total'] < 0) {
                    return back()->withErrors(['items' => 'Invalid quantity or price'])->withInput();
                }
            }
    
            $status = $request->payment_method === 'cash' ? 'success' : 'pending';
            // Simpan transaksi
            $transaction = Transaction::create([
                'bisnis_id' => $request->bisnis_id,
                'invoice_number' => 'INV-' . strtoupper(uniqid()),
                'cashier_name' => 'staff',
                'customer_name' => $request->customer_name,
                'total_amount' => array_sum(array_column($items, 'total')),
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
                'status' => $status,
                'type' => $status === 'success' ? 'income' : 'expense' || $request->type,
            ]);
    
            // Simpan items
            foreach ($items as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['total'],
                ]);

                $product = Product::find($item['product_id']);
                if($product->stock < $item['quantity']){
                    return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
                }
                $product->decrement('stock', $item['quantity']);
            }
    
            return redirect()->back()->with('success', 'Transaksi Berhasil!');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }

    public function confirm(Transaction $transaction)
    {
        // Hanya update jika masih pending
        if ($transaction->status === 'pending' && $transaction->payment_method === 'qris') {
            $transaction->status = 'success';
            $transaction->save();
            return back()->with('success', "Transaction {$transaction->invoice_number} updated to success.");
        }

        return back()->with('error', 'Transaction cannot be updated.');
    }
    public function print(Transaction $transaction)
    {
        // Hanya print jika status success
        if ($transaction->status !== 'success') {
            return back()->with('error', 'Transaction not ready for printing.');
        }

        // Tampilkan view invoice untuk print
        return view('main.transaction.print', compact('transaction'));
    }

    public function list()
    {
        $user = Auth::user();

        // Ambil semua bisnis milik user
        $bisnisList = $user->bisnis()->pluck('id');

        $transactions = Transaction::with(['bisnis', 'user'])
            ->where(function ($q) use ($bisnisList, $user) {
                if ($bisnisList->isNotEmpty()) {
                    $q->whereIn('bisnis_id', $bisnisList)
                        ->orWhere('user_id', $user->id);
                } else {
                    $q->where('user_id', $user->id);
                }
            })
            ->latest()
            ->paginate(20);


        return view('main.transaction.transaction-list', compact('transactions'));
    }
}
