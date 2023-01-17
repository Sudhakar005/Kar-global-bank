<?php

use Codeception\Util\HttpCode;
use Faker\Factory;

class DepositAmountCest
{
    public function _before(ApiTester $I)
    {
    }
    public function ValidRequest(ApiTester $I)
    {
        $faker = Factory::create();
        $I->sendPost(
            'account/deposit',
            [
                'account_number' => '5010013677',
                'amount'       => '500',
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
            'account/deposit',
            [
                'account_number' => '5010013677',
                'amount'       => '500',
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
            'account/deposit',
            [
                'account_number' => '50100136777777',
                'amount'       => '500',
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
    public function AmountMissing(ApiTester $I) {
        $faker = Factory::create();
        $I->sendPost(
            'account/deposit',
            [
                'account_number' => '5010013677',
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
            'account/deposit',
            [
                'account_number' => '5010013677',
                'amount' => 'wew'
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
}
