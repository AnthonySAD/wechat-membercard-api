<?php

namespace App\Http\Controllers;

use App\Card;
use App\CardShare;
use App\CardType;
use App\Exceptions\ApiException;
use App\Exceptions\ErrorCodes;
use App\Http\Traits\Validator;
use App\UserCardRelation;
use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @OA\Tag(
 *    name="会员卡",
 *    description="会员卡相关API",
 * )
 */

class CardController extends Controller
{
    use Validator;

    private $shareExpires = 604800;


    /**
     * @OA\Get(
     *     path="/card",
     *     summary="获取我拥有的会员卡",
     *     tags={"会员卡"},
     *     description="获取登入用户的会员卡信息",
     *     operationId="createUser",
     *     security={{"apiToken":{}}},
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="meta",
     *                  type="object",
     *                  @OA\Property(property="code",type="integer"),
     *                  @OA\Property(property="message",type="string"),
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(),
     *              )
     *          )
     *     )
     * )
     *
     */
    public function getMyCard(Request $request)
    {
        $cards = UserCardRelation::with(['cardInfo:id,code,color,info,avatar,name,code_type'])
            ->where('user_id', $request->userId)
            ->orderByDesc('rank')
            ->get(['rank','card_id','type']);

        if ($cards->isEmpty()){
            return $this->noContent();
        }else{
            return $this->ok($cards);
        }
    }

    /**
     * @OA\Get(
     *     path="/card/type",
     *     summary="获取会员卡类型",
     *     tags={"会员卡"},
     *     security={{"apiToken":{}}},
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="meta",
     *                  type="object",
     *                  @OA\Property(property="code",type="integer"),
     *                  @OA\Property(property="message",type="string"),
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(),
     *              )
     *          )
     *     )
     * )
     *
     */
    public function getCardType(Request $request)
    {
        $types = CardType::orderBy('name')->get();
        if ($types->isEmpty()){
            return $this->noContent();
        }else{
            return $this->ok($types);
        }
    }

    /**
     * @OA\Post(
     *     path="/card",
     *     summary="添加会员卡",
     *     tags={"会员卡"},
     *     security={{"apiToken":{}}},
     *     @OA\RequestBody(
     *          description="会员卡数据",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="code",
     *                  type="string",
     *                  description="会员卡号",
     *                  maxLength=30,
     *                  minLength=4,
     *              ),
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  description="会员卡名称",
     *                  maxLength=30,
     *                  minLength=2,
     *                  default="名称",
     *              ),
     *              @OA\Property(
     *                  property="type_id",
     *                  type="integer",
     *                  description="会员卡类型编号",
     *              ),
     *              @OA\Property(
     *                  property="color",
     *                  type="string",
     *                  description="颜色",
     *              ),
     *              @OA\Property(
     *                  property="info",
     *                  type="string",
     *                  description="备注信息",
     *              ),
     *              @OA\Property(
     *                  property="avatar",
     *                  type="string",
     *                  description="图标",
     *              ),
     *          )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="创建成功",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="meta",
     *                  type="object",
     *                  @OA\Property(property="code",type="integer"),
     *                  @OA\Property(property="message",type="string"),
     *              ),
     *          )
     *     )
     * )
     */
    public function addCard(Request $request)
    {
        $rules = [
            'name'=>'required|string|min:2|max:12',
            'code'=>'required|alpha_num|min:4|max:22',
            'info'=>'nullable|string|max:50',
            'color'=>'required|regex:/^#[\da-fA-F]{6}$/',
            'type_id'=>'required|integer',
            'code_type'=>'required|in:0,1',
        ];

        $data = $this->easyValidator($request, $rules);
        if($data['type_id'] != 0){
            $cardType = CardType::find($data['type_id']);
            if (!$cardType){
                throw new ApiException(ErrorCodes::BAD_REQUEST);
            }
            $data['avatar'] = $cardType->avatar;
        }else{
            $data['avatar'] = '';
        }
        $data['user_id'] = $request->userId;

        $card = Card::create($data);

        $lastCard = UserCardRelation::where('user_id', $request->userId)
            ->orderBy('rank', 'DESC')
            ->first();
        if (!$lastCard){
            $rank = 0;
        }else{
            $rank = $lastCard->rank + 1;
        }
        UserCardRelation::create([
            'user_id'=>$request->userId,
            'card_id'=>$card->id,
            'rank'=>$rank,
        ]);

        return $this->ok([
            'rank'=>$rank,
            'card_id'=>$card->id,
            'type'=>0,
            'card_info'=>[
                'avatar'=>$data['avatar'],
                'code'=>$data['code'],
                'code_type'=>$data['code_type'],
                'color'=>$data['color'],
                'name'=>$data['name'],
                'info'=>isset($data['info']) ? $data['info']: '',
            ]
        ]);
    }

