import { PropType } from "vue";
declare type Option = {
    index: string;
    label: string;
    selected?: boolean;
    disabled?: boolean;
};
declare const _default: import("vue").DefineComponent<{
    placeholder: {
        type: StringConstructor;
        default: string;
    };
    options: {
        type: PropType<Option[]>;
        default: () => never[];
    };
    valid: {
        type: BooleanConstructor;
        default: boolean;
    };
    disabled: {
        type: BooleanConstructor;
        default: boolean;
    };
}, unknown, {
    focused: boolean;
    value: string;
}, {}, {
    focus(): void;
    blur(): void;
    hasFocus(): boolean;
    onChange(event: Event): void;
    onFocus(event: Event): void;
    onBlur(event: Event): void;
    onClick(event: MouseEvent): void;
    onMouseDown(event: MouseEvent): void;
    onKeyDown(event: InputEvent): void;
}, import("vue").ComponentOptionsMixin, import("vue").ComponentOptionsMixin, ("click" | "focus" | "blur" | "mousedown" | "keydown" | "update:value")[], "click" | "focus" | "blur" | "mousedown" | "keydown" | "update:value", import("vue").VNodeProps & import("vue").AllowedComponentProps & import("vue").ComponentCustomProps, Readonly<import("vue").ExtractPropTypes<{
    placeholder: {
        type: StringConstructor;
        default: string;
    };
    options: {
        type: PropType<Option[]>;
        default: () => never[];
    };
    valid: {
        type: BooleanConstructor;
        default: boolean;
    };
    disabled: {
        type: BooleanConstructor;
        default: boolean;
    };
}>> & {
    onFocus?: ((...args: any[]) => any) | undefined;
    onBlur?: ((...args: any[]) => any) | undefined;
    onKeydown?: ((...args: any[]) => any) | undefined;
    onClick?: ((...args: any[]) => any) | undefined;
    onMousedown?: ((...args: any[]) => any) | undefined;
    "onUpdate:value"?: ((...args: any[]) => any) | undefined;
}, {
    disabled: boolean;
    placeholder: string;
    valid: boolean;
    options: Option[];
}>;
export default _default;
//# sourceMappingURL=SelectInput.vue.d.ts.map