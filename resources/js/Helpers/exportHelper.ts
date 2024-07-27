import { Leistung } from '@/Interfaces/Interface';
import { DataTableColumn } from "@svws-nrw/svws-ui";

/**
 * Exports tabledata as a CSV file.
 * 
 * @param cols - The visible columns of the table.
 * @param hiddenColumns - The columns that are hidden.
 * @param rowsFiltered - The filtered data for export.
 * @param fileName - The name of the file to be downloaded.
 */
export const exportDataToCSV = (cols: DataTableColumn[], hiddenColumns: Set<string>, rowsFiltered: Leistung[], fileName: string): void => {
    const visibleColumns = cols.filter(col => !hiddenColumns.has(col.key));
    const keyAndLabel = visibleColumns.map(col => ({ key: col.key, label: col.label || col.key }));

    const exportData: Record<string, string>[] = rowsFiltered.map(row =>
        keyAndLabel.reduce((filteredRow: Record<string, string>, col) => {
            const value = row[col.key];
            filteredRow[col.key] = value === undefined || value === null ? '' : String(value);
            return filteredRow;
        }, {})
    );

    downloadCSV(arrayToCSV(exportData, keyAndLabel), fileName);
};


/**
 * Converts array data to a CSV-formatted string.
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
                value = value.replace(/"/g, '""');
            }
            return `"${value}"`;
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