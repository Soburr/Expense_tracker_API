<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{

    public function index()
    {
        $transactions = Transaction::all();

        return response()->json($transactions, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $validator = Validator::make($request->all(), [
            "user_id" => "required",
            "type" => "required|in:income,expense",
            "amount" => "required|decimal:0,9",
            "description" => "required",
            "date" => "required"
         ]);

         if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "message" => "Error!",
                "data" => $validator->errors()->all()
            ]);
         }

         $transaction = Transaction::create([
            "user_id" => $request->user_id,
            "type" => $request->type,
            "amount" => $request->amount,
            "description" => $request->description,
            "date" => $request->date
         ]);

            return response()->json([
              "status" => 1,
              "message" => "Transaction Created",
              "data" => $transaction
           ]);
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
    public function update(UpdateTransactionRequest $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "type" => "required|in:income,expense",
            "amount" => "required|decimal:0,9",
            "description" => "required",
            "date" => "required"
         ]);

         if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "message" => "Error!",
                "data" => $validator->errors()->all()
            ]);
         }

         $transaction = Transaction::find($id);
         $transaction->type = $request->type;
         $transaction->amount = $request->amount;
         $transaction->description = $request->description;
         $transaction->date = $request->date;
         $transaction->save();

         return response()->json([
            "status" => 1,
            "message" => "Transaction Updated",
            "data" => $transaction
         ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $transaction = Transaction::find($id);
        $transaction->delete();

        return response()->json([
            "status" => 1,
            "message" => "Transaction Deleted",
            "data" => $transaction
         ]);
    }
}
