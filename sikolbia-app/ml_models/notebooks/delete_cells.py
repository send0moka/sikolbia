import json

print("Loading notebook...")
with open('COLAB_NBM_Prediction_Complete.ipynb', 'r', encoding='utf-8') as f:
    notebook = json.load(f)

original_count = len(notebook['cells'])
print(f"Original: {original_count} cells")

# Cells to DELETE (by index - will be adjusted as we delete)
# We'll delete from end to beginning so indices don't shift

delete_ranges = [
    # Emergency strategies and ULTRA aggressive (cells 63-69)
    (63, 70, "Emergency/Multi-range/ULTRA aggressive"),
    
    # Extra EXTREME strategies (cells 51-57, but keep 58-62 for 2-way)
    (51, 58, "Extra EXTREME strategies"),
    
    # Advanced strategies (cells 46-50)
    (46, 51, "Advanced strategies"),
    
    # NUCLEAR (cells 44-45)
    (44, 46, "NUCLEAR option"),
    
    # MEGA (cells 39-43)
    (39, 44, "MEGA aggressive"),
    
    # ULTRA (cells 33-38)  
    (33, 39, "ULTRA strategies"),
]

# Delete from end to beginning
for start, end, desc in reversed(delete_ranges):
    del notebook['cells'][start:end]
    print(f"✅ Deleted cells {start}-{end-1}: {desc}")

new_count = len(notebook['cells'])
deleted = original_count - new_count
print(f"\n📊 Summary:")
print(f"  Original: {original_count} cells")
print(f"  Deleted:  {deleted} cells")  
print(f"  Remaining: {new_count} cells")

# Save
with open('COLAB_NBM_Prediction_Complete.ipynb', 'w', encoding='utf-8') as f:
    json.dump(notebook, f, indent=1, ensure_ascii=False)

print(f"\n✅ Notebook cleaned and saved!")
print(f"   Backup: COLAB_NBM_Prediction_Complete_FULL.ipynb.backup")

# Show remaining structure
print(f"\n📋 Remaining sections:")
for idx, cell in enumerate(notebook['cells']):
    if cell['cell_type'] == 'markdown':
        content = ''.join(cell.get('source', []))
        if '##' in content and idx < 50:  # Show first 50 for overview
            first_line = content.split('\n')[0]
            print(f"  Cell {idx}: {first_line[:65]}")
