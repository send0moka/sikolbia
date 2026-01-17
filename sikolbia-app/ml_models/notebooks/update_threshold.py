import json
import re

print("Loading notebook...")
with open('COLAB_NBM_Prediction_Complete.ipynb', 'r', encoding='utf-8') as f:
    notebook = json.load(f)

# Comprehensive list of all patterns to replace
# Format: (pattern, replacement, description)
replacements = [
    # Main MAPE threshold
    (r'\bMAPE < 15%', 'MAPE < 15%', 'Already updated'),  # Skip if already 15
    (r'\b< 15%', '< 15%', 'Already updated'),  # Skip if already 15
    (r'\bMAPE < 10%', 'MAPE < 15%', 'MAPE threshold in text'),
    (r'\b< 10%', '< 15%', 'Generic < 10% in text'),
    (r'\b<10%', '<15%', 'Generic <10% in text'),
    (r'\bTarget: < 10%', 'Target: < 15%', 'Target threshold'),
    (r'\btarget < 10%', 'target < 15%', 'Lowercase target'),
    (r'\bTARGET < 10%', 'TARGET < 15%', 'Uppercase target'),
    
    # Code conditions
    (r'\bif mape < 10\.0', 'if mape < 15.0', 'If condition'),
    (r'\bif best_mape < 10\.0', 'if best_mape < 15.0', 'Best mape condition'),
    (r'\bif best_mape < 10\b', 'if best_mape < 15', 'Best mape condition (int)'),
    (r'\bexpected_mape < 10', 'expected_mape < 15', 'Expected mape'),
    (r'\bmape_xgb_ultra < 10\.0', 'mape_xgb_ultra < 15.0', 'XGB ultra check'),
    (r'\bmape_huber_ultra < 10\.0', 'mape_huber_ultra < 15.0', 'Huber ultra check'),
    (r'\bmape_lstm_enhanced < 10\.0', 'mape_lstm_enhanced < 15.0', 'LSTM enhanced check'),
    (r'\bmape_ensemble_mega < 10\.0', 'mape_ensemble_mega < 15.0', 'Ensemble mega check'),
    (r'\bmape_nuclear < 10\.0', 'mape_nuclear < 15.0', 'Nuclear check'),
    (r'\bmape_extreme < 10\.0', 'mape_extreme < 15.0', 'Extreme check'),
    (r'\bmape_catboost_extreme < 10\.0', 'mape_catboost_extreme < 15.0', 'CatBoost check'),
    (r'\bmape_3way < 10\.0', 'mape_3way < 15.0', '3-way check'),
    (r'\bmape_mega_aligned < 10\.0', 'mape_mega_aligned < 15.0', 'Mega aligned check'),
    (r'\bmape_super < 10\.0', 'mape_super < 15.0', 'Super check'),
    (r'\bmape_multi_range < 10\.0', 'mape_multi_range < 15.0', 'Multi-range check'),
    (r'\bmape_engineered < 10\.0', 'mape_engineered < 15.0', 'Engineered check'),
    (r'\bmape_top20 < 10\.0', 'mape_top20 < 15.0', 'Top20 check'),
    (r'\bmape_commodity < 10\.0', 'mape_commodity < 15.0', 'Commodity check'),
    (r'\bmape_poly < 10\.0', 'mape_poly < 15.0', 'Poly check'),
    (r'\bmape_lgb_extreme < 10\.0', 'mape_lgb_extreme < 15.0', 'LightGBM check'),
    (r'\bmape_lstm_extreme < 10\.0', 'mape_lstm_extreme < 15.0', 'LSTM extreme check'),
    (r'ensemble_test_metrics\[.mape.\] < 10', 'ensemble_test_metrics["mape"] < 15', 'Ensemble dict check'),
    
    # Calculations with 10
    (r'\b10 - ensemble_test_metrics', '15 - ensemble_test_metrics', 'Gap calculation'),
    (r'\b10 - best_mape', '15 - best_mape', 'Gap from best'),
    (r'\b10\.0 - best_mape', '15.0 - best_mape', 'Gap from best (float)'),
    (r'ensemble_test_metrics\[.mape.\] - 10\b', 'ensemble_test_metrics["mape"] - 15', 'Gap from target'),
    (r'\bbest_mape - 10\.0', 'best_mape - 15.0', 'Mape above target'),
    (r'\bbest_mape - 10\b', 'best_mape - 15', 'Mape above target (int)'),
    (r'\bmape - 10\.0', 'mape - 15.0', 'Generic mape gap'),
    (r'\bmape - 10\b', 'mape - 15', 'Generic mape gap (int)'),
    (r'\bmape_xgb_ultra - 10\.0', 'mape_xgb_ultra - 15.0', 'XGB gap'),
    
    # Text messages
    (r'Below 10% target', 'Below 15% target', 'Success message'),
    (r'SUCCESS! Below 10%', 'SUCCESS! Below 15%', 'Success message 2'),
    (r'<10% TARGET', '<15% TARGET', 'Target message'),
    (r'ACHIEVED <10%', 'ACHIEVED <15%', 'Achievement message'),
    (r'achieve <10%', 'achieve <15%', 'Achieve message'),
    (r'<10% MAPE', '<15% MAPE', 'MAPE message'),
    (r'Ensemble MAPE < 10%', 'Ensemble MAPE < 15%', 'Ensemble message'),
    (r'meeting the <10% target', 'meeting the <15% target', 'Meeting target'),
    (r'successfully meeting the <10%', 'successfully meeting the <15%', 'Successfully meeting'),
    (r'gap from <10%', 'gap from <15%', 'Gap message'),
    (r'Gap from <10%', 'Gap from <15%', 'Gap message capital'),
    (r'with MAPE <10%', 'with MAPE <15%', 'With MAPE'),
    (r'only {mape - 10:.2f}% above target', 'only {mape - 15:.2f}% above target', 'Format string'),
    
    # Elif/else conditions
    (r'elif mape < 10\.0', 'elif mape < 15.0', 'Elif condition'),
    (r'elif best_mape < 10\.0', 'elif best_mape < 15.0', 'Elif best mape'),
    (r'elif.*< 10\.0', lambda m: m.group(0).replace('< 10.0', '< 15.0'), 'Generic elif'),
]

changes_count = 0
changed_lines = []

for cell_idx, cell in enumerate(notebook['cells']):
    if 'source' in cell:
        for line_idx, line in enumerate(cell['source']):
            original = line
            
            # Skip lines that are already using 15
            if '< 15' in line or '<15' in line:
                continue
            
            # Skip lines about kalori (not MAPE)
            if '< 10 kkal' in line or '10-100' in line:
                continue
            
            # Apply replacements
            for pattern, replacement, desc in replacements:
                if callable(replacement):
                    line = re.sub(pattern, replacement, line)
                else:
                    line = re.sub(pattern, replacement, line)
            
            if line != original:
                changes_count += 1
                changed_lines.append((cell_idx, line_idx, original.strip()[:80], line.strip()[:80]))
                cell['source'][line_idx] = line

# Save
print(f"\nSaving changes...")
with open('COLAB_NBM_Prediction_Complete.ipynb', 'w', encoding='utf-8') as f:
    json.dump(notebook, f, indent=1, ensure_ascii=False)

print(f"\n✅ Updated {changes_count} lines")
print(f"\nSample changes (first 10):")
for i, (c, l, old, new) in enumerate(changed_lines[:10]):
    print(f"{i+1}. Cell {c}, Line {l}:")
    print(f"   Before: {old}")
    print(f"   After:  {new}")

print(f"\n✅ Done! Threshold updated from <10% to <15%")
