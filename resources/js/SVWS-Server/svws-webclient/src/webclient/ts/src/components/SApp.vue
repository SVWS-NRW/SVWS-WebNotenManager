<template>
	<svws-ui-app-layout>
		<template #sidebar>
			<svws-ui-sidebar-menu :collapsed="isCollapsed" class="print:hidden" @toggle="onToggle">
				<template #header>
					<svws-ui-sidebar-menu-header :collapsed="isCollapsed">
						SVWS-NRW
					</svws-ui-sidebar-menu-header>
					<div v-if="!isCollapsed">
						<div v-if="schule_abschnitte" class="mt-4 px-4">
							<svws-ui-multi-select
v-model="akt_abschnitt" :items="schule_abschnitte" :item-sort="item_sort"
								:item-text="item_text"></svws-ui-multi-select>
						</div>
					</div>
					<div v-else class="mt-9" />
				</template>
				<template #default>
					<div class="mt-6 mb-8">
						<svws-ui-sidebar-menu-item
v-for="menuItem in menuItems" :key="menuItem.caption" :collapsed="isCollapsed"
							:active="sidebarMenuItemActive(menuItem)" @click="menubar_selected = menuItem.value"><template #label>{{
							menuItem.caption }}</template>
							<template #icon>
								<i-ri-team-line v-if="menuItem.icon === 'team'" />
								<i-ri-user--2-line v-else-if="menuItem.icon === 'user-2'" />
								<i-ri-artboard-line v-else-if="menuItem.icon === 'artboard'" />
								<i-ri-numbers-line v-else-if="menuItem.icon === 'numbers'" />
								<i-ri-group-line v-else-if="menuItem.icon === 'group'" />
								<i-ri-line-chart-line v-else-if="menuItem.icon === 'line-chart'" />
								<i-ri-book-read-line v-else-if="menuItem.icon === 'book-read'" />
								<i-ri-parent-line v-else-if="menuItem.icon === 'parent'" />
								<i-ri-community-line v-else-if="menuItem.icon === 'community'" />
							</template>
						</svws-ui-sidebar-menu-item>
					</div>
				</template>
				<template #footer>
					<svws-ui-sidebar-menu-item
class="print:hidden" subline="" :collapsed="isCollapsed"
						@click="menubar_selected = 'abmelden'"><template #label>Abmelden</template><template #icon>
							<i-ri-logout-box-line />
						</template></svws-ui-sidebar-menu-item>
				</template>
			</svws-ui-sidebar-menu>
		</template>
		<template #secondaryMenu>
			<component :is="getAuswahlComponent()" class="print:hidden" />
		</template>
		<template #main>
			<svws-ui-overlay v-if="showOverlay || pending" />
			<div class="page-wrapper svws-ui-bg-white">
				<svws-ui-overlay v-if="showOverlay || pending" />
				<main class="relative h-screen">
					<component
:is="getGruppenaktionenComponent()" v-if="ausgewaehlt_gruppe.length > 1"
						:items="ausgewaehlt_gruppe" />
					<component :is="getAppComponent()" v-else />
				</main>
				<s-app-status />
			</div>
		</template>
		<template #contentSidebar>
			<div
id="sidebar"
				class="svws-ui-bg-white svws-ui-text-black svws-ui-border-l-2 svws-ui-border-dark-20 print:hidden"></div>
		</template>
	</svws-ui-app-layout>
</template>

<script setup lang="ts">
import {
	SchuelerListeEintrag,
	Schuljahresabschnitt
} from "@svws-nrw/svws-core-ts";
import { computed, ComputedRef, defineAsyncComponent, ref, watch, WritableComputedRef } from "vue";
import { injectMainApp } from "~/apps/Main";

import { Schule } from "~/apps/schule/Schule";

interface MenuItem {
	caption: string;
	value: string;
	icon: string;
	active: string[];
}

