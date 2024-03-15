<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Card;

class CardController extends Controller
{
    public function index()
    {
        return view('cards.index');
    }

    public function create()
    {
        return view('cards.create');
    }

    public function store(Request $request)
    {
        $card = new Card;

        $data = $card->getCardDataFromCr($request->url);

        $card->search_term = $request->search_term;
        $card->url = $request->url;
        $card->cr_price = $data['cr_price'];
        $card->image_url = $data['image_url'];
        //$card->cr_price = '14,800';
        $card->save();
    }
}
