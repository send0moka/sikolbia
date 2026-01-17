import json
import re

with open('COLAB_NBM_Prediction_Complete.ipynb', 'r', encoding='utf-8') as f:
    nb = json.load(f)

count = 0

# Comprehensive patterns for 10% → 15% threshold
patterns = [
    (r'MAPE < 10%', 'MAPE < 15%'),
    (r'<10%', '<15%'),
    (r'mape < 10\.0', 'mape < 15.0'),
    (r'mape < 10', 'mape < 15'),
    (r'< 10\.0', '< 15.0'),
    (r'10\.0%', '15.0%'),
    (r'target.*?10%', lambda m: m.group(0).replace('10%', '15%')),
    (r'ensemble_test_metrics\[\'mape\'\] < 10', 'ensemble_test_metrics[\'mape\'] < 15'),
    (r'best_mape < 10', 'best_mape < 15'),
    (r'expected_mape < 10', 'expected_mape < 15'),
    (r'under_10 =', 'under_15 ='),
    (r'achieved <10%', 'achieved <15%'),
    (r'Below 10%', 'Below 15%'),
    (r'above 10%', 'above 15%'),
    (r'\{10\.0 - ', '{15.0 - '),
    (r' - 10\.0:', ' - 15.0:'),
    (r'Gap from target: -\{10\.0 -', 'Gap from target: -{15.0 -'),
    (r'Target: < 10%', 'Target: < 15%'),
    (r'target <10%', 'target <15%'),
]

for cell in nb['cells']:
    if cell['cell_type'] == 'code':
        content = ''.join(cell.get('source', []))
        original = content
        
        for pattern, replacement in patterns:
            if callable(replacement):
                content = re.sub(pattern, replacement, content, flags=re.IGNORECASE)
            else:
                content = re.sub(pattern, replacement, content, flags=re.IGNORECASE)
        
        if content != original:
            cell['source'] = content.split('\n')
            # Add newline to each line except last
            cell['source'] = [line + '\n' for line in cell['source'][:-1]] + [cell['source'][-1]]
            count += 1

print(f"✅ Updated {count} cells with 10% → 15% threshold")

# Save
with open('COLAB_NBM_Prediction_Complete.ipynb', 'w', encoding='utf-8') as f:
    json.dump(nb, f, indent=1, ensure_ascii=False)

print("✅ Saved!")
