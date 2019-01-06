<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\CardType
 *
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardType newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\CardType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardType query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\CardType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\CardType withoutTrashed()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $avatar
 * @property string $color
 * @property int $user_id
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardType whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardType whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardType whereUserId($value)
 */
class CardType extends Model
{
    public $timestamps = false;
    protected $guarded = ['id'];
}
