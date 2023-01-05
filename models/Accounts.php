<?php

namespace app\models;

use Yii;

use app\models\AccountTypes;
use app\models\InvestmentAccountTypes;
/**
 * This is the model class for table "accounts".
 *
 * @property int $account_id
 * @property int|null $account_number
 * @property string|null $name
 * @property int|null $account_type_id
 * @property int $investment_type_id
 * @property int $balance
 * @property int $is_active
 * @property string|null $created_at
 * @property string $modified_at
 */
class Accounts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'accounts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_number', 'account_type_id', 'investment_type_id', 'balance', 'is_active'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'account_id' => 'Account ID',
            'account_number' => 'Account Number',
            'name' => 'Name',
            'account_type_id' => 'Account Type ID',
            'investment_type_id' => 'Investment Type ID',
            'balance' => 'Balance',
            'is_active' => 'Is Active',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
        ];
    }
    public function getAccounttype() 
    {
        return $this->hasOne(AccountTypes::className(), ['id' => 'account_type_id']);
    }
    public function getinvestmenttype() 
    {
        return $this->hasOne(InvestmentAccountTypes::className(), ['id' => 'investment_type_id']);
    }
}
