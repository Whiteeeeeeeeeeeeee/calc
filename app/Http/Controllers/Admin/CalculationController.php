<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Calculation;

class CalculationController extends Controller
{
    public function index()
    {
        $calculations = Calculation::with('user')->latest()->paginate(20);
        return view('admin.calculations.index', compact('calculations'));
    }

    public function destroy(Calculation $calculation)
    {
        $calculation->delete();
        return response()->json(['success' => true]);
    }
}