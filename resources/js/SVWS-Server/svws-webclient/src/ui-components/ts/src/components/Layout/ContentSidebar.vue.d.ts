declare type ComponentData = {
    main: HTMLElement | null;
    sidebar: HTMLElement | null;
    mainWidth: number;
    sidebarWidth: number;
    sizeMultiplier: number;
};
declare const _default: import("vue").DefineComponent<{
    size: {
        type: StringConstructor;
        default: string;
        validator: (value: string) => boolean;
    };
}, unknown, ComponentData, {}, {
    handleWidth(): void;
}, import("vue").ComponentOptionsMixin, import("vue").ComponentOptionsMixin, Record<string, any>, string, import("vue").VNodeProps & import("vue").AllowedComponentProps & import("vue").ComponentCustomProps, Readonly<import("vue").ExtractPropTypes<{
    size: {
        type: StringConstructor;
        default: string;
        validator: (value: string) => boolean;
    };
}>>, {
    size: string;
}>;
export default _default;
//# sourceMappingURL=ContentSidebar.vue.d.ts.map