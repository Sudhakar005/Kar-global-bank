<?php

namespace app\models;

use Yii;
use yii\base\Model;

class InvestmentAccountTypes extends Model
{
    public static function findByType($accountType)
    {
        $jsonFileLink = Yii::getAlias('@app/store/data.json');
        $getData = file_get_contents($jsonFileLink);
        $getBankDetails = json_decode($getData, true);
        $getInvestmentAccountTypeInfo = isset($getBankDetails['kar-global-bank']['investment_account_types']) ? $getBankDetails['kar-global-bank']['investment_account_types'] : [];
        if(is_array($getInvestmentAccountTypeInfo)) {
            foreach($getInvestmentAccountTypeInfo as $key => $value) {
                if($value['investment_type_name'] == $accountType && $value['is_active'] == 1) {
                    return $value['id'];
                }
            }
        }
        return 0;
    }
}
