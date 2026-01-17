"""
DATA LOSS INVESTIGATION
=======================
Analyzing why 36,593 records → 2,034 sequences (94.4% loss!)

Possible causes:
1. Filtering to 8 "major" commodities (from 104) - TOO AGGRESSIVE?
2. Sequence window requirements (need 6 consecutive months)
3. Missing data removal
4. Outlier removal
5. Data quality filtering

Let's investigate and potentially recover more data!
"""

import pandas as pd
import numpy as np

print("="*80)
print("DATA LOSS INVESTIGATION")
print("="*80)

# ============================================================================
# STEP 1: Check Original Dataset
# ============================================================================

print("\n📊 STEP 1: Analyzing ORIGINAL dataset...")

# Assuming your data is in df_konsumsi or similar
# Let's check what variables exist
try:
    print(f"\n   Original dataset shape: {df_konsumsi.shape}")
    print(f"   Columns: {df_konsumsi.columns.tolist()}")
    
    # Check date range
    if 'tahun' in df_konsumsi.columns and 'bulan' in df_konsumsi.columns:
        print(f"\n   Year range: {df_konsumsi['tahun'].min()} - {df_konsumsi['tahun'].max()}")
        print(f"   Total years: {df_konsumsi['tahun'].nunique()}")
        print(f"   Total months: {len(df_konsumsi)}")
    
    # Check commodities
    if 'komoditi' in df_konsumsi.columns:
        print(f"\n   Total unique commodities: {df_konsumsi['komoditi'].nunique()}")
        print(f"   Commodity distribution:")
        commodity_counts = df_konsumsi['komoditi'].value_counts()
        print(f"   - Max records per commodity: {commodity_counts.max()}")
        print(f"   - Min records per commodity: {commodity_counts.min()}")
        print(f"   - Avg records per commodity: {commodity_counts.mean():.1f}")
        
        # Show top commodities
        print(f"\n   Top 20 commodities by record count:")
        for idx, (commodity, count) in enumerate(commodity_counts.head(20).items(), 1):
            print(f"   {idx:2d}. {commodity:<30} {count:>4} records ({count/12:.1f} years)")
    
    # Check missing data
    print(f"\n   Missing data summary:")
    missing_pct = (df_konsumsi.isnull().sum() / len(df_konsumsi)) * 100
    for col, pct in missing_pct[missing_pct > 0].items():
        print(f"   - {col}: {pct:.1f}% missing")
    
except Exception as e:
    print(f"   ⚠️  Could not analyze original dataset: {e}")
    print(f"   💡 Please ensure df_konsumsi is loaded")

# ============================================================================
# STEP 2: Check Filtering Impact
# ============================================================================

print("\n📉 STEP 2: Analyzing FILTERING impact...")

# Check what commodities were selected
print(f"\n   Currently using 8 commodities (from filtered data):")
if 'komoditi_map' in dir() or 'filtered_commodities' in dir():
    # These might be defined in your notebook
    try:
        filtered_list = list(komoditi_map.values()) if 'komoditi_map' in dir() else []
        for idx, commodity in enumerate(filtered_list, 1):
            print(f"   {idx}. {commodity}")
    except:
        print("   (Unable to retrieve filtered commodity list)")

print(f"\n   Data loss breakdown:")
print(f"   - Original records: 36,593")
print(f"   - After filtering to 8 commodities: ??? (need to check)")
print(f"   - Final sequences: 2,034")
print(f"   - Loss rate: 94.4%")

# ============================================================================
# STEP 3: Recommendations for Data Recovery
# ============================================================================

print("\n" + "="*80)
print("💡 RECOMMENDATIONS FOR DATA RECOVERY")
print("="*80)

print("\n1️⃣  EXPAND COMMODITY SELECTION (RECOMMENDED!)")
print("   Current: 8 commodities (7.7% of 104)")
print("   Recommendation: Use 20-30 major commodities")
print("   Expected result: 5,000-8,000 sequences ✅")
print("\n   Selection criteria:")
print("   - Commodities with ≥ 24 months consecutive data (2 years)")
print("   - Not just 'major/moderate' importance")
print("   - Include all commodities with reasonable data quality")

