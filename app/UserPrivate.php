<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UserPrivate
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserPrivate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserPrivate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserPrivate query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id
 * @property int $score
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserPrivate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserPrivate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserPrivate whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserPrivate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserPrivate whereUserId($value)
 */
class UserPrivate extends Model
{
    protected $guarded = ['id'];
}
