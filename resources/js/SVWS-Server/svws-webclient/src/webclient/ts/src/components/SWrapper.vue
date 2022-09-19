<template>
	<template v-if="connected && authenticated">
		<s-app />
	</template>
	<template v-else>
		<s-login />
	</template>
</template>

<script setup lang="ts">
	import { computed, ComputedRef } from "vue";
	import { injectMainApp, Main } from "~/apps/Main";

	const main: Main = injectMainApp();
    const connected: ComputedRef<boolean> = computed(() => {return main.connected});

	const authenticated: ComputedRef<boolean> = computed(() => {
		return main.authenticated;
	});
</script>
