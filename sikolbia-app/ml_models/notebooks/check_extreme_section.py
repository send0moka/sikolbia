import json

with open('COLAB_NBM_Prediction_Complete.ipynb', 'r', encoding='utf-8') as f:
    nb = json.load(f)

print(f"Total cells: {len(nb['cells'])}\n")

# Check cells around EXTREME section
print("=" * 80)
print("CELLS 33-42 (EXTREME and NO SURRENDER sections):")
print("=" * 80)

for i in range(33, min(43, len(nb['cells']))):
    cell = nb['cells'][i]
    cell_type = cell['cell_type']
    content = ''.join(cell.get('source', []))
    
    # Extract first meaningful line
    lines = [l.strip() for l in content.split('\n') if l.strip()]
    first_line = lines[0] if lines else ''
    
    print(f"\nCell {i} ({cell_type}):")
    print(f"  First line: {first_line[:100]}")
    
    # Check if it contains 2-way ensemble code
    if '2-way' in content or 'XGBoost+LSTM' in content:
        print("  ✅ Contains 2-way ensemble - KEEP")
    elif 'CatBoost' in content or '3-way' in content or 'LightGBM' in content:
        print("  ❌ Contains other strategies - DELETE")
    elif cell_type == 'markdown' and ('EXTREME' in content or 'NO SURRENDER' in content):
        print("  ⚠️  Section header - CHECK if needed")
