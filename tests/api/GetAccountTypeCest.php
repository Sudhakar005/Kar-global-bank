<?php

use Codeception\Util\HttpCode;

class GetAccountTypeCest
{
    public function _before(ApiTester $I)
    {
    }
    public function validRequest(ApiTester $I) {
        $I->sendGet('/account/checking/5010050933');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $validResponseJsonSchema = json_encode(
            [
                'type' => 'object',
                'required' => [
                    'status',
                    'result',
                ]
            ]
        );
        $I->seeResponseMatchesJsonType(
            [
                'status' => 'string',
                'result' => 'array',
            ]
        );
        $I->seeResponseIsValidOnJsonSchemaString($validResponseJsonSchema);
        $I->seeResponseContainsJson(
            [
                'status' => 'success',
                'result' => [
                    'account_number' => '5010050933',
                    'is_active' => '1'
                ]
            ]
        );
    }
    public function InvalidRequsetMethod(ApiTester $I) {
        $I->sendPost('/account/checking/5010050933');
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
                'message' => 'Request method is wrong, Kindly use GET method'
            ]
        );
    }
    public function InvalidAccountNumber(ApiTester $I) {
        $I->sendGet('/account/checking/50100509232323');
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
                'message' => 'No Records Found!'
            ]
        );
    }
}
