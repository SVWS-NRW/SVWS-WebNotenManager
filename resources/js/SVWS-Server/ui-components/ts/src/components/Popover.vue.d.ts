import { PropType } from "vue";
declare const placementValues: readonly ["auto", "auto-start", "auto-end", "top", "top-start", "top-end", "bottom", "bottom-start", "bottom-end", "right", "right-start", "right-end", "left", "left-start", "left-end"];
declare type PLACEMENT = typeof placementValues[number];
declare const _default: import("vue").DefineComponent<{
    /**
     * Preferred placement (the "auto" placements will choose the side with most space.)
     */
    placement: {
        type: PropType<"auto" | "auto-start" | "auto-end" | "top" | "top-start" | "top-end" | "bottom" | "bottom-start" | "bottom-end" | "right" | "right-start" | "right-end" | "left" | "left-start" | "left-end">;
        default: string;
        validator: (value: PLACEMENT) => boolean;
    };
    /**
     * Disables automatically closing the popover when the user clicks away from it
     */
    disableClickAway: {
        type: BooleanConstructor;
        default: boolean;
    };
    /**
     * Distance in pixels along the trigger element
     */
    offsetX: {
        type: StringConstructor;
        default: string;
    };
    /**
     * Popover Opening Delay in ms
     */
    openDelay: {
        type: NumberConstructor;
        default: number;
    };
    /**
     * Distance in pixels away from the trigger element
     */
    offsetY: {
        type: StringConstructor;
        default: string;
    };
    /**
     * Trigger the popper on hover
     */
    hover: {
        type: BooleanConstructor;
        default: boolean;
    };
}, unknown, unknown, {}, {}, import("vue").ComponentOptionsMixin, import("vue").ComponentOptionsMixin, Record<string, any>, string, import("vue").VNodeProps & import("vue").AllowedComponentProps & import("vue").ComponentCustomProps, Readonly<import("vue").ExtractPropTypes<{
    /**
     * Preferred placement (the "auto" placements will choose the side with most space.)
     */
    placement: {
        type: PropType<"auto" | "auto-start" | "auto-end" | "top" | "top-start" | "top-end" | "bottom" | "bottom-start" | "bottom-end" | "right" | "right-start" | "right-end" | "left" | "left-start" | "left-end">;
        default: string;
        validator: (value: PLACEMENT) => boolean;
    };
    /**
     * Disables automatically closing the popover when the user clicks away from it
     */
    disableClickAway: {
        type: BooleanConstructor;
        default: boolean;
    };
    /**
     * Distance in pixels along the trigger element
     */
    offsetX: {
        type: StringConstructor;
        default: string;
    };
    /**
     * Popover Opening Delay in ms
     */
    openDelay: {
        type: NumberConstructor;
        default: number;
    };
    /**
     * Distance in pixels away from the trigger element
     */
    offsetY: {
        type: StringConstructor;
        default: string;
    };
    /**
     * Trigger the popper on hover
     */
    hover: {
        type: BooleanConstructor;
        default: boolean;
    };
}>>, {
    placement: "auto" | "auto-start" | "auto-end" | "top" | "top-start" | "top-end" | "bottom" | "bottom-start" | "bottom-end" | "right" | "right-start" | "right-end" | "left" | "left-start" | "left-end";
    disableClickAway: boolean;
    hover: boolean;
    openDelay: number;
    offsetX: string;
    offsetY: string;
}>;
export default _default;
//# sourceMappingURL=Popover.vue.d.ts.map