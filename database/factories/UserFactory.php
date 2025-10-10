<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Campos de AdminLTE/Laravel
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),

            // CAMPOS PERSONALIZADOS AGREGADOS
            'apellido_paterno' => fake()->lastName(),
            'apellido_materno' => fake()->optional()->lastName(),
            'telefono' => fake()->optional()->phoneNumber(),
            'user_estado' => 'activo',
            'last_login_at' => null,
            
            // EL CAMPO CLAVE QUE FALTABA: id_rol
            // Asignamos un ID de Rol fijo (ej: 2 para un rol de 'Usuario' general)
            'id_rol' => 2, 
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}


