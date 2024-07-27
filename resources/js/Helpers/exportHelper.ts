/**
 * Handles the export of data to CSV format.
 * 
 * @param data - The data to be exported.
 * @param columns - The columns to be included in the export.
 * @param fileName - The name of the file to be downloaded.
 */
export const handleExport = (data: Record<string, string>[], header: { key: string, label: string }[], fileName: string): void => {
    const csvData = arrayToCSV(data, header);
    downloadCSV(csvData, fileName);
};

/**
 * Converts array-data to a CSV-formatted string.
 * 
 * @param data - The data to be converted.
 * @param columns - The columns to be included in the CSV.
 * @returns A CSV-formatted string.
 */
const arrayToCSV = (data: Record<string, any>[], columns: { key: string; label: string }[]): string => {
    const headers = columns.map(col => `"${col.label}"`).join(';');

    const rows = data.map(row =>
        columns.map(col => {
            let value = row[col.key] || ''; // Use empty string if value is undefined or null
            if (typeof value === 'string') {
                value = `"${value.replace(/"/g, '""')}"`;
            }
            return value;
        }).join(';')
    );

    return [headers, ...rows].join('\n');
};

/**
 * Downloads CSV data as a file.
 * 
 * @param csvData - The CSV-formatted data.
 * @param title - The title to be used for the downloaded file.
 */
const downloadCSV = (csvData: string, title: string): void => {
    const blob = new Blob([csvData], { type: 'text/csv;charset=utf-8' });
    const url = window.URL.createObjectURL(blob);

    const a = document.createElement('a');
    a.href = url;
    a.download = `${title}.csv`;

    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);

    window.URL.revokeObjectURL(url);
};
