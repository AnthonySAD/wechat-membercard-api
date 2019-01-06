<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\CardShare
 *
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardShare newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardShare newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\CardShare onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardShare query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\CardShare withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\CardShare withoutTrashed()
 * @mixin \Eloquent
 * @property int $id
 * @property int $card_id
 * @property int $user_id
 * @property int $rank
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardShare whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardShare whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardShare whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardShare whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardShare whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardShare whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardShare whereUserId($value)
 */
class CardShare extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function cardInfo()
    {
        return $this->belongsTo(Card::class, 'card_id', 'id');
    }
}
