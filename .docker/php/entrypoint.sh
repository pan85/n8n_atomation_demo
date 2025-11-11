#!/bin/bash

# If vendor doesn't exist locally, copy it from the build
if [ ! -d "/var/www/n8n_automation/vendor" ]; then
    echo "Copying vendor directory from build..."
    cp -r /tmp/vendor /var/www/n8n_automation/vendor
fi

# Execute the main command
exec "$@"
