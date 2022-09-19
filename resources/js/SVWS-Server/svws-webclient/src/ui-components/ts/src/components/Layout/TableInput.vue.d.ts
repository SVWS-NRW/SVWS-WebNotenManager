declare const _default: import("vue").DefineComponent<{
    tableId: {
        type: StringConstructor;
        default: string;
    };
    autofocus: {
        type: BooleanConstructor;
        default: boolean;
    };
    border: {
        type: BooleanConstructor;
        default: boolean;
    };
    multiSelect: {
        type: BooleanConstructor;
        default: boolean;
    };
    cols: {
        type: ArrayConstructor;
        default: () => never[];
    };
    rows: {
        type: ArrayConstructor;
        default: () => never[];
    };
    selected: {
        type: ObjectConstructor;
    };
    footer: {
        type: BooleanConstructor;
        default: boolean;
    };
}, unknown, {
    sort: string;
    asc: boolean;
    sorting: {
        column: string;
        asc: boolean;
    };
    current: {
        data: {};
        selected: boolean;
    };
    items: {
        data: {};
        selected: boolean;
    };
    selectedItems: never[];
}, {}, {
    log(item: any): void;
    changeSort(column: any): void;
    doSort(): void;
    selectAll(): void;
    mousePressed(item: any): void;
    toggleSelect(item: any): void;
    updateData(): void;
    removeFromArray(arr: any, val: any): any;
    changeCurrent(item: any): any;
    onKeyDown(): void;
    onKeyDownSpace(): void;
    onKeyUp(): void;
    focusAndScroll(element: any): void;
}, import("vue").ComponentOptionsMixin, import("vue").ComponentOptionsMixin, ("update:sort" | "update:asc" | "update:selected" | "update:selectedItems")[], "update:sort" | "update:asc" | "update:selected" | "update:selectedItems", import("vue").VNodeProps & import("vue").AllowedComponentProps & import("vue").ComponentCustomProps, Readonly<import("vue").ExtractPropTypes<{
    tableId: {
        type: StringConstructor;
        default: string;
    };
    autofocus: {
        type: BooleanConstructor;
        default: boolean;
    };
    border: {
        type: BooleanConstructor;
        default: boolean;
    };
    multiSelect: {
        type: BooleanConstructor;
        default: boolean;
    };
    cols: {
        type: ArrayConstructor;
        default: () => never[];
    };
    rows: {
        type: ArrayConstructor;
        default: () => never[];
    };
    selected: {
        type: ObjectConstructor;
    };
    footer: {
        type: BooleanConstructor;
        default: boolean;
    };
}>> & {
    "onUpdate:sort"?: ((...args: any[]) => any) | undefined;
    "onUpdate:asc"?: ((...args: any[]) => any) | undefined;
    "onUpdate:selected"?: ((...args: any[]) => any) | undefined;
    "onUpdate:selectedItems"?: ((...args: any[]) => any) | undefined;
}, {
    footer: boolean;
    rows: unknown[];
    border: boolean;
    multiSelect: boolean;
    cols: unknown[];
    tableId: string;
    autofocus: boolean;
}>;
export default _default;
//# sourceMappingURL=TableInput.vue.d.ts.map