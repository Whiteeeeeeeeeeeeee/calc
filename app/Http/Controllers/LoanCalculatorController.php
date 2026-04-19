<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::all();
        return view('admin.loans.index', compact('loans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'type' => 'required|string|unique:loans',
            'annual_rate' => 'required|numeric',
            'min_amount' => 'required|numeric',
            'max_amount' => 'required|numeric',
            'min_term_months' => 'required|integer',
            'max_term_months' => 'required|integer',
        ]);

        Loan::create($request->all());
        return redirect()->back()->with('success', 'Кредит успешно добавлен');
    }

    public function update(Request $request, Loan $loan)
    {
        $request->validate([
            'name' => 'required|string',
            'annual_rate' => 'required|numeric',
            'min_amount' => 'required|numeric',
            'max_amount' => 'required|numeric',
        ]);

        $loan->update([
            'name' => $request->name,
            'annual_rate' => $request->annual_rate,
            'min_amount' => $request->min_amount,
            'max_amount' => $request->max_amount,
            'is_active' => $request->has('is_active')
        ]);
        
        return redirect()->back()->with('success', 'Кредит обновлен');
    }

    public function updateRate(Request $request, Loan $loan)
    {
        $loan->update(['annual_rate' => $request->annual_rate]);
        return response()->json(['success' => true]);
    }

    public function toggle(Loan $loan)
    {
        $loan->update(['is_active' => !$loan->is_active]);
        return response()->json(['success' => true]);
    }

    public function destroy(Loan $loan)
    {
        $loan->delete();
        return response()->json(['success' => true]);
    }
}