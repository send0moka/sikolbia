import json

print("Loading notebook...")
with open('COLAB_NBM_Prediction_Complete.ipynb', 'r', encoding='utf-8') as f:
    nb = json.load(f)

print(f"Current: {len(nb['cells'])} cells")

# Manually specify cells to delete
# Based on analysis: Cell 35, 36, 38, 40, 41 contain unwanted strategies
cells_to_check_and_delete = [35, 36, 38, 40, 41]

actually_delete = []
for idx in cells_to_check_and_delete:
    if idx < len(nb['cells']):
        content = ''.join(nb['cells'][idx].get('source', []))
        
        # Check if contains unwanted content
        if any(kw in content for kw in ['CatBoost', '3-way', 'LightGBM', 'NO SURRENDER']):
            actually_delete.append(idx)
            keyword_found = [kw for kw in ['CatBoost', '3-way', 'LightGBM', 'NO SURRENDER'] if kw in content][0]
            print(f"Will delete Cell {idx}: Contains '{keyword_found}'")

# Also check cells 33-34 (markdown headers we don't need)
# But keep Cell 39 (contains 2-way ensemble)

# Delete cell 33-34 (EXTREME STRATEGIES headers)
if len(nb['cells']) > 33:
    content_33 = ''.join(nb['cells'][33].get('source', []))
    if 'EXTREME STRATEGIES' in content_33:
        actually_delete.extend([33, 34])  # Delete markdown + its first code cell
        print(f"Will delete Cells 33-34: EXTREME STRATEGIES section (not needed)")

# Sort and remove duplicates, reverse order
actually_delete = sorted(list(set(actually_delete)), reverse=True)

print(f"\nTotal to delete: {len(actually_delete)}")
print(f"Cells: {actually_delete}\n")

# Delete
for idx in actually_delete:
    del nb['cells'][idx]
    print(f"✅ Deleted cell {idx}")

# Update cell 39 marker (now shifted) to be clearer
# Find the cell that contains '2-way' ensemble training
for idx, cell in enumerate(nb['cells']):
    if cell['cell_type'] == 'code':
        content = ''.join(cell.get('source', []))
        if '2-way' in content and 'XGBoost+LSTM' in content and 'mape_lstm_enhanced_extreme' in content:
            print(f"\n✅ Found 2-way ensemble training at Cell {idx}")
            # Maybe add a markdown cell before it
            break

print(f"\n📊 Final: {len(nb['cells'])} cells")

# Save
with open('COLAB_NBM_Prediction_Complete.ipynb', 'w', encoding='utf-8') as f:
    json.dump(nb, f, indent=1, ensure_ascii=False)

print("\n✅ Final cleanup done!")

# Show structure
print("\n📋 Final notebook structure:")
count = 0
for idx, cell in enumerate(nb['cells']):
    if cell['cell_type'] == 'markdown':
        content = ''.join(cell.get('source', []))
        if '##' in content:
            lines = content.split('\n')
            first = lines[0] if lines else ''
            print(f"  Cell {idx}: {first[:75]}")
            count += 1
            if count > 15:
                print("  ...")
                break
