# Vending Machine.

A PHP coding Challenge
[View the Senior Backend Engineer Challenge](Challenge.md)

# Setup

```bash
git clone git@github.com:rjacobs1969/vending.git
```

```bash
cd vending
```

## How to run the environment for the first time

First run, This will download the docker containers, install all dependencies, initialize the database.
```bash
make init up
```

After the initial start, its not necessary to use init every time you want to start the environment. you can use "make down" to stop & "make up" to start the environment

# Tests

To run the tests
```bash
make test
```

# Usage (Nelmio Open API interface)

After running the docker, the easiest way to use the API is with the Open API web interface located  as http://localhost/api/doc
Here you will find the following endpoints

## User Operations
- **POST** `/api/coin` - Insert a coin
- **POST** `/api/coin/return` - Return coins
- **POST** `/api/vend` - Get an item

## Service Operations
- **PUT** `/api/service/change` - Update change coins
- **PATCH** `/api/service/item/{name}` - Update item quantity

## Information Operations to view the states of the machine
- **GET** `/api/change` - List coins available for change
- **GET** `/api/coin/total` - Get the currently inserted amount (Current Credit)
- **GET** `/api/item` - List items

# Notes/design choices

Some of the requirement/workings of the system were left (intentionally) vague. I've modeled those behaviors after my (limited) experience with real vending machines.

I decided on the following additional behavior
- The vending machine is delivered empty, the products are defined but no inventory has been added. The same goes for the coins available for change.
- Coins inserted into the machine by the user are NEVER added to the collection of available change. (they are typically collected and an unordered cash box separate from the change coins dispenser)
- If a user inserts a coin with a value that's not accepted by machine it will immediate return it
- "Return coins" can only return ALL inserted coins, never in part
- If there is not enough change available when selecting an item the item will not be vended, it will inform you will have to insert the exact amount
- Change will be provided by using the minimum amount of coins, it will use 0.25 coins first, then 0.10 then 0.05. If a larger value isn't available anymore it will be substituted by multiple smaller value coins.

# BONUS

As the available items are stored in a database it's possible to add other products to the vending machine
The assignment did not ask for a way to do this so no endpoint has been provided, however you can add a product directly from the command line.

connect to the docker container with MYSQL:
```bash
make shell-sql
```

insert a product, for example add 5 bags of Chips with a price of 0.85
(note that price must be given in cents and should be a multiple of 5 otherwise there will never be change available, nor is the user able to insert the exact amount)
```bash
INSERT INTO items (name, price, quantity)  VALUES ('Chips', 85, 5);
```

exit the docker container
```bash
exit
```

Now the new item will be available in the vending machine just as water, juice and soda

# Examples using Curl to send request

Add (insert) a coin (ej. insert a 0.25 coin)
```bash
curl -X 'POST' \
  'http://localhost/api/coin' \
  -H 'accept: */*' \
  -H 'Content-Type: application/json' \
  -d '{
  "coin": 0.25
}'
```

Return coins
```bash
curl -X 'POST' \
  'http://localhost/api/coin/return' \
  -H 'accept: */*' \
  -d ''
```

Vend (buy) an Item (ej Water)
```bash
curl -X 'POST' \
  'http://localhost/api/vend' \
  -H 'accept: */*' \
  -H 'Content-Type: application/json' \
  -d '{
  "item": "water"
}'
```

Set the number of coins available for change (ej. 10X 0.05 coins)
```bash
curl -X 'PUT' \
  'http://localhost/api/service/change' \
  -H 'accept: */*' \
  -H 'Content-Type: application/json' \
  -d '{
  "coin": 0.05,
  "quantity": 10
}'
```

Set the number of an item available in the machine (ej. 3X juice)
```bash
curl -X 'PATCH' \
  'http://localhost/api/service/item/juice' \
  -H 'accept: */*' \
  -H 'Content-Type: application/json' \
  -d '{
  "quantity": 3
}'
```

Get a list of items
```bash
curl -X 'GET' 'http://localhost/api/item' -H 'accept: */*'
```

Get the total amount of the currently inserted coins
```bash
curl -X 'GET' 'http://localhost/api/coin/total' -H 'accept: */*'
```

Get a list of available coins for change
```bash
curl -X 'GET' 'http://localhost/api/change' -H 'accept: */*'
```
