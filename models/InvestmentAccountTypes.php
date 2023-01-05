<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "investment_account_types".
 *
 * @property int $id
 * @property string|null $investment_type_name
 * @property int $is_active
 * @property string|null $created_at
 * @property string $modified_at
 */
class InvestmentAccountTypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'investment_account_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_active'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['investment_type_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'investment_type_name' => 'Investment Type Name',
            'is_active' => 'Is Active',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
        ];
    }
}
