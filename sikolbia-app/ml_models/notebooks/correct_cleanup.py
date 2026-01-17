import json

with open('COLAB_NBM_Prediction_Complete.ipynb', 'r', encoding='utf-8') as f:
    nb = json.load(f)

print(f"Starting cells: {len(nb['cells'])}\n")

# Identify all markdown headers
sections = []
for idx, cell in enumerate(nb['cells']):
    if cell['cell_type'] == 'markdown':
        content = ''.join(cell.get('source', []))
        if '##' in content:
            first_line = content.strip().split('\n')[0]
            sections.append((idx, first_line))
            print(f"Cell {idx:2d}: {first_line[:80]}")

print(f"\n{'='*80}")
print("SECTIONS TO DELETE (experimental strategies):")
print('='*80)

delete_keywords = [
    '🚨 MEGA AGGRESSIVE',
    '🔥 FINAL PUSH: EXTREME STRATEGIES',
    '🎯 LAST RESORT: Individual Commodity',
    '🔥 NO SURRENDER',
    '🚨 EMERGENCY STRATEGIES',
    '**ULTRA AGGRESSIVE TUNING',
]

# Find sections to delete and their end boundaries
sections_to_delete = []
for i, (idx, header) in enumerate(sections):
    if any(kw in header for kw in delete_keywords):
        # Find next section index
        next_idx = sections[i + 1][0] if i + 1 < len(sections) else len(nb['cells'])
        sections_to_delete.append((idx, next_idx, header))
        print(f"DELETE Cells {idx:2d}-{next_idx-1:2d}: {header[:60]}")

print(f"\n{'='*80}")
print("CRITICAL SECTIONS TO KEEP:")
print('='*80)

keep_keywords = [
    '10. Model Comparison',
    '11. Predictions Visualization',
    '12. Prediction Function',
    '13. Save Models',
    '14. Summary'
]

for idx, header in sections:
    if any(kw in header for kw in keep_keywords):
        print(f"✅ KEEP Cell {idx:2d}: {header[:60]}")

print(f"\n{'='*80}")
print(f"Will delete {sum(end-start for start, end, _ in sections_to_delete)} cells total")
print('='*80)

# Collect all cell indices to delete
cells_to_delete = set()
for start_idx, end_idx, header in sections_to_delete:
    for j in range(start_idx, end_idx):
        cells_to_delete.add(j)

# Delete from end to beginning
for idx in sorted(list(cells_to_delete), reverse=True):
    del nb['cells'][idx]

print(f"\n✅ Deleted {len(cells_to_delete)} cells")
print(f"✅ Remaining: {len(nb['cells'])} cells")

# Save
with open('COLAB_NBM_Prediction_Complete.ipynb', 'w', encoding='utf-8') as f:
    json.dump(nb, f, indent=1, ensure_ascii=False)

print("\n" + "="*80)
print("✅ DONE! Cleaned notebook saved.")
print("="*80)
print("\nStructure now:")
print("  ✅ Setup & Data (cells 0-16)")
print("  ✅ Model 1: XGBoost")
print("  ✅ Model 2: LSTM")
print("  ✅ Model 3: HuberRegressor")
print("  ✅ Model 4: LSTM Enhanced Ensemble")
print("  ✅ Model Comparison")
print("  ✅ Predictions Visualization")
print("  ✅ Prediction Function")
print("  ✅ Save Models")
print("  ✅ Summary & Conclusion")
