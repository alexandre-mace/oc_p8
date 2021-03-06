# ToDo & Co

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/fa3af388fcac4778abc3db674b7f9a4c)](https://app.codacy.com/app/codacy_alexandre-mace/oc_p8?utm_source=github.com&utm_medium=referral&utm_content=alexandre-mace/oc_p8&utm_campaign=Badge_Grade_Dashboard)
[![Build Status](https://travis-ci.org/alexandre-mace/oc_p8.svg?branch=dev)](https://travis-ci.org/alexandre-mace/oc_p8)
[![Codacy Badge](https://api.codacy.com/project/badge/Coverage/571bab4db3424670aa1c17fc583d251a)](https://www.codacy.com/app/codacy_alexandre-mace/oc_p8?utm_source=github.com&utm_medium=referral&utm_content=alexandre-mace/oc_p8&utm_campaign=Badge_Coverage)

Enhance an existing Symfony 3 application, 8th project from OpenClassroom's class

## Requirements 
*   [MySQL](https://www.mysql.com/fr/)
*   [PHP](http://php.net/manual/fr/intro-whatis.php)
*   [Apache](https://www.apache.org/)

## Installation 
*   Clone the repository and open it.

		$ git clone https://github.com/alexandre-mace/oc_p8.git
		$ cd oc_p8

*   Install dependencies.
		
		$ composer install

## Configuration
*   Customize the .env file

#### doctrine
```
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"
```

*   Create database 

		$ php bin/console doctrine:database:create

*   Get tables 

		$ php bin/console doctrine:make:migration
		$ php bin/console doctrine:migrations:migrate

*   Get data

		$ php bin/console hautelook:fixtures:load

## Tests
* Configure the phpunit.xml.dist file
```
<env name="DATABASE_URL" value="mysql://db_user:db_password@127.0.0.1:3306/db_name"/>
```
* run in console `./bin/phpunit` and results will show up in console
