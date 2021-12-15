# mycoolpay-payment-gateway

This package is made for developers who want to integrate my-coolpay payment 
gateway on their php application.

The requirements are 

"php": ">=7.2"

"ext-curl": "*"

"ext-json": "*"

## Installation

```bash
composer require samyasm/mycoolpay
```
## Usage

For using this, you first need a merchant account on www.my-coolpay.com

Then, after installing you must create a common class that will do custom process
like DB logs after and before any interactions with the API.
This class must extends the MyCoolPay\MCP class, and must implements the MyCoolPay\
MCPInterface, then just define all methods on this class.

You can see example on The Test directory of the project.


have 4 actions you can perform with your Custom MCP class

##### 1. Pay (get paid by your customers)
Can be performed with getPaymentLink method

##### 2. Pay out (withdraw money from your account)
Can be performed with payout method

##### 3. Callback (Manage callback from My-CoolPay API)
Can be performed with callback method

##### 4. Check (Check transaction status on API)
Can be performed with checkStatus method

For all these methods, the first parameter is just the ones specified on the 
API doc.


***