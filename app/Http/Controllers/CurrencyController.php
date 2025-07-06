<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::all();
        return view('currencies.index', compact('currencies'));
    }

    public function create()
    {
        $currencies = Currency::all();
        return view('currencies.create',compact('currencies'));
    }

    public function set()
    {
        $currencies = Currency::all();
        return view('currencies.create',compact('currencies'));
    }

    public function setDefault(Request $request)
    {
        $request->validate([
            'currency_id' => 'required|exists:currencies,id',
        ]);

        Currency::query()->update(['is_default' => false]);

        Currency::where('id', $request->currency_id)->update(['is_default' => true]);

        return redirect()->back();
    }


    public function store(Request $request)
    {
        $request->validate([
            'symbol' => 'required|string|max:10',
            'name' => 'required|string|max:255',
        ]);

        Currency::create($request->only('symbol', 'name'));

        return redirect()->route('currencies.index');
    }

    public function edit(Currency $currency)
    {
        return view('currencies.edit', compact('currency'));
    }

    public function update(Request $request, Currency $currency)
    {
        $request->validate([
            'symbol' => 'required|string|max:10',
            'name' => 'required|string|max:255',
        ]);

        $currency->update($request->only('symbol', 'name'));

        return redirect()->route('currencies.index');
    }

    public function destroy(Currency $currency)
    {
        $currency->delete();

        return redirect()->route('currencies.index');
    }
}

