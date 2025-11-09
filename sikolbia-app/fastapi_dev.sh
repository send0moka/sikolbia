#!/bin/bash
# Docker ML API Development Helper Script

echo "=== FastAPI ML Container Management ==="
echo "1. Logs        - View FastAPI logs"
echo "2. Restart     - Restart FastAPI container"
echo "3. Rebuild     - Rebuild FastAPI container"
echo "4. Test        - Test API endpoints"
echo "5. Shell       - Access container shell"
echo "6. Stop        - Stop FastAPI container"
echo "7. Full Reset  - Stop, rebuild, start"
echo "8. Exit"
echo

read -p "Choose an option (1-8): " choice

case $choice in
    1)
        echo "Showing FastAPI logs..."
        docker-compose logs -f fastapi-ml
        ;;
    2)
        echo "Restarting FastAPI container..."
        docker-compose restart fastapi-ml
        ;;
    3)
        echo "Rebuilding FastAPI container..."
        docker-compose build fastapi-ml
        docker-compose up -d fastapi-ml
        ;;
    4)
        echo "Testing API endpoints..."
        echo "Health Check:"
        curl -s http://localhost:8082/health | jq .
        echo
        echo "Model Info:"
        curl -s http://localhost:8082/model/info | jq .
        ;;
    5)
        echo "Accessing container shell..."
        docker-compose exec fastapi-ml bash
        ;;
    6)
        echo "Stopping FastAPI container..."
        docker-compose stop fastapi-ml
        ;;
    7)
        echo "Full reset: stop, rebuild, start..."
        docker-compose stop fastapi-ml
        docker-compose build --no-cache fastapi-ml
        docker-compose up -d fastapi-ml
        ;;
    8)
        echo "Exiting..."
        exit 0
        ;;
    *)
        echo "Invalid option"
        ;;
esac
