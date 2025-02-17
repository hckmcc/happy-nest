<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'name' => 'Пользователь',
            'slug' => 'user'
        ];
    }

    public function admin(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Администратор',
                'slug' => 'admin'
            ];
        });
    }
}
