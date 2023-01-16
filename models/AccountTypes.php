<?php

namespace app\models;

use Yii;
use yii\base\Model;

class AccountTypes extends Model
{
    public static function findByType($accountType)
    {
        $jsonFileLink = Yii::getAlias('@app/store/data.json');
        $getData = file_get_contents($jsonFileLink);
        $getBankDetails = json_decode($getData, true);
        $getAccountTypeInfo = isset($getBankDetails['kar-global-bank']['account_types']) ? $getBankDetails['kar-global-bank']['account_types'] : [];
        if(is_array($getAccountTypeInfo)) {
            foreach($getAccountTypeInfo as $key => $value) {
                if($value['account_type_name'] == $accountType && $value['is_active'] == 1) {
                    return $value['id'];
                }
            }
        }
        return 0;
    }
}
