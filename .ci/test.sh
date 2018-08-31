#!/usr/bin/env bash

set -ex

export PATH="$PWD/vendor/bin:$PATH"

wp core download --path=wp

cp src/db.php wp/wp-content

cd wp

mv wp-config-sample.php wp-config.php

wp core install \
    --url="http://travis.test" \
    --title="Travis" \
    --admin_user="travis" \
    --admin_email="travis@travis.test"

wp core is-installed
