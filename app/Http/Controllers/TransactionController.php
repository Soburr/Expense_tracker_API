<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{

    public function index()
    {
        return TransactionResource::collection(Transaction::all());
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

         return new TransactionResource($transaction);
        //     return response()->json([
        //       "status" => 1,
        //       "message" => "Transaction Created",
        //       "data" => $transaction
        //    ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        return new TransactionResource($transaction);
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

    public function monthlySummary ($month) {

        //Ensure month is within range of 1-12
       $month = (int) $month;
       if($month < 1 || $month > 12) {
          return response()->json(['error' => 'Invalid month'], 400);
       }

       //Filter transaction for the given month
       $transactions = auth()->user()->transactions()
                      ->whereRaw('MONTH(date) = ?', [$month]) // Extract month for the date
                      ->get()
                      ->groupBy('type');

        // Summarize income and expense
       $income = $transactions->get('income', collect()->sum('amount'));
       $expense = $transactions->get('income', collect()->sum('amount'));

       return response()->json([
          'income' => $income,
          'expenses' => $expense
       ], 200);
    }
}
