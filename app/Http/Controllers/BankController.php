<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Account;
use App\Models\Holder;
use Validator;

class BankController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
      // validate inputs
      $validator = Validator::make($request->all(), [
        'name' => 'required',
        'number' => 'required|unique:accounts'
       ]);

       if ($validator->fails()) {
           return response()->json($validator->messages(), 400);
       }

      // create holder
      $holder = new Holder(
        array(
          'name' => $request->name?:null,
          'phone' => $request->phone?:null,
          'email' => $request->email?:null
        ));
      $holder->save();

      // create account
      $account = new Account(
        array(
            'balance' => $request->balance?:0,
            'number' => $request->number?:null,
            'overdraft_limit' => $request->overdraft_limit?:0
        ));

        // Bind account to holder
      $account->holder()->associate($holder);
      $account->save();

      return response()->json($account, 200);

    }

    /**
     * Display the specified bank account.
     *
     * @param  int  $number
     * @return \Illuminate\Http\Response
     */
    public function show($number)
    {
        $account = Account::with('holder')
        ->where('number', '=', $number)
        ->where('active', '=', true)
        ->first();

        if (empty($account)) {
          return response()->json($account, 204);
        }

        return response()->json($account, 200);
    }

    /**
     * Update the bank account resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deposit(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'number' => 'required|Integer',
        'amount' => 'required|Numeric'
       ]);

       if ($validator->fails()) {
           return response()->json($validator->messages(), 400);
       }

       $account = Account::where('number', '=', $request->number)
       ->where('active', '=', true)
       ->first();

      if (empty($account)) {
        return response()->json($account, 204);
      }
      $account->balance = $account->balance + $request->amount;
      $account->save();

      return response()->json($account, 200);
    }

    /**
     * Update the bank account resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function withdraw(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'number' => 'required|Integer',
        'amount' => 'required|Numeric'
       ]);

       if ($validator->fails()) {
           return response()->json($validator->messages(), 400);
       }

       $account = Account::where('number', '=', $request->number)
       ->where('active', '=', true)
       ->first();

      if (empty($account)) {
        return response()->json($account, 204);
      }

      if (!$this->check($account, $request->amount)) {
        return response()->json($account, 403); // forbidden
      };

      $account->balance = $account->balance - $request->amount;
      $account->save();

      return response()->json($account, 200);
    }

    /**
     * Remove the specified bank account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function close(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'number' => 'required|Integer',
       ]);

       if ($validator->fails()) {
           return response()->json($validator->messages(), 400);
       }

       $account = Account::where('number', '=', $request->number)
       ->where('active', '=', true)
       ->first();

      if (empty($account)) {
        return response()->json($account, 204);
      }

      $account->active = false;
      $account->save();

      return response()->json($account, 200);
    }

    protected function check($account, $withdrawal)
    {
      if ($account->balance + $account->overdraft_limit < $withdrawal) {
        return false;
      }

      return true;
    }

}
