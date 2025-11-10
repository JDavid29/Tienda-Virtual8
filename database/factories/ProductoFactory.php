<?php

namespace Database\Factories;
// importamos el modelo de Producto
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Producto::class;

    public function definition()
    {
        return [
            'category_id' => rand(1, 2),
            'nombre'=> $this->faker->sentence(2),
            'descripcion' => $this->faker->sentence(20),
            'precio' => $this->faker->numberBetween(100, 2000),

        ];
    }
}
