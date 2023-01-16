<?php

namespace app\models;

use Yii;
use yii\base\Model;

class Accounts extends Model
{
    public static function findByEmailid($emailId)
    {
        $jsonFileLink = Yii::getAlias('@app/store/data.json');
        $getData = file_get_contents($jsonFileLink);
        $getAccountDetails = json_decode($getData, true);
        $getAccountInfo = isset($getAccountDetails['kar-global-bank']['accounts']) ? $getAccountDetails['kar-global-bank']['accounts'] : [];
        if(is_array($getAccountInfo)) {
            foreach($getAccountInfo as $key => $value) {
                if($value['email_id'] == $emailId && $value['is_active'] == 1) {
                    return true;
                }
            }
        }
        return false;
    }
    public static function findByMobileNumber($mobileNumber)
    {
        $jsonFileLink = Yii::getAlias('@app/store/data.json');
        $getData = file_get_contents($jsonFileLink);
        $getAccountDetails = json_decode($getData, true);
        $getAccountInfo = isset($getAccountDetails['kar-global-bank']['accounts']) ? $getAccountDetails['kar-global-bank']['accounts'] : [];
        if(is_array($getAccountInfo)) {
            foreach($getAccountInfo as $key => $value) {
                if($value['mobile_number'] == $mobileNumber && $value['is_active'] == 1) {
                    return true;
                }
            }
        }
        return false;
    }
    public static function findByAccountNumber($accountNumber)
    {
        $jsonFileLink = Yii::getAlias('@app/store/data.json');
        $getData = file_get_contents($jsonFileLink);
        $getAccountDetails = json_decode($getData, true);
        $getAccountInfo = isset($getAccountDetails['kar-global-bank']['accounts']) ? $getAccountDetails['kar-global-bank']['accounts'] : [];
        if(is_array($getAccountInfo)) {
            foreach($getAccountInfo as $key => $value) {
                if($value['account_number'] == $accountNumber && $value['is_active'] == 1) {
                    return true;
                }
            }
        }
        return false;
    }
    public static function save($data, $mode)
    {
        $jsonFileLink = Yii::getAlias('@app/store/data.json');
        $getData = file_get_contents($jsonFileLink);
        $getAccountDetails = json_decode($getData, true);
        $getAccountInfo = isset($getAccountDetails['kar-global-bank']['accounts']) ? $getAccountDetails['kar-global-bank']['accounts'] : [];
        if($mode == 'update') {
            $getIndex = 0;
            $getRowCount = 0;
            $getCreatedDate = '';
            $getBalance = 0;
            if(is_array($getAccountInfo)) {
                foreach($getAccountInfo as $key => $value) {
                    if($value['account_number'] == $data['account_number'] && $value['is_active'] == 1) {
                        $getIndex = $key;
                        $getRowCount = $value['id'];
                        $getCreatedDate = $value['created_at'];
                        $getBalance = $value['balance'];
                    }
                }
            }
            unset($getAccountInfo[$getIndex]);
            $index = $getIndex;
            $data['id'] = $getRowCount;
            $data['created_at'] = $getCreatedDate;
            $data['balance'] = $getBalance;
        } else {
            $index = count($getAccountInfo);
            $data['id'] = count($getAccountInfo) + 1;
        }
        $data['is_active'] = '1';
        if(count($getAccountInfo) > 0) {
            $getAccountInfo[$index] = $data;
            $getAccountDetails['kar-global-bank']['accounts'] = $getAccountInfo;
        } else {
            $getAccountInfo = $data;
            $getAccountDetails['kar-global-bank']['accounts'][] = $getAccountInfo;
        }
        sort($getAccountDetails['kar-global-bank']['accounts']);
        $fileOpen = fopen($jsonFileLink, 'w+');
        fwrite($fileOpen, json_encode($getAccountDetails, JSON_PRETTY_PRINT));
        fclose($fileOpen);
        return true;
    }
    public static function remove($accountNumber)
    {
        $jsonFileLink = Yii::getAlias('@app/store/data.json');
        $getData = file_get_contents($jsonFileLink);
        $getAccountDetails = json_decode($getData, true);
        $getAccountInfo = isset($getAccountDetails['kar-global-bank']['accounts']) ? $getAccountDetails['kar-global-bank']['accounts'] : [];
        $getIndex = array_search($accountNumber, array_column($getAccountInfo, 'account_number'));
        if(isset($getAccountDetails['kar-global-bank']['accounts'][$getIndex]['is_active'])) {
            $getAccountDetails['kar-global-bank']['accounts'][$getIndex]['is_active'] = '0';
        }
        sort($getAccountDetails['kar-global-bank']['accounts']);
        $fileOpen = fopen($jsonFileLink, 'w+');
        fwrite($fileOpen, json_encode($getAccountDetails, JSON_PRETTY_PRINT));
        fclose($fileOpen);
        return true;
    }
    public static function getAccountList()
    {
        $jsonFileLink = Yii::getAlias('@app/store/data.json');
        $getData = file_get_contents($jsonFileLink);
        $getAccountDetails = json_decode($getData, true);
        $getAccountAllList = isset($getAccountDetails['kar-global-bank']['accounts']) ? $getAccountDetails['kar-global-bank']['accounts'] : [];
        $getActiveList = [];
        if(count($getAccountAllList) > 0) {
            foreach($getAccountAllList as $key => $value) {
                if(isset($value['is_active']) && ($value['is_active'] == '1')) {
                    $getActiveList[] = $value;
                }
            }
        }
        return $getActiveList;
    }
    public static function getAccountInfo($accountNumber)
    {
        $jsonFileLink = Yii::getAlias('@app/store/data.json');
        $getData = file_get_contents($jsonFileLink);
        $getAccountDetails = json_decode($getData, true);
        $getAccountAllList = isset($getAccountDetails['kar-global-bank']['accounts']) ? $getAccountDetails['kar-global-bank']['accounts'] : [];
        $getActiveAccount = [];
        if(count($getAccountAllList) > 0) {
            foreach($getAccountAllList as $key => $value) {
                if(isset($value['is_active']) && ($value['is_active'] == '1') && isset($value['account_number']) && ($value['account_number'] == $accountNumber)) {
                    $getActiveAccount = $value;
                }
            }
        }
        return $getActiveAccount;
    }
    public static function getAccountTypeInfo($accountNumber)
    {
        $jsonFileLink = Yii::getAlias('@app/store/data.json');
        $getData = file_get_contents($jsonFileLink);
        $getAccountDetails = json_decode($getData, true);
        $getAccountAllList = isset($getAccountDetails['kar-global-bank']['accounts']) ? $getAccountDetails['kar-global-bank']['accounts'] : [];
        $getActiveAccount = [];
        $accountTypeId = 0;
        $investmentTypeId = 0;
        if(count($getAccountAllList) > 0) {
            foreach($getAccountAllList as $key => $value) {
                if(isset($value['is_active']) && ($value['is_active'] == '1') && isset($value['account_number']) && ($value['account_number'] == $accountNumber)) {
                    $getActiveAccount = $value;
                    $accountTypeId = $value['account_type_id'];
                    $investmentTypeId = $value['investment_type_id'];
                }
            }
        }
        if(!isset($getActiveAccount['account_number'])) {
            return [];
        }
        $getAccountTypeList = isset($getAccountDetails['kar-global-bank']['account_types']) ? $getAccountDetails['kar-global-bank']['account_types'] : [];
        $getAccountTypeIndex = array_search($accountTypeId, array_column($getAccountTypeList, 'id'));
        $getAccountTypeData = isset($getAccountTypeList[$getAccountTypeIndex]) ? $getAccountTypeList[$getAccountTypeIndex] : [];
        $getInvestmentTypeList = isset($getAccountDetails['kar-global-bank']['investment_account_types']) ? $getAccountDetails['kar-global-bank']['investment_account_types'] : [];
        $getInvestmentTypeIndex = array_search($investmentTypeId, array_column($getInvestmentTypeList, 'id'));
        $getInvestmentTypeData = isset($getInvestmentTypeList[$getInvestmentTypeIndex]) ? $getInvestmentTypeList[$getInvestmentTypeIndex] : [];
        $getActiveAccount['accounttype'] = $getAccountTypeData;
        $getActiveAccount['investmenttype'] = $getInvestmentTypeData;
        return $getActiveAccount;
    }
    public static function updateBalance($balance, $accountNumber)
    {
        $jsonFileLink = Yii::getAlias('@app/store/data.json');
        $getData = file_get_contents($jsonFileLink);
        $getAccountDetails = json_decode($getData, true);
        $getAccountAllList = isset($getAccountDetails['kar-global-bank']['accounts']) ? $getAccountDetails['kar-global-bank']['accounts'] : [];
        $getActiveAccount = [];
        $getIndex = '';
        if(count($getAccountAllList) > 0) {
            foreach($getAccountAllList as $key => $value) {
                if(isset($value['is_active']) && ($value['is_active'] == '1') && isset($value['account_number']) && ($value['account_number'] == $accountNumber)) {
                    $getIndex = $key;
                }
            }
        }
        if(isset($getAccountDetails['kar-global-bank']['accounts'][$getIndex]['balance'])) {
            $getAccountDetails['kar-global-bank']['accounts'][$getIndex]['balance'] = $balance;
        }
        $fileOpen = fopen($jsonFileLink, 'w+');
        fwrite($fileOpen, json_encode($getAccountDetails, JSON_PRETTY_PRINT));
        fclose($fileOpen);
        return true;
    }
}
