#!/bin/bash

# 
# colocar links aleatorios no notastreino.csv
#

# Source and target files
SOURCE_FILE="listaLinks.txt"
TARGET_CSV="notastreino.csv"
TEMP_FILE="temp_target.csv"

# Get all lines from the source file and shuffle them
SHUFFLED_LINES=$(shuf "$SOURCE_FILE")

# Get the number of lines in the target CSV
NUM_LINES=$(wc -l < "$TARGET_CSV")

# Add a new column to each line by pasting the shuffled lines
paste -d ';' "$TARGET_CSV" <(echo "$SHUFFLED_LINES" | head -n "$NUM_LINES") > "$TEMP_FILE"

# Replace the target CSV with the updated one
mv "$TEMP_FILE" "$TARGET_CSV"