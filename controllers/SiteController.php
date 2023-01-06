<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\BankDetails;
use app\models\Accounts;
use app\models\AccountTypes;
use app\models\InvestmentAccountTypes;
use app\models\TransactionHistory;
use app\models\TransactionTypes;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    public function beforeAction($action)
    {            
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    /***** BANK WELCOME MESSAGE *****/
    public function actionIndex()
    {
        $response['status'] = "success";
        $response['message'] = "Welcome to KAR GLOBAL BANK!";
        return json_encode($response, true);
    }
    /***** TO GET BANK DETAILS *****/
    public function actionGetBankInfo()
    {
        if(Yii::$app->request->isGet) {
            $getBankDetails = BankDetails::find()->asArray()->one();
            return json_encode($getBankDetails, true);
        } else {
            $response['status'] = "error";
            $response['message'] = "Request method is wrong, Kindly use GET method";
            return json_encode($response, true);
        }
    }
    /***** TO GET ALL ACCOUNT DETAILS *****/
    public function actionGetAccountList()
    {
        if(Yii::$app->request->isGet) {
            $getAccountList = Accounts::find()->select('account_id, account_number, name, email_id, mobile_number, address, balance, is_active, created_at, modified_at')->where(['is_active' => 1])->asArray()->all();
            if(count($getAccountList) > 0 ) {
                $response['status'] = "success";
                $response['results'] = $getAccountList;
            } else {
                $response['status'] = "success";
                $response['message'] = "No Records Found!";
            }
        } else {
            $response['status'] = "error";
            $response['message'] = "Request method is wrong, Kindly use GET method";
        }
        return json_encode($response, true);
    }
    /***** TO GET SPECIFIC ACCOUNT DETAILS *****/
    public function actionGetAccountInfo($id)
    {
        if(Yii::$app->request->isGet) {
            $getAccountInfo = Accounts::find()->select('account_number, name, email_id, mobile_number, address, balance, is_active, created_at, modified_at')->where(['account_number' => $id, 'is_active' => 1])->asArray()->one();
            if($getAccountInfo) {
                $response['status'] = "success";
                $response['result'] = $getAccountInfo;
            } else {
                $response['status'] = "success";
                $response['message'] = "No Records Found!";
            }
        } else {
            $response['status'] = "error";
            $response['message'] = "Request method is wrong, Kindly use GET method";
        }
        return json_encode($response, true);
    }
    /***** TO GET SPECIFIC ACCOUNT TYPE DETAILS *****/
    public function actionGetAccountTypeInfo($id)
    {
        if(Yii::$app->request->isGet) {
            $getAccountInfo = Accounts::find()->alias('accounts')->joinWith(['accounttype','investmenttype'])->where(['accounts.account_number' => $id, 'accounts.is_active' => 1])->asArray()->one();
            if($getAccountInfo) {
                $data['account_number'] = isset($getAccountInfo['account_number']) ? $getAccountInfo['account_number'] : null;
                $data['name'] = isset($getAccountInfo['name']) ? $getAccountInfo['name'] : null;
                $data['balance'] = isset($getAccountInfo['balance']) ? $getAccountInfo['balance'] : null;
                $data['account_type'] = isset($getAccountInfo['accounttype']['account_type_name']) ? $getAccountInfo['accounttype']['account_type_name'] : null;
                $data['investment_type'] = isset($getAccountInfo['investmenttype']['investment_type_name']) ? $getAccountInfo['investmenttype']['investment_type_name'] : null;
                $data['is_active'] = isset($getAccountInfo['is_active']) ? $getAccountInfo['is_active'] : null;
                $data['created_at'] = isset($getAccountInfo['created_at']) ? $getAccountInfo['created_at'] : null;
                $data['modified_at'] = isset($getAccountInfo['modified_at']) ? $getAccountInfo['modified_at'] : null;
                $response['status'] = "success";
                $response['result'] = $data;
            } else {
                $response['status'] = "success";
                $response['message'] = "No Records Found!";
            }
        } else {
            $response['status'] = "error";
            $response['message'] = "Request method is wrong, Kindly use GET method";
        }
        return json_encode($response, true);
    }
    /***** TO CREATE BANK ACCOUNT *****/
    public function actionCreateAccount()
    {
        if(Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            /* Account create data validation */
            $validationResponse = $this->accountValidation($data);
            if($validationResponse["success"]) {
                /* To check email address already present or not */
                $getAccountEmailInfo = Accounts::find()->where(['email_id' => $data['email_id'], 'is_active' => 1])->asArray()->one();
                if(isset($getAccountEmailInfo['email_id'])) {
                    $response["status"] = "error";
                    $response["message"] = "Email address is already exists!";
                    return json_encode($response, true);
                }
                /* To check mobile number already present or not */
                $getAccountMobileInfo = Accounts::find()->where(['mobile_number' => $data['mobile_number'], 'is_active' => 1])->asArray()->one();
                if(isset($getAccountMobileInfo['mobile_number'])) {
                    $response["status"] = "error";
                    $response["message"] = "Mobile number is already exists!";
                    return json_encode($response, true);
                }
                $accountModel = new Accounts;
                $accountNumberPrefix = "50100";
                $generateAccountNumber = $accountNumberPrefix.rand(10000, 99999);
                $accountModel->account_number = $generateAccountNumber;
                /* To get account type id based on account type */
                $getAccountTypeId = AccountTypes::find()->select('id')->where(['account_type_name' => $data['account_type']])->asArray()->one();
                $getInvestmentTypeId = [];
                if($data['account_type'] == "investment") {
                    /* To get investment type id based on investment type */
                    $getInvestmentTypeId = InvestmentAccountTypes::find()->select('id')->where(['investment_type_name' => $data['investment_type']])->asArray()->one();
                }
                $accountModel->name = $data['name'];
                $accountModel->email_id = $data['email_id'];
                $accountModel->mobile_number = $data['mobile_number'];
                $accountModel->address = $data['address'];
                $accountModel->account_type_id = $getAccountTypeId['id'];
                $accountModel->investment_type_id = isset($getInvestmentTypeId['id']) ? $getInvestmentTypeId['id'] : 0;
                $accountModel->created_at = date("Y-m-d H:i:s");
                if($accountModel->save()) { // To create account in the account table
                    $response['status'] = "success";
                    $response['message'] = "New account created successfully.";
                } else {
                    $response['status'] = "error";
                    $response['message'] = "Something went to wrong.";
                }
            } else {
                $response['status'] = "error";
                $response['message'] = $validationResponse["message"];
            }
        } else {
            $response['status'] = "error";
            $response['message'] = "Request method is wrong, Kindly use POST method";
        }
        return json_encode($response, true);
    }
    /***** TO UPDATE BANK ACCOUNT DETAILS *****/
    public function actionUpdateAccount()
    {
        if(Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            /* Account update data validation */
            $validationResponse = $this->accountValidation($data);
            if($validationResponse["success"]) {
                if(!isset($data['account_number'])) {
                    $response["status"] = "error";
                    $response["message"] = "The 'account_number' key is missing, Kindly add 'account_number' key in the POST request data.";
                    return json_encode($response, true);
                } else if(empty($data['account_number'])) {
                    $response["status"] = "error";
                    $response["message"] = "Account number is required!";
                    return json_encode($response, true);
                }
                /* To get account information based on account number */
                $getAccountInfo = Accounts::find()->where(['account_number' => $data['account_number'], 'is_active' => 1])->one();
                if(!isset($getAccountInfo->account_number)) {
                    $response["status"] = "error";
                    $response["message"] = "Account number is invalid!";
                    return json_encode($response, true);
                }
                /* To get account type id based on account type */
                $getAccountTypeId = AccountTypes::find()->select('id')->where(['account_type_name' => $data['account_type']])->asArray()->one();
                $getInvestmentTypeId = [];
                if($data['account_type'] == "investment") {
                    /* To get investment type id based on investment type */
                    $getInvestmentTypeId = InvestmentAccountTypes::find()->select('id')->where(['investment_type_name' => $data['investment_type']])->asArray()->one();
                }
                $getAccountInfo->name = $data['name'];
                $getAccountInfo->email_id = $data['email_id'];
                $getAccountInfo->mobile_number = $data['mobile_number'];
                $getAccountInfo->address = $data['address'];
                $getAccountInfo->account_type_id = $getAccountTypeId['id'];
                $getAccountInfo->investment_type_id = isset($getInvestmentTypeId['id']) ? $getInvestmentTypeId['id'] : 0;
                if($getAccountInfo->save()) { // To update account information in the account table
                    $response['status'] = "success";
                    $response['message'] = "Account details updated successfully.";
                } else {
                    $response['status'] = "error";
                    $response['message'] = "Something went to wrong.";
                }
            } else {
                $response['status'] = "error";
                $response['message'] = $validationResponse["message"];
            }
        } else {
            $response['status'] = "error";
            $response['message'] = "Request method is wrong, Kindly use POST method";
        }
        return json_encode($response, true);
    }
    /***** TO DELETE BANK ACCOUNT *****/
    public function actionDeleteAccount()
    {
        if(Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            if(!isset($data['account_number'])) {
                $response["status"] = "error";
                $response["message"] = "The 'account_number' key is missing, Kindly add 'account_number' key in the POST request data.";
                return json_encode($response, true);
            } else if(empty($data['account_number'])) {
                $response["status"] = "error";
                $response["message"] = "Account number is required!";
                return json_encode($response, true);
            }
            /* To get account information based on account number */
            $getAccountInfo = Accounts::find()->where(['account_number' => $data['account_number'], 'is_active' => 1])->one();
            if(!isset($getAccountInfo->account_number)) {
                $response["status"] = "error";
                $response["message"] = "Account number is invalid!";
                return json_encode($response, true);
            }
            $getAccountInfo->is_active = 0;
            if($getAccountInfo->save()) { // To update account status in the account table
                $response['status'] = "success";
                $response['message'] = "Account deleted successfully.";
            } else {
                $response['status'] = "error";
                $response['message'] = "Something went to wrong.";
            }
        } else {
            $response['status'] = "error";
            $response['message'] = "Request method is wrong, Kindly use POST method";
        }
        return json_encode($response, true);
    }
    /***** TO VALIDATE CREATE ACCOUNT DETAILS *****/
    public function accountValidation($data = [])
    {
        $response["success"] = true;
        $response["message"] = '';
        if(!isset($data['name'])) {
            $response["success"] = false;
            $response["message"] = "The 'name' key is missing, Kindly add 'name' key in the POST request data.";
        } else if(empty($data['name'])) {
            $response["success"] = false;
            $response["message"] = "Account name is required!";
        } else if(!isset($data['email_id'])) {
            $response["success"] = false;
            $response["message"] = "The 'email_id' key is missing, Kindly add 'email_id' key in the POST request data.";
        } else if(empty($data['email_id'])) {
            $response["success"] = false;
            $response["message"] = "Email address is required!";
        } else if(isset($data['email_id']) && (!filter_var($data['email_id'], FILTER_VALIDATE_EMAIL))) {
            $response["success"] = false;
            $response["message"] = "Email address is invalid!";
        } else if(!isset($data['mobile_number'])) {
            $response["success"] = false;
            $response["message"] = "The 'mobile_number' key is missing, Kindly add 'mobile_number' key in the POST request data.";
        } else if(empty($data['mobile_number'])) {
            $response["success"] = false;
            $response["message"] = "Mobile number is required!";
        } else if(isset($data['mobile_number']) && (!preg_match('/^[0-9]{10}+$/', $data['mobile_number']))) {
            $response["success"] = false;
            $response["message"] = "Mobile number is invalid!";
        } else if(!isset($data['account_type'])) {
            $response["success"] = false;
            $response["message"] = "The 'account_type' key is missing, Kindly add 'account_type' key in the POST request data.";
        } else if(empty($data['account_type'])) {
            $response["success"] = false;
            $response["message"] = "Account type is required!";
        } else if(isset($data['account_type']) && ($data['account_type']  != "investment") && ($data['account_type']  != "checking")) {
            $response["success"] = false;
            $response["message"] = "Account type is invalid. the valid types are investment, checking.";
        } else if(isset($data['account_type']) && ($data['account_type'] == "investment") && !isset($data['investment_type'])) {
            $response["success"] = false;
            $response["message"] = "The 'investment_type' key is missing, Kindly add 'investment_type' key in the POST request data.";
        } else if(isset($data['account_type']) && ($data['account_type'] == "investment") && empty($data['investment_type'])) {
            $response["success"] = false;
            $response["message"] = "investment type is required!";
        } else if(isset($data['account_type']) && ($data['account_type'] == "investment") && ($data['investment_type']  != "individual") && ($data['investment_type']  != "corporate")) {
            $response["success"] = false;
            $response["message"] = "investment type is invalid. the valid types are individual, corporate.";
        } else if(!isset($data['address'])) {
            $response["success"] = false;
            $response["message"] = "The 'address' key is missing, Kindly add 'address' key in the POST request data.";
        } else if(empty($data['address'])) {
            $response["success"] = false;
            $response["message"] = "Address is required!";
        }
        return $response;
    }
    /***** TO MAKE BANK TRANSACTIONS *****/
    public function actionMakeTransaction()
    {
        if(Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            /* Transaction data validation */
            $getTransactionValidation = $this->transactionValidation($data);
            if($getTransactionValidation["success"]) {
                /* To get account information based on account number */
                $getAccountInfo = Accounts::find()->alias('accounts')->joinWith(['accounttype','investmenttype'])->where(['accounts.account_number' => $data['account_number'], 'accounts.is_active' => 1])->one();
                if(!isset($getAccountInfo->account_number)) {
                    $response["status"] = "error";
                    $response["message"] = "Account number is invalid!";
                    return json_encode($response, true);
                }
                $accountBalance = isset($getAccountInfo->balance) ? $getAccountInfo->balance : 0;
                $amount = isset($data['amount']) ? $data['amount'] : "";
                $accountType = isset($getAccountInfo->accounttype->account_type_name) ? $getAccountInfo->accounttype->account_type_name : "";
                $investmentAccountType = isset($getAccountInfo->investmenttype->investment_type_name) ? $getAccountInfo->investmenttype->investment_type_name : "";
                $getUrlInfoArray = explode('/', Yii::$app->request->pathInfo);
                $getTransactionMode = isset($getUrlInfoArray['1']) ? $getUrlInfoArray['1'] : "";
                $getTransactionModeDetails = TransactionTypes::find()->where(['type_name' => $getTransactionMode])->one();
                if(!isset($getTransactionModeDetails->id)) {
                    $response["status"] = "error";
                    $response["message"] = "Transaction mode is invalid!";
                    return json_encode($response, true);
                }
                $toAccountId = '';
                if($getTransactionMode == "deposit") {
                    $accountBalance += $amount;
                } else if($getTransactionMode == "withdrawal") {
                    if(($accountType == "investment") && ($investmentAccountType == "individual") && ($amount > 500)) {
                        $response['status'] = "error";
                        $response['message'] = "Individual accounts have a withdrawal limit of 500 dollars. Pleade enter amount 500 or less than 500";
                        return json_encode($response, true);
                    }
                    if($accountBalance < $amount) {
                        $response['status'] = "error";
                        $response['message'] = "Insufficient balance!";
                        return json_encode($response, true);
                    }
                    $accountBalance -= $amount;
                } else {
                    if(!isset($data['to_account_number'])) {
                        $response["status"] = "error";
                        $response["message"] = "The 'to_account_number' key is missing, Kindly add 'to_account_number' key in the POST request data.";
                        return json_encode($response, true);
                    } else if(empty($data['to_account_number'])) {
                        $response["status"] = "error";
                        $response["message"] = "To account number is required!";
                        return json_encode($response, true);
                    }
                    $getToAccountInfo = Accounts::find()->where(['account_number' => $data['to_account_number'], 'is_active' => 1])->one();
                    if(!isset($getToAccountInfo->account_number)) {
                        $response["status"] = "error";
                        $response["message"] = "To account number is invalid!";
                        return json_encode($response, true);
                    } else if($data['to_account_number'] == $data['account_number']) {
                        $response["status"] = "error";
                        $response["message"] = "Amount can't transfer within same account!";
                        return json_encode($response, true);
                    }
                    if($accountBalance < $amount) {
                        $response['status'] = "error";
                        $response['message'] = "Insufficient balance!";
                        return json_encode($response, true);
                    }
                    $toAccountId = isset($getToAccountInfo->account_id) ? $getToAccountInfo->account_id : "";
					$toAccountBalance = isset($getToAccountInfo->balance) ? $getToAccountInfo->balance : "";
                    $accountBalance -= $amount;
					$toAccountBalance += $amount;
                }
                $saveTransactionHistory = new TransactionHistory;
                $saveTransactionHistory->account_id = $getAccountInfo->account_id;
                $saveTransactionHistory->transaction_type_id = $getTransactionModeDetails->id;
                $saveTransactionHistory->transaction_amount = $amount;
                $saveTransactionHistory->to_account_id = $toAccountId;
                if($saveTransactionHistory->save()) { // To save transaction history
                    $getAccountInfo->balance = $accountBalance;
                    $getAccountInfo->save(); // To update balance in the account table
                    if(!empty($toAccountId)) {
                        $getToAccountInfo->balance = $toAccountBalance;
                        $getToAccountInfo->save(); // To update receiver's account balance in the account table
                    }
                    $response['status'] = "success";
                    $response['message'] = "Transaction completed successfully!";
                } else {
                    $response['status'] = "error";
                    $response['message'] = "Something went wrong!";
                    return json_encode($response, true);
                }
            } else {
                $response['status'] = "error";
                $response['message'] = $getTransactionValidation["message"];
            }
        } else {
            $response['status'] = "error";
            $response['message'] = "Request method is wrong, Kindly use POST method";
        }
        return json_encode($response, true);
    }
    /***** TO VALIDATE TRANSACTION DETAILS *****/
    public function transactionValidation($data = [])
    {
        $response["success"] = true;
        $response["message"] = '';
        if(!isset($data['account_number'])) {
            $response["success"] = false;
            $response["message"] = "The 'account_number' key is missing, Kindly add 'account_number' key in the POST request data.";
        } else if(empty($data['account_number'])) {
            $response["success"] = false;
            $response["message"] = "Account number is required!";
        } else if(!isset($data['amount'])) {
            $response["success"] = false;
            $response["message"] = "The 'amount' key is missing, Kindly add 'amount' key in the POST request data.";
        } else if(strlen($data['amount']) == 0) {
            $response["success"] = false;
            $response["message"] = "Amount is required!";
        } else if(!is_numeric($data['amount'])){
            $response['success'] = false;
            $response['message'] = "Invalid amount entered!";
        } else if($data['amount'] <= 0){
            $response['success'] = false;
            $response['message'] = "Amount must be grater than zero!";
        }
        return $response;
    }
    /***** NOT FOUND PAGE *****/
    public function actionNotFound()
    {
        $response['status'] = "error";
        $response['message'] = "Page Not Found!";
        return json_encode($response, true);
    }
}
