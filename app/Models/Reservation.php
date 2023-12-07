<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Reservation
 *
 * @property int $id
 * @property int $client_id
 * @property int $premises_id
 * @property int $cantidad_noches
 * @property string $fecha_inicio
 * @property string $fecha_final
 * @property string $tarifa_airbnb
 * @property string $costo_total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereCantidadNoches($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereCostoTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereFechaFinal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereFechaInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation wherePremisesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereTarifaAirbnb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'premises_id',
        'cantidad_noches',
        'fecha_inicio',
        'fecha_final',
        'tarifa_airbnb',
        'costo_total',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_final' => 'date',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function premise()
    {
        return $this->belongsTo(Premise::class, 'premises_id');
    }
}
