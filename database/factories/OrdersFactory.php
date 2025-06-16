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
        $isFromCompany = $this->faker->boolean();

        $user = $isFromCompany
            ? User::where('type', '3')->inRandomOrder()->first()
                ?? User::factory()->create(['type' => '3'])
            : User::where('type', '1')->inRandomOrder()->first()
                ?? User::factory()->create(['type' => '1']);

        // Define status como números
        $status = $this->faker->numberBetween(1, 2); // 1: pendente, 2: em_andamento

        // Define tamanho do pacote: 1 (pequeno), 2 (medio), 3 (grande)
        $packageSize = $this->faker->numberBetween(1, 3);

        if ($status !== 1) {
            $deliveryPerson = User::where('type', '2')->inRandomOrder()->first()
                ?? User::factory()->create(['type' => '2']);
        } else {
            $deliveryPerson = $this->faker->boolean(50)
                ? User::where('type', '2')->inRandomOrder()->first()
                    ?? User::factory()->create(['type' => '2'])
                : null;
        }

        return [
            'user_id' => $isFromCompany ? null : $user->id,
            'company_id' => $isFromCompany ? $user->id : null,
            'delivery_person_id' => $deliveryPerson?->id,
            'status' => $status,
            'origin_address' => $this->faker->address(),
            'destination_address' => $this->faker->address(),
            'total_price' => $this->faker->randomFloat(2, 10, 200),
            'payment_method' => $this->faker->randomElement(['pix', 'cartao', 'dinheiro']),
            'observations' => $this->faker->sentence(),
            'package_size' => $packageSize,
            'fragile' => $this->faker->boolean(30),
        ];
    }
}
