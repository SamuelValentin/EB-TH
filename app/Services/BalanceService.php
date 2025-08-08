<?php

namespace App\Services;

use Illuminate\Http\Request;

class BalanceService
{
    public function getBalance(Request $request, array &$accounts){
        if (!isset($accounts[$request->input('account_id')])) {
            throw new \Exception("Account not found");
        }
        return $accounts[$request->input('account_id')]['balance'];
    }
}