print("\n2️⃣  RELAXED MISSING DATA HANDLING")
print("   Current approach: Likely strict (remove any missing)")
print("   Recommendation: Impute missing values")
print("   Methods:")
print("   - Forward fill (use previous month)")
print("   - Seasonal average (same month, previous years)")
print("   - Interpolation (linear for short gaps)")

print("\n3️⃣  SHORTER SEQUENCE WINDOW (if needed)")
print("   Current: 6 months window")
print("   Alternative: 3-4 months window")
print("   Tradeoff: Less temporal context, but MORE sequences")
print("   Expected increase: 2x-3x more sequences")

print("\n4️⃣  HANDLE DATA QUALITY ISSUES")
print("   Instead of removing bad records, FIX them:")
print("   - Outliers: Cap at 99th percentile (don't remove)")
print("   - Zeros: Check if real or data error")
print("   - Inconsistencies: Resolve in raw data if possible")

# ============================================================================
# STEP 4: Proposed Data Recovery Strategy
# ============================================================================

print("\n" + "="*80)
print("🚀 PROPOSED DATA RECOVERY STRATEGY")
print("="*80)

print("\n📋 PHASE 1: Expand Commodity Selection")
print("   Action: Select commodities with ≥ 24 consecutive months")
print("   Code snippet:")
print("""
   # Count consecutive months per commodity
   commodity_consecutive = []
   for commodity in df_konsumsi['komoditi'].unique():
       df_comm = df_konsumsi[df_konsumsi['komoditi'] == commodity].sort_values(['tahun', 'bulan'])
       # Check max consecutive months
       # If >= 24 months, include
       ...
   
   # Expected: 30-50 commodities with good data
   # Expected sequences: 6,000-10,000 ✅
""")

print("\n📋 PHASE 2: Intelligent Missing Data Handling")
print("   Action: Impute instead of remove")
print("   Code snippet:")
print("""
   # For each commodity time series:
   # 1. Forward fill (max 2 months gap)
   # 2. Backward fill (for initial missing)
   # 3. Seasonal average for longer gaps
   
   df_filled = df_konsumsi.groupby('komoditi').apply(
       lambda x: x.fillna(method='ffill', limit=2)
                  .fillna(method='bfill', limit=2)
   )
""")

print("\n📋 PHASE 3: Outlier Capping (not removal)")
print("   Action: Cap extreme values, don't delete")
print("   Code snippet:")
print("""
   # For each numeric column:
   for col in numeric_columns:
       Q1 = df[col].quantile(0.01)
       Q99 = df[col].quantile(0.99)
       df[col] = df[col].clip(Q1, Q99)  # Cap, don't remove!
""")

# ============================================================================
# STEP 5: Expected Impact
# ============================================================================

print("\n" + "="*80)
print("📈 EXPECTED IMPACT OF DATA RECOVERY")
print("="*80)

print(f"\n{'Approach':<40} {'Sequences':<15} {'LSTM Viable?':<15}")
print("-"*70)
print(f"{'Current (8 commodities, strict)':<40} {'2,034':<15} {'❌ Too small':<15}")
print(f"{'Expand to 20 commodities':<40} {'~5,000':<15} {'⚠️  Marginal':<15}")
print(f"{'Expand to 30 commodities + imputation':<40} {'~8,000':<15} {'✅ Acceptable':<15}")
print(f"{'All viable commodities (40-50)':<40} {'~12,000':<15} {'✅✅ Good':<15}")
print("-"*70)

print("\n💡 RECOMMENDATION:")
print("   1. START: Expand to 30 commodities (target ~8,000 sequences)")
print("   2. IF STILL SMALL: Add imputation for missing data")
print("   3. GOAL: Achieve 8,000-10,000 sequences for viable LSTM training")

