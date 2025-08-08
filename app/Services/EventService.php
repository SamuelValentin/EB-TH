<?php

namespace App\Services;

use Illuminate\Http\Request;

class EventService
{
    public function postEvent(Request $request, array &$accounts){
        $destination = $request->input('destination');
        $origin = $request->input('origin');
        $amount = $request->input('amount');
        $type = $request->input('type');

        return $this->$type($destination, $origin, $amount, $accounts);
    }

    private function deposit($destination, $origin, $amount, array &$accounts){
        if (!isset($accounts[$destination])) {
            $accounts[$destination] = ['balance' => 0];
        }

        $accounts[$destination]['balance'] += $amount;

        return [
            'destination' => [
                'id' => $destination,
                'balance' => $accounts[$destination]['balance']
            ]
        ];
    }

    private function withdraw($destination, $origin, $amount, array &$accounts){
        if (!isset($accounts[$origin])) {
            throw new \Exception("Account not found");
        }

        $accounts[$origin]['balance'] -= $amount;

        return [
            'origin' => [
                'id' => $origin,
                'balance' => $accounts[$origin]['balance']
            ]
        ];
    }

    private function transfer($destination, $origin, $amount, array &$accounts){
        if (!isset($accounts[$origin])) {
            throw new \Exception("Origin account not found");
        }

        if (!isset($accounts[$destination])) {
            $accounts[$destination] = ['balance' => 0];
        }

        $accounts[$origin]['balance'] -= $amount;
        $accounts[$destination]['balance'] += $amount;

        return [
            'origin' => [
                'id' => $origin,
                'balance' => $accounts[$origin]['balance']
            ],
            'destination' => [
                'id' => $destination,
                'balance' => $accounts[$destination]['balance']
            ]
        ];
    }

}
