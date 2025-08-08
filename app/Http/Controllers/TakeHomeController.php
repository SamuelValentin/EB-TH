<?php

namespace App\Http\Controllers;

use App\Services\BalanceService;
use App\Services\EventService;
use Illuminate\Http\Request;

class TakeHomeController extends Controller
{
    public static array $accounts = [];
    private $eventService;
    private $balanceService;

    public function __construct(EventService $eventService, BalanceService $balanceService)
    {
        $this->eventService = $eventService;
        $this->balanceService = $balanceService;
    }

    public function reset(){
        self::$accounts = [];
        return response()->json("OK", 200);
    }

    public function getBalance(Request $request){
        try {
            $balance = $this->balanceService->getBalance($request, self::$accounts);
            return response()->json($balance, 201);
        } catch (\Throwable $th) {
            return response()->json(0, 404);
        }
    }

    public function postEvent(Request $request){
        try {
            $event = $this->eventService->postEvent($request, self::$accounts);
            return response()->json($event, 201);
        } catch (\Throwable $th) {
            return response()->json(0, 404);
        }
    }
}
