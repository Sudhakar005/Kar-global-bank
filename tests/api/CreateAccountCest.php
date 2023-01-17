<?php

use Codeception\Util\HttpCode;
use Faker\Factory;

class CreateAccountCest
{
    public function _before(ApiTester $I)
    {
    }
    public function ValidRequest(ApiTester $I)
    {
        $faker = Factory::create();
        $I->sendPost(
            'account/create',
            [
                'name'       => $faker->name,
                'email_id' => $faker->email,
                'mobile_number' => str_replace('+1', '', $faker->unique()->e164PhoneNumber()),
                'account_type' => 'investment',
                'investment_type' => 'corporate',
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
                'message' => 'New account created successfully.'
            ]
        );
    }
    public function InvalidRequsetMethod(ApiTester $I) {
        $faker = Factory::create();
        $I->sendGet(
            'account/create',
            [
                'name'       => $faker->name,
                'email_id' => $faker->email,
                'mobile_number' => str_replace('+1', '', $faker->unique()->e164PhoneNumber()),
                'account_type' => 'investment',
                'investment_type' => 'corporate',
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
            'account/create',
            [
                'name'       => $faker->name,
                'email_id' => $faker->email,
                'mobile_number' => str_replace('+1', '', $faker->unique()->e164PhoneNumber()),
                'investment_type' => 'corporate',
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
            'account/create',
            [
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
            'account/create',
            [
                'name'       => $faker->name,
                'email_id' => $faker->email,
                'mobile_number' => str_replace('+1', '', $faker->unique()->e164PhoneNumber()),
                'account_type' => 'investmentss',
                'investment_type' => 'corporate',
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
    public function InvalidInvestmentType(ApiTester $I) {
        $faker = Factory::create();
        $I->sendPost(
            'account/create',
            [
                'name'       => $faker->name,
                'email_id' => $faker->email,
                'mobile_number' => str_replace('+1', '', $faker->unique()->e164PhoneNumber()),
                'account_type' => 'investment',
                'investment_type' => 'corporates',
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
                'message' => 'investment type is invalid. the valid types are individual, corporate.'
            ]
        );
    }
    public function EmailIdRequired(ApiTester $I) {
        $faker = Factory::create();
        $I->sendPost(
            'account/create',
            [
                'name'       => $faker->name,
                'email_id' => '',
                'mobile_number' => str_replace('+1', '', $faker->unique()->e164PhoneNumber()),
                'account_type' => 'investment',
                'investment_type' => 'corporate',
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
    public function MobileNumberdRequired(ApiTester $I) {
        $faker = Factory::create();
        $I->sendPost(
            'account/create',
            [
                'name'       => $faker->name,
                'email_id' => $faker->email,
                'mobile_number' => '',
                'account_type' => 'investment',
                'investment_type' => 'corporate',
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
                'message' => 'Mobile number is required!'
            ]
        );
    }
    public function InvalidEmailId(ApiTester $I) {
        $faker = Factory::create();
        $I->sendPost(
            'account/create',
            [
                'name'       => $faker->name,
                'email_id' => 'test',
                'mobile_number' => str_replace('+1', '', $faker->unique()->e164PhoneNumber()),
                'account_type' => 'investment',
                'investment_type' => 'corporate',
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
                'message' => 'Email address is invalid!'
            ]
        );
    }
    public function InvalidMobileNumberd(ApiTester $I) {
        $faker = Factory::create();
        $I->sendPost(
            'account/create',
            [
                'name'       => $faker->name,
                'email_id' => $faker->email,
                'mobile_number' => '2323',
                'account_type' => 'investment',
                'investment_type' => 'corporate',
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
    public function EmailIdExists(ApiTester $I) {
        $faker = Factory::create();
        $emailId = $faker->email;
        $I->sendPost(
            'account/create',
            [
                'name'       => $faker->name,
                'email_id' => $emailId,
                'mobile_number' => str_replace('+1', '', $faker->unique()->e164PhoneNumber()),
                'account_type' => 'investment',
                'investment_type' => 'corporate',
                'address' => $faker->address
            ]
        );
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        
        $I->sendPost(
            'account/create',
            [
                'name'       => $faker->name,
                'email_id' => $emailId,
                'mobile_number' => str_replace('+1', '', $faker->unique()->e164PhoneNumber()),
                'account_type' => 'investment',
                'investment_type' => 'corporate',
                'address' => $faker->address
            ]
        );
        $I->seeResponseContainsJson(
            [
                'status' => 'error',
                'message' => 'Email address is already exists!'
            ]
        );
    }
    public function MobileNumberExists(ApiTester $I) {
        $faker = Factory::create();
        $mobileNumber = str_replace('+1', '', $faker->unique()->e164PhoneNumber());
        $I->sendPost(
            'account/create',
            [
                'name'       => $faker->name,
                'email_id' => $faker->email,
                'mobile_number' => $mobileNumber,
                'account_type' => 'investment',
                'investment_type' => 'corporate',
                'address' => $faker->address
            ]
        );
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        
        $I->sendPost(
            'account/create',
            [
                'name'       => $faker->name,
                'email_id' => $faker->email,
                'mobile_number' => $mobileNumber,
                'account_type' => 'investment',
                'investment_type' => 'corporate',
                'address' => $faker->address
            ]
        );
        $I->seeResponseContainsJson(
            [
                'status' => 'error',
                'message' => 'Mobile number is already exists!'
            ]
        );
    }
}
