<?php

use Codeception\Util\HttpCode;
use Faker\Factory;

class TransferAmountCest
{
    public function _before(ApiTester $I)
    {
    }
    public function ValidRequest(ApiTester $I)
    {
        $faker = Factory::create();
        $I->sendPost(
            'account/transfer',
            [
                'account_number' => '5010013677',
                'amount'       => '50',
                'to_account_number' => '5010038479'
            ]
        );
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $validResponseJsonSchema = json_encode(
            [
                'type' => 'object',
                'required' => [
                    'status',
                    'message',
                ]
            ]
        );
        $I->seeResponseMatchesJsonType(
            [
                'status' => 'string',
                'message' => 'string',
            ]
        );
        $I->seeResponseIsValidOnJsonSchemaString($validResponseJsonSchema);
        $I->seeResponseContainsJson(
            [
                'status' => 'success',
                'message' => 'Transaction completed successfully!'
            ]
        );
    }
    public function InvalidRequsetMethod(ApiTester $I) {
        $faker = Factory::create();
        $I->sendGet(
            'account/transfer',
            [
                'account_number' => '5010013677',
                'amount'       => '50',
                'to_account_number' => '5010038479'
            ]
        );
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $validResponseJsonSchema = json_encode(
            [
                'type' => 'object',
                'required' => [
                    'status',
                    'message',
                ]
            ]
        );
        $I->seeResponseMatchesJsonType(
            [
                'status' => 'string',
                'message' => 'string',
            ]
        );
        $I->seeResponseIsValidOnJsonSchemaString($validResponseJsonSchema);
        $I->seeResponseContainsJson(
            [
                'status' => 'error',
                'message' => 'Request method is wrong, Kindly use POST method'
            ]
        );
    }
    public function InvalidAccountNumber(ApiTester $I) {
        $faker = Factory::create();
        $I->sendPost(
            'account/transfer',
            [
                'account_number' => '50100136777777',
                'amount'       => '500',
                'to_account_number' => '5010038479'
            ]
        );
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $validResponseJsonSchema = json_encode(
            [
                'type' => 'object',
                'required' => [
                    'status',
                    'message',
                ]
            ]
        );
        $I->seeResponseMatchesJsonType(
            [
                'status' => 'string',
                'message' => 'string',
            ]
        );
        $I->seeResponseIsValidOnJsonSchemaString($validResponseJsonSchema);
        $I->seeResponseContainsJson(
            [
                'status' => 'error',
                'message' => 'Account number is invalid!'
            ]
        );
    }
    public function InvalidToAccountNumber(ApiTester $I) {
        $faker = Factory::create();
        $I->sendPost(
            'account/transfer',
            [
                'account_number' => '5010013677',
                'amount'       => '500',
                'to_account_number' => '4545'
            ]
        );
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $validResponseJsonSchema = json_encode(
            [
                'type' => 'object',
                'required' => [
                    'status',
                    'message',
                ]
            ]
        );
        $I->seeResponseMatchesJsonType(
            [
                'status' => 'string',
                'message' => 'string',
            ]
        );
        $I->seeResponseIsValidOnJsonSchemaString($validResponseJsonSchema);
        $I->seeResponseContainsJson(
            [
                'status' => 'error',
                'message' => 'To account number is invalid!'
            ]
        );
    }
    public function AmountMissing(ApiTester $I) {
        $faker = Factory::create();
        $I->sendPost(
            'account/transfer',
            [
                'account_number' => '5010013677',
                'to_account_number' => '5010038479'
            ]
        );
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $validResponseJsonSchema = json_encode(
            [
                'type' => 'object',
                'required' => [
                    'status',
                    'message',
                ]
            ]
        );
        $I->seeResponseMatchesJsonType(
            [
                'status' => 'string',
                'message' => 'string',
            ]
        );
        $I->seeResponseIsValidOnJsonSchemaString($validResponseJsonSchema);
        $I->seeResponseContainsJson(
            [
                'status' => 'error',
                'message' => "The 'amount' key is missing, Kindly add 'amount' key in the POST request data."
            ]
        );
    }
    public function InvalidAmount(ApiTester $I) {
        $faker = Factory::create();
        $I->sendPost(
            'account/transfer',
            [
                'account_number' => '5010013677',
                'amount' => 'wew',
                'to_account_number' => '5010038479'
            ]
        );
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $validResponseJsonSchema = json_encode(
            [
                'type' => 'object',
                'required' => [
                    'status',
                    'message',
                ]
            ]
        );
        $I->seeResponseMatchesJsonType(
            [
                'status' => 'string',
                'message' => 'string',
            ]
        );
        $I->seeResponseIsValidOnJsonSchemaString($validResponseJsonSchema);
        $I->seeResponseContainsJson(
            [
                'status' => 'error',
                'message' => 'Invalid amount entered!'
            ]
        );
    }
    public function AccountNumberRequired(ApiTester $I) {
        $faker = Factory::create();
        $I->sendPost(
            'account/transfer',
            [
                'amount' => '100',
                'to_account_number' => '5010038479'
            ]
        );
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $validResponseJsonSchema = json_encode(
            [
                'type' => 'object',
                'required' => [
                    'status',
                    'message',
                ]
            ]
        );
        $I->seeResponseMatchesJsonType(
            [
                'status' => 'string',
                'message' => 'string',
            ]
        );
        $I->seeResponseIsValidOnJsonSchemaString($validResponseJsonSchema);
        $I->seeResponseContainsJson(
            [
                'status' => 'error',
                'message' => "The 'account_number' key is missing, Kindly add 'account_number' key in the POST request data."
            ]
        );
    }
    public function ToAccountNumberRequired(ApiTester $I) {
        $faker = Factory::create();
        $I->sendPost(
            'account/transfer',
            [
                'account_number' => '5010013677',
                'amount' => '100',
            ]
        );
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $validResponseJsonSchema = json_encode(
            [
                'type' => 'object',
                'required' => [
                    'status',
                    'message',
                ]
            ]
        );
        $I->seeResponseMatchesJsonType(
            [
                'status' => 'string',
                'message' => 'string',
            ]
        );
        $I->seeResponseIsValidOnJsonSchemaString($validResponseJsonSchema);
        $I->seeResponseContainsJson(
            [
                'status' => 'error',
                'message' => "The 'to_account_number' key is missing, Kindly add 'to_account_number' key in the POST request data."
            ]
        );
    }
}
