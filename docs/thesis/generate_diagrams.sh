#!/bin/bash
# Script untuk generate UML diagrams dari PlantUML ke PNG
# Requirements: Java dan PlantUML JAR file

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${YELLOW}========================================${NC}"
echo -e "${YELLOW}  UML Diagram Generator untuk Thesis${NC}"
echo -e "${YELLOW}========================================${NC}"
echo ""

# Check if PlantUML exists
PLANTUML_JAR="plantuml.jar"
PLANTUML_URL="https://github.com/plantuml/plantuml/releases/download/v1.2024.7/plantuml-1.2024.7.jar"

if [ ! -f "$PLANTUML_JAR" ]; then
    echo -e "${YELLOW}PlantUML JAR not found. Downloading...${NC}"
    curl -L -o "$PLANTUML_JAR" "$PLANTUML_URL"
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓ PlantUML downloaded successfully${NC}"
    else
        echo -e "${RED}✗ Failed to download PlantUML${NC}"
        echo "Please download manually from: $PLANTUML_URL"
        exit 1
    fi
fi

# Check if Java is installed
if ! command -v java &> /dev/null; then
    echo -e "${RED}✗ Java is not installed${NC}"
    echo "Please install Java JRE or JDK first"
    exit 1
fi

echo -e "${GREEN}✓ Java found: $(java -version 2>&1 | head -n 1)${NC}"
echo ""

# Directory containing PlantUML files
DIAGRAM_DIR="docs/thesis/images"
cd "$DIAGRAM_DIR" || exit

echo -e "${YELLOW}Generating diagrams...${NC}"
echo ""

# Generate Activity Diagram
if [ -f "activity_diagram.puml" ]; then
    echo -e "Processing: ${GREEN}activity_diagram.puml${NC}"
    java -jar "../../../$PLANTUML_JAR" -tpng activity_diagram.puml
    
    if [ -f "activity_diagram.png" ]; then
        echo -e "${GREEN}✓ Generated: activity_diagram.png${NC}"
    else
        echo -e "${RED}✗ Failed to generate activity_diagram.png${NC}"
    fi
else
    echo -e "${RED}✗ activity_diagram.puml not found${NC}"
fi

echo ""

# Generate Sequence Diagram
if [ -f "sequence_diagram.puml" ]; then
    echo -e "Processing: ${GREEN}sequence_diagram.puml${NC}"
    java -jar "../../../$PLANTUML_JAR" -tpng sequence_diagram.puml
    
    if [ -f "sequence_diagram.png" ]; then
        echo -e "${GREEN}✓ Generated: sequence_diagram.png${NC}"
    else
        echo -e "${RED}✗ Failed to generate sequence_diagram.png${NC}"
    fi
else
    echo -e "${RED}✗ sequence_diagram.puml not found${NC}"
fi

echo ""
echo -e "${YELLOW}========================================${NC}"
echo -e "${GREEN}Diagram generation complete!${NC}"
echo -e "${YELLOW}========================================${NC}"
echo ""
echo "Generated files:"
ls -lh *.png 2>/dev/null | awk '{print "  - " $9 " (" $5 ")"}'
echo ""
echo -e "${YELLOW}Note:${NC} PNG files are ready to be referenced in thesis"
echo "Location: $DIAGRAM_DIR/"
