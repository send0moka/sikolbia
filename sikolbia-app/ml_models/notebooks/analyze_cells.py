import json
import shutil

print("Creating backup...")
shutil.copy('COLAB_NBM_Prediction_Complete.ipynb', 'COLAB_NBM_Prediction_Complete_FULL.ipynb.backup')

print("Loading notebook...")
with open('COLAB_NBM_Prediction_Complete.ipynb', 'r', encoding='utf-8') as f:
    notebook = json.load(f)

original_count = len(notebook['cells'])
print(f"Original cell count: {original_count}")

# Define sections to KEEP
keep_sections = [
    # Setup and data loading (cells 0-13 approx)
    (0, 20, "Setup, imports, and data loading"),
    
    # Feature engineering and split
    (20, 32, "Feature engineering and train-test split"),
    
    # Model 1: XGBoost (around cell 32-34)
    (32, 35, "Model 1: XGBoost"),
    
    # Model 2: LSTM (around cell 35-36)
    (35, 37, "Model 2: LSTM Component"),
    
    # Model 3: Huber (around cell 37-39)
    (37, 40, "Model 3: HuberRegressor"),
    
    # Model 4: Original 3-way ensemble (around cell 40-45)
    (40, 46, "Model 4: LSTM Enhanced Ensemble (Original 3-way)"),
    
    # EXTREME section with 2-way ensemble (around cell 51-63)
    (51, 64, "EXTREME Strategy: 2-way Ensemble (Final Model)"),
    
    # Visualization and conclusion (last few cells)
    (70, 100, "Visualization and final conclusion"),
]

# Identify cells to delete based on content
cells_to_delete = []

for idx, cell in enumerate(notebook['cells']):
    # Check if cell is in any keep section
    in_keep_section = False
    for start, end, desc in keep_sections:
        if start <= idx < end:
            in_keep_section = True
            break
    
    if in_keep_section:
        continue
    
    # Check cell content for delete keywords
    content = ''.join(cell.get('source', []))
    
    delete_keywords = [
        'ULTRA AGGRESSIVE',
        'ULTRA',
        'MEGA AGGRESSIVE',
        'MEGA',
        'NUCLEAR OPTION',
        'STACKING ENSEMBLE',
        'CatBoost',
        'LightGBM',
        'Multi-Range',
        'Optuna',
        'Polynomial',
        'Commodity-Specific',
        'Feature Engineering for MAPE',
    ]
    
    for keyword in delete_keywords:
        if keyword in content and idx not in [i for i, _ in cells_to_delete]:
            cells_to_delete.append((idx, keyword))
            break

print(f"\nCells to delete: {len(cells_to_delete)}")
for i, (idx, reason) in enumerate(cells_to_delete[:10]):
    print(f"  {i+1}. Cell {idx}: {reason}")

# Actually let's be more precise - manually specify cells to keep
# Based on the structure, let's keep only essential cells

print("\n⚠️ This will require manual review. Let me create a simpler approach...")

# Simpler approach: Mark sections to delete
delete_ranges = [
    # ULTRA section (cells ~33-38)
    (33, 39, "ULTRA strategy"),
    # MEGA section (cells ~40-43)  
    (40, 44, "MEGA strategy"),
    # NUCLEAR section (cells ~44-46)
    (44, 47, "NUCLEAR strategy"),
    # Advanced strategies (cells ~46-51)
    (46, 51, "Advanced strategies"),
    # Some EXTREME substrat (cells ~54-60 - but keep 2-way ensemble!)
    # We need to be careful here
]

# Let's just show what we have
print("\n📋 Current notebook structure:")
for idx, cell in enumerate(notebook['cells']):
    if cell['cell_type'] == 'markdown':
        content = ''.join(cell.get('source', []))
        if '##' in content:
            first_line = content.split('\n')[0]
            print(f"Cell {idx}: {first_line[:70]}")

print("\n✅ Analysis complete. Ready for manual deletion.")
print("Backup saved as: COLAB_NBM_Prediction_Complete_FULL.ipynb.backup")
