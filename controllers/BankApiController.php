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

class BankApiController extends Controller
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
            $getBankDetails = BankDetails::find();
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
            $getAccountList = Accounts::getAccountList();
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
            $getAccountInfo = Accounts::getAccountInfo($id);
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
            $getAccountInfo = Accounts::getAccountTypeInfo($id);
            if($getAccountInfo) {
                $data['account_number'] = isset($getAccountInfo['account_number']) ? $getAccountInfo['account_number'] : null;
                $data['name'] = isset($getAccountInfo['name']) ? $getAccountInfo['name'] : null;
                $data['balance'] = isset($getAccountInfo['balance']) ? $getAccountInfo['balance'] : null;
                $data['account_type'] = isset($getAccountInfo['accounttype']['account_type_name']) ? $getAccountInfo['accounttype']['account_type_name'] : null;
                $data['investment_type'] = isset($getAccountInfo['investmenttype']['investment_type_name']) ? $getAccountInfo['investmenttype']['investment_type_name'] : null;
                $data['is_active'] = isset($getAccountInfo['is_active']) ? $getAccountInfo['is_active'] : null;
                $data['created_at'] = isset($getAccountInfo['created_at']) ? $getAccountInfo['created_at'] : null;
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
                $checkEmailExist = Accounts::findByEmailid($data['email_id']);
                if($checkEmailExist) {
                    $response["status"] = "error";
                    $response["message"] = "Email address is already exists!";
                    return json_encode($response, true);
                }
                /* To check mobile number already present or not */
                $checkNumberExist = Accounts::findByMobileNumber($data['mobile_number']);
                if($checkNumberExist) {
                    $response["status"] = "error";
                    $response["message"] = "Mobile number is already exists!";
                    return json_encode($response, true);
                }
                $accountModel = [];
                $accountNumberPrefix = "50100";
                $generateAccountNumber = $accountNumberPrefix.rand(10000, 99999);
                $accountModel['account_number'] = $generateAccountNumber;
                /* To get account type id based on account type */
                $getAccountTypeId = AccountTypes::findByType($data['account_type']);
                $getInvestmentTypeId = [];
                if($data['account_type'] == "investment") {
                    /* To get investment type id based on investment type */
                    $getInvestmentTypeId = InvestmentAccountTypes::findByType($data['investment_type']);
                }
                $accountModel['name'] = $data['name'];
                $accountModel['email_id'] = $data['email_id'];
                $accountModel['mobile_number'] = $data['mobile_number'];
                $accountModel['address'] = $data['address'];
                $accountModel['account_type_id'] = $getAccountTypeId;
                $accountModel['balance'] = 0;
                $accountModel['investment_type_id'] = $getInvestmentTypeId;
                $accountModel['created_at'] = date("Y-m-d H:i:s");
                if(Accounts::save($accountModel, 'create')) { // To create account in the account table
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
                $checkAccountExists = Accounts::findByAccountNumber($data['account_number']);
                if(!$checkAccountExists) {
                    $response["status"] = "error";
                    $response["message"] = "Account number is invalid!";
                    return json_encode($response, true);
                }
                /* To get account type id based on account type */
                $getAccountTypeId = AccountTypes::findByType($data['account_type']);
                $getInvestmentTypeId = [];
                if($data['account_type'] == "investment") {
                    /* To get investment type id based on investment type */
                    $getInvestmentTypeId = InvestmentAccountTypes::findByType($data['investment_type']);
                }
                $updateAccountInfo = [];
                $updateAccountInfo['account_number'] = $data['account_number'];
                $updateAccountInfo['name'] = $data['name'];
                $updateAccountInfo['email_id'] = $data['email_id'];
                $updateAccountInfo['mobile_number'] = $data['mobile_number'];
                $updateAccountInfo['address'] = $data['address'];
                $updateAccountInfo['account_type_id'] = $getAccountTypeId;
                $updateAccountInfo['investment_type_id'] = $getInvestmentTypeId;
                if(Accounts::save($updateAccountInfo, 'update')) { // To update account information in the account table
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
            $checkAccountExists = Accounts::findByAccountNumber($data['account_number']);
            if(!$checkAccountExists) {
                $response["status"] = "error";
                $response["message"] = "Account number is invalid!";
                return json_encode($response, true);
            }
            if(Accounts::remove($data['account_number'])) { // To update account status in the account table
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
                $getAccountInfo = Accounts::getAccountTypeInfo($data['account_number']);
                if(!isset($getAccountInfo['account_number'])) {
                    $response["status"] = "error";
                    $response["message"] = "Account number is invalid!";
                    return json_encode($response, true);
                }
                $accountBalance = isset($getAccountInfo['balance']) ? $getAccountInfo['balance'] : 0;
                $amount = isset($data['amount']) ? $data['amount'] : "";
                $accountType = isset($getAccountInfo['accounttype']['account_type_name']) ? $getAccountInfo['accounttype']['account_type_name'] : "";
                $investmentAccountType = isset($getAccountInfo['investmenttype']['investment_type_name']) ? $getAccountInfo['investmenttype']['investment_type_name'] : "";
                $getUrlInfoArray = explode('/', Yii::$app->request->pathInfo);
                $getTransactionMode = isset($getUrlInfoArray['1']) ? $getUrlInfoArray['1'] : "";
                $getTransactionModeId = TransactionTypes::findByType($getTransactionMode);
                if($getTransactionModeId == '0') {
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
                    $getToAccountInfo = Accounts::getAccountTypeInfo($data['to_account_number']);
                    if(!isset($getToAccountInfo['account_number'])) {
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
                    $toAccountId = isset($getToAccountInfo['id']) ? $getToAccountInfo['id'] : "";
					$toAccountBalance = isset($getToAccountInfo['balance']) ? $getToAccountInfo['balance'] : "";
                    $accountBalance -= $amount;
					$toAccountBalance += $amount;
                }
                $saveTransactionHistory = [];
                $saveTransactionHistory['account_id'] = $getAccountInfo['id'];
                $saveTransactionHistory['transaction_type_id'] = $getTransactionModeId;
                $saveTransactionHistory['transaction_amount'] = $amount;
                $saveTransactionHistory['to_account_id'] = $toAccountId;
                $saveTransactionHistory['created_at'] = date("Y-m-d H:i:s");
                if(TransactionHistory::save($saveTransactionHistory)) { // To save transaction history
                    Accounts::updateBalance($accountBalance, $data['account_number']); // To update balance in the account table
                    if(!empty($toAccountId)) {
                        Accounts::updateBalance($toAccountBalance, $data['to_account_number']); // To update receiver's account balance in the account table
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
