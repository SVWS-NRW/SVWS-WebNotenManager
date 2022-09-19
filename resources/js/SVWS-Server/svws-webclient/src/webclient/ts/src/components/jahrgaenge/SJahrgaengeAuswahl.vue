<template>
	<svws-ui-secondary-menu>
		<template #headline
			><div>
				<i-ri-arrow-left-line
					class="inline-block cursor-pointer"
					@click="main.config.selected_app = 'kataloge'"
				/>
				Jahrgangsauswahl
			</div>
		</template>
		<template #header> </template>
		<template #content>
			<div class="container">
				<svws-ui-table
					v-model:selected="selected"
					:cols="cols"
					:rows="rows"
					:footer="false"
					asc="true"
				/>
			</div>
		</template>
	</svws-ui-secondary-menu>
</template>

<script setup lang="ts">
	import { JahrgangsListeEintrag } from "@svws-nrw/svws-core-ts";
	import { computed, ComputedRef, WritableComputedRef } from "vue";
	import { injectMainApp, Main } from "~/apps/Main";

	const cols = [
		{
			id: "kuerzel",
			title: "Kuerzel",
			width: "6em",
			sortable: true
		},
		{ id: "bezeichnung", title: "Bezeichnung", sortable: true }
	];
	const main: Main = injectMainApp();
	const app = main.apps.jahrgaenge;

	const rows: ComputedRef<JahrgangsListeEintrag[] | undefined> = computed(
		() => {
			return app.auswahl.liste;
		}
	);

	const selected: WritableComputedRef<JahrgangsListeEintrag> = computed({
		get(): JahrgangsListeEintrag {
			return app.auswahl.ausgewaehlt || new JahrgangsListeEintrag();
		},
		set(value: JahrgangsListeEintrag) {
			if (app.auswahl.ausgewaehlt) {
				app.auswahl.ausgewaehlt = value;
			}
		}
	});
</script>
