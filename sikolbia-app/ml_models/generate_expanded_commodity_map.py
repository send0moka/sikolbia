"""
GENERATE EXPANDED COMMODITY MAPPING
====================================
Generate Python code to load top 30 commodities (instead of just 8)
Based on quality analysis results

Usage: python ml_models/generate_expanded_commodity_map.py
"""

import json
import os

# Read analysis results
seeders_path = os.path.join(os.path.dirname(__file__), '..', 'database', 'seeders')
analysis_file = os.path.join(seeders_path, 'recommended_commodities.json')

if not os.path.exists(analysis_file):
    print("ERROR: Please run analyze_sql_quality.php first!")
    print(f"Expected file: {analysis_file}")
    exit(1)

with open(analysis_file, 'r') as f:
    analysis = json.load(f)

top_commodities = analysis['top_30']

print("="*80)
print("EXPANDED COMMODITY MAPPING FOR LSTM TRAINING")
print("="*80)
print(f"Commodities: {len(top_commodities)}")
print(f"Estimated sequences: {analysis['estimated_sequences']:,}")
print()

# Generate Python mapping code
print("# Copy this code to your Colab notebook:")
print()
print("# " + "="*76)
print("# TOP 30 COMMODITIES FOR LSTM TRAINING")
print(f"# Generated from quality analysis: {analysis['analysis_date']}")
print(f"# Expected sequences: {analysis['estimated_sequences']:,}")
print("# " + "="*76)
print()
print("# Commodity mapping (kelompok_komoditi -> nama)")
print("komoditi_map = {")

for commodity in top_commodities:
    code = commodity['code']
    name = commodity['commodity'].replace('_', ' ').title()
    records = commodity['records']
    quality = commodity['quality_score']
    print(f"    '{code}': '{name}',  # {records} records, quality: {quality:.1f}%")

print("}")
print()

# Generate filter code
print("# Filter to top 30 commodities")
print("print(f'\\n� Filtering to {len(komoditi_map)} high-quality commodities...')")
print("df_filtered = df_konsumsi[")
print("    df_konsumsi.apply(")
print("        lambda row: f\"{row['kode_kelompok']}{row['kode_komoditi']}\" in komoditi_map,")
print("        axis=1")
print("    )")
print("].copy()")
print()
print("print(f'✅ Filtered: {len(df_konsumsi):,} → {len(df_filtered):,} records')")
print("print(f'   Commodities: 104 → {df_filtered.apply(lambda row: f\"{row[\"kode_kelompok\"]}{row[\"kode_komoditi\"]}\", axis=1).nunique()}')")
print()

# Generate summary stats
print("# Expected data size")
print(f"# - Original: 36,593 records, 104 commodities")
print(f"# - After filter (8 commodities): 2,034 sequences (5.6%)")
print(f"# - After filter (30 commodities): ~{analysis['estimated_sequences']:,} sequences (~{(analysis['estimated_sequences']/36593)*100:.1f}%)")
print(f"# - Data increase: {analysis['estimated_sequences']/2034:.1f}x more!")
print()

# Generate quality thresholds
print("# Commodity quality categories:")
excellent = [c for c in top_commodities if c['quality_score'] >= 80]
good = [c for c in top_commodities if 60 <= c['quality_score'] < 80]
print(f"# - EXCELLENT (≥80%): {len(excellent)} commodities")
print(f"# - GOOD (60-80%): {len(good)} commodities")
print()

# Save to file
output_file = 'expanded_commodity_mapping.py'
with open(output_file, 'w', encoding='utf-8') as f:
    f.write("# " + "="*76 + "\n")
    f.write("# EXPANDED COMMODITY MAPPING - TOP 30 COMMODITIES\n")
    f.write(f"# Generated: {analysis['analysis_date']}\n")
    f.write(f"# Expected sequences: {analysis['estimated_sequences']:,}\n")
    f.write("# " + "="*76 + "\n\n")
    
    f.write("komoditi_map = {\n")
    for commodity in top_commodities:
        code = commodity['code']
        name = commodity['commodity'].replace('_', ' ').title()
        records = commodity['records']
        quality = commodity['quality_score']
        f.write(f"    '{code}': '{name}',  # {records} records, {quality:.1f}% quality\n")
    f.write("}\n\n")
    
    f.write("# Commodity codes for quick filtering\n")
    f.write("TOP_30_CODES = [\n")
    for commodity in top_commodities:
        f.write(f"    '{commodity['code']}',\n")
    f.write("]\n\n")
    
    f.write("# Quality tiers\n")
    f.write("EXCELLENT_QUALITY = [  # ≥80% quality\n")
    for c in excellent:
        f.write(f"    '{c['code']}',  # {c['commodity']}\n")
    f.write("]\n\n")
    
    f.write("GOOD_QUALITY = [  # 60-80% quality\n")
    for c in good:
        f.write(f"    '{c['code']}',  # {c['commodity']}\n")
    f.write("]\n")

print(f"✅ Saved expanded mapping to: {output_file}")
print()
print("="*80)
print("NEXT STEPS")
print("="*80)
print("1. Copy the code above to your Colab notebook")
print("2. Replace the old komoditi_map (8 commodities) with new one (30 commodities)")
print("3. Re-run data loading and preprocessing")
print(f"4. Expected result: {analysis['estimated_sequences']:,} sequences (vs 2,034 current)")
print("5. Retrain LSTM - expected MAPE: 12-18% (vs current 29.87%)")
print("="*80)
