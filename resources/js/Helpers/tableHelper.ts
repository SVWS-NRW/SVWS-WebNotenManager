import { Leistung } from '@/Interfaces/Leistung';
import { Schueler } from '@/Interfaces/Schueler';

const emptyValueDescription: string = 'Leer';

const searchHelper = (model: Leistung|Schueler, search: string[], searchFilter: string): boolean => {
    return search.some((searchString: string): boolean => model[searchString].toLocaleLowerCase().includes(
        searchFilter?.toLocaleLowerCase() ?? ''
    )
)};

const multiSelectHelper = (model: Leistung|Schueler, column: string, filterItems: string[] = []): boolean => {
    return filterItems.length === 0 || filterItems.some((item: string|null): boolean => {
        return (item === 'Leer' ? null : item) === model[column];
    });
};


const mapFilterOptionsHelper = (rows: Leistung[]|Schueler[], column: string): string[] => Array.from(
    new Set(rows.map((model: Leistung|Schuler): string => model[column] ?? emptyValueDescription))
);

export {
    searchHelper,
    multiSelectHelper,
    mapFilterOptionsHelper,
};