<template>
	<label
		class="svws-ui--radio--label"
		:class="{
			'svws-ui--radio--label--disabled': disabled,
			'svws-ui--radio--statistics': statistics
		}"
	>
		<input
			type="radio"
			:name="name"
			:value="value"
			:disabled="disabled"
			:checked="checked"
			class="svws-ui--radio--indicator"
			@input="onInput"
		/>
		<span class="svws-ui--radio--label"
			>{{ label }}
			<i-ri-bar-chart-fill v-if="statistics" class="svws-ui-ml-2" />
		</span>
	</label>
</template>

<script lang="ts">
	import { defineComponent } from "vue";

	export default defineComponent({
		name: "SvwsUiRadioOption",
		components: {},
		props: {
			name: {
				type: String
			},
			label: {
				type: String
			},
			value: {
				type: String
			},
			disabled: {
				type: Boolean,
				default: false
			},
			statistics: {
				type: Boolean,
				default: false
			},
			checked: {
				type: Boolean,
				default: false
			}
		},
		emits: ["input"],
		methods: {
			onInput(event: { target: HTMLInputElement }) {
				if (!this.disabled) {
					this.$emit("input", event.target.value);
				}
			}
		}
	});
</script>

<style>
	.svws-ui--radio--label {
		@apply svws-ui-cursor-pointer;
		@apply svws-ui-flex svws-ui-flex-row svws-ui-items-center;
		@apply svws-ui-select-none;
		@apply svws-ui-space-x-2;
		@apply svws-ui-text-input;
	}

	.svws-ui--radio--label.svws-ui--radio--statistics {
		@apply svws-ui-text-purple;
	}

	.svws-ui--radio--statistics .svws-ui--radio--indicator {
		@apply svws-ui-border-purple;
	}

	.svws-ui--radio--statistics .svws-ui--radio--indicator:checked::before {
		@apply svws-ui-bg-purple;
	}

	.svws-ui--radio--indicator {
		@apply svws-ui-appearance-none;
		@apply svws-ui-rounded-full svws-ui-border-2 svws-ui-border-black;
		@apply svws-ui-cursor-pointer;
		@apply svws-ui-flex svws-ui-flex-shrink-0 svws-ui-items-center svws-ui-justify-center;
		@apply svws-ui-h-5 svws-ui-w-5;
	}

	.svws-ui--radio--indicator:focus {
		@apply svws-ui-outline-none svws-ui-ring-2 svws-ui-ring-primary svws-ui-ring-opacity-50;
	}

	.svws-ui--radio--indicator:checked::before {
		@apply svws-ui-bg-black;
		@apply svws-ui-block;
		@apply svws-ui-rounded-full;
		@apply svws-ui-h-3 svws-ui-w-3;
		content: "";
	}

	.svws-ui--radio--indicator:disabled {
		@apply svws-ui-bg-disabled;
		@apply svws-ui-border-disabled-medium;
		@apply svws-ui-cursor-not-allowed;
		@apply svws-ui-text-disabled-dark;
	}

	.svws-ui--radio--label--disabled {
		@apply svws-ui-cursor-not-allowed;
		@apply svws-ui-text-disabled-dark;
	}
</style>
