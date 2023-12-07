<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Premise
 *
 * @property int $id
 * @property int $client_id
 * @property string $titulo
 * @property string $descripcion
 * @property int $cantidad_habitaciones
 * @property int $cantidad_camas
 * @property int $cantidad_banos
 * @property int $max_personas
 * @property int $tiene_wifi
 * @property string $tipo_propiedad 0: Casa, 1: Departamento, 2: Cabaña, 3: Loft, 4: Hostal, 5: Hotel, 6: Otro
 * @property string $precio_por_noche
 * @property string $ubicacion_lat
 * @property string $ubicacion_long
 * @property string $ubicacion_ciudad
 * @property string $tarifa_limpieza
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Premise newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Premise newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Premise query()
 * @method static \Illuminate\Database\Eloquent\Builder|Premise whereCantidadBanos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Premise whereCantidadCamas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Premise whereCantidadHabitaciones($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Premise whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Premise whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Premise whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Premise whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Premise whereMaxPersonas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Premise wherePrecioPorNoche($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Premise whereTarifaLimpieza($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Premise whereTieneWifi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Premise whereTipoPropiedad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Premise whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Premise whereUbicacionCiudad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Premise whereUbicacionLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Premise whereUbicacionLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Premise whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Premise extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'titulo',
        'descripcion',
        'cantidad_habitaciones',
        'cantidad_camas',
        'cantidad_banos',
        'max_personas',
        'tiene_wifi',
        'tipo_propiedad',
        'precio_por_noche',
        'ubicacion_lat',
        'ubicacion_long',
        'ubicacion_ciudad',
        'tarifa_limpieza',
    ];

    protected $casts = [
        'tiene_wifi' => 'boolean',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class,'premises_id');
    }
}
