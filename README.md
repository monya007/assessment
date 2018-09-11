# Assessment results

## Prerequisites

- Docker-compose

## Usage

First, you need to clone this repository.

    git clone git@github.com:monya007/assessment.git

Then, change current folder and copy .env.example to .env file.

    cd assessment
    cp .env.example .env

Then, run install:

    make install

Once started, you can access environment on url http://assessment.docker.localhost:8000 (creds admin:admin). Create math field example content and check formatter work(http://assessment.docker.localhost:8000/node/add/math_field_example).
Navigate to http://front.assessment.docker.localhost:8000/node to see simplified front end example.

## Tests

Execute the following:

    make run-tests

## Cleaning

    docker-compose down
