declare const _default: {
    new (...args: any[]): {
        $: import("vue").ComponentInternalInstance;
        $data: {};
        $props: Partial<{}> & Omit<Readonly<import("vue").ExtractPropTypes<__VLS_TypePropsToRuntimeProps<{
            type: "number" | "text" | "date" | "email";
            modelValue: string;
            placeholder: string;
            statistics: boolean;
            valid: boolean;
            inline: boolean;
            disabled: boolean;
            required: boolean;
            readonly: boolean;
        }>>> & {
            onFocus?: ((event: Event) => any) | undefined;
            onBlur?: ((event: Event) => any) | undefined;
            onKeydown?: ((event: Event) => any) | undefined;
            onClick?: ((event: Event) => any) | undefined;
            onMousedown?: ((event: Event) => any) | undefined;
            "onUpdate:modelValue"?: ((value: string) => any) | undefined;
        } & import("vue").VNodeProps & import("vue").AllowedComponentProps & import("vue").ComponentCustomProps, never>;
        $attrs: {
            [x: string]: unknown;
        };
        $refs: {
            [x: string]: unknown;
        };
        $slots: Readonly<{
            [name: string]: import("vue").Slot | undefined;
        }>;
        $root: import("vue").ComponentPublicInstance<{}, {}, {}, {}, {}, {}, {}, {}, false, import("vue").ComponentOptionsBase<any, any, any, any, any, any, any, any, any, {}>> | null;
        $parent: import("vue").ComponentPublicInstance<{}, {}, {}, {}, {}, {}, {}, {}, false, import("vue").ComponentOptionsBase<any, any, any, any, any, any, any, any, any, {}>> | null;
        $emit: ((event: "click", event: Event) => void) & ((event: "update:modelValue", value: string) => void) & ((event: "focus", event: Event) => void) & ((event: "blur", event: Event) => void) & ((event: "mousedown", event: Event) => void) & ((event: "keydown", event: Event) => void);
        $el: any;
        $options: import("vue").ComponentOptionsBase<Readonly<import("vue").ExtractPropTypes<__VLS_TypePropsToRuntimeProps<{
            type: "number" | "text" | "date" | "email";
            modelValue: string;
            placeholder: string;
            statistics: boolean;
            valid: boolean;
            inline: boolean;
            disabled: boolean;
            required: boolean;
            readonly: boolean;
        }>>> & {
            onFocus?: ((event: Event) => any) | undefined;
            onBlur?: ((event: Event) => any) | undefined;
            onKeydown?: ((event: Event) => any) | undefined;
            onClick?: ((event: Event) => any) | undefined;
            onMousedown?: ((event: Event) => any) | undefined;
            "onUpdate:modelValue"?: ((value: string) => any) | undefined;
        }, {}, unknown, {}, {}, import("vue").ComponentOptionsMixin, import("vue").ComponentOptionsMixin, {
            "update:modelValue": (value: string) => void;
        } & {
            focus: (event: Event) => void;
        } & {
            blur: (event: Event) => void;
        } & {
            click: (event: Event) => void;
        } & {
            mousedown: (event: Event) => void;
        } & {
            keydown: (event: Event) => void;
        }, string, {}> & {
            beforeCreate?: ((() => void) | (() => void)[]) | undefined;
            created?: ((() => void) | (() => void)[]) | undefined;
            beforeMount?: ((() => void) | (() => void)[]) | undefined;
            mounted?: ((() => void) | (() => void)[]) | undefined;
            beforeUpdate?: ((() => void) | (() => void)[]) | undefined;
            updated?: ((() => void) | (() => void)[]) | undefined;
            activated?: ((() => void) | (() => void)[]) | undefined;
            deactivated?: ((() => void) | (() => void)[]) | undefined;
            beforeDestroy?: ((() => void) | (() => void)[]) | undefined;
            beforeUnmount?: ((() => void) | (() => void)[]) | undefined;
            destroyed?: ((() => void) | (() => void)[]) | undefined;
            unmounted?: ((() => void) | (() => void)[]) | undefined;
            renderTracked?: (((e: import("vue").DebuggerEvent) => void) | ((e: import("vue").DebuggerEvent) => void)[]) | undefined;
            renderTriggered?: (((e: import("vue").DebuggerEvent) => void) | ((e: import("vue").DebuggerEvent) => void)[]) | undefined;
            errorCaptured?: (((err: unknown, instance: import("vue").ComponentPublicInstance<{}, {}, {}, {}, {}, {}, {}, {}, false, import("vue").ComponentOptionsBase<any, any, any, any, any, any, any, any, any, {}>> | null, info: string) => boolean | void) | ((err: unknown, instance: import("vue").ComponentPublicInstance<{}, {}, {}, {}, {}, {}, {}, {}, false, import("vue").ComponentOptionsBase<any, any, any, any, any, any, any, any, any, {}>> | null, info: string) => boolean | void)[]) | undefined;
        };
        $forceUpdate: () => void;
        $nextTick: typeof import("vue").nextTick;
        $watch(source: string | Function, cb: Function, options?: import("vue").WatchOptions<boolean> | undefined): import("vue").WatchStopHandle;
    } & Readonly<import("vue").ExtractPropTypes<__VLS_TypePropsToRuntimeProps<{
        type: "number" | "text" | "date" | "email";
        modelValue: string;
        placeholder: string;
        statistics: boolean;
        valid: boolean;
        inline: boolean;
        disabled: boolean;
        required: boolean;
        readonly: boolean;
    }>>> & {
        onFocus?: ((event: Event) => any) | undefined;
        onBlur?: ((event: Event) => any) | undefined;
        onKeydown?: ((event: Event) => any) | undefined;
        onClick?: ((event: Event) => any) | undefined;
        onMousedown?: ((event: Event) => any) | undefined;
        "onUpdate:modelValue"?: ((value: string) => any) | undefined;
    } & import("vue").ShallowUnwrapRef<{}> & {} & import("vue").ComponentCustomProperties;
    __isFragment?: undefined;
    __isTeleport?: undefined;
    __isSuspense?: undefined;
} & import("vue").ComponentOptionsBase<Readonly<import("vue").ExtractPropTypes<__VLS_TypePropsToRuntimeProps<{
    type: "number" | "text" | "date" | "email";
    modelValue: string;
    placeholder: string;
    statistics: boolean;
    valid: boolean;
    inline: boolean;
    disabled: boolean;
    required: boolean;
    readonly: boolean;
}>>> & {
    onFocus?: ((event: Event) => any) | undefined;
    onBlur?: ((event: Event) => any) | undefined;
    onKeydown?: ((event: Event) => any) | undefined;
    onClick?: ((event: Event) => any) | undefined;
    onMousedown?: ((event: Event) => any) | undefined;
    "onUpdate:modelValue"?: ((value: string) => any) | undefined;
}, {}, unknown, {}, {}, import("vue").ComponentOptionsMixin, import("vue").ComponentOptionsMixin, {
    "update:modelValue": (value: string) => void;
} & {
    focus: (event: Event) => void;
} & {
    blur: (event: Event) => void;
} & {
    click: (event: Event) => void;
} & {
    mousedown: (event: Event) => void;
} & {
    keydown: (event: Event) => void;
}, string, {}> & import("vue").VNodeProps & import("vue").AllowedComponentProps & import("vue").ComponentCustomProps & (new () => {
    $slots: typeof import('./TextInput.vue.__VLS_template').default;
});
export default _default;
declare type __VLS_NonUndefinedable<T> = T extends undefined ? never : T;
declare type __VLS_TypePropsToRuntimeProps<T> = {
    [K in keyof T]-?: {} extends Pick<T, K> ? {
        type: import('vue').PropType<__VLS_NonUndefinedable<T[K]>>;
    } : {
        type: import('vue').PropType<T[K]>;
        required: true;
    };
};
//# sourceMappingURL=TextInput.vue.d.ts.map