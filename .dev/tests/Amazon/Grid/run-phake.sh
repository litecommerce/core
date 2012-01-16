#!/usr/bin/env bash

EC2_DIR=$2;
PHAKE_DIR=$1;

source ${EC2_DIR}/env-up.sh;
${PHAKE_DIR}/phake;