<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Card
 *
 * @property-read \App\CardType $typeInfo
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Card onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Card withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Card withoutTrashed()
 * @mixin \Eloquent
 * @property int $id
 * @property int $type_id
 * @property int $user_id
 * @property string $code
 * @property string $info
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereUserId($value)
 * @property string $name
 * @property string $avatar
 * @property string $color
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereName($value)
 */
class Card extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

}
