# campervan-test
Coding testing task, Symfony

## Setup
change database url in .env as per example
```
DATABASE_URL="mysql://roo:@127.0.0.1:3306/campervan?serverVersion=5.7"
```

## Run 
use symfony cli to run the application
```shell
cd campervan-test
bin/console doctrine:migrations:migrate
symfony serve
```

open url `http://localhost:8000/station/equipment/calendar` to check dashboard

add 1 campervan and 2-3 stations to the database, create sample equipment

## API
### Place order
to place example rental order

POST http://localhost:8000/api/rental/order 
```json
{
    "campervan_id": "1",
    "start_station_id": 1,
    "end_station_id": 2,
    "start_date": "2021-05-10",
    "end_date": "2021-05-18",
    "equipment": [{"equipment_id": 2, "amount": 1}, 
                  {"equipment_id": 3, "amount": 4}]
}
```

Output: HTTP status code 201 when everything is okay, otherwise 400 and error message

### Get calendar
use http://localhost:8000/api/station/equipment/calendar
at the moment there is issue https://github.com/symfony/symfony/issues/37334