    /**
     * @OA\Put(
     *     path="/card",
     *     summary="修改会员卡",
     *     tags={"会员卡"},
     *     security={{"apiToken":{}}},
     *     @OA\RequestBody(
     *          description="会员卡数据",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="card_id",
     *                  type="integer",
     *                  description="会员卡id",
     *              ),
     *              @OA\Property(
     *                  property="code",
     *                  type="string",
     *                  description="会员卡号",
     *                  maxLength=30,
     *                  minLength=4,
     *              ),
     *              @OA\Property(
     *                  property="color",
     *                  type="string",
     *                  description="颜色",
     *              ),
     *              @OA\Property(
     *                  property="info",
     *                  type="string",
     *                  description="备注信息",
     *              ),
     *          )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="meta",
     *                  type="object",
     *                  @OA\Property(property="code",type="integer"),
     *                  @OA\Property(property="message",type="string"),
     *              ),
     *          )
     *     )
     * )
     */
    public function changeCard(Request $request)
    {
        $rules = [
            'name'=>'required|string|min:2|max:12',
            'code'=>'required|alpha_num|min:4|max:22',
            'info'=>'nullable|string|max:50',
            'color'=>'required|regex:/^#[\da-fA-F]{6}$/',
            'type_id'=>'required|integer',
            'code_type'=>'required|in:0,1',
        ];

        $data = $this->easyValidator($request, $rules);

        $cardId = (string) $request->input('card_id');
        $card = Card::where('user_id', $request->userId)->where('id', $cardId)->first();
        if (!$card){
            throw new ApiException(ErrorCodes::BAD_REQUEST, 'invalid card');
        }
        $card->update($data);
        return $this->ok([
            'code'=>$data['code'],
            'code_type'=>$data['code_type'],
            'color'=>$data['color'],
            'name'=>$data['name'],
            'info'=>isset($data['info']) ? $data['info']: '',
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/card",
     *     summary="删除会员卡",
     *     tags={"会员卡"},
     *     security={{"apiToken":{}}},
     *     @OA\RequestBody(
     *          description="会员卡id",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="card_id",
     *                  type="integer",
     *                  description="会员卡id",
     *              ),
     *          )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="meta",
     *                  type="object",
     *                  @OA\Property(property="code",type="integer"),
     *                  @OA\Property(property="message",type="string"),
     *              ),
     *          )
     *     )
     * )
     */
    public function deleteCard(Request $request)
    {
        $card = Card::find($request->input('card_id', 0));
        if (!$card){
            throw new ApiException(ErrorCodes::BAD_REQUEST, 'invalid card');
        }

        if ($card->user_id = $request->userId){
            UserCardRelation::where('card_id', $card->id)->delete();
            CardShare::where('card_id', $card->id)->delete();
            $card->delete();
        }else{
            UserCardRelation::where('card_id', $card->id)->where('user_id', $request->userId)->delete();
        }

        return $this->ok();
    }

    /**
     * @OA\Post(
     *     path="/card/click",
     *     summary="点击会员卡",
     *     tags={"会员卡"},
     *     security={{"apiToken":{}}},
     *     @OA\RequestBody(
     *          description="会员卡id",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="card_id",
     *                  type="integer",
     *                  description="会员卡id",
     *              ),
     *              @OA\Property(
     *                  property="rank",
     *                  type="integer",
     *                  description="排序等级",
     *              ),
     *          )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="meta",
     *                  type="object",
     *                  @OA\Property(property="code",type="integer"),
     *                  @OA\Property(property="message",type="string"),
     *              ),
     *          )
     *     )
     * )
     */
    public function clickCard(Request $request)
    {
        $this->easyValidator($request, ['card_id'=>'required|integer','rank'=>'required|integer'], false);
        UserCardRelation::where('card_id', $request->input('card_id', 0))
            ->where('user_id', $request->userId)
            ->update(['rank'=>$request->input('rank', 1)]);

        return $this->ok();
    }

    /**
     * @OA\Post(
     *     path="/card/share",
     *     summary="会员卡分享",
     *     tags={"会员卡"},
     *     security={{"apiToken":{}}},
     *     @OA\RequestBody(
     *          description="会员卡id",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="card_id",
     *                  type="integer",
     *                  description="会员卡id",
     *
     *              ),
     *          )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="meta",
     *                  type="object",
     *                  @OA\Property(property="code",type="integer"),
     *                  @OA\Property(property="message",type="string"),
     *              ),
     *          )
     *     )
     * )
     */
    public function shareCard(Request $request)
    {
        $card = Card::where('id', $request->input('card_id', 0))
            ->where('user_id', $request->userId)
            ->first();
        if (!$card){
            throw new ApiException(ErrorCodes::BAD_REQUEST, 'invalid card');
        }
        $cardToken = (string)Str::uuid();
        \Redis::setex('share_card_' . $cardToken, $this->shareExpires, $card->id);
        return $this->ok(['card_token'=>$cardToken]);
    }

    /**
     * @OA\Get(
     *     path="/card/share",
     *     summary="获取被分享的会员卡",
     *     tags={"会员卡"},
     *     security={{"apiToken":{}}},
     *     @OA\Parameter(
     *         name="card_token",
     *         in="query",
     *         description="会员卡token",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="meta",
     *                  type="object",
     *                  @OA\Property(property="code",type="integer"),
     *                  @OA\Property(property="message",type="string"),
     *              ),
     *          )
     *     )
     * )
     */
    public function getShareCard(Request $request)
    {
        $cardToken = (string)$request->input('card_token');
        $cardId = \Redis::get('share_card_' . $cardToken);
        if (!$cardId){
            throw new ApiException(ErrorCodes::BAD_REQUEST, 'invalid card token');
        }
        $card = Card::find($cardId);
        if (!$card){
            \Redis::del('share_card_' . $cardToken);
            throw new ApiException(ErrorCodes::CARD_NOT_FOUND, 'card was deleted');
        }

        $hasCard = UserCardRelation::where('card_id', $cardId)
            ->where('user_id', $request->userId)
            ->first();

        if ($hasCard){
            throw new ApiException(ErrorCodes::CARD_OWNED, 'card already owned');
        }

        \Redis::del('share_card_' . $cardToken);
        $lastCardRank = UserCardRelation::where('user_id', $request->userId)
            ->orderByDesc('rank')
            ->first();

        if (!$lastCardRank){
            $rank = 0;
        }else{
            $rank = ++ $lastCardRank->rank;
        }

        UserCardRelation::create([
            'card_id'=>$cardId,
            'user_id'=>$request->userId,
            'type'=>1,
            'rank'=>$rank
        ]);

        return $this->ok([
            'rank'=>$rank,
            'card_id'=>$card->id,
            'type'=>1,
            'card_info'=>[
                'avatar'=>$card->avatar,
                'code'=>$card->code,
                'code_type'=>$card->code_type,
                'color'=>$card->color,
                'name'=>$card->name,
                'info'=>$card->info,
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/card/global",
     *     summary="全局分享会员卡",
     *     tags={"会员卡"},
     *     security={{"apiToken":{}}},
     *     @OA\RequestBody(
     *          description="会员卡id",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="card_id",
     *                  type="integer",
     *                  description="会员卡id",
     *
     *              ),
     *          )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="meta",
     *                  type="object",
     *                  @OA\Property(property="code",type="integer"),
     *                  @OA\Property(property="message",type="string"),
     *              ),
     *          )
     *     )
     * )
     */
    public function shareCardGlobal(Request $request)
    {
        $cardId = (string)$request->input('card_id', 0);
        $card = Card::where('id', $cardId)
            ->where('user_id', $request->userId)
            ->first();
        if (!$card){
            throw new ApiException(ErrorCodes::BAD_REQUEST, 'invalid card id');
        }
        CardShare::firstOrCreate(['card_id'=>$cardId, 'user_id'=>$request->userId]);

        return $this->ok();
    }

    /**
     * @OA\Get(
     *     path="/card/global",
     *     summary="获取全局会员卡",
     *     tags={"会员卡"},
     *     security={{"apiToken":{}}},
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="meta",
     *                  type="object",
     *                  @OA\Property(property="code",type="integer"),
     *                  @OA\Property(property="message",type="string"),
     *              ),
     *          )
     *     )
     * )
     */
    public function getGlobalCard()
    {
        $cards = CardShare::with('cardInfo:id,code,name,info,avatar,color')
            ->orderBy('rank', 'DESC')
            ->get(['card_id', 'rank', 'user_id', 'status']);
        if ($cards->isEmpty()){
            return $this->noContent();
        }else{
            return $this->ok($cards);
        }
    }

}
