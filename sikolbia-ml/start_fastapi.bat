@echo off
echo Starting FastAPI ML Service on port 8082...
cd /d %~dp0
cd app
python -m uvicorn main_simple:app --host 0.0.0.0 --port 8082 --reload
