#!/bin/bash

composer self-update --snapshot

composer update $1
