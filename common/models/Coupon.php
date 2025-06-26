<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "coupon".
 *
 * @property int $id
 * @property string $text
 * @property string $type sum / percent
 * @property float $amount
 * @property float $minOrderSum
 * @property float $maxOrderSum
 * @property int|null $excludeSale
 * @property string $includeProductIds
 * @property string $excludeProductIds
 * @property string $dateCreated
 * @property string|null $dateActiveUntil
 * @property int|null $limitUsages
 * @property int|null $limitUsagesPerUser
 * @property int|null $stateId
 * @property string|null $description
 * @property string|null $emailBind
 * @property string|null $groupType
 */
class Coupon extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'coupon';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text', 'type', 'amount'], 'required'],
            [['text', 'includeProductIds', 'excludeProductIds', 'emailBind','groupType'], 'string'],
            [['amount', 'minOrderSum', 'maxOrderSum'], 'number'],
            [['excludeSale', 'limitUsages', 'limitUsagesPerUser','stateId'], 'integer'],
            [['dateCreated', 'dateActiveUntil'], 'safe'],
            [['type'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 2000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Text',
            'type' => 'Type',
            'amount' => 'Amount',
            'minOrderSum' => 'Min Order Sum',
            'maxOrderSum' => 'Max Order Sum',
            'excludeSale' => 'Exclude Sale',
            'includeProductIds' => 'Include Product Ids',
            'excludeProductIds' => 'Exclude Product Ids',
            'dateCreated' => 'Date Created',
            'dateActiveUntil' => 'Date Active Until',
            'limitUsages' => 'Limit Usages',
            'limitUsagesPerUser' => 'Limit Usages Per User',
            'description' => 'Description',
            'stateId' => 'State ID',
            'emailBind' => 'emailBind',
            'groupType' => 'Group Type'
        ];
    }

    public static function addPersonal($amount,$type='percent',$srok,$groupType,$email,$sendEmail){

        //srok like "+30 minutes"

        $dateActiveUntil = date("y-m-d H:i:s", strtotime($srok));

        $c = new Coupon;
        $c->text = Coupon::generateText();
        $c->type = $type;
        $c->amount = $amount;
        $c->limitUsages = 1;
        $c->dateCreated = date("y-m-d H:i:s", strtotime('-1 days'));
        $c->dateActiveUntil = $dateActiveUntil;
        $c->maxOrderSum = null;
		$c->minOrderSum = null;
        $c->emailBind = trim(strtolower($email));
        $c->stateId = 1;
        $c->groupType = $groupType;

        if( !$c->save() ) return $c->getErrors();
        
        if( $sendEmail ){
            exec('/opt/php74/bin/php '.Yii::$app->basePath.'/../yii mail/couponpersonal '.$c->id.' > /dev/null 2>/dev/null &');
        }

        return true;
        
    }

    public static function plusUsage($text){
        $c = Coupon::find()->where(['text'=>$text])->one();
        if($c) {
            $c->usagesCount += 1;
            $c->save();
        }
        
    }

    public static function minusUsage($text){
        $c = Coupon::find()->where(['text'=>$text])->one();
        if($c && $c->usagesCount > 0){
            $c->usagesCount -= 1;
            $c->save();
        }
        
    }

    public static function canUsageByCount($text){
        $c = Coupon::find()->where(['text'=>$text])->one();
        if(!$c) return false;

        if(!$c->limitUsages || $c->limitUsages == 0) return true;

        if($c->usagesCount >= $c->limitUsages) return false;
        else return true;
        
    }

    public static function generateText($length = 5){
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}
