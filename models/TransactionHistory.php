<?php

namespace app\models;

use Yii;
use yii\base\Model;

class TransactionHistory extends Model
{
    public static function save($data)
    {
        $jsonFileLink = Yii::getAlias('@app/store/data.json');
        $getData = file_get_contents($jsonFileLink);
        $getAccountDetails = json_decode($getData, true);
        $getTransactionInfo = isset($getAccountDetails['kar-global-bank']['transaction_history']) ? $getAccountDetails['kar-global-bank']['transaction_history'] : [];
        $index = count($getTransactionInfo);
        $data['id'] = count($getTransactionInfo) + 1;
        $data['is_active'] = '1';
        if(count($getTransactionInfo) > 0) {
            $getTransactionInfo[$index] = $data;
            $getAccountDetails['kar-global-bank']['transaction_history'] = $getTransactionInfo;
        } else {
            $getTransactionInfo = $data;
            $getAccountDetails['kar-global-bank']['transaction_history'][] = $getTransactionInfo;
        }
        $fileOpen = fopen($jsonFileLink, 'w+');
        fwrite($fileOpen, json_encode($getAccountDetails, JSON_PRETTY_PRINT));
        fclose($fileOpen);
        return true;
    }
}
