<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoleTest extends TestCase
{
    /** @test */
    public function tiene_la_tabla_correcta()
    {
        $role = new Role();
        $this->assertEquals('roles', $role->getTable());
    }

    /** @test */
    public function tiene_la_llave_primaria_correcta()
    {
        $role = new Role();
        $this->assertEquals('id_rol', $role->getKeyName());
    }

    /** @test */
    public function tiene_los_campos_fillable_correctos()
    {
        $role = new Role();
        $this->assertEquals(
            ['nombre', 'descripcion'],
            $role->getFillable()
        );
    }

    /** @test */
    public function la_relacion_usuarios_es_has_many()
    {
        $role = new Role();
        $relation = $role->usuarios();

        // Verificamos que sea instancia de HasMany
        $this->assertInstanceOf(HasMany::class, $relation);

        // Verificamos que apunte al modelo correcto
        $this->assertEquals(User::class, $relation->getRelated()::class);

        // Verificamos la foreign key usada
        $this->assertEquals('id_rol', $relation->getForeignKeyName());
    }
}
