import pandas as pd

# Path to your Excel file
file_path = r"C:\Users\Asus\Downloads\List of AHS Colleges (1) (2).xlsx"

# Output text file path
output_file = r"C:\Users\Asus\Downloads\college_data_output.txt"

# Sheet names to read
sheets = ["AHS Colleges", "Physitherapy Colleges"]

with open(output_file, 'w', encoding='utf-8') as f:
    for sheet in sheets:
        f.write(f"===== {sheet} =====\n\n")

        # Read full data from sheet
        df = pd.read_excel(file_path, sheet_name=sheet)

        # Drop completely empty rows
        df = df.dropna(how='all')

        # Reset index and write column headers
        df.reset_index(drop=True, inplace=True)
        f.write(" | ".join(df.columns.astype(str)) + "\n")
        f.write("-" * 80 + "\n")

        # Write each row with numbering
        for i, row in df.iterrows():
            row_data = " | ".join(str(val).strip() for val in row)
            f.write(f"{i+1}. {row_data}\n")

        f.write("\n\n")

print("✅ Data from both sheets has been written to:", output_file)
