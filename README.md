# Symfony recipes to boost project setup

### TL;TD

* Create a new project first, f.e.:
  ```bash
  composer create-project symfony/website-skeleton my_project
  cd my_project
  ```
* Add kaliber5's custom servers to the `composer.json`
    ```bash
    composer config repositories.k5 composer https://composer.kaliber5.de
    composer config extra.symfony.endpoint https://flex.kaliber5.de
    ```
  
* Install kaliber5's setup-pack recipe
    ```bash
    composer req --dev kaliber5/setup-pack
    ```

* Add a build command to the script section of `composer.json`
  ```json
  {
    "scripts": {
      "build": [
        "@composer install"
      ]
    }
  }
  ```
* Now you're able to spin up the default docker environment and login into php's container
  ```bash
  docker-compose up -d
  docker-compose exec php bash
  ```
* Install the kaliber5's dev-pack recipe
  ```bash
  composer req --dev kaliber5/dev-pack
  ```
* Add the following scripts to the script section of `composer.json`
  ```json
  {
    "scripts": {
      "test": [
            "@composer install -n",
            "@dev-test"
        ],
        "dev-test": [
            "APP_ENV=test vendor/bin/phpspec run -n",
            "APP_ENV=test vendor/bin/ecs check src --no-progress-bar",
            "APP_ENV=test vendor/bin/behat --colors"
        ],
        "ecs-fix": [
            "APP_ENV=test vendor/bin/ecs check src --no-progress-bar --fix"
        ]
    }
  }
  ```
* Install the kaliber5's api-pack recipe
  ```bash
  composer req kaliber5/api-pack --no-scripts
  ```
  After install replace the files `config/services.yaml` and `config/packages/api_platform.yaml` with the their underscored versions. 
### Documentation

Full documentation is available here: [Documentation](https://server-for-symfony-flex.readthedocs.io)

### License

Published under the [MIT License](https://github.com/moay/server-for-symfony-flex/blob/master/LICENSE).
