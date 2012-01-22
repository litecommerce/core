#!/usr/bin/env bash

EC2_DIR=$2;
PHAKE_DIR=$1;

source ${EC2_DIR}/env-up.sh;
${PHAKE_DIR}/phake screenshots_url='http://xcart2-530.crtdev.local/~xcart/general/projects/xlite/build/logs/screenshots';