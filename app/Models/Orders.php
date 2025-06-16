<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'delivery_person_id',
        'company_id',
        'status',
        'origin_address',
        'destination_address',
        'total_price',
        'payment_method',
        'observations',
        'package_size',
        'fragile',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function deliveryPerson()
    {
        return $this->belongsTo(User::class, 'delivery_person_id');
    }

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    public const STATUS_PENDENTE = 1;
    public const STATUS_EM_ANDAMENTO = 2;
    public const STATUS_ENTREGUE = 3;
    public const STATUS_CANCELADO = 4;

    public static function statusLabels(): array
    {
        return [
            self::STATUS_PENDENTE => 'Pendente',
            self::STATUS_EM_ANDAMENTO => 'Em andamento',
            self::STATUS_ENTREGUE => 'Entregue',
            self::STATUS_CANCELADO => 'Cancelado',
        ];
    }

    public const PACKAGE_SIZE_PEQUENO = 1;
    public const PACKAGE_SIZE_MEDIO = 2;
    public const PACKAGE_SIZE_GRANDE = 3;

    public static function packageSizeLabels(): array
    {
        return [
            self::PACKAGE_SIZE_PEQUENO => 'Pequeno',
            self::PACKAGE_SIZE_MEDIO => 'Médio',
            self::PACKAGE_SIZE_GRANDE => 'Grande',
        ];
    }
}
