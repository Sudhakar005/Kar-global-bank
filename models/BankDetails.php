<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bank_details".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $ifsc_code
 * @property string|null $branch
 * @property string|null $state
 * @property string|null $country
 */
class BankDetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bank_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'ifsc_code', 'branch', 'state', 'country'], 'string', 'max' => 25],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'ifsc_code' => 'Ifsc Code',
            'branch' => 'Branch',
            'state' => 'State',
            'country' => 'Country',
        ];
    }
}
