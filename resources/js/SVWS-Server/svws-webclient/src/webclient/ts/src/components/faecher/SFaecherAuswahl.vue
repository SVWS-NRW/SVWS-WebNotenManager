<template>
	<svws-ui-secondary-menu>
		<template #headline
			><div>
				<i-ri-arrow-left-line
					class="inline-block cursor-pointer"
					@click="main.config.selected_app = 'kataloge'"
				/>
				Fächerauswahl
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
	import type { FaecherListeEintrag } from "@svws-nrw/svws-core-ts";
	import { computed, ComputedRef, ref, Ref, WritableComputedRef } from "vue";
	import { injectMainApp, Main } from "~/apps/Main";

	const none_selected: Ref<FaecherListeEintrag> = ref({
		id: -1,
		kuerzel: "",
		kuerzelStatistik: "",
		bezeichnung: "",
		sortierung: -1,
		istOberstufenFach: false,
		istSichtbar: false,
		istAenderbar: false
	} as unknown as FaecherListeEintrag);

	const cols = ref([
		{
			id: "kuerzel",
			title: "Kuerzel",
			width: "6em",
			sortable: true
		},
		{ id: "bezeichnung", title: "Bezeichnung", sortable: true }
	]);

	const main: Main = injectMainApp();
	const app = main.apps.faecher;

	const rows: ComputedRef<FaecherListeEintrag[] | undefined> = computed(
		() => {
			return app.auswahl.liste;
		}
	);

	const selected: WritableComputedRef<FaecherListeEintrag | undefined> =
		computed({
			get(): FaecherListeEintrag {
				if (!app.auswahl.ausgewaehlt) return none_selected.value;
				return app.auswahl.ausgewaehlt;
			},
			set(value: FaecherListeEintrag) {
				if (app) {
					app.auswahl.ausgewaehlt = value;
				}
			}
		});
</script>
