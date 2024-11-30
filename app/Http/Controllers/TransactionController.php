<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;

class TransactionController extends Controller
{

    public function index()
    {
        $transaction = Transaction::all();

        return response()->json($transaction, 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $request->validate([
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'date' => 'required|string'
        ]);

        $transaction = auth()->user()->transactions()->create($request->all());
        return response()->json($transaction, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        return response()->json([
            'data' => [
                'id' => $transaction->id,
                'type' => 'Transactions',
                    'attributes' => [
                        'type' => $transaction->type,
                        'amount' => $transaction->amount,
                        'description' => $transaction->description,
                        'category' => $transaction->category,
                        'date' => $transaction->date
                    ]
            ]
        ]);
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
}