const SSchuelerApp = defineAsyncComponent({
	loader: () => import("~/components/schueler/SSchuelerApp.vue")
});
const SAppStatus = defineAsyncComponent({
	loader: () => import("~/components/SAppStatus.vue")
});
const SSchuleApp = defineAsyncComponent({
	loader: () => import("~/components/schule/SSchuleApp.vue")
});
const SSchuleAuswahl = defineAsyncComponent({
	loader: () => import("~/components/schule/SSchuleAuswahl.vue")
});
// Mieses Spiel mit gleichen Komponenten...
const SSchuleDatenaustauschAuswahl = defineAsyncComponent({
	loader: () => import("~/components/schule/SSchuleAuswahl.vue")
});
const SSchueler = defineAsyncComponent({
	loader: () => import("~/components/schueler/SSchueler.vue")
});
// import SSchuelerApp from "~/components/schueler/SSchuelerApp.vue";
const SSchuelerAuswahl = defineAsyncComponent({
	loader: () => import("~/components/schueler/SSchuelerAuswahl.vue")
});
const SSchuelerGruppenaktionen = defineAsyncComponent({
	loader: () => import("~/components/schueler/SSchuelerGruppenaktionen.vue")
}); const SLehrerAuswahl = defineAsyncComponent({
	loader: () => import("~/components/lehrer/SLehrerAuswahl.vue")
});
const SLehrerApp = defineAsyncComponent({
	loader: () => import("~/components/lehrer/SLehrerApp.vue")
});
const SKatalogeApp = defineAsyncComponent({
	loader: () => import("~/components/kataloge/SKatalogeApp.vue")
});
const SKatalogeAuswahl = defineAsyncComponent({
	loader: () => import("~/components/kataloge/SKatalogeAuswahl.vue")
});
const SKlassenAuswahl = defineAsyncComponent({
	loader: () => import("~/components/klassen/SKlassenAuswahl.vue")
});
const SKlassenApp = defineAsyncComponent({
	loader: () => import("~/components/klassen/SKlassenApp.vue")
});
const SKurseAuswahl = defineAsyncComponent({
	loader: () => import("~/components/kurse/SKurseAuswahl.vue")
});
const SKurseApp = defineAsyncComponent({
	loader: () => import("~/components/kurse/SKurseApp.vue")
});
const SJahrgaengeAuswahl = defineAsyncComponent({
	loader: () => import("~/components/jahrgaenge/SJahrgaengeAuswahl.vue")
});
const SJahrgaengeApp = defineAsyncComponent({
	loader: () => import("~/components/jahrgaenge/SJahrgaengeApp.vue")
});
const SFaecherAuswahl = defineAsyncComponent({
	loader: () => import("~/components/faecher/SFaecherAuswahl.vue")
});
const SFaecherApp = defineAsyncComponent({
	loader: () => import("~/components/faecher/SFaecherApp.vue")
});
const SGostAuswahl = defineAsyncComponent({
	loader: () => import("~/components/gost/SGostAuswahl.vue")
});
const SGostApp = defineAsyncComponent({
	loader: () => import("~/components/gost/SGostApp.vue")
});
const SStatistikApp = defineAsyncComponent({
	loader: () => import("~/components/statistik/SStatistikApp.vue")
});
const SStatistikAuswahl = defineAsyncComponent({
	loader: () => import("~/components/statistik/SStatistikAuswahl.vue")
});
const SSchuleDatenaustauschApp = defineAsyncComponent({
	loader: () => import("~/components/schule/datenaustausch/SSchuleDatenaustauschApp.vue")
});

const main = injectMainApp();
const appSchule: ComputedRef<Schule> = computed(() => { return main.apps.schule });

const menubar_selected: WritableComputedRef<string> = computed({
	get(): string {
			return main.config.selected_app;
	},
	set(val: string) {
		if (val === "abmelden") {
			main.logout();
		} else {
			if (val && val !== menubar_selected.value) {
				main.config.selected_app = val;
				selectedItems.value = [];
			}
		}
	}
});

const status_height = ref("2em");
const selectedItems = ref([]);
const isCollapsed = ref(false);

const schulname: ComputedRef<string> = computed(() => {
	const name = appSchule.value.schuleStammdaten.daten?.bezeichnung1
	return name ? name.toString() : "fehlende Bezeichnung"
});

const fullMenuItems: MenuItem[] = [
	{
		caption: schulname.value,
		value: "Schule",
		active: ["schule"],
		icon: "community"
	},
	{
		caption: "Kataloge",
		value: "Kataloge",
		active: ["kataloge", "faecher", "jahrgaenge"],
		icon: "book-read"
	},
	{
		caption: "Schüler",
		value: "Schueler",
		active: ["schueler"],
		icon: "team"
	},
	{
		caption: "Lehrkräfte",
		value: "Lehrer",
		active: ["lehrer"],
		icon: "user-2"
	},
	{
		caption: "Klassen",
		value: "Klassen",
		active: ["klassen"],
		icon: "group"
	},
	{
		caption: "Kurse",
		value: "Kurse",
		active: ["kurse"],
		icon: "parent"
	},
	{
		caption: "Oberstufe",
		value: "Gost",
		active: ["gost"],
		icon: "numbers"
	},
	{
		caption: "Statistik",
		value: "Statistik",
		active: ["statistik"],
		icon: "line-chart"
	}
] as MenuItem[];
const minDelayReached = ref(false);
const minDurationReached = ref(false);
const showOverlay = ref(false);

const menuItems: ComputedRef<{
	caption: string;
	value: string;
	icon: string;
	active: string[];
}[]> = computed(() => {
	if (main.config.hasGost) {
		return fullMenuItems.filter(
			item => item.value !== "gost"
		);
	}
	return fullMenuItems;
});

const pending: ComputedRef<boolean> = computed(() => main.config.pending);

