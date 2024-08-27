# fruits-and-vegetables

## Core components
- `\App\Model\Edible`: Represents a fruit or vegetable
- `\App\Repository\EdibleRepository\EdibleRepositoryInterface`: Interface for the repository. Implemented by `InMemoryEdibleRepository`. Configured in services.yaml.
- `\App\Filters\EdibleFilter`: Filter parameters for the list operation
- `\App\Service\EdibleStorage\EdibleStorageServiceInterface`: Interface for the storage service. Implemented by `EdibleStorageService`. Configured in services.yaml.
- `\App\Service\EdibleStorage\Collection\FruitCollection`: Collection of fruits
- `\App\Service\EdibleStorage\Collection\VegetableCollection`: Collection of vegetables

## Local Setup

1. Clone the repository
2. Run `docker compose up -d`
3. Run `docker compose exec app composer install`

## Running tests

Run `docker compose exec app composer run test`

## API Endpoints

### List all

`GET http://localhost:8080/v1/edibles`

Supported query parameters:
- `type`: optional string (`fruit` or `vegetable`)
- `keyword`: optional string (search by name)
- `minGrams`: optional number (minimum quantity in grams)
- `maxGrams`: optional number (maximum quantity in grams)
- `unit`: optional string (output unit, defaults to `g`)

### Add new

`POST http://localhost:8080/v1/edibles`

Request body sample:
```json
{
    "type": "fruit",
    "name": "New Fruit",
    "quantity": 2,
    "unit": "kg"
}
```

curl sample:
```bash
 curl -X POST -d '{"type":"fruit","name":"New Fruit","quantity":2,"unit":"kg"}' http://localhost:8080/v1/edibles
```

#### Known issues:
- The API uses in-memory storage, so the data will be reset on every request. In order to indicate successful creation,
the `add` endpoint will return all the items in the storage. The newly added item should have the ID `21`.
