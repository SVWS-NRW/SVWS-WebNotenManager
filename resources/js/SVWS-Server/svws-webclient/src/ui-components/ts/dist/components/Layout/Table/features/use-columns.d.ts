import type { DataTableColumn, DataTableItem } from "../types";
export declare const buildTableColumn: (source: DataTableColumn | string) => DataTableColumn;
export default function useColumns(items: DataTableItem[], columns: DataTableColumn[]): {
    columnsComputed: import("vue").ComputedRef<DataTableColumn[]>;
};
//# sourceMappingURL=use-columns.d.ts.map