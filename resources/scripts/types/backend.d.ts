import {MessageOptions} from "naive-ui/es/message/src/types";

export interface Message {
    content: string;
    options: MessageOptions;
}

export interface Model {
    id: number;
    created_at: string;
    updated_at: string;
}

declare module App.Models {
    module GDCS {
        export interface Account extends Model {
            name: string;
            email: string;
            email_verified_at: string;
            password: string;
            mod_level: number;
            comment_color: string;
            remember_token: string;
            user?: User;
        }

        export interface User extends Model {
            name: string;
            uuid: string;
            udid: string;
            levels_count?: number;
        }
    }

    module NGProxy {
        export interface Song extends Model {
            song_id: number;
            name: string;
            size: number;
            artist_id: number;
            artist_name: string;
            disabled: boolean;
            original_download_url: string;
            download_url?: string;
            valid?: boolean;
        }
    }
}
