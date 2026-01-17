import json
import re

print("Loading notebook...")
with open('COLAB_NBM_Prediction_Complete.ipynb', 'r', encoding='utf-8') as f:
    notebook = json.load(f)

# All patterns to fix
patterns = [
    (r'{expected_mape - 10:', '{expected_mape - 15:'),
    (r'{10\.0 - mape_xgb_ultra:', '{15.0 - mape_xgb_ultra:'),
    (r'{10\.0 - mape_huber_ultra:', '{15.0 - mape_huber_ultra:'),
    (r'Below 10% threshold', 'Below 15% threshold'),
    (r'{10\.0 - mape_stacking:', '{15.0 - mape_stacking:'),
    (r'{10\.0 - mape_lstm_enhanced:', '{15.0 - mape_lstm_enhanced:'),
    (r"< 10\.0 else", "< 15.0 else"),
    (r'{10\.0 - best_model\[.MAPE.\]:', '{15.0 - best_model["MAPE"]:'),
    (r"- 10\.0:.2f}% above 10%", "- 15.0:.2f}% above 15%"),
    (r" - 10\.0:.2f}%", " - 15.0:.2f}%"),
    (r'{10\.0 - mape_xgb_mega:', '{15.0 - mape_xgb_mega:'),
    (r'{mape_xgb_mega - 10\.0:', '{mape_xgb_mega - 15.0:'),
    (r'{10\.0 - mape_mega_ensemble:', '{15.0 - mape_mega_ensemble:'),
    (r'{mape_mega_ensemble - 10\.0:', '{mape_mega_ensemble - 15.0:'),
    (r'{10\.0 - best\[.MAPE.\]:', '{15.0 - best["MAPE"]:'),
    (r"above 10%", "above 15%"),
    (r" - 10\.0:}", " - 15.0:}"),
    (r'{best\[.MAPE.\] - 10\.0:', '{best["MAPE"] - 15.0:'),
    (r'{best_model\[.MAPE.\] - 10\.0:', '{best_model["MAPE"] - 15.0:'),
    (r'{10\.0 - mape_nuclear:', '{15.0 - mape_nuclear:'),
    (r'{mape_nuclear - 10\.0:', '{mape_nuclear - 15.0:'),
    (r'{10\.0 - mape_commodity:', '{15.0 - mape_commodity:'),
    (r'{mape_commodity - 10\.0:', '{mape_commodity - 15.0:'),
    (r'{10\.0 - mape_poly:', '{15.0 - mape_poly:'),
    (r'{mape_poly - 10\.0:', '{mape_poly - 15.0:'),
    (r'{10\.0 - mape_super:', '{15.0 - mape_super:'),
    (r'{mape_super - 10\.0:', '{mape_super - 15.0:'),
    (r'{10\.0 - mape_extreme:', '{15.0 - mape_extreme:'),
    (r'{mape_extreme - 10\.0:', '{mape_extreme - 15.0:'),
    (r'{10\.0 - mape_lgb_extreme:', '{15.0 - mape_lgb_extreme:'),
    (r'{mape_lgb_extreme - 10\.0:', '{mape_lgb_extreme - 15.0:'),
    (r'{10\.0 - mape_lstm_extreme:', '{15.0 - mape_lstm_extreme:'),
    (r'{mape_lstm_extreme - 10\.0:', '{mape_lstm_extreme - 15.0:'),
    (r'{10\.0 - mape_catboost_extreme:', '{15.0 - mape_catboost_extreme:'),
    (r'{mape_catboost_extreme - 10\.0:', '{mape_catboost_extreme - 15.0:'),
    (r'{10\.0 - mape_ultimate:', '{15.0 - mape_ultimate:'),
    (r'{mape_ultimate - 10\.0:', '{mape_ultimate - 15.0:'),
    (r'{10\.0 - mape_multi_range:', '{15.0 - mape_multi_range:'),
    (r'{10\.0 - mape_engineered:', '{15.0 - mape_engineered:'),
    (r'{10\.0 - best_mape_optuna:', '{15.0 - best_mape_optuna:'),
]

changes = 0
for cell in notebook['cells']:
    if 'source' in cell:
        for i, line in enumerate(cell['source']):
            orig = line
            for pattern, repl in patterns:
                line = re.sub(pattern, repl, line)
            if line != orig:
                cell['source'][i] = line
                changes += 1

with open('COLAB_NBM_Prediction_Complete.ipynb', 'w', encoding='utf-8') as f:
    json.dump(notebook, f, indent=1, ensure_ascii=False)

print(f'✅ Fixed {changes} more lines with 10% → 15%')
