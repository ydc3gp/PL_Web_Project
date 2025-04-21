import csv
import sys

def extract_specific_columns(input_file, output_file):
    # Columns to extract (0-indexed)
    columns_to_extract = [0, 3, 4, 5, 8, 10, 15, 16, 17, 20, 24, 25]
    
    try:
        # Open the input CSV file for reading
        with open(input_file, 'r', newline='') as infile:
            reader = csv.reader(infile)
            
            # Open the output CSV file for writing
            with open(output_file, 'w', newline='') as outfile:
                writer = csv.writer(outfile)
                
                # Process each row in the input file
                for row in reader:
                    # Create a new row with only the specified columns
                    new_row = []
                    for col_index in columns_to_extract:
                        # Check if the row has this many columns
                        if col_index < len(row):
                            new_row.append(row[col_index])
                        else:
                            # If column doesn't exist in this row, add empty string
                            new_row.append("")
                    
                    # Write the new row to the output file
                    writer.writerow(new_row)
                    
        print(f"Successfully created {output_file} with only the specified columns from {input_file}.")
        return True
    
    except FileNotFoundError:
        print(f"Error: The file '{input_file}' was not found.")
        return False
    except PermissionError:
        print(f"Error: Permission denied when accessing '{input_file}' or '{output_file}'.")
        return False
    except Exception as e:
        print(f"An error occurred: {str(e)}")
        return False

if __name__ == "__main__":
    # Check if command line arguments are provided
    if len(sys.argv) == 3:
        input_file = sys.argv[1]
        output_file = sys.argv[2]
    else:
        # Default filenames
        input_file = "input.csv"
        output_file = "output.csv"
        print(f"Using default filenames: {input_file} and {output_file}")
        print("Usage: python script.py input.csv output.csv")
    
    extract_specific_columns(input_file, output_file)
