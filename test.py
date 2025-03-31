import csv

def process_csv(input_file, output_file):
    with open(input_file, 'r', newline='') as infile, open(output_file, 'w', newline='') as outfile:
        reader = csv.reader(infile)
        writer = csv.writer(outfile)
        
        for row in reader:
            # Only keep the first 6 columns
            if len(row) >= 6:
                writer.writerow(row[:6])
            else:
                # Handle cases where a row might have fewer than 6 columns
                writer.writerow(row + [''] * (6 - len(row)))

if __name__ == "__main__":
    input_file = "small.csv"  # Original CSV file
    output_file = "processed_data.csv"  # Where the trimmed data will be saved
    
    process_csv(input_file, output_file)
    print(f"Processed data saved to {output_file}")
