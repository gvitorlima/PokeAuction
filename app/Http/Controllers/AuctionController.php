<?php

namespace App\Http\Controllers;

use App\Http\External\PokeApiClient;
use App\Http\Services\AuctionService;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

use Exception;

class AuctionController extends Controller
{
    public function __construct(private AuctionService $auctionService)
    {
    }

    public function getDailyAuction()
    {
        try {

            echo '<pre>';
            print_r($this->auctionService->dailyPokemons());
            echo '</pre>';
            exit;

            // $this->auctionService->dailyAuction();
        } catch (Exception $error) {

            $response = [
                "code" => $error->getCode(),
                "message" => $error->getMessage()
            ];

            return response()->json($response, $error->getCode());
        }
    }
}
