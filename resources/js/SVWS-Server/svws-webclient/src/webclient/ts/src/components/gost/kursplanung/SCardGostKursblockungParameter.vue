
<template>
	<svws-ui-content-card :title="'Regeln zur Blockung'">
		<div class="flex flex-row">
			<div class="w-full flex-none sm:-mx-6 lg:-mx-8">
				<div class="py-2 align-middle sm:px-6 lg:px-8">
					<div class="overflow-hidden rounded-lg shadow">
						 <div class="py-4 px-8 bg-slate-100 shadow-lg rounded-lg ">
							 <Blockungsregel_1/>
						 </div>
							<Blockungsregel_2/>
							<Blockungsregel_3/>
							<Blockungsregel_4/>
							<Blockungsregel_5/>
					</div>
				</div>
			</div>
		</div>
	</svws-ui-content-card>
</template>

<script setup lang="ts">
import { GostBlockungRegel, GostKursblockungRegelTyp, Vector } from "@svws-nrw/svws-core-ts";
import { computed, ComputedRef, ref, Ref } from "vue";

import { injectMainApp, Main } from "~/apps/Main";

const main: Main = injectMainApp();
const app = main.apps.gost;

</script>
