#!/bin/bash
find vendor/veocode/ -type f -exec chmod 644 {} \;
find vendor/veocode/ -type d -exec chmod 755 {} \;
