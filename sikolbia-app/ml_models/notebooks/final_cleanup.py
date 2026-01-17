import json

print("Loading notebook...")
with open('COLAB_NBM_Prediction_Complete.ipynb', 'r', encoding='utf-8') as f:
    notebook = json.load(f)

print(f"Current: {len(notebook['cells'])} cells\n")

# Find cells to delete by content
cells_to_delete = []

for idx, cell in enumerate(notebook['cells']):
    content = ''.join(cell.get('source', []))
    
    # Delete markers
    delete_keywords = [
        'MEGA AGGRESSIVE STRATEGY',
        'NUCLEAR OPTION',
        'ULTRA AGGRESSIVE TUNING',
        'EMERGENCY STRATEGIES',
        '🚨 MEGA',
        '🚨 NUCLEAR',
    ]
    
    for keyword in delete_keywords:
        if keyword in content:
            cells_to_delete.append(idx)
            print(f"Will delete Cell {idx}: Contains '{keyword}'")
            break

# Also find and delete their associated code cells
# (cells immediately after markdown headers)
extended_delete = []
for idx in cells_to_delete:
    extended_delete.append(idx)
    # Delete next 1-5 cells if they're code cells
    for i in range(1, 6):
        next_idx = idx + i
        if next_idx < len(notebook['cells']):
            next_cell = notebook['cells'][next_idx]
            if next_cell['cell_type'] == 'code':
                extended_delete.append(next_idx)
            elif next_cell['cell_type'] == 'markdown':
                break  # Stop at next markdown

# Remove duplicates and sort in reverse
extended_delete = sorted(list(set(extended_delete)), reverse=True)

print(f"\nTotal cells to delete: {len(extended_delete)}")
print(f"Cells: {extended_delete}\n")

# Delete
for idx in extended_delete:
    del notebook['cells'][idx]
    print(f"✅ Deleted cell {idx}")

print(f"\n📊 Final count: {len(notebook['cells'])} cells")

# Save
with open('COLAB_NBM_Prediction_Complete.ipynb', 'w', encoding='utf-8') as f:
    json.dump(notebook, f, indent=1, ensure_ascii=False)

print("\n✅ Notebook cleaned!")

# Show final structure
print("\n📋 Final structure:")
for idx, cell in enumerate(notebook['cells']):
    if cell['cell_type'] == 'markdown':
        content = ''.join(cell.get('source', []))
        if '##' in content:
            first_line = content.split('\n')[0]
            if any(x in first_line for x in ['Model', 'Setup', 'Data', 'Save', 'Summary', 'Conclusion', 'FINAL', 'NO SURRENDER']):
                print(f"  Cell {idx}: {first_line[:70]}")
