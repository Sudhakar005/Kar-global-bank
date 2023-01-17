<?php

use Codeception\Util\HttpCode;
use Faker\Factory;

class UpdateAccountCest
{
    public function _before(ApiTester $I)
    {
    }
    public function ValidRequest(ApiTester $I)
    {
        $faker = Factory::create();
        $I->sendPost(
            'account/modify',
            [
                'account_number' => '5010050933',
                'name'       => $faker->name,
                'email_id' => $faker->email,
                'mobile_number' => str_replace('+1', '', $faker->unique()->e164PhoneNumber()),
                'account_type' => 'investment',
                'investment_type' => 'individual',
                'address' => $faker->address
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
                'message' => 'Account details updated successfully.'
            ]
        );
    }
    public function InvalidRequsetMethod(ApiTester $I) {
        $faker = Factory::create();
        $I->sendGet(
            'account/modify',
            [
                'account_number' => '5010050933',
                'name'       => $faker->name,
                'email_id' => $faker->email,
                'mobile_number' => str_replace('+1', '', $faker->unique()->e164PhoneNumber()),
                'account_type' => 'investment',
                'investment_type' => 'individual',
                'address' => $faker->address
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
    public function AccountTypeMissingRequest(ApiTester $I) {
        $faker = Factory::create();
        $I->sendPost(
            'account/modify',
            [
                'account_number' => '5010050933',
                'name'       => $faker->name,
                'email_id' => $faker->email,
                'mobile_number' => str_replace('+1', '', $faker->unique()->e164PhoneNumber()),
                'investment_type' => 'individual',
                'address' => $faker->address
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
                'message' => "The 'account_type' key is missing, Kindly add 'account_type' key in the POST request data."
            ]
        );
    }
    public function InvestmentTypeMissingRequest(ApiTester $I) {
        $faker = Factory::create();
        $I->sendPost(
            'account/modify',
            [
                'account_number' => '5010050933',
                'name'       => $faker->name,
                'email_id' => $faker->email,
                'mobile_number' => str_replace('+1', '', $faker->unique()->e164PhoneNumber()),
                'account_type' => 'investment',
                'address' => $faker->address
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
                'message' => "The 'investment_type' key is missing, Kindly add 'investment_type' key in the POST request data."
            ]
        );
    }
    public function InvalidAccountType(ApiTester $I) {
        $faker = Factory::create();
        $I->sendPost(
            'account/modify',
            [
                'account_number' => '5010050933',
                'name'       => $faker->name,
                'email_id' => $faker->email,
                'mobile_number' => str_replace('+1', '', $faker->unique()->e164PhoneNumber()),
                'account_type' => 'investments',
                'investment_type' => 'individual',
                'address' => $faker->address
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
                'message' => 'Account type is invalid. the valid types are investment, checking.'
            ]
        );
    }
    public function EmailIdRequired(ApiTester $I) {
        $faker = Factory::create();
        $I->sendPost(
            'account/modify',
            [
                'account_number' => '5010050933',
                'name'       => $faker->name,
                'email_id' => '',
                'mobile_number' => str_replace('+1', '', $faker->unique()->e164PhoneNumber()),
                'account_type' => 'investments',
                'investment_type' => 'individual',
                'address' => $faker->address
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
                'message' => 'Email address is required!'
            ]
        );
    }
    public function InvalidMobileNumberd(ApiTester $I) {
        $faker = Factory::create();
        $I->sendPost(
            'account/modify',
            [
                'account_number' => '5010050933',
                'name'       => $faker->name,
                'email_id' => $faker->email,
                'mobile_number' => '3434',
                'account_type' => 'investment',
                'investment_type' => 'individual',
                'address' => $faker->address
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
                'message' => 'Mobile number is invalid!'
            ]
        );
    }
    public function InvalidAccountNumber(ApiTester $I) {
        $faker = Factory::create();
        $I->sendPost(
            'account/modify',
            [
                'account_number' => '5010050933333',
                'name'       => $faker->name,
                'email_id' => $faker->email,
                'mobile_number' => str_replace('+1', '', $faker->unique()->e164PhoneNumber()),
                'account_type' => 'investment',
                'investment_type' => 'individual',
                'address' => $faker->address
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
}
