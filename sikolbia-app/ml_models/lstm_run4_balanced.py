"""
LSTM RUN 4 - BALANCED REGULARIZATION
=====================================
Strategy: Middle ground between Run 2 (moderate) and Run 3 (extreme)

Run 2: Gap 0.045274 (best so far but still high)
Run 3: Gap 0.068190 (TOO MUCH regularization - worse!)
Run 4: Target gap < 0.020

Changes from Run 2:
- Architecture: 32-64-32 (SAME - proven to work)
- Dropout: 0.35-0.38 (slightly higher than Run 2's 0.30-0.35)
- Recurrent dropout: 0.18 (vs Run 2's 0.15)
- L2: 0.003 (3x stronger than Run 2's 0.001, but not extreme)
- Learning rate: 0.0004 (between Run 2's 0.0005 and Run 3's 0.0003)
- Batch size: 56 (between Run 2's 48 and Run 3's 64)
- Early stop patience: 28 (between Run 2's 30 and Run 3's 25)
"""

# ===========================
# CELL 18 REPLACEMENT
# ===========================

def build_lstm_model_balanced_regularization(input_shape, units=[32, 64, 32]):
    """
    BALANCED REGULARIZATION - Middle ground between moderate and extreme
    
    Architecture: 32-64-32 (proven in Run 2)
    Dropout: 0.35-0.38 (slightly increased)
    L2: 0.003 (3x Run 2, but not extreme)
    Learning rate: 0.0004 (balanced)
    """
    from tensorflow.keras import regularizers
    
    # L2 regularization strength - balanced between Run 2 and Run 3
    l2_strength = 0.003  # 3x stronger than Run 2 (0.001), but 3.3x weaker than Run 3 (0.01)
    
    model = keras.Sequential([
        # First LSTM layer
        keras.layers.LSTM(
            units[0],
            return_sequences=True,
            input_shape=input_shape,
            kernel_regularizer=regularizers.l2(l2_strength),
            recurrent_regularizer=regularizers.l2(l2_strength),
            recurrent_dropout=0.18,  # Increased from 0.15
            dropout=0.35  # Increased from 0.30
        ),
        keras.layers.BatchNormalization(),
        keras.layers.Dropout(0.35),
        
        # Second LSTM layer
        keras.layers.LSTM(
            units[1],
            return_sequences=True,
            kernel_regularizer=regularizers.l2(l2_strength),
            recurrent_regularizer=regularizers.l2(l2_strength),
            recurrent_dropout=0.18,
            dropout=0.36  # Slightly progressive
        ),
        keras.layers.BatchNormalization(),
        keras.layers.Dropout(0.36),
        
        # Third LSTM layer
        keras.layers.LSTM(
            units[2],
            return_sequences=False,
            kernel_regularizer=regularizers.l2(l2_strength),
            recurrent_regularizer=regularizers.l2(l2_strength),
            recurrent_dropout=0.18,
            dropout=0.37
        ),
        keras.layers.BatchNormalization(),
        keras.layers.Dropout(0.37),
        
        # Dense layers
        keras.layers.Dense(
            64,
            activation='relu',
            kernel_regularizer=regularizers.l2(l2_strength)
        ),
        keras.layers.Dropout(0.38),
        
        keras.layers.Dense(
            32,
            activation='relu',
            kernel_regularizer=regularizers.l2(l2_strength)
        ),
        keras.layers.Dropout(0.38),
        
        # Output layer
        keras.layers.Dense(1, activation='linear')
    ])
    
    # Compile with balanced learning rate
    model.compile(
        optimizer=keras.optimizers.Adam(
            learning_rate=0.0004,  # Between Run 2 (0.0005) and Run 3 (0.0003)
            clipnorm=1.0
        ),
        loss='huber',
        metrics=['mae']
    )
    
    return model

# Build model
print("="*80)
print("LSTM ARCHITECTURE - BALANCED REGULARIZATION (RUN 4)")
print("="*80)

LSTM_UNITS_BALANCED = [32, 64, 32]
model_lstm = build_lstm_model_balanced_regularization(
    input_shape=(SEQUENCE_WINDOW, X_train_seq.shape[2]),
    units=LSTM_UNITS_BALANCED
)

model_lstm.summary()

# Calculate parameters
total_params = model_lstm.count_params()
trainable_params = sum([np.prod(v.shape) for v in model_lstm.trainable_weights])
non_trainable_params = total_params - trainable_params

print("\n" + "="*80)
print("BALANCED REGULARIZATION APPLIED:")
print("="*80)
print(f"✅ Architecture: {LSTM_UNITS_BALANCED[0]}-{LSTM_UNITS_BALANCED[1]}-{LSTM_UNITS_BALANCED[2]} (PROVEN in Run 2)")
print(f"✅ L2 regularization: 0.003 (3x Run 2, but not extreme)")
print(f"✅ Dropout: 0.35-0.38 (slightly increased from Run 2)")
print(f"✅ Recurrent dropout: 0.18 (vs Run 2's 0.15)")
print(f"✅ Learning rate: 0.0004 (balanced)")
print(f"✅ Gradient clipping: clipnorm=1.0")

print(f"\n📊 PARAMETER ANALYSIS:")
print(f"   Total parameters: {total_params:,}")
print(f"   Training samples: {len(X_train_seq):,}")
print(f"   Params per sample: {total_params / len(X_train_seq):.2f}")
params_per_sample = total_params / len(X_train_seq)
if params_per_sample < 20:
    print(f"   ✅ Excellent ratio: Very appropriate for dataset")
