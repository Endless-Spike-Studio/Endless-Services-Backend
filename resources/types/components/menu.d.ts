import {MenuOption} from "naive-ui";

export type SelectableMenuOption = MenuOption & {
    onSelect: () => unknown;
    children?: SelectableMenuOption[];
}
