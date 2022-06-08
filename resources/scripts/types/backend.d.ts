import {MessageOptions} from "naive-ui";

export interface Model {
    id: number;
    created_at: string;
    updated_at: string;
}

declare namespace GDCS {
    export interface Account extends Model {
        name: string;
        email: string;
        email_verified_at: string;
        failedLogs?: AccountFailedLog[];
    }

    export interface AccountFailedLog extends Model {
        account_id: number;
        content: string;
        ip: string;
    }

    export interface User extends Model {
        name: string;
        uuid: string | number;
        udid: string;
    }
}

export interface User extends Model {
    name: string;
    email: string;
    email_verified_at: string;
}

export interface Song extends Model {
    song_id: number;
    name: string;
    artist_id: number;
    artist_name: string;
    size: number;
    disabled: boolean;
    download_url: string | null;
}

export interface CustomSong extends Model {
    name: string;
    account?: User;
    artist_name: string;
    size: number;
    disabled: boolean;
    download_url: string | null;
}

export interface AccountLink extends Model {
    account_id: number;
    server: string;
    target_name: string;
    target_account_id: number;
    target_user_id: number;
}

export interface TempLevelUploadAccess extends Model {
    account_id: number;
    ip: string;
}

export interface Message {
    content: string;
    options: MessageOptions;
}
