<script setup lang="ts">
import { injectMainApp, Main } from "~/apps/Main";
import { GostBlockungKurs, GostBlockungSchiene, GostKursblockungRegelTyp, Vector } from "@svws-nrw/svws-core-ts";
import { Ref, ref } from "vue";

const main: Main = injectMainApp();
const app = main.apps.gost;

const regel_typ = GostKursblockungRegelTyp.KURS_FIXIERE_IN_SCHIENE
// public static readonly KURS_FIXIERE_IN_SCHIENE : GostKursblockungRegelTyp = 
// new GostKursblockungRegelTyp("KURS_FIXIERE_IN_SCHIENE", 2, 2, "Kurs: Fixiere in Schiene", 
//Arrays.asList(GostKursblockungRegelParameterTyp.KURS_ID, GostKursblockungRegelParameterTyp.SCHIENEN_NR));
const kurse = app.dataKursblockung.daten?.kurse || new Vector<GostBlockungKurs>()
const schienen = app.dataKursblockung.daten?.schienen || new Vector<GostBlockungSchiene>()

const kurs: Ref<GostBlockungKurs> = ref(kurse.get(0))
const schiene: Ref<GostBlockungSchiene> = ref(schienen.get(0))

const speichern = async () => {
	const regel = await app.dataKursblockung.add_blockung_regel(regel_typ.typ)
	if (!regel) return
	regel.parameter.set(0, kurs.value.id)
	regel.parameter.set(1, schiene.value.nummer)
	app.dataKursblockung.manager?.addRegel(regel)
	app.dataKursblockung.patch_blockung_regel(regel)
}
</script>

<template>
	<div>
		<div>{{ regel_typ.bezeichnung }}</div>
		Fixiere <parameter-kurs v-model="kurs"/>
		in <parameter-schiene v-model="schiene"/>
		<svws-ui-button type="primary" @click="speichern">Speichern</svws-ui-button>
	</div>
</template>