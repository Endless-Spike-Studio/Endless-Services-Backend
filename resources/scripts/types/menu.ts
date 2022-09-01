import {MenuOption} from "naive-ui";

export type ExtraMenuOption = MenuOption & {
    route: string;
    mobileOnly?: boolean;
}
