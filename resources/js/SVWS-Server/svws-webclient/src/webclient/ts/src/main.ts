import { createApp, defineAsyncComponent } from "vue";
import SvwsUi from "@svws-nrw/svws-ui";
import { mainApp, mainInjectKey } from "~/apps/Main";

import "./tailwind.css";
import "@svws-nrw/svws-ui/dist/style.css";
const SWrapper = defineAsyncComponent(
	() => import("~/components/SWrapper.vue")
);

const app = createApp(SWrapper).use(SvwsUi);
// to access app with composition API/ script-setup (use injectMainApp() in components)
app.provide(mainInjectKey, mainApp);

app.mixin({
	created() {
		const title = this.$options.title;
		if (title) {
			document.title = title;
		}
	}
});
app.mount("#app");
