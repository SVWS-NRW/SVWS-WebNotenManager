<script setup lang="ts">
import { injectMainApp, Main } from "~/apps/Main";
import { GostBlockungKurs, GostKursblockungRegelTyp, SchuelerListeEintrag, Vector } from "@svws-nrw/svws-core-ts";
import { Ref, ref } from "vue";

const main: Main = injectMainApp();
const app = main.apps.gost;

const regel_typ = GostKursblockungRegelTyp.SCHUELER_VERBIETEN_IN_KURS
// public static readonly SCHUELER_VERBIETEN_IN_KURS : GostKursblockungRegelTyp =
// new GostKursblockungRegelTyp("SCHUELER_VERBIETEN_IN_KURS", 5, 5, "Schüler: Verbiete in Kurs",
// Arrays.asList(GostKursblockungRegelParameterTyp.SCHUELER_ID, GostKursblockungRegelParameterTyp.KURS_ID));
const kurse = app.dataKursblockung.daten?.kurse || new Vector<GostBlockungKurs>()
const schuelerliste = app.listAbiturjahrgangSchueler.liste || []

const kurs: Ref<GostBlockungKurs> = ref(kurse.get(0))
const schueler = ref(schuelerliste[0]) as Ref<SchuelerListeEintrag>

const speichern = async () => {
	const regel = await app.dataKursblockung.add_blockung_regel(regel_typ.typ)
	if (!regel) return
	regel.parameter.set(0, schueler.value.id)
	regel.parameter.set(1, kurs.value.id)
	app.dataKursblockung.manager?.addRegel(regel)
	app.dataKursblockung.patch_blockung_regel(regel)
}
</script>

<template>
	<div>
		<div>{{ regel_typ.bezeichnung }}</div>
		Verbiete <parameter-schueler v-model="schueler"/>
		in <parameter-kurs v-model="kurs"/>
		<svws-ui-button type="primary" @click="speichern">Speichern</svws-ui-button>
	</div>
</template>