import {MenuOption} from "naive-ui";

export type Menus = { [position in MenuPosition]: MenuOption[] };

export type MenuPosition = 'left' | 'right' | 'mobile';
export type ExtraMenuOption = MenuOption & { route?: string; url?: string }
export type ExtraMenuOptionMap = Record<string, ExtraMenuOption>;
