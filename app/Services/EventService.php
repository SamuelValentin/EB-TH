<?php

namespace App\Services;

use Illuminate\Http\Request;

class EventService
{
    public function postEvent(Request $request, array &$accounts){
        $accountId = $request->input('destination');
        $amount = $request->input('amount');
        $type = $request->input('type');

        if (!isset($accounts[$accountId])) {
            $accounts[$accountId] = ['balance' => 0];
        }

        $accounts[$accountId]['balance'] += $amount;

        return [
            'destination' => [
                'id' => $accountId,
                'balance' => $accounts[$accountId]['balance']
            ]
        ];
    }
}
