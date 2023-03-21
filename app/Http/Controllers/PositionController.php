<?php

namespace App\Http\Controllers;

use App\Jobs\PositronIntegraJob;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * @throws \Exception
     */
    public function store(Request $request)
    {
        dispatch(new PositronIntegraJob());

        return response()->json(['message' => 'Listening to the queue and storing the data...'], 200);
    }
}
