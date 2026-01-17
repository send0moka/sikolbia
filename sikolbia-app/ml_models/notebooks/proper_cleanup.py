import json

with open('COLAB_NBM_Prediction_Complete.ipynb', 'r', encoding='utf-8') as f:
    nb = json.load(f)

print(f"Starting with: {len(nb['cells'])} cells\n")

# List cells to understand structure
print("Analyzing structure...")
sections_to_keep = []
sections_to_delete = []

for idx, cell in enumerate(nb['cells']):
    content = ''.join(cell.get('source', []))
    
    # Find section headers
    if cell['cell_type'] == 'markdown' and content.strip().startswith('##'):
        header = content.strip().split('\n')[0]
        print(f"Cell {idx}: {header}")
        
        # Identify cells to DELETE
        if any(kw in header for kw in [
            'ULTRA', 'MEGA', 'NUCLEAR', 'EMERGENCY', 
            'EXTREME STRATEGIES', 'ADVANCED',
            'CatBoost', 'LightGBM', 'Polynomial', 'Optuna',
            'Per-Commodity', 'Stacking', 'Multi-Range'
        ]):
            sections_to_delete.append((idx, header))
            print(f"  ❌ WILL DELETE")
        else:
            sections_to_keep.append((idx, header))
            print(f"  ✅ KEEP")

print(f"\n\nWill delete sections: {len(sections_to_delete)}")
for idx, name in sections_to_delete:
    print(f"  {idx}: {name}")

print(f"\n\nWill keep sections: {len(sections_to_keep)}")
for idx, name in sections_to_keep:
    print(f"  {idx}: {name}")

# Find cells to delete (from section start until next section)
cells_to_delete = set()

for i, (del_idx, del_name) in enumerate(sections_to_delete):
    # Find where this section ends
    next_section = None
    for j in range(del_idx + 1, len(nb['cells'])):
        cell = nb['cells'][j]
        if cell['cell_type'] == 'markdown':
            content = ''.join(cell.get('source', []))
            if content.strip().startswith('##'):
                next_section = j
                break
    
    if next_section is None:
        # Check if this is before a section we want to keep
        keep_indices = [k[0] for k in sections_to_keep if k[0] > del_idx]
        if keep_indices:
            next_section = min(keep_indices)
    
    # Delete from del_idx to next_section (exclusive)
    if next_section:
        for j in range(del_idx, next_section):
            cells_to_delete.add(j)
            print(f"  Mark for deletion: Cell {j}")
    else:
        # Delete until end
        for j in range(del_idx, len(nb['cells'])):
            cells_to_delete.add(j)
            print(f"  Mark for deletion: Cell {j}")

print(f"\n\nTotal cells to delete: {len(cells_to_delete)}")
print(f"Final count will be: {len(nb['cells']) - len(cells_to_delete)} cells")

# Ask for confirmation
print("\n" + "="*80)
print("IMPORTANT CHECK:")
print("="*80)
print("Will we KEEP these critical sections?")
print("  - Model Comparison (section 10)")
print("  - Predictions Visualization (section 11)")  
print("  - Prediction Function (section 12)")
print("  - Save Models (section 13)")
print("  - Summary & Conclusion (section 14)")
print("\nScanning...")

for idx, name in sections_to_keep:
    if any(kw in name for kw in ['Model Comparison', 'Predictions', 'Prediction Function', 'Save Models', 'Summary']):
        print(f"  ✅ FOUND: Cell {idx} - {name}")

print("\n" + "="*80)
input("Press ENTER to proceed with deletion, or Ctrl+C to cancel...")

# Delete cells
for idx in sorted(list(cells_to_delete), reverse=True):
    del nb['cells'][idx]
    print(f"✅ Deleted cell {idx}")

print(f"\n✅ Final: {len(nb['cells'])} cells")

# Save
with open('COLAB_NBM_Prediction_Complete.ipynb', 'w', encoding='utf-8') as f:
    json.dump(nb, f, indent=1, ensure_ascii=False)

print("\n✅ Done! Notebook cleaned.")
print(f"   From: {len(nb['cells']) + len(cells_to_delete)} cells")
print(f"   To:   {len(nb['cells'])} cells")
