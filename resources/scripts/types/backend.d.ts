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
            comments?: AccountComment[];
            links?: AccountLink[];
        }

        export interface AccountLink extends Model {
            account?: Account;
            account_id: number;
            server: string;
            target_name: string;
            target_account_id: number;
            target_user_id: number;
        }

        export interface AccountComment extends Model {
            account?: Account;
            account_id: number;
            comment: string;
            likes: number;
            spam: boolean;
        }

        export interface AccountFriend extends Model {
            account?: Account;
            account_id: number;
            friend_account?: Account;
            friend_account_id: number;
            new: boolean;
            friend_new: boolean;
        }

        export interface User extends Model {
            name: string;
            uuid: string;
            udid: string;
            levels?: Level[];
            levels_count?: number;
            score?: UserScore;
        }

        export interface Level extends Model {
            user?: User;
            user_id: number;
            name: string;
            desc: string;
            downloads: number;
            likes: number;
            version: number;
            length: number;
            password: number;
            audio_track: number;
            song?: NGProxy.Song;
            song_id: number;
            original?: Level;
            original_level_id: number;
            two_player: boolean;
            objects: number;
            coins: number;
            requested_stars: number;
            unlisted: boolean;
            ldm: boolean;
            created_at: string;
            updated_at: string;
            rating?: LevelRating;
            comments?: LevelComment[];
            daily?: DailyLevel;
            weekly?: WeeklyLevel;
        }

        export interface LevelComment extends Model {
            account?: Account;
            account_id: number;
            level?: Level;
            level_id: number;
            comment: string;
            likes: number;
            created_at: string;
        }

        export interface LevelRating extends Model {
            level?: Level;
            level_id: number;
            difficulty: number;
            featured_score: number;
            epic: boolean;
            demon_difficulty: number;
            auto: boolean;
            demon: boolean;
            stars: number;
            coin_verified: boolean;
        }

        export interface DailyLevel extends Model {
            level?: Level;
            level_id: number;
            apply_at: string;
        }

        export interface WeeklyLevel extends Model {
            level?: Level;
            level_id: number;
            apply_at: string;
        }

        export interface UserScore extends Model {
            user?: User;
            user_id: number;
            stars: number;
            demons: number;
            diamonds: number;
            icon: number;
            icon_type: number;
            coins: number;
            user_coins: number;
            color1: number;
            color2: number;
            special: number;
            acc_icon: number;
            acc_ship: number;
            acc_ball: number;
            acc_bird: number;
            acc_dart: number;
            acc_robot: number;
            acc_glow: number;
            acc_spider: number;
            acc_explosion: number;
            creator_points: number;
        }

        export interface CustomSong extends Model {
            account?: Account;
            account_id: number;
            name: string;
            artist_name: string;
            size: number;
            download_url: string;
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

export interface RemoteLevel {
    id: number;
    name: string;
    desc: string;
    transferred: boolean;
}
