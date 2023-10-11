<?php

namespace App\Http\Controllers;

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

            $this->auctionService->dailyAuction();
        } catch (Exception $error) {
        }
    }
}
