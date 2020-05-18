# Quotes API Demo

REST API that returns quotes shouted by famous persons.

To setup new docker dev environment run the following command:
```
make dev
```
By default API will be available on port `8080`

To run unit tests:
```
make unit
```
Generated code coverage report will be available under `build/phpspec-coverage`

To set up test infrastructure and run functional tests:
```
make test
```

To see the full commands list:
```
make help
```

Project development documentation is under [/doc](https://github.com/evodevo/quotes_api/tree/master/doc/index.md) directory

API documentation generated from Swagger annotations is available at `http://127.0.0.1:8080/doc`