elif params_per_sample < 30:
    print(f"   ✅ Good ratio: Appropriate for dataset")
else:
    print(f"   ⚠️  High ratio: May need more data")

print("\n💡 STRATEGY:")
print("   - Proven architecture from Run 2 (32-64-32)")
print("   - Slightly stronger regularization than Run 2")
print("   - Not as extreme as Run 3 (which over-regularized)")
print("   - Target: Gap < 0.020 (better than Run 2's 0.045)")
print("="*80)


# ===========================
# CELL 19 REPLACEMENT
# ===========================

print("\n🔥 Training LSTM (BALANCED REGULARIZATION - RUN 4)...")
print(f"   Architecture: {LSTM_UNITS_BALANCED[0]}-{LSTM_UNITS_BALANCED[1]}-{LSTM_UNITS_BALANCED[2]}")
print(f"   Batch size: 56 (BALANCED between Run 2 and Run 3)")
print(f"   Learning rate: 0.0004 (BALANCED)")
print(f"   Dropout: 0.35-0.38 (SLIGHTLY increased)")
print(f"   L2 regularization: 0.003 (3x stronger than Run 2)")
print(f"   Expected training time: ~8-10 minutes")

# Training configuration - BALANCED
BATCH_SIZE_BALANCED = 56  # Between Run 2 (48) and Run 3 (64)

# Callbacks
early_stop = keras.callbacks.EarlyStopping(
    monitor='val_loss',
    patience=28,  # Between Run 2 (30) and Run 3 (25)
    restore_best_weights=True,
    verbose=1
)

reduce_lr = keras.callbacks.ReduceLROnPlateau(
    monitor='val_loss',
    factor=0.5,
    patience=9,  # Slightly more patient than Run 2 (10)
    min_lr=1e-6,
    verbose=1
)

model_checkpoint = keras.callbacks.ModelCheckpoint(
    'best_lstm_model_balanced.keras',
    monitor='val_loss',
    save_best_only=True,
    verbose=0
)

# Train model
history_lstm = model_lstm.fit(
    X_train_seq, y_train_lstm_scaled,
    validation_data=(X_val_seq, y_val_lstm_scaled),
    epochs=300,
    batch_size=BATCH_SIZE_BALANCED,
    callbacks=[early_stop, reduce_lr, model_checkpoint],
    verbose=1
)

# Get training metrics
train_loss = history_lstm.history['loss'][-1]
val_loss = history_lstm.history['val_loss'][-1]
best_val_loss = min(history_lstm.history['val_loss'])
best_epoch = history_lstm.history['val_loss'].index(best_val_loss) + 1

# Calculate overfitting gap
overfitting_gap = val_loss - train_loss

print("\n" + "="*80)
print("✅ LSTM TRAINING COMPLETE!")
print(f"   Best epoch: {best_epoch}")
print(f"   Best val_loss: {best_val_loss:.6f}")
print("="*80)

print(f"\n📊 TRAINING METRICS:")
print(f"   Final train loss: {train_loss:.6f}")
print(f"   Final val loss:   {val_loss:.6f}")
print(f"   Best val loss:    {best_val_loss:.6f}")
print(f"   Overfitting gap:  {overfitting_gap:.6f}")

# Evaluate overfitting with detailed thresholds
if overfitting_gap < 0.010:
    print(f"   ✅✅ Excellent generalization (OPTIMAL)")
elif overfitting_gap < 0.015:
    print(f"   ✅ Very good generalization (ACCEPTABLE)")
elif overfitting_gap < 0.025:
    print(f"   ⚠️  Good generalization (BORDERLINE)")
elif overfitting_gap < 0.040:
    print(f"   ⚠️  Moderate overfitting (needs improvement)")
else:
    print(f"   ❌ High overfitting (needs more regularization)")

print(f"   💡 Gap threshold: < 0.015 for production use")

# Compare with previous runs
print(f"\n📈 IMPROVEMENT HISTORY:")
print(f"   Run 1 (baseline):   Gap 0.047588")
print(f"   Run 2 (moderate):   Gap 0.045274 (reduced 4.9%)")
print(f"   Run 3 (extreme):    Gap 0.068190 (WORSE - over-regularized)")
print(f"   Run 4 (balanced):   Gap {overfitting_gap:.6f}")

if overfitting_gap < 0.045274:
    improvement = ((0.045274 - overfitting_gap) / 0.045274) * 100
    print(f"   ✅ Improved by: {improvement:.1f}% vs Run 2!")
else:
    worsening = ((overfitting_gap - 0.045274) / 0.045274) * 100
    print(f"   ❌ Worsened by: {worsening:.1f}% vs Run 2")

# Gap to train loss ratio
gap_ratio = overfitting_gap / train_loss if train_loss > 0 else float('inf')
print(f"\n📏 GAP TO TRAIN LOSS RATIO: {gap_ratio:.1f}x")
if gap_ratio < 5:
    print(f"   ✅✅ Excellent: Gap < 5x train loss")
elif gap_ratio < 10:
    print(f"   ✅ Good: Gap < 10x train loss")
else:
    print(f"   ⚠️  High: Gap > 10x train loss")

print("="*80)
print()
