import {MessageOptions} from "naive-ui"

export interface Model {
    id: number
    created_at: string
    updated_at: string
}

declare namespace GDCS {
    export interface Account extends Model {
        name: string
        email: string
        email_verified_at: string
        failedLogs?: AccountFailedLog[]
    }

    export interface AccountLink extends Model {
        account_id: number
        account: Account
        server: string
        target_name: string
        target_account_id: number
        target_user_id: number
    }

    export interface AccountFailedLog extends Model {
        account_id: number
        account: Account
        content: string
        ip: string
    }

    export interface User extends Model {
        account: Account | undefined
        name: string
        uuid: string | number
        udid: string
    }

    export interface DailyLevel extends Model {
        level_id: number
        apply_at: string
    }

    export interface WeeklyLevel extends Model {
        level_id: number
        apply_at: string
    }

    export interface Level extends Model {
        user_id: number
        user: User
        name: string
        desc: string
        downloads: number
        likes: number
        version: number
        length: number
        password: number
        audio_track: number
        song_id: number
        song: Song
        original_level_id: number
        original: Level
        two_player: boolean
        objects: number
        coins: number
        requested_stars: number
        unlisted: boolean
        ldm: boolean
        created_at: string
        updated_at: string
        rating: LevelRating
        comments: LevelComment[]
        daily: DailyLevel
        weekly: WeeklyLevel
    }

    export interface LevelComment extends Model {
        account_id: number
        account: Account
        level_id: number
        comment: string
        likes: number
        created_at: string
    }

    export interface LevelRating extends Model {
        level_id: number
        difficulty: number
        featured_score: number
        epic: boolean
        demon_difficulty: number
        auto: boolean
        demon: boolean
        stars: number
        coin_verified: boolean
    }

    export interface Song extends Model {
        song_id: number
        name: string
        artist_id: number
        artist_name: string
        size: number
        disabled: boolean
        download_url: string | null
    }

    export interface CustomSong extends Model {
        name: string
        account: Account
        artist_name: string
        size: number
        disabled: boolean
        download_url: string | null
    }

    export interface TempLevelUploadAccess extends Model {
        account_id: number
        ip: string
    }
}

declare namespace NGProxy {
    export interface Song extends Model {
        song_id: number
        name: string
        artist_id: number
        artist_name: string
        size: number
        disabled: boolean
        download_url: string | null
    }
}

export interface User extends Model {
    name: string
    email: string
    email_verified_at: string
}

export interface Message {
    content: string
    options: MessageOptions
}
