<?php

namespace App\Services;

use Illuminate\Http\Request;

class EventService
{
    public function postEvent(Request $request, array &$accounts){
        $accountId = $request->input('destination');
        $amount = $request->input('amount');
        $type = $request->input('type');

        return $this->$type($accountId, $amount, $accounts);
    }

    private function deposit($accountId, $amount, array &$accounts){
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

    private function withdraw($accountId, $amount, array &$accounts){

    }

    private function transfer($accountId, $amount, array &$accounts){
    }

}
