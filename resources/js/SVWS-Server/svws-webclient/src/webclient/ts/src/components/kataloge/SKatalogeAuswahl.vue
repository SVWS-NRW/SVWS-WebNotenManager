<template>
	<svws-ui-secondary-menu>
		<template #headline> Kataloge </template>
		<template #header> </template>
		<template #content>
			<div class="container mt-2">
				<svws-ui-sidebar-menu-item
					v-for="item in menu_items"
					:key="item.value"
					@click="menubar_selected = item.value"
				>
					<template #label>
						<span>{{ item.title }}</span>
					</template>
				</svws-ui-sidebar-menu-item>
				<svws-ui-sidebar-menu-item
					><template #label><span></span></template
				></svws-ui-sidebar-menu-item>
				<svws-ui-sidebar-menu-item
					><template #label
						><span>Konfessionen</span></template
					></svws-ui-sidebar-menu-item
				>
				<svws-ui-sidebar-menu-item
					><template #label
						><span>Förderschwerpunkte</span></template
					></svws-ui-sidebar-menu-item
				>
				<svws-ui-sidebar-menu-item
					><template #label
						><span>Haltestellen</span></template
					></svws-ui-sidebar-menu-item
				>
				<svws-ui-sidebar-menu-item
					><template #label
						><span>Betriebe</span></template
					></svws-ui-sidebar-menu-item
				>
			</div>
		</template>
	</svws-ui-secondary-menu>
</template>

<script setup lang="ts">
	import { computed, WritableComputedRef } from "vue";
	import { injectMainApp, Main } from "~/apps/Main";

	const menu_items = [
		{ title: "Fächer", value: "faecher" },
		{ title: "Jahrgänge", value: "jahrgaenge" }
	];
	const main: Main = injectMainApp();

	const menubar_selected: WritableComputedRef<string | undefined> = computed({
		get(): string | undefined {
			return main.config.selected_app;
		},
		set(val: string | undefined) {
			if (
				val &&
				val !== menubar_selected.value &&
				main.config.selected_app
			) {
				main.config.selected_app = val;
			}
		}
	});
</script>
