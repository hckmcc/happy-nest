<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->unique()->phoneNumber(),
            'password' => Hash::make('password'), // дефолтный пароль для тестов
            'photo' => null
        ];
    }
    public function admin(): static
    {
        return $this->afterCreating(function (User $user) {
            $adminRole = Role::where('slug', 'admin')->first() ?? Role::factory()->admin()->create();
            $user->roles()->attach($adminRole->id);
        });
    }

    public function withRole(string $roleName = 'user'): static
    {
        return $this->afterCreating(function (User $user) use ($roleName) {
            $role = Role::where('slug', $roleName)->first()
                ?? Role::factory()->state(['name' => $roleName, 'slug' => $roleName])->create();
            $user->roles()->attach($role->id);
        });
    }
}
