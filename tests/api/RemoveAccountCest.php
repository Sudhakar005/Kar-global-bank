<?php

use Codeception\Util\HttpCode;
use Faker\Factory;

class RemoveAccountCest
{
    public function _before(ApiTester $I)
    {
    }
    public function InvalidAccountNumber(ApiTester $I)
    {
        $faker = Factory::create();
        $I->sendPost(
            'account/remove',
            [
                'account_number' => '33222',
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
    public function InvalidRequestMethod(ApiTester $I)
    {
        $faker = Factory::create();
        $I->sendGet(
            'account/remove',
            [
                'account_number' => '5010093618',
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
}
