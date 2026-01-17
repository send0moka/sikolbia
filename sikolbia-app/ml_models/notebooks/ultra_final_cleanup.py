import json

with open('COLAB_NBM_Prediction_Complete.ipynb', 'r', encoding='utf-8') as f:
    nb = json.load(f)

print(f"Current cells: {len(nb['cells'])}\n")

# Find all cells after Model 4 but before Save Models
# Should only keep: 2-way ensemble training + final verdict

delete_list = []
keep_2way = None
keep_verdict = None

for idx in range(30, len(nb['cells'])):  # Start after Model 4
    cell = nb['cells'][idx]
    content = ''.join(cell.get('source', []))
    
    # Check if this is Save Models section - stop here
    if 'Save Models' in content or '## **13.' in content:
        print(f"Found Save Models section at cell {idx}")
        break
    
    # Check if this is 2-way ensemble (the one we want to keep)
    if 'mape_lstm_enhanced_extreme' in content and '2-way' in content:
        keep_2way = idx
        print(f"✅ KEEP Cell {idx}: 2-way ensemble training")
        continue
    
    # Check if this is final verdict (edited by us)
    if 'HASIL AKHIR - MODEL TERPILIH' in content:
        keep_verdict = idx
        print(f"✅ KEEP Cell {idx}: Final verdict")
        continue
    
    # Everything else between Model 4 and Save should be deleted
    if cell['cell_type'] == 'code':
        # Check for unwanted strategies
        if any(kw in content for kw in ['EMERGENCY', 'PER-COMMODITY', 'POLYNOMIAL', 'OPTUNA']):
            delete_list.append(idx)
            keyword = [kw for kw in ['EMERGENCY', 'PER-COMMODITY', 'POLYNOMIAL', 'OPTUNA'] if kw in content][0]
            print(f"❌ DELETE Cell {idx}: {keyword}")

print(f"\nWill delete {len(delete_list)} cells: {delete_list}")

# Delete from end to beginning
for idx in reversed(delete_list):
    del nb['cells'][idx]
    print(f"✅ Deleted cell {idx}")

print(f"\nFinal: {len(nb['cells'])} cells")

# Save
with open('COLAB_NBM_Prediction_Complete.ipynb', 'w', encoding='utf-8') as f:
    json.dump(nb, f, indent=1, ensure_ascii=False)

print("\n✅ Done! Notebook now contains only:")
print("  1. Setup & Data Loading")
print("  2. Model 1: XGBoost")
print("  3. Model 2: LSTM")
print("  4. Model 3: HuberRegressor")
print("  5. Model 4: LSTM Enhanced Ensemble (3-way original)")
print("  6. LSTM Enhanced 2-way: XGBoost+LSTM (FINAL MODEL)")
print("  7. Final Verdict")
print("  8. Save Models & Conclusion")
