<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\transactions;

class TransactionController extends Controller
{
    public function list()
    {
        $transactions = transactions::all();
        return view('transactions.list', ['transactions' => $transactions]);
    }

    public function create()
    {
        return view('transactions.create');
    }

    //  This method will store in erp DB
    public function store(Request $request)
    {
        $rules = [
            'date'=> 'required|date',
            'amount'=> 'required|numeric|min:0',
            'type'=> 'required|string|in:income,expense',
            'category'=> 'required|string',
            'description'=>'nullable|string'
    

        ];
     $validator = Validator::make($request->all(),$rules);
     if($validator->fails()){
        return redirect()->route('transactions.create')->withInput()->withErrors($validator);
     }

     $data = $request->only(['date', 'amount', 'type', 'category', 'description']);
        if (empty($data['description'])) {
            $data['description'] = '';
        }
    //  if transaction passes
     
    transactions::create($data);
    return redirect()->route('transactions.list');


    }

    public function edit($id){
        $transaction = transactions::findOrFail($id);
        
       
        return view('transactions.edit', ['transaction' => $transaction]);
    }
  
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|string|in:income,expense', // Validation rule for type
            'category' => 'required|string',
            'description' => 'nullable|string'
        ]);

        $transaction = transactions::findOrFail($id);
        $transaction->update($validated);

        return redirect()->route('transactions.list');
    }

    public function destroy($id)
    {
        $transaction = transactions::findOrFail($id);
        $transaction->delete();
        return redirect()->route('transactions.list');
    }

}
