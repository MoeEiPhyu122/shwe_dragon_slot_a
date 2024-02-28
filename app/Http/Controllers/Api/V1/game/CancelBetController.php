<?php

namespace App\Http\Controllers\Api\V1\Game;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class CancelBetController extends Controller
{
    public function CancelBet(Request $request)
    {
        $operatorCode = $request->input("OperatorCode");
        $memberName = $request->get("MemberName");
        $requestTime = $request->input("RequestTime");
        $secretKey = config("game.api.secret_key");
        $sign = $request->input("Sign");
        $signature = md5($operatorCode . $requestTime . 'cancelbet' . $secretKey);
        if ($sign !== $signature) {
            return response()->json([
                "ErrorCode" => 1004,
                "ErrorMessage" => "Wrong Sign",
                "Balance" => 0
            ]);
        }

        $member = User::where("user_name", $memberName)->first();
        if (!$member) {
            return response()->json([
                "ErrorCode" => 1002,
                "ErrorMessage" => "Member not found",
                "Balance" => 0
            ]);
        }

        $transactions = $request->get("Transactions");
        $transaction = $transactions[0];
        
        // Make sure BetAmount exists and is a number
        if (!isset($transaction["TransactionAmount"])) {
            return response()->json([
                "ErrorCode" => 1005,
                "ErrorMessage" => "Invalid Bet Amount",
                "Balance" => $member->balance
            ]);
        }

        $betAmount = floatval($transaction["TransactionAmount"]);
        $after_balance = $member->balance + $betAmount;

        if ($after_balance < 0) {
            return response()->json([
                "ErrorCode" => 1001,
                "ErrorMessage" => "Insufficient Balance",
                "Balance" => $after_balance,
                "BeforeBalance" => $member->balance,
            ]);
        }

        // Perform the balance update and transaction cancellation logic here
        // Begin database transaction
        try {
            if(Transaction::where("external_transaction_id", $transaction["TransactionID"])->exists()){
                return [
                    "ErrorCode" => 1003,
                    "ErrorMessage" => "Duplicate Transaction",
                    "Balance" => $after_balance,
                    "BeforeBalance" => $member->balance
                ];
            }
            
            DB::beginTransaction();
    
            Transaction::create([
                "user_id" => $member->id,
                "external_transaction_id" => $transaction["TransactionID"],
                "wager_id" => $transaction["WagerID"]
            ]);

            // Update the user's balance
            // $member->balance = $after_balance;
            // $member->save();

            // Add logic to record the transaction cancellation in your database

            // Commit the transaction
            DB::commit();

            return response()->json([
                "ErrorCode" => 0,
                "ErrorMessage" => "Transaction cancelled successfully",
                "Balance" => $after_balance,
                "BeforeBalance" => $member->balance,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('CancelBet transaction failed: ' . $e->getMessage());

            return response()->json([
                "ErrorCode" => 1006,
                "ErrorMessage" => "Transaction cancellation failed",
                "Balance" => $member->balance
            ]);
        }
    }
}