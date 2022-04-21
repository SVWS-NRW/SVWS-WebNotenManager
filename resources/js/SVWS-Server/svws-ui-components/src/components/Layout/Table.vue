<template>
	<table class="svws-ui--table">
		<thead class="svws-ui--table--header">
			<tr>
				<td
					v-if="multiSelect"
					class="svws-ui--table--cell svws-ui--table--cell-padded svws-ui-w-1"
				></td>
				<td
					v-for="col in cols"
					:key="col.id"
					class="svws-ui--table--cell svws-ui--table--cell-padded"
					:width="col.width"
					@click="changeSort(col)"
				>
					<div class="svws-ui--table--header-col">
						<span class="svws-ui--table--header-col--text">
							{{ col.title }}
						</span>
						<span v-if="col.sortable">
							<svws-ui-icon v-show="col.id !== sorting.column"
								><i-ri-arrow-up-down-line
							/></svws-ui-icon>
							<svws-ui-icon
								v-show="
									sorting.asc && col.id === sorting.column
								"
								><i-ri-sort-asc
							/></svws-ui-icon>
							<svws-ui-icon
								v-show="
									!sorting.asc && col.id === sorting.column
								"
								><i-ri-sort-desc
							/></svws-ui-icon>
						</span>
					</div>
				</td>
				<td
					v-if="actions && actions.length > 0"
					class="svws-ui--table--cell svws-ui--table--cell-padded"
				></td>
			</tr>
		</thead>
		<tbody>
			<template v-for="item in items" :key="item.data.id">
				<tr
					:id="'row_' + (items.indexOf(item) + 1)"
					:tabindex="items.indexOf(item) + 1"
					class="svws-ui--table--row"
					:class="{
						'svws-ui--table--row-selected': current === item
					}"
					@keydown.space="onKeyDownSpace"
					@keydown.down="onKeyDown"
					@keydown.up="onKeyUp"
				>
					<td
						v-if="multiSelect"
						class="svws-ui--table--cell svws-ui--table--cell-padded"
						:class="{ 'svws-ui--table--border': border }"
					>
						<svws-ui-checkbox
							:model-value="item.selected"
							@change="toggleSelect(item)"
						/>
					</td>
					<td
						v-for="col in cols"
						:key="item.data[col.id]"
						class="svws-ui--table--cell svws-ui--table--cell-padded"
						:class="{ 'svws-ui--table--border': border }"
						:width="col.width"
						@click="mousePressed(item)"
					>
						<div class="flex">
							<template
								v-if="
									current === item &&
									tr_slot &&
									col === cols[0]
								"
							>
								<i-ri-arrow-down-s-line
									v-if="slot_open"
									class="inline-flex"
									@click="open_slot"
								></i-ri-arrow-down-s-line>
								<i-ri-arrow-right-s-line
									v-else
									class="inline-flex"
									@click="open_slot"
								></i-ri-arrow-right-s-line>
							</template>
							{{ item.data[col.id] }}
						</div>
					</td>
					<td
						v-if="actions && actions.length > 0"
						class="svws-ui--table--cell"
						:class="{ 'svws-ui--table--border': border }"
					>
						<svws-ui-popover
							:hover="false"
							placement="left-end"
							:disable-click-away="false"
						>
							<template #trigger>
								<button class="svws-ui--table--action-button">
									<svws-ui-icon
										><i-ri-more--2-fill
									/></svws-ui-icon>
								</button>
							</template>
							<template #content>
								<div class="svws-ui--table--action-items">
									<div
										v-for="action in actions"
										:key="action"
									>
										<svws-ui-button
											class="svws-ui--table--action-item"
											type="transparent"
											@click="
												$emit('action', [
													action.action,
													item
												])
											"
											>{{ action.label }}</svws-ui-button
										>
									</div>
								</div>
							</template>
						</svws-ui-popover>
					</td>
				</tr>
				<slot v-if="current === item && slot_open" name="tr"> </slot>
			</template>
		</tbody>
		<tfoot>
			<tr
				v-if="multiSelect || footer"
				class="svws-ui--table--footer-wrapper"
			>
				<td class="svws-ui--table--footer-row" colspan="1000">
					<div class="svws-ui--table--footer">
						<svws-ui-checkbox
							v-if="multiSelect"
							:model-value="selectedItems === items"
							@change="selectAll()"
						/>
						<div class="svws-ui--table--footer--actions">
							<slot v-if="footer" name="footer" />
						</div>
					</div>
				</td>
			</tr>
		</tfoot>
	</table>
