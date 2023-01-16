<?php

namespace app\models;

use Yii;
use yii\base\Model;

class BankDetails extends Model
{
    public static function find()
    {
        $data = [
            'id' => 1,
            'name' => 'Kar Global',
            'ifsc_code' => 'KG1010GK',
            'branch' => 'Chennai',
            'state' => 'Tamilnadu',
            'country' => 'India',
        ];
        $jsonFileLink = Yii::getAlias('@app/store/data.json');
        $getData = file_get_contents($jsonFileLink);
        $getBankDetails = json_decode($getData, true);
        if(isset($getBankDetails['kar-global-bank']['bank_details'])) {
            $getBankDetails['kar-global-bank']['bank_details'] = $data;
        } else {
            $getBankDetails['kar-global-bank']['bank_details'] = $data;
        }
        $fileOpen = fopen($jsonFileLink, 'w+');
        fwrite($fileOpen, json_encode($getBankDetails, JSON_PRETTY_PRINT));
        fclose($fileOpen);
        return $data;
    }
}
