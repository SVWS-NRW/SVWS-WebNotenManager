<script lang="ts" setup>
type ButtonType =
	"primary" |
	"secondary" |
	"danger" |
	"transparent";

const {
	type = 'primary',
	disabled = false,
	dropdownAction = false
} = defineProps<{
	type?: ButtonType;
	disabled?: boolean;
	dropdownAction?: boolean;
}>();

const emit = defineEmits<{
	(e: 'click', event: MouseEvent): void;
}>();

function onClick(event: MouseEvent) {
	if (!disabled) {
		emit("click", event);
	}
}
</script>

<template>
	<button
class="button" :class="{
		'button--primary': type === 'primary',
		'button--secondary': type === 'secondary',
		'button--danger': type === 'danger',
		'button--transparent': type === 'transparent',
		'button--dropdown-action': dropdownAction === true
	}" :disabled="disabled" @click="onClick">
		<slot />
	</button>
</template>

<style>
.button {
	@apply rounded-full border-2;
	@apply px-5 py-2;
	@apply select-none;
	@apply text-button font-bold;
	@apply flex items-center;
}

.button:focus {
	@apply outline-none ring;
}

.button--primary {
	@apply bg-primary;
	@apply border-primary;
	@apply text-white;
}

.button--primary:focus {
	@apply ring-primary ring-opacity-50;
}

.button--secondary {
	@apply bg-transparent;
	@apply border-black;
	@apply text-black;
}

.button--secondary:focus {
	@apply ring-primary ring-opacity-50;
}

.button--danger {
	@apply bg-transparent;
	@apply border-error;
	@apply text-error;
}

.button--danger:focus {
	@apply bg-error;
	@apply ring-error ring-opacity-50;
	@apply text-white;
}

.button--transparent {
	@apply bg-transparent;
	@apply border-transparent;
	@apply text-primary;
}

.button--transparent:hover {
	@apply bg-dark-20 bg-opacity-50;
}

.button--transparent:focus {
	@apply ring-primary;
}

.button:disabled {
	@apply bg-disabled;
	@apply border-disabled-medium;
	@apply cursor-not-allowed;
	@apply text-disabled-dark;
}

.button--dropdown-action {
	@apply pr-3;
	@apply relative z-20;
	@apply rounded-r-none;
}
</style>