</template>

<script lang="ts">
	import { defineComponent } from "vue";

	export default defineComponent({
		name: "SvwsUiTable",
		props: {
			border: {
				type: Boolean,
				default: true
			},
			multiSelect: {
				type: Boolean,
				default: false
			},
			cols: {
				type: Array,
				default: () => []
			},
			rows: {
				type: Array,
				default: () => []
			},
			actions: {
				type: Array,
				default: () => []
			},
			selected: {
				type: Object
			},
			footer: {
				type: Boolean,
				default: false
			}
		},
		emits: [
			"update:sort",
			"update:asc",
			"update:selected",
			"action",
			"update:selectedItems"
		],
		data() {
			return {
				sort: "",
				asc: true,
				sorting: {
					column: "",
					asc: false
				},
				current: {
					data: {},
					selected: false
				},
				items: {
					data: {},
					selected: false
				},
				selectedItems: [],
				slot_open: false
			};
		},
		computed: {
			tr_slot() {
				return this.$slots.tr;
			}
		},
		watch: {
			current() {
				//console.log(this.current);
			},
			sort() {
				this.sorting.column = this.sort;
			},
			asc() {
				this.sorting.asc = this.asc;
			},
			items: {
				deep: true,
				handler() {
					if (
						this.current.data.id === undefined &&
						this.items[0] !== undefined
					) {
						this.changeCurrent(this.items[0]);
					}
				}
			},
			rows: {
				deep: true,
				handler() {
					this.updateData();
				}
			},
			sorting: {
				deep: true,
				handler() {
					this.doSort();
				}
			}
		},
		created() {
			this.updateData();
		},
		mounted() {
			document.getElementById("row_1")?.focus();
		},
		methods: {
			open_slot() {
				this.slot_open = !this.slot_open;
			},
			changeSort(column) {
				if (column.sortable) {
					if (this.sorting.column === column.id) {
						this.sorting.asc = !this.sorting.asc;
						this.$emit("update:asc", this.sorting.asc);
					} else {
						this.sorting.column = column.id;
						this.sorting.asc = true;
						this.$emit("update:sort", this.sorting.column);
						this.$emit("update:asc", this.sorting.asc);
					}
				}
			},
			doSort() {
				if (this.sorting.column) {
					if (this.sorting.asc) {
						this.items.sort((a, b) =>
							b.data[this.sorting.column]
								.toString()
								.localeCompare(
									a.data[this.sorting.column].toString(),
									"de-DE"
								)
						);
					} else {
						this.items.sort((a, b) =>
							a.data[this.sorting.column]
								.toString()
								.localeCompare(
									b.data[this.sorting.column].toString(),
									"de-DE"
								)
						);
					}
				}
			},
			selectAll() {
				if (
					this.selectedItems.length >= 0 &&
					this.selectedItems.length < this.items.length
				) {
					this.items.forEach(item => (item.selected = true));
					this.selectedItems = this.items;
				} else if (this.selectedItems.length === this.items.length) {
					this.items.forEach(item => (item.selected = false));
					this.selectedItems = [];
				}
				this.$emit("update:selectedItems", this.selectedItems);
			},
			mousePressed(item) {
				this.changeCurrent(item);
			},
			toggleSelect(item) {
				item.selected = !item.selected;
				if (item.selected) {
					this.selectedItems.push(item);
				} else {
					this.selectedItems = this.removeFromArray(
						this.selectedItems,
						item
					);
				}
				this.$emit("update:selectedItems", this.selectedItems);
			},
			updateData() {
				this.items = this.rows.map(item => ({
					data: item,
					selected: false
				}));
			},
			removeFromArray(arr, val) {
				return arr.filter(function (ele) {
					return ele != val;
				});
			},
			changeCurrent(item) {
				if (!item) {
					return undefined;
				}
				this.current = item;
				this.$emit("update:selected", item.data);
				return item.data;
			},
			/* KEYBOARD NAVIGATION */
			onKeyDown() {
				event?.preventDefault();
				let element = null;
				const index = this.items.indexOf(this.current);
				if (index + 1 === this.items.length) {
					this.changeCurrent(this.items[0]);
					element = document.getElementById("row_1");
				} else {
					this.changeCurrent(this.items[index + 1]);
					element = document.getElementById("row_" + (index + 1));
				}
				this.focusAndScroll(element);
			},
			onKeyDownSpace() {
				event?.preventDefault();
				if (this.multiSelect) {
					this.toggleSelect(this.current);
				}
			},
			onKeyUp() {
				event?.preventDefault();
				let element = null;
				const index = this.items.indexOf(this.current);
				if (index === 0) {
					this.changeCurrent(this.items[this.items.length - 1]);
					element = document.getElementById(
						"row_" + this.items.length
					);
				} else {
					this.changeCurrent(this.items[index - 1]);
					element = document.getElementById("row_" + index);
				}
				this.focusAndScroll(element);
			},
			focusAndScroll(element) {
				element.focus();
				element.scrollIntoView({
					behavior: "smooth",
					block: "center",
					inline: "nearest"
				});
			}
		}
	});
