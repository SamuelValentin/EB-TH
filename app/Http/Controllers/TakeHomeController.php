<?php

namespace App\Http\Controllers;

use App\Services\BalanceService;
use App\Services\EventService;
use Illuminate\Http\Request;

class TakeHomeController extends Controller
{
    private $eventService;
    private $balanceService;
    private $dataFile;

    public function __construct(EventService $eventService, BalanceService $balanceService)
    {
        $this->eventService = $eventService;
        $this->balanceService = $balanceService;
        $this->dataFile = storage_path('app/accounts.json');
    }

    private function loadAccounts(){
        $content = file_get_contents($this->dataFile);
        return json_decode($content, true);
    }

    private function saveAccounts(array $accounts){
        file_put_contents($this->dataFile, json_encode($accounts));
    }

    public function reset(){
        $this->saveAccounts([]);
        return response('OK', 200);
    }

    public function getBalance(Request $request){
        try {
            $accounts = $this->loadAccounts();
            $balance = $this->balanceService->getBalance($request, $accounts);
            return response()->json($balance, 200);
        } catch (\Throwable $th) {
            return response()->json(0, 404);
        }
    }

    public function postEvent(Request $request){
        try {
            $accounts = $this->loadAccounts();
            $event = $this->eventService->postEvent($request, $accounts);
            $this->saveAccounts($accounts);
            return response()->json($event, 201);
        } catch (\Throwable $th) {
            return response()->json(0, 404);
        }
    }
}
