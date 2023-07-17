import { VNodeRef, nextTick } from 'vue'

export type Payload = {
  direction: string, 
  rowIndex: number, 
  cellIndex: number,
}

export type CellRef = VNodeRef | undefined

const navigateTable = async (payload: Payload, cellRefs: any, data: any) => {
  let rowIndex = payload.rowIndex
  let cellIndex = payload.cellIndex

  const getCellRefs = (): any => cellRefs[`${rowIndex}-${cellIndex}`]

  switch (payload.direction) {
    case 'up':
      rowIndex = payload.rowIndex > 0 ? payload.rowIndex - 1 : payload.rowIndex
      break;
    case 'down':
      rowIndex = payload.rowIndex < data.length - 1 ? payload.rowIndex + 1 : payload.rowIndex
      break;
    case 'left':
      cellIndex = payload.cellIndex > 0 ? payload.cellIndex - 1 : payload.cellIndex
      break;
    case 'right':
      cellIndex = getCellRefs() !== undefined ? payload.cellIndex + 1 : payload.cellIndex
      break;
    default:
      return;
  }

  await nextTick()     

  let target: any = cellRefs[`${rowIndex}-${cellIndex}`]

  if (target && target.input) {
    target.input.focus();
  }
}

export {
  navigateTable,
}