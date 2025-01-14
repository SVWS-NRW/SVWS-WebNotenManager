import { ref, Ref } from "vue";

//rows will be a reference map which will allow navigation within indexable elements in the tables
const itemRefsNoteInput = ref(new Map());
const itemRefsQuartalNoteInput = ref(new Map());
const itemRefsfs = ref(new Map());
const itemRefsfsu = ref(new Map());
const mahnungIndicator = ref(new Map());

//navigable element names are determined by child
function updateNavigableItems(rowIndex: number, el: Element, navigableElementName: string): void {
    switch (navigableElementName) {
        case "itemRefsquartalnoteInput":
            itemRefsQuartalNoteInput.value.set(rowIndex, el);
            break;
        case "itemRefsnoteInput":
            itemRefsNoteInput.value.set(rowIndex, el);
            break;
        case "itemRefsfs":
            itemRefsfs.value.set(rowIndex, el);
            break;
        case "itemRefsfsu":
            itemRefsfsu.value.set(rowIndex, el);
            break;
        case "mahnungIndicator":
            mahnungIndicator.value.set(rowIndex, el);
            break;
        default:
            console.log("Map not found: " + navigableElementName);
    }
}

//table navigation actions (go up/down within the column)
function next(id: number, itemRefs: Ref) {
    const el = itemRefs.value.get(id + 1);
    if (el) el.input.select();
}

const previous = (id: number, itemRefs: Ref) => {
    const el = itemRefs.value.get(id - 1);
    if (el) el.input.select();
};

//direction (up/down within the column) and map name are received from child component
const navigateTable = (
    direction: string,
    rowIndex: number,
    itemRefsName: string
): void => {
    switch (itemRefsName) {
        case "itemRefsquartalnoteInput":
            direction === "next"
                ? next(rowIndex, itemRefsQuartalNoteInput)
                : previous(rowIndex, itemRefsQuartalNoteInput);
            break;
        case "itemRefsnoteInput":
            direction === "next"
                ? next(rowIndex, itemRefsNoteInput)
                : previous(rowIndex, itemRefsNoteInput);
            break;
        case "itemRefsfs":
            direction === "next"
                ? next(rowIndex, itemRefsfs)
                : previous(rowIndex, itemRefsfs);
            break;
        case "itemRefsfsu":
            direction === "next"
                ? next(rowIndex, itemRefsfsu)
                : previous(rowIndex, itemRefsfsu);
            break;
        case "mahnungIndicator":
            direction === "next"
                ? next(rowIndex, mahnungIndicator)
                : previous(rowIndex, mahnungIndicator);
            break;
        default:
            console.log("itemRefs map not found");
    }
};

export { updateNavigableItems, navigateTable };
