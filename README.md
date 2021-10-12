# Export CSV from JSON Lines format
## Introduction
Hi guys, so I create the mini application base on Symfony framework to make summarize data from JSON line format and put the report into CSV files. In this application, I implement Service Layer design . Why I use this design ? because it will be easier to me to make separated microservices in the future use. Commonly, this design will have entity and repository base on entity. But I'm not using entity model because we will not save the data to database.

## System Requirement
- PHP 7.4
- [Composer](https://getcomposer.org/)

## Installation

Use composer as the package manager  to install dependecies.

```bash
# at project directory

cp .env.example .env

composer install

php bin/console cache:clear

```

## How to Run

```bash
# at project directory

php bin/console app:export-csv

#output will contains file location 
# ex: success create file . file location = /Users/vani/jsonl_csv_conversion/report/1634070922-report.csv

```

## Testing
I separate unit testing in two files base on service layer model

```bash

php vendor/bin/phpunit tests

```