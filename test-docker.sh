#!/usr/bin/env bash
# Local Docker Test Script
# This script helps you test the Docker build locally before deploying to Render

set -e

echo "🐳 Testing Docker Build Locally"
echo "================================"

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo -e "${RED}❌ Docker is not running. Please start Docker and try again.${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Docker is running${NC}"

# Build the Docker image
echo ""
echo "📦 Building Docker image..."
if docker build -t entry-karo:test .; then
    echo -e "${GREEN}✅ Docker image built successfully${NC}"
else
    echo -e "${RED}❌ Docker build failed${NC}"
    exit 1
fi

# Check image size
echo ""
echo "📊 Image Information:"
docker images entry-karo:test --format "table {{.Repository}}\t{{.Tag}}\t{{.Size}}"

# Optional: Run the container for testing
echo ""
echo -e "${YELLOW}To test the container locally:${NC}"
echo ""
echo "1. Create a .env file with your local settings"
echo "2. Run the following command:"
echo ""
echo "   docker run -p 8080:80 \\"
echo "     -e APP_KEY=base64:YOUR_APP_KEY \\"
echo "     -e APP_ENV=local \\"
echo "     -e APP_DEBUG=true \\"
echo "     -e DB_CONNECTION=sqlite \\"
echo "     -e DB_DATABASE=/var/www/html/database/database.sqlite \\"
echo "     entry-karo:test"
echo ""
echo "3. Visit http://localhost:8080"
echo ""
echo -e "${GREEN}✅ Docker build test completed successfully!${NC}"
echo ""
echo "Your application is ready to deploy to Render! 🚀"
