<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function topupNow(Request $request){
        $user_id = Auth::user()->id;
        $credit = $request->credit;
        $status = "proses";
        $description = 'Top Up Balance';

        Wallet::create([
            'user_id' => $user_id,
            'credit' => $credit,
            'status' => $status,
            'description' => $description
        ]);

        return redirect()->back()->with('status', 'Request topup success. Please deposit your money to Mini Bank Teller');
    }

    public function acceptRequest(Request $request){
        $wallet_id = $request->wallet_id;

        Wallet::find($wallet_id)->update([
            'status' => 'selesai'
        ]);

        return redirect()->back()->with('status', 'successfully approved the top up request');
    }
}
