import { PropType } from "vue";
declare type Item = {
    id: number;
    text: string;
};
declare const _default: import("vue").DefineComponent<{
    placeholder: {
        type: StringConstructor;
        default: string;
    };
    required: {
        type: BooleanConstructor;
        default: boolean;
    };
    tags: {
        type: BooleanConstructor;
        default: boolean;
    };
    autocomplete: {
        type: BooleanConstructor;
        default: boolean;
    };
    disabled: {
        type: BooleanConstructor;
        default: boolean;
    };
    inline: {
        type: BooleanConstructor;
        default: boolean;
    };
    statistics: {
        type: BooleanConstructor;
        default: boolean;
    };
    items: {
        type: PropType<Item[]>;
        required: true;
        default: () => never[];
    };
    itemText: {
        type: FunctionConstructor;
        default: (item: Item) => string;
    };
    itemSort: {
        type: PropType<(a: Item, b: Item) => number>;
        default: undefined;
    };
    title: {
        default: string;
        type: StringConstructor;
    };
    modelValue: {
        type: (StringConstructor | NumberConstructor | ObjectConstructor | ArrayConstructor)[];
        default: () => null;
    };
    itemFilter: {
        type: FunctionConstructor;
        default: undefined;
    };
}, unknown, {
    selected_item: unknown;
    selected_item_list: unknown[];
    selected_item_index: number;
    focused: boolean;
    input: boolean;
    search_text: string;
    itemRefs: never[];
}, {
    search: {
        get(): string;
        set(v: string): void;
    };
    list(): boolean;
    sorted_list(): Array<Item>;
    filtered_list(): Array<Item>;
}, {
    onUpdate(event: string): void;
    select(): void;
    select_item(item: Item): void;
    tag_remove(index: number): void;
    onFocus(event: Event): void;
    onClickTags(): void;
    blur(): void;
    onBlur(): void;
    onArrowDown(): void;
    onArrowUp(): void;
    scrollToActiveItem(): void;
}, import("vue").ComponentOptionsMixin, import("vue").ComponentOptionsMixin, ("update:modelValue" | "focus" | "blur")[], "update:modelValue" | "focus" | "blur", import("vue").VNodeProps & import("vue").AllowedComponentProps & import("vue").ComponentCustomProps, Readonly<import("vue").ExtractPropTypes<{
    placeholder: {
        type: StringConstructor;
        default: string;
    };
    required: {
        type: BooleanConstructor;
        default: boolean;
    };
    tags: {
        type: BooleanConstructor;
        default: boolean;
    };
    autocomplete: {
        type: BooleanConstructor;
        default: boolean;
    };
    disabled: {
        type: BooleanConstructor;
        default: boolean;
    };
    inline: {
        type: BooleanConstructor;
        default: boolean;
    };
    statistics: {
        type: BooleanConstructor;
        default: boolean;
    };
    items: {
        type: PropType<Item[]>;
        required: true;
        default: () => never[];
    };
    itemText: {
        type: FunctionConstructor;
        default: (item: Item) => string;
    };
    itemSort: {
        type: PropType<(a: Item, b: Item) => number>;
        default: undefined;
    };
    title: {
        default: string;
        type: StringConstructor;
    };
    modelValue: {
        type: (StringConstructor | NumberConstructor | ObjectConstructor | ArrayConstructor)[];
        default: () => null;
    };
    itemFilter: {
        type: FunctionConstructor;
        default: undefined;
    };
}>> & {
    onFocus?: ((...args: any[]) => any) | undefined;
    onBlur?: ((...args: any[]) => any) | undefined;
    "onUpdate:modelValue"?: ((...args: any[]) => any) | undefined;
}, {
    title: string;
    disabled: boolean;
    required: boolean;
    modelValue: string | number | Record<string, any> | unknown[];
    statistics: boolean;
    placeholder: string;
    inline: boolean;
    tags: boolean;
    autocomplete: boolean;
    items: Item[];
    itemText: Function;
    itemSort: (a: Item, b: Item) => number;
    itemFilter: Function;
}>;
export default _default;
//# sourceMappingURL=MultiSelect.vue.d.ts.map