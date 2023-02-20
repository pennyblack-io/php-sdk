# Penny Black PHP API Client

This client library introduces a reusable API interface for PHP applications to communicate with the
Penny Black platform. 

## Prerequisites

* PHP >=7.4
* Composer

## Installation

* `composer install`

## Development

### Tests & Linting

We use PHPUnit for unit testing the app and PHPStan, PHP CodeSniffer and PHP Mess Detector for quality checks.

* Run `composer unit-test` to run the unit tests.
* Run `composer quality-check` to run the quality check tools.

## Releasing

This repo makes use of a single `main` branch, so in order to release any changes create a new tag against the current
state of the `main` branch.

Once the new tag has been created, any of our PHP apps that make use of this library should be updated to use the new
version.
