import {MessageOptions} from "naive-ui";

export interface BackendMessage {
    content: string;
    options: MessageOptions;
}

declare namespace App.Models {
    interface _ {
        id: number;
        created_at: string | null;
        updated_at: string | null;
    }

    export interface Account extends _ {
        name: string;
        email: string;
        email_verified_at: string | null;
        user?: User;
        links?: AccountLink[];
    }

    export interface AccountLink extends _ {
        account_id: number;
        server: string;
        target_name: string;
        target_account_id: number;
        target_user_id: number;
        account?: Account;
    }

    export interface Contest extends _ {
        account_id: number;
        name: string;
        desc: string;
        rules: string;
        deadline_at: string | null;
        account?: Account;
    }

    export interface ContestParticipant extends _ {
        contest_id: number;
        account_id: number;
        level_id: number;
        contest?: Contest;
        account?: Account;
        level?: Level;
    }

    export interface CustomSong extends _ {
        account_id: number;
        name: string;
        artist_name: string;
        size: number;
        download_url: string;
        account?: Account;
    }

    export interface Level extends _ {
        name: string;
        desc: string;
        coins: number;
        audio_track: number;
        song_id: number;
        downloads: number;
        likes: number;
        password: number;
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

    export interface LevelScore extends _ {
        account_id: number;
        level_id: number;
        percent: number;
        attempts: number;
        coins: number;
        account?: Account;
        level?: Level;
    }

    export interface LevelComment extends _ {
        account_id: number;
        level_id: number;
        comment: string;
        percent: number;
        likes: number;
        spam: boolean;
        account?: Account;
        level?: Level;
    }

    export interface LevelRating extends _ {
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

    export interface LevelTempUploadAccess extends _ {
        account_id: number;
        ip: string;
        account?: Account;
    }

    export interface Song extends _ {
        song_id: number;
        name: string;
        artist_name: string;
        size: number;
        disabled: boolean;
        download_url: string;
    }

    export interface User extends _ {
        name: string;
        uuid: string;
        udid: string;
        account?: Account;
        score?: UserScore;
    }

    export interface UserScore extends _ {
        user_id: number;
        stars: number;
        coins: number;
        user_coins: number;
        demons: number;
        creator_points: number;
        user?: User;
    }
}
