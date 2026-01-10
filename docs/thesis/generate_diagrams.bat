@echo off
REM Script untuk generate UML diagrams dari PlantUML ke PNG (Windows)
REM Requirements: Java dan PlantUML JAR file

echo ========================================
echo   UML Diagram Generator untuk Thesis
echo ========================================
echo.

REM Check if PlantUML exists
set PLANTUML_JAR=plantuml.jar
set PLANTUML_URL=https://github.com/plantuml/plantuml/releases/download/v1.2024.7/plantuml-1.2024.7.jar

if not exist "%PLANTUML_JAR%" (
    echo PlantUML JAR not found. Downloading...
    curl -L -o "%PLANTUML_JAR%" "%PLANTUML_URL%"
    
    if errorlevel 1 (
        echo [ERROR] Failed to download PlantUML
        echo Please download manually from: %PLANTUML_URL%
        exit /b 1
    )
    
    echo [OK] PlantUML downloaded successfully
)

REM Check if Java is installed
java -version >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Java is not installed
    echo Please install Java JRE or JDK first
    exit /b 1
)

echo [OK] Java found
java -version 2>&1 | findstr /i "version"
echo.

REM Directory containing PlantUML files
set DIAGRAM_DIR=docs\thesis\images
cd /d "%~dp0%DIAGRAM_DIR%" 2>nul || cd "%DIAGRAM_DIR%"

echo Generating diagrams...
echo.

REM Generate Activity Diagram
if exist "activity_diagram.puml" (
    echo Processing: activity_diagram.puml
    java -jar "..\..\..\%PLANTUML_JAR%" -tpng activity_diagram.puml
    
    if exist "activity_diagram.png" (
        echo [OK] Generated: activity_diagram.png
    ) else (
        echo [ERROR] Failed to generate activity_diagram.png
    )
) else (
    echo [ERROR] activity_diagram.puml not found
)

echo.

REM Generate Sequence Diagram
if exist "sequence_diagram.puml" (
    echo Processing: sequence_diagram.puml
    java -jar "..\..\..\%PLANTUML_JAR%" -tpng sequence_diagram.puml
    
    if exist "sequence_diagram.png" (
        echo [OK] Generated: sequence_diagram.png
    ) else (
        echo [ERROR] Failed to generate sequence_diagram.png
    )
) else (
    echo [ERROR] sequence_diagram.puml not found
)

echo.
echo ========================================
echo Diagram generation complete!
echo ========================================
echo.
echo Generated files:
dir /B *.png 2>nul
echo.
echo Note: PNG files are ready to be referenced in thesis
echo Location: %CD%
echo.

pause
