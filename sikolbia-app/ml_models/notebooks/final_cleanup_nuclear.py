import json

with open('COLAB_NBM_Prediction_Complete.ipynb', 'r', encoding='utf-8') as f:
    nb = json.load(f)

print(f"Current cells: {len(nb['cells'])}\n")

# Find remaining experimental sections
delete_more = [
    '🚨 NUCLEAR OPTION',
    '🎯 ADVANCED STRATEGIES',
]

sections = []
for idx, cell in enumerate(nb['cells']):
    if cell['cell_type'] == 'markdown':
        content = ''.join(cell.get('source', []))
        if '##' in content:
            first_line = content.strip().split('\n')[0]
            sections.append((idx, first_line))

sections_to_delete = []
for i, (idx, header) in enumerate(sections):
    if any(kw in header for kw in delete_more):
        # Find next section
        next_idx = sections[i + 1][0] if i + 1 < len(sections) else len(nb['cells'])
        sections_to_delete.append((idx, next_idx, header))
        print(f"DELETE Cells {idx:2d}-{next_idx-1:2d}: {header}")

# Collect cells to delete
cells_to_delete = set()
for start_idx, end_idx, header in sections_to_delete:
    for j in range(start_idx, end_idx):
        cells_to_delete.add(j)

# Delete
for idx in sorted(list(cells_to_delete), reverse=True):
    del nb['cells'][idx]

print(f"\n✅ Deleted {len(cells_to_delete)} cells")
print(f"✅ Remaining: {len(nb['cells'])} cells")

# Save
with open('COLAB_NBM_Prediction_Complete.ipynb', 'w', encoding='utf-8') as f:
    json.dump(nb, f, indent=1, ensure_ascii=False)

print("\n✅ DONE!")
print("\nFinal structure:")
for idx, cell in enumerate(nb['cells']):
    if cell['cell_type'] == 'markdown':
        content = ''.join(cell.get('source', []))
        if content.strip().startswith('##'):
            header = content.strip().split('\n')[0]
            print(f"  Cell {idx:2d}: {header[:70]}")
