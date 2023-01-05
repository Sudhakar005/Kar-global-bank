<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transaction_history".
 *
 * @property int $transaction_id
 * @property int|null $account_id
 * @property int|null $transaction_type_id
 * @property int $transaction_amount
 * @property int|null $to_account_id
 * @property string|null $created_at
 */
class TransactionHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id', 'transaction_type_id', 'transaction_amount', 'to_account_id'], 'integer'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'transaction_id' => 'Transaction ID',
            'account_id' => 'Account ID',
            'transaction_type_id' => 'Transaction Type ID',
            'transaction_amount' => 'Transaction Amount',
            'to_account_id' => 'To Account ID',
            'created_at' => 'Created At',
        ];
    }
}
