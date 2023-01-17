<?php

use Codeception\Util\HttpCode;

class GetBankDetailsCest
{
    public function _before(ApiTester $I)
    {
    }
    public function validRequest(ApiTester $I) {
        $I->sendGet('/info');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $validResponseJsonSchema = json_encode(
            [
                'type' => 'object',
                'required' => [
                    'id',
                    'name',
                    'ifsc_code',
                    'branch',
                    'state',
                    'country'
                ]
            ]
        );
        $I->seeResponseMatchesJsonType(
            [
                'id' => 'integer',
                'name' => 'string',
                'ifsc_code' => 'string',
                'branch' => 'string',
                'state' => 'string',
                'country' => 'string'
            ]
        );
        $I->seeResponseIsValidOnJsonSchemaString($validResponseJsonSchema);
    }
    public function InvalidRequsetMethod(ApiTester $I) {
        $I->sendPost('/info');
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
    }
} 
