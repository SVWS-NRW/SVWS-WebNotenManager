declare const _default: import("vue").DefineComponent<{
    arrows: {
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
    actions: {
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
    slot_open: boolean;
}, {}, {
    open_slot(): void;
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
}, import("vue").ComponentOptionsMixin, import("vue").ComponentOptionsMixin, ("update:sort" | "update:asc" | "update:selected" | "action" | "update:selectedItems")[], "update:sort" | "update:asc" | "update:selected" | "action" | "update:selectedItems", import("vue").VNodeProps & import("vue").AllowedComponentProps & import("vue").ComponentCustomProps, Readonly<import("vue").ExtractPropTypes<{
    arrows: {
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
    actions: {
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
    onAction?: ((...args: any[]) => any) | undefined;
    "onUpdate:selectedItems"?: ((...args: any[]) => any) | undefined;
}, {
    footer: boolean;
    actions: unknown[];
    rows: unknown[];
    arrows: boolean;
    border: boolean;
    multiSelect: boolean;
    cols: unknown[];
}>;
export default _default;
//# sourceMappingURL=Table.vue.d.ts.map