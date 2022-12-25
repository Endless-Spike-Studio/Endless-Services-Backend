import {MessageOptions} from "naive-ui";

export interface BackendMessage {
    content: string;
    options: MessageOptions;
}

export interface Model {
    id: number;
    created_at: string | null;
    updated_at: string | null;
}

declare namespace App.Models {
    export interface Account extends Model {
        name: string;
        email: string;
    }

    export interface User extends Model {
        name: string;
        uuid: string;
        udid: string;
    }
}
