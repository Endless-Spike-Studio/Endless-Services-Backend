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
        email_verified_at: string | null;
        user?: User;
        links?: AccountLink[];
    }

    export interface AccountLink extends Model {
        account_id: number;
        server: string;
        target_name: string;
        target_account_id: number;
        target_user_id: number;
    }

    export interface User extends Model {
        name: string;
        uuid: string;
        udid: string;
        account?: Account;
        score?: UserScore;
    }

    export interface UserScore extends Model {
        user_id: number;
        stars: number;
        demons: number;
        creator_points: number;
        user?: User;
    }
}
