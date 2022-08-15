<template>
	<img :src="src" :alt="alt" class="svws-ui-avatar" @error="onError" />
</template>

<script lang="ts">
	import { defineComponent } from "vue";
	import imageFile from "/src/assets/img/avatar_placeholder.svg";

	export default defineComponent({
		name: "SvwsUiAvatar",
		props: {
			src: {
				type: String
			},
			alt: {
				type: String,
				default: "" // ToDo: Default Alt-Text festlegen
			}
		},
		methods: {
			onError(e) {
				e.target.src = imageFile;
			}
		}
	});
</script>

<style>
	.svws-ui-avatar {
		@apply svws-ui-h-32 svws-ui-w-32 svws-ui-rounded-full svws-ui-object-cover;
	}
</style>
