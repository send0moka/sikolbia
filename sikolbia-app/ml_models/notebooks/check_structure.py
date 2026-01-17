import json

with open('COLAB_NBM_Prediction_Complete.ipynb', 'r', encoding='utf-8') as f:
    notebook = json.load(f)

print(f'Current cells: {len(notebook["cells"])}')
print('\n📋 All markdown headers:')
for idx, cell in enumerate(notebook['cells']):
    if cell['cell_type'] == 'markdown':
        content = ''.join(cell.get('source', []))
        if '##' in content:
            lines = content.split('\n')
            first_line = lines[0] if lines else ''
            print(f'Cell {idx}: {first_line[:80]}')

# Find cells to delete
delete_cells = []
for idx, cell in enumerate(notebook['cells']):
    if cell['cell_type'] == 'markdown':
        content = ''.join(cell.get('source', []))
        
        # Keywords that indicate unwanted sections
        if any(kw in content for kw in ['MEGA AGGRESSIVE', 'NUCLEAR OPTION', 'EMERGENCY STRATEGIES']):
            delete_cells.append(idx)
            print(f'\n⚠️ Will delete Cell {idx}')

print(f'\n\nTotal cells to delete: {len(delete_cells)}')
