#!/bin/bash

echo "Testing mysqldump command directly..."
echo ""

# Test mysqldump with hardcoded values (adjust if needed)
echo "Running mysqldump test..."
docker-compose exec app mysqldump --user=root --password=root --host=db --port=3306 --single-transaction sikolbia konsumsi > /tmp/test_backup.sql 2>&1

echo "Exit code: $?"
echo ""

# Check if file was created
docker-compose exec app ls -lh /tmp/test_backup.sql 2>/dev/null

if [ $? -eq 0 ]; then
    echo "Backup file created successfully!"
else
    echo "Backup file NOT created!"
fi

echo ""
echo "Done!"
