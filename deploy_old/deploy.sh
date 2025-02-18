#!/bin/bash

set -x

INSTANCE_PUBLIC_DNS="${PUBLIC_DNS}"
REMOTE_USER="${USER}"

if [ -z "$INSTANCE_PUBLIC_DNS" ]; then
    echo "Could not find DNS or user for SSH connection"
    exit 1
fi

echo "Remote user: {$REMOTE_USER}"
echo "Instance DNS: {$INSTANCE_PUBLIC_DNS}"

echo "Copying project files to remote server..."
scp -o StrictHostKeyChecking=no -r ./src "$REMOTE_USER@$INSTANCE_PUBLIC_DNS:/tmp"
scp -o StrictHostKeyChecking=no ./deploy/build.sh "$REMOTE_USER@$INSTANCE_PUBLIC_DNS:/tmp"

echo "Connecting to remote server via SSH..."


ssh -o StrictHostKeyChecking=no "$REMOTE_USER@$INSTANCE_PUBLIC_DNS" << 'EOF'
    chmod +x /tmp/build.sh
    /tmp/build.sh
EOF


echo "Deployment complete!"
    

