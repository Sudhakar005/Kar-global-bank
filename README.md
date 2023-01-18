<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">KAR GLOBAL CODE CHALLANGE</h1>
    <br>
</p>

I have used the Yii 2 framework to complete this code challange.

DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 7.4 (OR) install XAMPP application.

XAMPP DOWNLOAD LINK:
~~~
https://www.apachefriends.org/download.html
~~~

Postman - To test the API

SITE URL
--------

You can then access the application through the following URL: 

~~~
http://localhost/kar-global-bank/
~~~

STEPS TO SETUP
--------------

- Download the code from GitHub
- Extract the downloaded file
- If using XAMPP, Copy the extracted folder and paste it into the XAMPP folder(i.e.inside htdocs)
- XAMPP folder Location: xampp\htdocs\<project folder>(ex: xampp\htdocs\kar-global-bank)
- If PHP installed directly, Copy the extracted folder and paste it into specific folder
- To check site is working or not using the site URL: http://localhost/kar-global-bank/

POSTMAN SETUP
-------------

- Install postman application
- I have shared the postman collection. Collection name is 'KAR GLOBAL BANK.postman_collection'
- Import the collection and start to test the all api's through postman.

DATA STORE
----------

- I have used the JSON file to store the all data's
- The JSON file used to get, save the data
- JSON file name is 'data.json'
- File Location: /store/data.json

LIST OF API's
-------------

### GET API's

1) http://localhost/kar-global-bank/info - To get bank information
2) http://localhost/kar-global-bank/account - To get all active accounts\
3) http://localhost/kar-global-bank/account/<account number> - To get specific account information based on account number
4) http://localhost/kar-global-bank/account/checking/<account number> - To get specific account type information based on the account number

### POST API's

1) http://localhost/kar-global-bank/account/create - To create bank account
2) http://localhost/kar-global-bank/account/modify - To update account information based on the account number
3) http://localhost/kar-global-bank/account/remove - To delete account information based on the account number
4) http://localhost/kar-global-bank/account/deposit - To deposit amount to spicific account based on the account number
5) http://localhost/kar-global-bank/account/withdrawal - To withdraw amount from the specific account based on the account number
6) http://localhost/kar-global-bank/account/transfer - To transfer amount from one account to another account


API ENDPOINT DETAILS
--------------------

### 1) SHOW BANK INFO

URL: http://localhost/kar-global-bank/info
METHOD: GET
OUTPUT:
~~~
{
    "id": 1,
    "name": "Kar Global",
    "ifsc_code": "KG1010GK",
    "branch": "Chennai",
    "state": "Tamilnadu",
    "country": "India"
}
~~~

### 2) SHOW ALL ACCOUNTS

URL: http://localhost/kar-global-bank/account
METHOD: GET
OUTPUT:
~~~
{
    "status": "success",
    "results": [
        {
            "account_number": "5010013677",
            "name": "Greg",
            "email_id": "mithu@gmail.com",
            "mobile_number": "2323232323",
            "address": "chennaii",
            "balance": 450,
            "account_type_id": "2",
            "investment_type_id": "1",
            "id": 1,
            "is_active": "1",
            "created_at": "2023-01-13 11:09:50"
        },
        {
            "account_number": "5010038479",
            "name": "Mrs. Florine Harvey IV",
            "email_id": "geoffrey.corkery@roberts.org",
            "mobile_number": "5399509684",
            "address": "66192 Rutherford Lodge\nCartwrightburgh, NJ 78665",
            "account_type_id": "2",
            "balance": 50,
            "investment_type_id": "2",
            "created_at": "2023-01-17 17:58:14",
            "id": 5,
            "is_active": "1"
        }
    ]
}
~~~

### 3) SHOW SPECIFIC ACCOUNT INFO

