<template>
	<svws-ui-secondary-menu>
		<template #headline> Klassenauswahl </template>
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
	import type { KlassenListeEintrag } from "@svws-nrw/svws-core-ts";
	import { computed, ComputedRef, ref, WritableComputedRef } from "vue";
	import { injectMainApp, Main } from "~/apps/Main";

	const none_selected = {
		id: -1,
		kuerzel: "",
		idJahrgang: -1,
		parallelitaet: "",
		sortierung: -1,
		kuerzelLehrer: "",
		kuerzelLehrer2: "",
		istSichtbar: false,
		klassenLehrer: []
	} as unknown as KlassenListeEintrag;

	const cols = ref([
		{
			id: "kuerzel",
			title: "Kuerzel",
			width: "6em",
			sortable: true
		},
		{
			id: "bezeichnung",
			title: "Bezeichnung",
			sortable: true
		}
	]);
	const main: Main = injectMainApp();
	const app = main.apps.klassen;

	const rows: ComputedRef<KlassenListeEintrag[] | undefined> = computed(
		() => {
			return app.auswahl.liste;
		}
	);

	const selected: WritableComputedRef<KlassenListeEintrag | undefined> =
		computed({
			get(): KlassenListeEintrag {
				if (!app.auswahl.ausgewaehlt) {
					return none_selected;
				}
				return app.auswahl.ausgewaehlt;
			},
			set(value: KlassenListeEintrag) {
				if (app.auswahl.ausgewaehlt) {
					app.auswahl.ausgewaehlt = value;
				}
			}
		});
</script>
