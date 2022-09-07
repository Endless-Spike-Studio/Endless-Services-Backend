import {MenuOption} from "naive-ui";

export type ExtraMenuOption = MenuOption & { route?: string; }
export type ExtraMenuOptionMap = Record<string, ExtraMenuOption>;
