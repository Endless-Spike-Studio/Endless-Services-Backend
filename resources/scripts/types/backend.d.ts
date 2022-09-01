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
        }

        export interface User extends Model {
            name: string;
            uuid: string;
            udid: string;
        }
    }
}