const schule_abschnitte: ComputedRef<Array<Schuljahresabschnitt> | undefined> = computed(() => {
	const liste = appSchule.value.schuleStammdaten.daten?.abschnitte;
	return liste?.toArray(new Array<Schuljahresabschnitt>()) || [];
});

const akt_abschnitt: WritableComputedRef<Schuljahresabschnitt> = computed({
	get(): Schuljahresabschnitt  {
		return main.config.akt_abschnitt;
	},
	set(abschnitt: Schuljahresabschnitt) {
			main.config.akt_abschnitt = abschnitt
	}
});


const selected_auswahl: ComputedRef<string> = computed(() => {
	return `S${menubar_selected.value}Auswahl`;
});

function getAuswahlComponent() {
	switch (selected_auswahl.value) {
		case `SSchuelerAuswahl`:
			return SSchuelerAuswahl;
		case `SSchuleAuswahl`:
			return SSchuleAuswahl;
		case `SSchuleDatenaustauschAuswahl`:
			return SSchuleDatenaustauschAuswahl;
		case `SLehrerAuswahl`:
			return SLehrerAuswahl;
		case `SKatalogeAuswahl`:
			return SKatalogeAuswahl;
		case `SKlassenAuswahl`:
			return SKlassenAuswahl;
		case `SKurseAuswahl`:
			return SKurseAuswahl;
		case `SJahrgaengeAuswahl`:
			return SJahrgaengeAuswahl;
		case `SFaecherAuswahl`:
			return SFaecherAuswahl;
		case `SGostAuswahl`:
			return SGostAuswahl;
		case `SStatistikAuswahl`:
			return SStatistikAuswahl;
	}
}

const selected_app: ComputedRef<string> = computed(() => {
	return `S${menubar_selected.value}App`;
});

function getAppComponent() {
	switch (selected_app.value) {
		case 'SSchuleApp':
			return SSchuleApp;
		case 'SSchuelerApp':
			return SSchuelerApp;
		case 'SLehrerApp':
			return SLehrerApp;
		case 'SKatalogeApp':
			return SKatalogeApp;
		case 'SKlassenApp':
			return SKlassenApp;
		case 'SKurseApp':
			return SKurseApp;
		case 'SJahrgaengeApp':
			return SJahrgaengeApp;
		case 'SFaecherApp':
			return SFaecherApp;
		case 'SGostApp':
			return SGostApp;
		case 'SStatistikApp':
			return SStatistikApp;
		case 'SSchuleDatenaustauschApp':
			return SSchuleDatenaustauschApp;
	}
}

const selected_gruppenaktionen: ComputedRef<string> = computed(() => {
	return `S${menubar_selected.value}Gruppenaktionen`;
});

function getGruppenaktionenComponent() {
	switch (selected_gruppenaktionen.value) {
		case 'SSchuelerGruppenaktionen':
			return SSchuelerGruppenaktionen;
	}
}

const ausgewaehlt_gruppe: ComputedRef<SchuelerListeEintrag[]> = computed(() => {
	if (main?.config.selected_app) {
		return main.apps.schueler.auswahl.ausgewaehlt_gruppe;
	}
	return [];
});

setTimeout(() => (minDelayReached.value = true), 100); // Minimum Delay bevor das Overlay eingeblendet wird. Soll flackern des Bildschirms vermeiden.
setTimeout(() => (minDurationReached.value = true), 400); // Minimum Dauer bevor das Overlay ausgeblendet wird. Soll flackern des Bildschirms vermeiden.
watch(minDelayReached, () => {
	showOverlay.value = true;
});
watch(minDurationReached, () => {
	showOverlay.value = false;
});

function onToggle(value: boolean) {
	isCollapsed.value = value;
};

function item_sort(a: Schuljahresabschnitt, b: Schuljahresabschnitt) {
	return (
		b.schuljahr +
		b.abschnitt * 0.1 -
		(a.schuljahr + a.abschnitt * 0.1)
	);
};

function item_text(item: Schuljahresabschnitt) {
	return item.schuljahr
		? `${item.schuljahr}, ${item.abschnitt}. HJ`
		: "Abschnitt";
};

function sidebarMenuItemActive(item: MenuItem): boolean {
	if (menubar_selected.value) {
		return item.active.includes(menubar_selected.value.toLocaleLowerCase());
	}
	return false;
}
</script>

<style>
.svws-app {
	display: flex;
	position: relative;
	flex: 1 1 auto;
	flex-direction: column;
	height: 100%;
	width: 100%;
	-ms-user-select: None;
	-moz-user-select: None;
	-webkit-user-select: None;
	user-select: None;
	color: var(--font-color);
}

@page {
	size: A4 portrait;
	margin: 0mm;
}

@media print {
	body {
		width: 210mm;
		height: 296.8mm;
		padding: 10mm 25mm 10mm 25mm !important;
		/* overflow: hidden; */
	}

	.page {}
}
</style>