URL: http://localhost/kar-global-bank/account/5010038479
METHOD: GET
OUTPUT:
~~~
{
    "status": "success",
    "result": {
        "account_number": "5010038479",
        "name": "Mrs. Florine Harvey IV",
        "email_id": "geoffrey.corkery@roberts.org",
        "mobile_number": "5399509684",
        "address": "66192 Rutherford Lodge\nCartwrightburgh, NJ 78665",
        "account_type_id": "2",
        "balance": 50,
        "investment_type_id": "2",
        "created_at": "2023-01-17 17:58:14",
        "id": 5,
        "is_active": "1"
    }
}
~~~

### 4) SHOW SPECIFIC ACCOUNT TYPE

URL: http://localhost/kar-global-bank/account/checking/5010038479
METHOD: GET
OUTPUT:
~~~
{
    "status": "success",
    "result": {
        "account_number": "5010038479",
        "name": "Mrs. Florine Harvey IV",
        "balance": 50,
        "account_type": "investment",
        "investment_type": "corporate",
        "is_active": "1",
        "created_at": "2023-01-17 17:58:14"
    }
}
~~~

### 5) CREATE NEW ACCOUNT

URL: http://localhost/kar-global-bank/account/create
METHOD: POST
INPUT:
~~~
--form 'name="Adam Mathew"' \
--form 'email_id="adams@gmail.com"' \
--form 'mobile_number="3434343434"' \
--form 'account_type="checking"' \
--form 'address="chennai"'
~~~
OUTPUT:
~~~
{
    "status": "success",
    "message": "New account created successfully."
}
~~~

### 6) UPDATE ACCOUNT INFORMATION

URL: http://localhost/kar-global-bank/account/modify
METHOD: POST
INPUT:
~~~
--form 'name="Mithu"' \
--form 'email_id="mithu@gmail.com"' \
--form 'mobile_number="2323232323"' \
--form 'account_type="investment"' \
--form 'investment_type="individual"' \
--form 'address="chennaii"' \
--form 'account_number="5010050933"'
~~~
OUTPUT:
~~~
{
    "status": "success",
    "message": "Account details updated successfully."
}
~~~

### 7) REMOVE ACCOUNT

URL: http://localhost/kar-global-bank/account/remove
METHOD: POST
INPUT:
~~~
--form 'account_number="5010093618"'
~~~
OUTPUT:
~~~
{
    "status": "success",
    "message": "Account deleted successfully."
}
~~~

### 8) DEPOSIT AMOUNT INTO AN ACCOUNT

URL: http://localhost/kar-global-bank/account/deposit
METHOD: POST
INPUT:
~~~
--form 'account_number="5010050933"' \
--form 'amount="1500"'
~~~
OUTPUT:
~~~
{
    "status": "success",
    "message": "Transaction completed successfully!"
}
~~~

### 9) WITHDRAW AMOUNT FROM ACCOUNT

URL: http://localhost/kar-global-bank/account/withdrawal
METHOD: POST
INPUT:
~~~
--form 'account_number="5010050933"' \
--form 'amount="100"'
~~~
OUTPUT:
~~~
{
    "status": "success",
    "message": "Transaction completed successfully!"
}
~~~

### 10) TRANSFER AMOUNT FROM ONE ACCOUNT TO ANOTHER ACCOUNT

URL: http://localhost/kar-global-bank/account/transfer
METHOD: POST
INPUT:
~~~
--form 'account_number="5010050933"' \
--form 'amount="100"' \
--form 'to_account_number="5010071134"'
~~~
OUTPUT:
~~~
{
    "status": "success",
    "message": "Transaction completed successfully!"
}
~~~

TESTING
-------

Tests are located in `tests` directory. They are developed with [Codeception PHP Testing Framework](http://codeception.com/).

- `api`

Tests can be executed by running

```
vendor/bin/codecept run
```
(OR)

```
vendor/bin/codecept run api
```
(OR)

```
composer test-api
```

- The command above will execute all api tests.
- You can see output under the `tests/_output` directory.
