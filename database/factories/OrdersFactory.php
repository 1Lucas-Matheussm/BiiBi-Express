<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrdersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create(['role' => 'cliente']);
        $deliveryPerson = User::factory()->create(['role' => 'entregador']);
        $company = User::factory()->create(['role' => 'empresa']);

        return [
            'user_id' => $user->id,
            'delivery_person_id' => $deliveryPerson->id,
            'company_id' => $company->id,

            'status' => $this->faker->randomElement(['pendente', 'em_andamento', 'entregue', 'cancelado']),
            'origin_address' => $this->faker->address(),
            'destination_address' => $this->faker->address(),

            'total_price' => $this->faker->randomFloat(2, 10, 200),
            'payment_method' => $this->faker->randomElement(['pix', 'cartao', 'dinheiro']),
            'observations' => $this->faker->optional()->sentence(),

            'height' => $this->faker->randomFloat(2, 5, 100),
            'width' => $this->faker->randomFloat(2, 5, 100),
            'length' => $this->faker->randomFloat(2, 5, 100),
        ];
    }
}
