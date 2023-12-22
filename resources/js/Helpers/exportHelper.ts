 import ExcelJS from 'exceljs';

/**
 * Handles the export of data to different formats such as CSV or Excel.
 * @param data - The data to be exported.
 * @param type - The type of export (e.g., 'csv', 'excel').
 * @param fileName - The name of the file to be downloaded.
 */
export const handleExport = (data: Record<string, any>[], type: string, fileName: string): void => {
    // Check the export type and call the corresponding export function
    if (type === 'csv') {
        const csvData = convertToCSV(data)
        downloadCSV(csvData, fileName)
    } else if (type === 'excel') {
        downloadExcel(data, fileName)
    }
};

/**
 * Converts data to a CSV-formatted string.
 * @param data - The data to be converted.
 * @returns A CSV-formatted string.
 */
const convertToCSV = (data: Record<string, any>[]): string => {
    // Check if the data array is empty and handle the case
    if (data.length === 0) {
        return '' // TODO: Handle the case where data is an empty array
    }

    // Generate CSV header and body
    const header = Object.keys(data[0]).join(';') + '\n'
    const body = data.map(item => Object.values(item).join(';')).join('\n')
    return header + body
}

/**
 * Downloads CSV data as a file.
 * @param csvData - The CSV-formatted data.
 * @param title - The title to be used for the downloaded file.
 */
const downloadCSV = (csvData: string, title: String) => {
    // Create a Blob from the CSV data
    const blob = new Blob([csvData], { type: 'text/csv' })
    
    // Create a URL for the Blob
    const url = window.URL.createObjectURL(blob)

    // Create a link element for the download
    const a = document.createElement('a')
    a.href = url
    a.download = title+'.csv'
    
    // Append the link to the body, trigger a click, and remove the link
    document.body.appendChild(a)
    a.click()
    document.body.removeChild(a)

    // Revoke the Object URL to free up resources
    window.URL.revokeObjectURL(url)
}

/**
 * Downloads data in Excel format with selected columns.
 * @param data - The data to be exported to Excel.
 * @param fileName - The name of the Excel file.
 */
const downloadExcel = (data: Record<string, any>[], fileName: string): void => {
    // Use the keys from the first data row as visible columns
    const visibleColumns: string[] = Object.keys(data[0]);

    // Create a new Excel workbook and worksheet
    const workbook = new ExcelJS.Workbook();
    const worksheet = workbook.addWorksheet(fileName);

    // Add column headers to the worksheet
    worksheet.addRow(visibleColumns);

    // Add data rows
    data.forEach(row => {
        const rowData = visibleColumns.map(col => row[col]);
        worksheet.addRow(rowData);
    });

    // Create a Blob for the Excel file
    workbook.xlsx.writeBuffer().then(buffer => {
        const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        const url = window.URL.createObjectURL(blob);

        // Create a link and click it to start the download
        const a = document.createElement('a');
        a.href = url;
        a.download = fileName+'.xlsx';
        a.click();

        // Revoke the Object URL to free up resources
        window.URL.revokeObjectURL(url);
    });
};