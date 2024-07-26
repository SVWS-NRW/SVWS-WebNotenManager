/**
 * Handles the export of data to CSV format.
 * 
 * @param data - The data to be exported.
 * @param columns - The columns to be included in the export.
 * @param fileName - The name of the file to be downloaded.
 */
export const handleExport = (data: Record<string, any>[], columns: string[], fileName: string): void => {
    // Convert data to CSV and download
    const csvData = arrayToCSV(data, columns);
    downloadCSV(csvData, fileName);
};

/**
 * Converts array-data to a CSV-formatted string.
 * 
 * @param data - The data to be converted.
 * @param columns - The columns to be included in the CSV.
 * @returns A CSV-formatted string.
 */
const arrayToCSV = (data: Record<string, any>[], columns: string[]): string => {
    // Map data to array of arrays format
    const arrayData = data.map(row => 
        columns.map(col => {
            const value = row[col];
            if (value === undefined || value === null) {
                return "";
            }
            return value;
        })
    );

    // Combine columns header and data
    const allData = [columns, ...arrayData];

    // Convert array of arrays to CSV string
    return allData.map(row => row.map(String).map(value => `"${value.replace(/"/g, '""')}"`).join(';')).join('\n');
};

/**
 * Downloads CSV data as a file.
 * 
 * @param csvData - The CSV-formatted data.
 * @param title - The title to be used for the downloaded file.
 */
const downloadCSV = (csvData: string, title: string): void => {
    // Create a Blob from the CSV data
    const blob = new Blob([csvData], { type: 'text/csv' });

    // Create a URL for the Blob
    const url = window.URL.createObjectURL(blob);

    // Create a link element for the download
    const a = document.createElement('a');
    a.href = url;
    a.download = title + '.csv';

    // Append the link to the body, trigger a click, and remove the link
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);

    // Revoke the Object URL to free up resources
    window.URL.revokeObjectURL(url);
};