</script>

<style>
	.svws-ui--table--header {
		@apply svws-ui-sticky svws-ui--top-px svws-ui-left-0 svws-ui-z-10 svws-ui-bg-white;
		@apply svws-ui-shadow-border-b svws-ui-shadow-dark-20;
		position: -webkit-sticky;
	}

	.svws-ui--table--header-col {
		@apply svws-ui-inline-flex svws-ui-flex-row svws-ui-items-center;
		@apply svws-ui-select-none;
		@apply svws-ui-space-x-2;
		@apply svws-ui-text-button svws-ui-font-bold svws-ui-text-black;
	}

	.svws-ui--table--row {
		@apply svws-ui-text-button svws-ui-text-black;
	}

	.svws-ui--table--row:hover {
		@apply svws-ui-cursor-pointer;
	}

	.svws-ui--table--row:focus {
		@apply svws-ui-outline-none;
	}

	.svws-ui--table--row-selected {
		@apply svws-ui-font-bold svws-ui-text-primary;
	}

	.svws-ui--table--row-selected .svws-ui--checkbox {
		@apply svws-ui-font-normal;
	}

	.svws-ui--table--row-selected
		.svws-ui--checkbox
		.svws-ui--checkbox--indicator {
		@apply svws-ui-border-primary;
	}

	.svws-ui--table--cell {
		@apply svws-ui-bg-white;
		@apply svws-ui-border-dark-20;
	}

	.svws-ui--table--border {
		@apply svws-ui-border;
	}

	.svws-ui--table--cell-padded {
		@apply svws-ui-px-3 svws-ui-py-1;
	}

	.svws-ui--table {
		width: calc(100% - 1px);
	}

	.svws-ui--table--action-button {
		@apply svws-ui-h-6 svws-ui-w-6;
	}

	.svws-ui--table--action-items {
		@apply svws-ui-bg-white;
		@apply svws-ui-flex svws-ui-flex-col;
		@apply svws-ui-px-2 svws-ui-py-1;
		@apply svws-ui-ring-1;
		@apply svws-ui-ring-black svws-ui-ring-opacity-5;
		@apply svws-ui-rounded-md;
		@apply svws-ui-shadow-lg;
		@apply svws-ui-w-48;
	}

	.svws-ui--table--action-item {
		@apply svws-ui-block;
		@apply svws-ui-w-full;
	}

	.svws-ui--table--action-items:focus {
		@apply svws-ui-outline-none;
	}

	.svws-ui--table--action-button:focus {
		@apply svws-ui-outline-none svws-ui-ring-2 svws-ui-ring-inset svws-ui-ring-primary;
	}

	.svws-ui--table--footer {
		@apply svws-ui-flex svws-ui-justify-between;
		@apply svws-ui-w-full;
	}

	.svws-ui--table--footer-wrapper {
		@apply svws-ui-sticky svws-ui-bottom-0 svws-ui-left-0 svws-ui-z-10  svws-ui-bg-white;
		@apply svws-ui-shadow-border-t svws-ui-shadow-dark-20;
		position: -webkit-sticky;
	}

	.svws-ui--table--footer-row {
		@apply svws-ui-bg-white;
		@apply svws-ui-py-2 svws-ui-px-3;
	}

	.svws-ui--table--footer--actions {
		@apply svws-ui-flex svws-ui-flex-row svws-ui-items-center svws-ui-space-x-2;
	}
</style>
