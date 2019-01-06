<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\UserCardRelation
 *
 * @property-read \App\Card $cardInfo
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserCardRelation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserCardRelation newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\UserCardRelation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserCardRelation query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\UserCardRelation withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\UserCardRelation withoutTrashed()
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id
 * @property int $card_id
 * @property int $rank
 * @property int $type
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserCardRelation whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserCardRelation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserCardRelation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserCardRelation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserCardRelation whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserCardRelation whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserCardRelation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserCardRelation whereUserId($value)
 */
class UserCardRelation extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function cardInfo()
    {
        return $this->belongsTo(Card::class, 'card_id');
    }
}
