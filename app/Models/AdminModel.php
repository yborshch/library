<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AdminModel extends Model
{
    const ORDER_ASC = 'asc';
    const ORDER_DESC = 'desc';

    const ALLOWED_ORDER = [
        self::ORDER_ASC,
        self::ORDER_DESC,
    ];

    /**
     * @param string $order
     * @return bool
     */
    public static function validateOrder(string $order): bool
    {
        return in_array($order, self::ALLOWED_ORDER);
    }
}
