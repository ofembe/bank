<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AccountTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A test for accout capabilities.
     *
     * @return void
     */
    public function testAccount()
    {
      // create account
      $this->json('POST', '/account', [
        'name' => 'Emmanuel Ofembe',
        'email' => 'ofembe12345234@yahoo.com',
        'phone' => 3264564356,
        'number' => 545264654565,
        'balance' =>10000000,
        'overdraft_limit' => 100000
      ])
      ->seeJson([
       'balance' =>10000000,
       'overdraft_limit' => 100000,
       'number' => 545264654565
      ]);

      // Get account
      $this->json('GET', '/account/545264654565')
      ->seeJson([
        'balance' => 10000000
      ]);

      // Do a deposit
      $this->json('POST', '/account/deposit', [
         'number' => 545264654565,
         'amount' => 200
      ])
      ->seeJson([
        'balance' =>10000200
      ]);

      // do a withdrawal
      $this->json('POST', '/account/withdrawal', [
       'number' => 545264654565,
       'amount' => 200
      ])
      ->seeJson([
        'balance' =>10000000
      ]);

      // Do an overdraft
      $this->json('POST', '/account/withdrawal', [
        'number' => 545264654565,
        'amount' => 100000000
      ])
      ->seeJson([
       'balance' => 10000000
      ]);

      // close account
      $this->json('DELETE', '/account', [
       'number' => 545264654565
      ])
      ->seeJson([
        'active' => false
      ]);

    }

}
