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
        account?: Account;
    }

    export interface CustomSong extends Model {
        account_id: number;
        name: string;
        artist_name: string;
        size: number;
        download_url: string;
        account?: Account;
    }

    export interface Level extends Model {
        name: string;
        desc: string;
        coins: number;
        audio_track: number;
        song_id: number;
        downloads: number;
        likes: number;
        length: number;
        version: number;
        objects: number;
        requested_stars: number;
        unlisted: boolean;
        rating?: LevelRating;
        user?: User;
        creator?: User;
        song?: Song;
        scores?: LevelScore[];
        comments?: LevelComment[];
    }

    export interface LevelScore extends Model {
        account_id: number;
        level_id: number;
        percent: number;
        attempts: number;
        coins: number;
        account?: Account;
        level?: Level;
    }

    export interface LevelComment extends Model {
        account_id: number;
        level_id: number;
        comment: string;
        percent: number;
        likes: number;
        span: boolean;
        account?: Account;
        level?: Level;
    }

    export interface LevelRating extends Model {
        level_id: number;
        stars: number;
        difficulty: number;
        featured_score: number;
        epic: boolean;
        auto: boolean;
        demon: boolean;
        demon_difficulty: number;
        coin_verified: boolean;
        level?: Level;
    }

    export interface LevelTempUploadAccess extends Model {
        account_id: number;
        ip: string;
        account?: Account;
    }

    export interface Song extends Model {
        name: string;
        artist_name: string;
        size: number;
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

export interface PaginatedData<T extends unknown> {
    total: number;
    per_page: number;
    current_page: number;
    last_page: number;
    data: T[];
}
