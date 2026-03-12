<?php

namespace Database\Factories;

use App\Models\Kyc;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class KycFactory extends Factory
{
    protected $model = Kyc::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'full_name' => $this->faker->name(),
            'date_of_birth' => '1990-01-01',
            'address' => $this->faker->address(),
            'id_type' => 'aadhaar',
            'id_number' => $this->faker->numerify('############'),
            'id_document' => 'documents/test.jpg',
            'selfie_image' => 'selfies/test.jpg',
            'status' => 'approved',
        ];
    }
}
