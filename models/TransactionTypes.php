<?php

namespace app\models;

use Yii;
use yii\base\Model;

class TransactionTypes extends Model
{
    public static function findByType($transactionType)
    {
        $jsonFileLink = Yii::getAlias('@app/store/data.json');
        $getData = file_get_contents($jsonFileLink);
        $getBankDetails = json_decode($getData, true);
        $getTransactionTypeInfo = isset($getBankDetails['kar-global-bank']['transaction_types']) ? $getBankDetails['kar-global-bank']['transaction_types'] : [];
        if(is_array($getTransactionTypeInfo)) {
            foreach($getTransactionTypeInfo as $key => $value) {
                if($value['type_name'] == $transactionType && $value['is_active'] == 1) {
                    return $value['id'];
                }
            }
        }
        return 0;
    }
}
