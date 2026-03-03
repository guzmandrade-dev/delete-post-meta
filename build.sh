#!/usr/bin/env bash

SLUG="delete-post-meta"
wp dist-archive . "${SLUG}.zip"
mv "../${SLUG}.zip" dist/
