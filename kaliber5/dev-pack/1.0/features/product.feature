@api
Feature: product api tests
    In order to get data from api
    As an api consumer
    I want to get data from specific endpoint

    Background:
        Given there are "products" in the database
        And I add "accept" header equal to "application/vnd.api+json"

    Scenario: Get data for single product
        When I send a GET request to "/api/products/1"
        Then the api response should match:
        """
        {
          "data": {
            "id": "1",
            "type": "product",
            "attributes": {
              "name": "@string@"
            }
          }
        }
        """

    Scenario: Get data for finders
        When I send a GET request to "/api/products"
        And the api response should match:
        """
        {
          "links": {
            "self": "/api/products"
          },
          "meta": {
            "totalItems": "@integer@"
          },
          "data": [
            {
              "id": "@integer@",
              "type": "product",
              "attributes": {
                "name": "@string@"
              }
            },
            {
              "id": "@integer@",
              "type": "product",
              "attributes": {
                "name": "@string@"
              }
            },
            "@...@"
          ]
        }
        """