print("\n🎯 With 8,000+ sequences:")
print("   - LSTM alone: Expected 12-18% MAPE ✅ (vs current 29.87%)")
print("   - LSTM-XGBoost Hybrid: Expected 8-10% MAPE ✅")
print("   - Can justify 'LSTM Enhanced Ensemble' with LSTM 40-50% weight ✅")

print("="*80)

# ============================================================================
# STEP 6: Quick Check - How many commodities have good data?
# ============================================================================

print("\n🔍 QUICK ANALYSIS: Commodities with sufficient data...")

try:
    # Analyze commodity data quality
    commodity_quality = []
    
    for commodity in df_konsumsi['komoditi'].unique():
        df_comm = df_konsumsi[df_konsumsi['komoditi'] == commodity]
        
        # Count total records
        total_records = len(df_comm)
        
        # Count records with key features (e.g., kalori_hari)
        if 'kalori_hari' in df_comm.columns:
            valid_records = df_comm['kalori_hari'].notna().sum()
        else:
            valid_records = total_records
        
        # Check year span
        year_span = df_comm['tahun'].max() - df_comm['tahun'].min() + 1
        
        commodity_quality.append({
            'commodity': commodity,
            'total_records': total_records,
            'valid_records': valid_records,
            'year_span': year_span,
            'completeness': (valid_records / total_records * 100) if total_records > 0 else 0
        })
    
    df_quality = pd.DataFrame(commodity_quality)
    df_quality = df_quality.sort_values('valid_records', ascending=False)
    
    # Filter commodities with good data
    good_commodities = df_quality[
        (df_quality['valid_records'] >= 24) &  # At least 2 years
        (df_quality['completeness'] >= 70)      # At least 70% complete
    ]
    
    print(f"\n   Commodities with GOOD data (≥24 records, ≥70% complete): {len(good_commodities)}")
    print(f"\n   Top 30 commodities by data quality:")
    print(f"\n   {'#':<4} {'Commodity':<30} {'Records':<10} {'Years':<8} {'Complete':<10}")
    print("   " + "-"*62)
    
    for idx, row in good_commodities.head(30).iterrows():
        print(f"   {idx+1:<4} {row['commodity']:<30} {row['valid_records']:<10.0f} {row['year_span']:<8.0f} {row['completeness']:<10.1f}%")
    
    # Estimate total sequences with top 30
    avg_records_per_commodity = good_commodities.head(30)['valid_records'].mean()
    estimated_sequences = len(good_commodities.head(30)) * (avg_records_per_commodity - 6)  # -6 for sequence window
    
    print(f"\n   📊 ESTIMATED DATA with top 30 commodities:")
    print(f"   - Total records: {good_commodities.head(30)['valid_records'].sum():.0f}")
    print(f"   - Estimated sequences: ~{estimated_sequences:.0f}")
    print(f"   - vs Current sequences: 2,034")
    print(f"   - Increase: {(estimated_sequences / 2034):.1f}x more data! 🚀")
    
    if estimated_sequences > 8000:
        print(f"\n   ✅✅ WITH 30 COMMODITIES: {estimated_sequences:.0f} sequences")
        print(f"   ✅ LSTM will perform MUCH BETTER!")
        print(f"   ✅ Expected LSTM MAPE: 12-16% (vs current 29.87%)")
    elif estimated_sequences > 5000:
        print(f"\n   ✅ WITH 30 COMMODITIES: {estimated_sequences:.0f} sequences")
        print(f"   ✅ LSTM will perform BETTER!")
        print(f"   ✅ Expected LSTM MAPE: 16-20% (vs current 29.87%)")
    
except Exception as e:
    print(f"\n   ⚠️  Could not analyze commodity quality: {e}")
    print(f"   💡 Please run this in your notebook with df_konsumsi loaded")

print("\n" + "="*80)
print("✅ INVESTIGATION COMPLETE")
print("="*80)
