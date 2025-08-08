<?php

namespace Tests\Feature;

use Tests\TestCase;

class TakeHomeTest extends TestCase
{
    /** @test */
    public function take_home_api_test_sequence()
    {
        // Reset state before starting tests
        $this->postJson('/api/reset')
            ->assertStatus(200)
            ->assertExactJson(["OK"]);

        // Get balance for non-existing account
        $this->getJson('/api/balance?account_id=1234')
            ->assertStatus(404)
            ->assertExactJson([0]);

        // Create account with initial balance
        $this->postJson('/api/event', [
            'type' => 'deposit',
            'destination' => '100',
            'amount' => 10
        ])
            ->assertStatus(201)
            ->assertExactJson([
                'destination' => [
                    'id' => '100',
                    'balance' => 10
                ]
            ]);

        // Deposit into existing account
        $this->postJson('/api/event', [
            'type' => 'deposit',
            'destination' => '100',
            'amount' => 10
        ])
            ->assertStatus(201)
            ->assertExactJson([
                'destination' => [
                    'id' => '100',
                    'balance' => 20
                ]
            ]);

        // Get balance for existing account
        $this->getJson('/api/balance?account_id=100')
            ->assertStatus(201)
            ->assertExactJson([20]);

        // Withdraw from non-existing account
        $this->postJson('/api/event', [
            'type' => 'withdraw',
            'origin' => '200',
            'amount' => 10
        ])
            ->assertStatus(404)
            ->assertExactJson([0]);

        // Withdraw from existing account
        $this->postJson('/api/event', [
            'type' => 'withdraw',
            'origin' => '100',
            'amount' => 5
        ])
            ->assertStatus(201)
            ->assertExactJson([
                'origin' => [
                    'id' => '100',
                    'balance' => 15
                ]
            ]);

        // Transfer from existing account
        $this->postJson('/api/event', [
            'type' => 'transfer',
            'origin' => '100',
            'amount' => 15,
            'destination' => '300'
        ])
            ->assertStatus(201)
            ->assertExactJson([
                'origin' => [
                    'id' => '100',
                    'balance' => 0
                ],
                'destination' => [
                    'id' => '300',
                    'balance' => 15
                ]
            ]);

        // Transfer from non-existing account
        $this->postJson('/api/event', [
            'type' => 'transfer',
            'origin' => '200',
            'amount' => 15,
            'destination' => '300'
        ])
            ->assertStatus(404)
            ->assertExactJson([0]);
    }
}
