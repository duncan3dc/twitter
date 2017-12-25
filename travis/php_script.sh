#!/bin/bash

vendor/bin/phpunit

vendor/bin/composer-require-checker

tests/lint.sh
