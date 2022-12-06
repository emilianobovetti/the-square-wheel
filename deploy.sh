#!/bin/bash

set -e

if [ -z "$NETLIFY_AUTH_TOKEN" ]; then
  echo "NETLIFY_AUTH_TOKEN not setted"
  exit 1
fi

if [ -z "$NETLIFY_SITE_ID" ]; then
  echo "NETLIFY_SITE_ID not setted"
  exit 1
fi

if [ "$DRONE_BRANCH" = "master" ]; then
  "$DRONE_WORKSPACE/node_modules/.bin/netlify" deploy \
    --message="$DRONE_BRANCH@$(git rev-parse --short HEAD)" \
    --auth="$NETLIFY_AUTH_TOKEN" \
    --site="$NETLIFY_SITE_ID" \
    --dir=public \
    --prod
fi
