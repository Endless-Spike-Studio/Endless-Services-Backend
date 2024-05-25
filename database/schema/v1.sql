--
-- PostgreSQL database dump
--

-- Dumped from database version 16.3
-- Dumped by pg_dump version 16.3

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: abilities; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.abilities (
    id bigint NOT NULL,
    name character varying(191) NOT NULL,
    title character varying(191),
    entity_id bigint,
    entity_type character varying(191),
    only_owned boolean DEFAULT false NOT NULL,
    options json,
    scope integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: abilities_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.abilities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: abilities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.abilities_id_seq OWNED BY public.abilities.id;


--
-- Name: assigned_roles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.assigned_roles (
    id bigint NOT NULL,
    role_id bigint NOT NULL,
    entity_id bigint NOT NULL,
    entity_type character varying(191) NOT NULL,
    restricted_to_id bigint,
    restricted_to_type character varying(191),
    scope integer
);


--
-- Name: assigned_roles_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.assigned_roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: assigned_roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.assigned_roles_id_seq OWNED BY public.assigned_roles.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(191) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: gdcs_account_blocks; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_account_blocks (
    id bigint NOT NULL,
    account_id bigint NOT NULL,
    target_account_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_account_blocks_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_account_blocks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_account_blocks_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_account_blocks_id_seq OWNED BY public.gdcs_account_blocks.id;


--
-- Name: gdcs_account_comments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_account_comments (
    id bigint NOT NULL,
    account_id bigint NOT NULL,
    comment character varying(191) NOT NULL,
    likes integer DEFAULT 0 NOT NULL,
    spam boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_account_comments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_account_comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_account_comments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_account_comments_id_seq OWNED BY public.gdcs_account_comments.id;


--
-- Name: gdcs_account_failed_logs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_account_failed_logs (
    id bigint NOT NULL,
    account_id bigint NOT NULL,
    content character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    ip inet
);


--
-- Name: gdcs_account_failed_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_account_failed_logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_account_failed_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_account_failed_logs_id_seq OWNED BY public.gdcs_account_failed_logs.id;


--
-- Name: gdcs_account_friend_requests; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_account_friend_requests (
    id bigint NOT NULL,
    account_id bigint NOT NULL,
    target_account_id bigint NOT NULL,
    comment text,
    new boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_account_friend_requests_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_account_friend_requests_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_account_friend_requests_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_account_friend_requests_id_seq OWNED BY public.gdcs_account_friend_requests.id;


--
-- Name: gdcs_account_friends; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_account_friends (
    id bigint NOT NULL,
    account_id bigint NOT NULL,
    friend_account_id bigint NOT NULL,
    new boolean DEFAULT true NOT NULL,
    friend_new boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_account_friends_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_account_friends_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_account_friends_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_account_friends_id_seq OWNED BY public.gdcs_account_friends.id;


--
-- Name: gdcs_account_links; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_account_links (
    id bigint NOT NULL,
    account_id bigint NOT NULL,
    server character varying(191) NOT NULL,
    target_name character varying(191) NOT NULL,
    target_account_id bigint NOT NULL,
    target_user_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_account_links_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_account_links_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_account_links_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_account_links_id_seq OWNED BY public.gdcs_account_links.id;


--
-- Name: gdcs_account_messages; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_account_messages (
    id bigint NOT NULL,
    account_id bigint NOT NULL,
    target_account_id bigint NOT NULL,
    subject character varying(191) NOT NULL,
    body character varying(191) NOT NULL,
    new boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_account_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_account_messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_account_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_account_messages_id_seq OWNED BY public.gdcs_account_messages.id;


--
-- Name: gdcs_account_settings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_account_settings (
    id bigint NOT NULL,
    account_id bigint NOT NULL,
    message_state smallint NOT NULL,
    friend_request_state smallint NOT NULL,
    comment_history_state smallint NOT NULL,
    youtube_channel character varying(191),
    twitter character varying(191),
    twitch character varying(191),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_account_settings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_account_settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_account_settings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_account_settings_id_seq OWNED BY public.gdcs_account_settings.id;


--
-- Name: gdcs_accounts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_accounts (
    id bigint NOT NULL,
    name character varying(191) NOT NULL,
    email character varying(191) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(191) NOT NULL,
    mod_level smallint DEFAULT '0'::smallint NOT NULL,
    comment_color character varying(11) DEFAULT '255,255,255'::character varying NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_accounts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_accounts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_accounts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_accounts_id_seq OWNED BY public.gdcs_accounts.id;


--
-- Name: gdcs_banned_users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_banned_users (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    login_ban boolean DEFAULT false NOT NULL,
    comment_ban boolean DEFAULT false NOT NULL,
    expires_at timestamp(0) without time zone,
    reason character varying(191),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_banned_users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_banned_users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_banned_users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_banned_users_id_seq OWNED BY public.gdcs_banned_users.id;


--
-- Name: gdcs_challenges; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_challenges (
    id bigint NOT NULL,
    type smallint NOT NULL,
    name character varying(191) NOT NULL,
    collect integer NOT NULL,
    reward integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_challenges_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_challenges_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_challenges_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_challenges_id_seq OWNED BY public.gdcs_challenges.id;


--
-- Name: gdcs_contest_participants; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_contest_participants (
    id bigint NOT NULL,
    contest_id bigint NOT NULL,
    account_id bigint NOT NULL,
    level_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_contest_participants_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_contest_participants_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_contest_participants_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_contest_participants_id_seq OWNED BY public.gdcs_contest_participants.id;


--
-- Name: gdcs_contests; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_contests (
    id bigint NOT NULL,
    account_id bigint NOT NULL,
    name character varying(191) NOT NULL,
    "desc" character varying(191) NOT NULL,
    rules json NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deadline_at timestamp(0) without time zone
);


--
-- Name: gdcs_contests_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_contests_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_contests_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_contests_id_seq OWNED BY public.gdcs_contests.id;


--
-- Name: gdcs_custom_songs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_custom_songs (
    id bigint NOT NULL,
    account_id bigint NOT NULL,
    name character varying(191) NOT NULL,
    artist_name character varying(191) NOT NULL,
    size numeric(8,2) NOT NULL,
    download_url character varying(191),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_custom_songs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_custom_songs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_custom_songs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_custom_songs_id_seq OWNED BY public.gdcs_custom_songs.id;


--
-- Name: gdcs_daily_levels; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_daily_levels (
    id bigint NOT NULL,
    level_id bigint NOT NULL,
    apply_at date NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_daily_levels_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_daily_levels_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_daily_levels_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_daily_levels_id_seq OWNED BY public.gdcs_daily_levels.id;


--
-- Name: gdcs_level_comments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_level_comments (
    id bigint NOT NULL,
    account_id bigint NOT NULL,
    level_id bigint NOT NULL,
    comment character varying(191) NOT NULL,
    percent smallint DEFAULT '0'::smallint NOT NULL,
    likes integer DEFAULT 0 NOT NULL,
    spam boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_level_comments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_level_comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_level_comments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_level_comments_id_seq OWNED BY public.gdcs_level_comments.id;


--
-- Name: gdcs_level_demon_difficulty_suggestions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_level_demon_difficulty_suggestions (
    id bigint NOT NULL,
    ip inet NOT NULL,
    user_id bigint NOT NULL,
    level_id bigint NOT NULL,
    demon_diff smallint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_level_demon_difficulty_suggestions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_level_demon_difficulty_suggestions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_level_demon_difficulty_suggestions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_level_demon_difficulty_suggestions_id_seq OWNED BY public.gdcs_level_demon_difficulty_suggestions.id;


--
-- Name: gdcs_level_download_records; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_level_download_records (
    id bigint NOT NULL,
    level_id bigint NOT NULL,
    ip inet NOT NULL,
    user_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_level_download_records_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_level_download_records_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_level_download_records_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_level_download_records_id_seq OWNED BY public.gdcs_level_download_records.id;


--
-- Name: gdcs_level_gauntlets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_level_gauntlets (
    id bigint NOT NULL,
    gauntlet_id smallint NOT NULL,
    level1_id bigint NOT NULL,
    level2_id bigint NOT NULL,
    level3_id bigint NOT NULL,
    level4_id bigint NOT NULL,
    level5_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_level_gauntlets_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_level_gauntlets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_level_gauntlets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_level_gauntlets_id_seq OWNED BY public.gdcs_level_gauntlets.id;


--
-- Name: gdcs_level_packs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_level_packs (
    id bigint NOT NULL,
    name character varying(191) NOT NULL,
    levels character varying(191) NOT NULL,
    stars smallint NOT NULL,
    coins smallint DEFAULT '0'::smallint NOT NULL,
    difficulty smallint NOT NULL,
    text_color character varying(191) DEFAULT '255,255,255'::character varying NOT NULL,
    bar_color character varying(191) DEFAULT '255,255,255'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_level_packs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_level_packs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_level_packs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_level_packs_id_seq OWNED BY public.gdcs_level_packs.id;


--
-- Name: gdcs_level_rating_suggestions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_level_rating_suggestions (
    id bigint NOT NULL,
    account_id bigint NOT NULL,
    level_id bigint NOT NULL,
    rating smallint NOT NULL,
    featured boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_level_rating_suggestions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_level_rating_suggestions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_level_rating_suggestions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_level_rating_suggestions_id_seq OWNED BY public.gdcs_level_rating_suggestions.id;


--
-- Name: gdcs_level_ratings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_level_ratings (
    id bigint NOT NULL,
    level_id bigint NOT NULL,
    stars smallint NOT NULL,
    difficulty smallint NOT NULL,
    featured_score integer DEFAULT 0 NOT NULL,
    epic boolean DEFAULT false NOT NULL,
    coin_verified boolean DEFAULT false NOT NULL,
    demon_difficulty smallint DEFAULT '0'::smallint NOT NULL,
    auto boolean DEFAULT false NOT NULL,
    demon boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_level_ratings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_level_ratings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_level_ratings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_level_ratings_id_seq OWNED BY public.gdcs_level_ratings.id;


--
-- Name: gdcs_level_scores; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_level_scores (
    id bigint NOT NULL,
    level_id bigint NOT NULL,
    account_id bigint NOT NULL,
    attempts integer DEFAULT 0 NOT NULL,
    percent smallint DEFAULT '0'::smallint NOT NULL,
    coins smallint DEFAULT '0'::smallint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_level_scores_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_level_scores_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_level_scores_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_level_scores_id_seq OWNED BY public.gdcs_level_scores.id;


--
-- Name: gdcs_level_star_suggestions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_level_star_suggestions (
    id bigint NOT NULL,
    ip inet NOT NULL,
    user_id bigint NOT NULL,
    level_id bigint NOT NULL,
    stars smallint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_level_star_suggestions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_level_star_suggestions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_level_star_suggestions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_level_star_suggestions_id_seq OWNED BY public.gdcs_level_star_suggestions.id;


--
-- Name: gdcs_level_transfer_records; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_level_transfer_records (
    id bigint NOT NULL,
    server character varying(191) NOT NULL,
    account_id bigint NOT NULL,
    original_level_id bigint NOT NULL,
    level_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_level_transfer_records_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_level_transfer_records_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_level_transfer_records_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_level_transfer_records_id_seq OWNED BY public.gdcs_level_transfer_records.id;


--
-- Name: gdcs_levels; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_levels (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    game_version smallint NOT NULL,
    name character varying(191) NOT NULL,
    "desc" character varying(191),
    downloads integer DEFAULT 0 NOT NULL,
    likes integer DEFAULT 0 NOT NULL,
    version integer NOT NULL,
    length smallint NOT NULL,
    audio_track smallint NOT NULL,
    song_id bigint NOT NULL,
    auto boolean NOT NULL,
    password integer NOT NULL,
    original_level_id bigint NOT NULL,
    two_player boolean NOT NULL,
    objects integer NOT NULL,
    coins smallint NOT NULL,
    requested_stars smallint NOT NULL,
    unlisted boolean NOT NULL,
    ldm boolean NOT NULL,
    extra_string text NOT NULL,
    level_info text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_levels_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_levels_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_levels_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_levels_id_seq OWNED BY public.gdcs_levels.id;


--
-- Name: gdcs_like_records; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_like_records (
    id bigint NOT NULL,
    type smallint NOT NULL,
    ip inet NOT NULL,
    item_id bigint NOT NULL,
    user_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_like_records_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_like_records_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_like_records_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_like_records_id_seq OWNED BY public.gdcs_like_records.id;


--
-- Name: gdcs_temp_level_upload_accesses; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_temp_level_upload_accesses (
    id bigint NOT NULL,
    account_id bigint NOT NULL,
    ip inet NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_temp_level_upload_accesses_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_temp_level_upload_accesses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_temp_level_upload_accesses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_temp_level_upload_accesses_id_seq OWNED BY public.gdcs_temp_level_upload_accesses.id;


--
-- Name: gdcs_user_daily_chest; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_user_daily_chest (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    small_time timestamp(0) without time zone,
    small_count integer DEFAULT 0 NOT NULL,
    big_time timestamp(0) without time zone,
    big_count integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_user_daily_chest_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_user_daily_chest_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_user_daily_chest_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_user_daily_chest_id_seq OWNED BY public.gdcs_user_daily_chest.id;


--
-- Name: gdcs_user_scores; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_user_scores (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    stars integer NOT NULL,
    demons integer NOT NULL,
    diamonds smallint NOT NULL,
    icon smallint NOT NULL,
    icon_type smallint NOT NULL,
    coins integer NOT NULL,
    user_coins integer NOT NULL,
    color1 smallint NOT NULL,
    color2 smallint DEFAULT '3'::smallint NOT NULL,
    special smallint NOT NULL,
    acc_icon smallint NOT NULL,
    acc_ship smallint NOT NULL,
    acc_ball smallint NOT NULL,
    acc_bird smallint NOT NULL,
    acc_dart smallint NOT NULL,
    acc_robot smallint NOT NULL,
    acc_glow smallint NOT NULL,
    acc_spider smallint NOT NULL,
    acc_explosion smallint NOT NULL,
    creator_points integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_user_scores_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_user_scores_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_user_scores_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_user_scores_id_seq OWNED BY public.gdcs_user_scores.id;


--
-- Name: gdcs_users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_users (
    id bigint NOT NULL,
    name character varying(191) NOT NULL,
    uuid character varying(191) NOT NULL,
    udid character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_users_id_seq OWNED BY public.gdcs_users.id;


--
-- Name: gdcs_weekly_levels; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdcs_weekly_levels (
    id bigint NOT NULL,
    level_id bigint NOT NULL,
    apply_at date NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdcs_weekly_levels_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdcs_weekly_levels_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdcs_weekly_levels_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdcs_weekly_levels_id_seq OWNED BY public.gdcs_weekly_levels.id;


--
-- Name: gdproxy_level_song_replaces; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gdproxy_level_song_replaces (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    level_id bigint NOT NULL,
    song_id bigint NOT NULL,
    "offset" double precision,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gdproxy_level_song_replaces_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gdproxy_level_song_replaces_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gdproxy_level_song_replaces_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gdproxy_level_song_replaces_id_seq OWNED BY public.gdproxy_level_song_replaces.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(191) NOT NULL,
    batch integer NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: ngproxy_songs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ngproxy_songs (
    id bigint NOT NULL,
    song_id bigint NOT NULL,
    name character varying(191) NOT NULL,
    artist_id bigint NOT NULL,
    artist_name character varying(191) NOT NULL,
    size numeric(8,2) NOT NULL,
    disabled boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    original_download_url character varying(191)
);


--
-- Name: ngproxy_songs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.ngproxy_songs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ngproxy_songs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.ngproxy_songs_id_seq OWNED BY public.ngproxy_songs.id;


--
-- Name: permissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.permissions (
    id bigint NOT NULL,
    ability_id bigint NOT NULL,
    entity_id bigint,
    entity_type character varying(191),
    forbidden boolean DEFAULT false NOT NULL,
    scope integer
);


--
-- Name: permissions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.permissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;


--
-- Name: roles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.roles (
    id bigint NOT NULL,
    name character varying(191) NOT NULL,
    title character varying(191),
    scope integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- Name: abilities id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.abilities ALTER COLUMN id SET DEFAULT nextval('public.abilities_id_seq'::regclass);


--
-- Name: assigned_roles id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.assigned_roles ALTER COLUMN id SET DEFAULT nextval('public.assigned_roles_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: gdcs_account_blocks id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_account_blocks ALTER COLUMN id SET DEFAULT nextval('public.gdcs_account_blocks_id_seq'::regclass);


--
-- Name: gdcs_account_comments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_account_comments ALTER COLUMN id SET DEFAULT nextval('public.gdcs_account_comments_id_seq'::regclass);


--
-- Name: gdcs_account_failed_logs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_account_failed_logs ALTER COLUMN id SET DEFAULT nextval('public.gdcs_account_failed_logs_id_seq'::regclass);


--
-- Name: gdcs_account_friend_requests id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_account_friend_requests ALTER COLUMN id SET DEFAULT nextval('public.gdcs_account_friend_requests_id_seq'::regclass);


--
-- Name: gdcs_account_friends id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_account_friends ALTER COLUMN id SET DEFAULT nextval('public.gdcs_account_friends_id_seq'::regclass);


--
-- Name: gdcs_account_links id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_account_links ALTER COLUMN id SET DEFAULT nextval('public.gdcs_account_links_id_seq'::regclass);


--
-- Name: gdcs_account_messages id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_account_messages ALTER COLUMN id SET DEFAULT nextval('public.gdcs_account_messages_id_seq'::regclass);


--
-- Name: gdcs_account_settings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_account_settings ALTER COLUMN id SET DEFAULT nextval('public.gdcs_account_settings_id_seq'::regclass);


--
-- Name: gdcs_accounts id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_accounts ALTER COLUMN id SET DEFAULT nextval('public.gdcs_accounts_id_seq'::regclass);


--
-- Name: gdcs_banned_users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_banned_users ALTER COLUMN id SET DEFAULT nextval('public.gdcs_banned_users_id_seq'::regclass);


--
-- Name: gdcs_challenges id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_challenges ALTER COLUMN id SET DEFAULT nextval('public.gdcs_challenges_id_seq'::regclass);


--
-- Name: gdcs_contest_participants id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_contest_participants ALTER COLUMN id SET DEFAULT nextval('public.gdcs_contest_participants_id_seq'::regclass);


--
-- Name: gdcs_contests id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_contests ALTER COLUMN id SET DEFAULT nextval('public.gdcs_contests_id_seq'::regclass);


--
-- Name: gdcs_custom_songs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_custom_songs ALTER COLUMN id SET DEFAULT nextval('public.gdcs_custom_songs_id_seq'::regclass);


--
-- Name: gdcs_daily_levels id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_daily_levels ALTER COLUMN id SET DEFAULT nextval('public.gdcs_daily_levels_id_seq'::regclass);


--
-- Name: gdcs_level_comments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_level_comments ALTER COLUMN id SET DEFAULT nextval('public.gdcs_level_comments_id_seq'::regclass);


--
-- Name: gdcs_level_demon_difficulty_suggestions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_level_demon_difficulty_suggestions ALTER COLUMN id SET DEFAULT nextval('public.gdcs_level_demon_difficulty_suggestions_id_seq'::regclass);


--
-- Name: gdcs_level_download_records id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_level_download_records ALTER COLUMN id SET DEFAULT nextval('public.gdcs_level_download_records_id_seq'::regclass);


--
-- Name: gdcs_level_gauntlets id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_level_gauntlets ALTER COLUMN id SET DEFAULT nextval('public.gdcs_level_gauntlets_id_seq'::regclass);


--
-- Name: gdcs_level_packs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_level_packs ALTER COLUMN id SET DEFAULT nextval('public.gdcs_level_packs_id_seq'::regclass);


--
-- Name: gdcs_level_rating_suggestions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_level_rating_suggestions ALTER COLUMN id SET DEFAULT nextval('public.gdcs_level_rating_suggestions_id_seq'::regclass);


--
-- Name: gdcs_level_ratings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_level_ratings ALTER COLUMN id SET DEFAULT nextval('public.gdcs_level_ratings_id_seq'::regclass);


--
-- Name: gdcs_level_scores id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_level_scores ALTER COLUMN id SET DEFAULT nextval('public.gdcs_level_scores_id_seq'::regclass);


--
-- Name: gdcs_level_star_suggestions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_level_star_suggestions ALTER COLUMN id SET DEFAULT nextval('public.gdcs_level_star_suggestions_id_seq'::regclass);


--
-- Name: gdcs_level_transfer_records id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_level_transfer_records ALTER COLUMN id SET DEFAULT nextval('public.gdcs_level_transfer_records_id_seq'::regclass);


--
-- Name: gdcs_levels id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_levels ALTER COLUMN id SET DEFAULT nextval('public.gdcs_levels_id_seq'::regclass);


--
-- Name: gdcs_like_records id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_like_records ALTER COLUMN id SET DEFAULT nextval('public.gdcs_like_records_id_seq'::regclass);


--
-- Name: gdcs_temp_level_upload_accesses id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_temp_level_upload_accesses ALTER COLUMN id SET DEFAULT nextval('public.gdcs_temp_level_upload_accesses_id_seq'::regclass);


--
-- Name: gdcs_user_daily_chest id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_user_daily_chest ALTER COLUMN id SET DEFAULT nextval('public.gdcs_user_daily_chest_id_seq'::regclass);


--
-- Name: gdcs_user_scores id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_user_scores ALTER COLUMN id SET DEFAULT nextval('public.gdcs_user_scores_id_seq'::regclass);


--
-- Name: gdcs_users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_users ALTER COLUMN id SET DEFAULT nextval('public.gdcs_users_id_seq'::regclass);


--
-- Name: gdcs_weekly_levels id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_weekly_levels ALTER COLUMN id SET DEFAULT nextval('public.gdcs_weekly_levels_id_seq'::regclass);


--
-- Name: gdproxy_level_song_replaces id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdproxy_level_song_replaces ALTER COLUMN id SET DEFAULT nextval('public.gdproxy_level_song_replaces_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: ngproxy_songs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ngproxy_songs ALTER COLUMN id SET DEFAULT nextval('public.ngproxy_songs_id_seq'::regclass);


--
-- Name: permissions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);


--
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- Name: abilities abilities_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.abilities
    ADD CONSTRAINT abilities_pkey PRIMARY KEY (id);


--
-- Name: assigned_roles assigned_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.assigned_roles
    ADD CONSTRAINT assigned_roles_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: gdcs_account_blocks gdcs_account_blocks_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_account_blocks
    ADD CONSTRAINT gdcs_account_blocks_pkey PRIMARY KEY (id);


--
-- Name: gdcs_account_comments gdcs_account_comments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_account_comments
    ADD CONSTRAINT gdcs_account_comments_pkey PRIMARY KEY (id);


--
-- Name: gdcs_account_failed_logs gdcs_account_failed_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_account_failed_logs
    ADD CONSTRAINT gdcs_account_failed_logs_pkey PRIMARY KEY (id);


--
-- Name: gdcs_account_friend_requests gdcs_account_friend_requests_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_account_friend_requests
    ADD CONSTRAINT gdcs_account_friend_requests_pkey PRIMARY KEY (id);


--
-- Name: gdcs_account_friends gdcs_account_friends_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_account_friends
    ADD CONSTRAINT gdcs_account_friends_pkey PRIMARY KEY (id);


--
-- Name: gdcs_account_links gdcs_account_links_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_account_links
    ADD CONSTRAINT gdcs_account_links_pkey PRIMARY KEY (id);


--
-- Name: gdcs_account_messages gdcs_account_messages_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_account_messages
    ADD CONSTRAINT gdcs_account_messages_pkey PRIMARY KEY (id);


--
-- Name: gdcs_account_settings gdcs_account_settings_account_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_account_settings
    ADD CONSTRAINT gdcs_account_settings_account_id_unique UNIQUE (account_id);


--
-- Name: gdcs_account_settings gdcs_account_settings_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_account_settings
    ADD CONSTRAINT gdcs_account_settings_pkey PRIMARY KEY (id);


--
-- Name: gdcs_accounts gdcs_accounts_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_accounts
    ADD CONSTRAINT gdcs_accounts_email_unique UNIQUE (email);


--
-- Name: gdcs_accounts gdcs_accounts_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_accounts
    ADD CONSTRAINT gdcs_accounts_name_unique UNIQUE (name);


--
-- Name: gdcs_accounts gdcs_accounts_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_accounts
    ADD CONSTRAINT gdcs_accounts_pkey PRIMARY KEY (id);


--
-- Name: gdcs_banned_users gdcs_banned_users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_banned_users
    ADD CONSTRAINT gdcs_banned_users_pkey PRIMARY KEY (id);


--
-- Name: gdcs_challenges gdcs_challenges_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_challenges
    ADD CONSTRAINT gdcs_challenges_pkey PRIMARY KEY (id);


--
-- Name: gdcs_contest_participants gdcs_contest_participants_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_contest_participants
    ADD CONSTRAINT gdcs_contest_participants_pkey PRIMARY KEY (id);


--
-- Name: gdcs_contests gdcs_contests_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_contests
    ADD CONSTRAINT gdcs_contests_pkey PRIMARY KEY (id);


--
-- Name: gdcs_custom_songs gdcs_custom_songs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_custom_songs
    ADD CONSTRAINT gdcs_custom_songs_pkey PRIMARY KEY (id);


--
-- Name: gdcs_daily_levels gdcs_daily_levels_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_daily_levels
    ADD CONSTRAINT gdcs_daily_levels_pkey PRIMARY KEY (id);


--
-- Name: gdcs_level_comments gdcs_level_comments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_level_comments
    ADD CONSTRAINT gdcs_level_comments_pkey PRIMARY KEY (id);


--
-- Name: gdcs_level_demon_difficulty_suggestions gdcs_level_demon_difficulty_suggestions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_level_demon_difficulty_suggestions
    ADD CONSTRAINT gdcs_level_demon_difficulty_suggestions_pkey PRIMARY KEY (id);


--
-- Name: gdcs_level_download_records gdcs_level_download_records_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_level_download_records
    ADD CONSTRAINT gdcs_level_download_records_pkey PRIMARY KEY (id);


--
-- Name: gdcs_level_gauntlets gdcs_level_gauntlets_gauntlet_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_level_gauntlets
    ADD CONSTRAINT gdcs_level_gauntlets_gauntlet_id_unique UNIQUE (gauntlet_id);


--
-- Name: gdcs_level_gauntlets gdcs_level_gauntlets_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_level_gauntlets
    ADD CONSTRAINT gdcs_level_gauntlets_pkey PRIMARY KEY (id);


--
-- Name: gdcs_level_packs gdcs_level_packs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_level_packs
    ADD CONSTRAINT gdcs_level_packs_pkey PRIMARY KEY (id);


--
-- Name: gdcs_level_rating_suggestions gdcs_level_rating_suggestions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_level_rating_suggestions
    ADD CONSTRAINT gdcs_level_rating_suggestions_pkey PRIMARY KEY (id);


--
-- Name: gdcs_level_ratings gdcs_level_ratings_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_level_ratings
    ADD CONSTRAINT gdcs_level_ratings_pkey PRIMARY KEY (id);


--
-- Name: gdcs_level_scores gdcs_level_scores_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_level_scores
    ADD CONSTRAINT gdcs_level_scores_pkey PRIMARY KEY (id);


--
-- Name: gdcs_level_star_suggestions gdcs_level_star_suggestions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_level_star_suggestions
    ADD CONSTRAINT gdcs_level_star_suggestions_pkey PRIMARY KEY (id);


--
-- Name: gdcs_level_transfer_records gdcs_level_transfer_records_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_level_transfer_records
    ADD CONSTRAINT gdcs_level_transfer_records_pkey PRIMARY KEY (id);


--
-- Name: gdcs_levels gdcs_levels_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_levels
    ADD CONSTRAINT gdcs_levels_pkey PRIMARY KEY (id);


--
-- Name: gdcs_like_records gdcs_like_records_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_like_records
    ADD CONSTRAINT gdcs_like_records_pkey PRIMARY KEY (id);


--
-- Name: gdcs_temp_level_upload_accesses gdcs_temp_level_upload_accesses_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_temp_level_upload_accesses
    ADD CONSTRAINT gdcs_temp_level_upload_accesses_pkey PRIMARY KEY (id);


--
-- Name: gdcs_user_daily_chest gdcs_user_daily_chest_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_user_daily_chest
    ADD CONSTRAINT gdcs_user_daily_chest_pkey PRIMARY KEY (id);


--
-- Name: gdcs_user_daily_chest gdcs_user_daily_chest_user_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_user_daily_chest
    ADD CONSTRAINT gdcs_user_daily_chest_user_id_unique UNIQUE (user_id);


--
-- Name: gdcs_user_scores gdcs_user_scores_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_user_scores
    ADD CONSTRAINT gdcs_user_scores_pkey PRIMARY KEY (id);


--
-- Name: gdcs_user_scores gdcs_user_scores_user_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_user_scores
    ADD CONSTRAINT gdcs_user_scores_user_id_unique UNIQUE (user_id);


--
-- Name: gdcs_users gdcs_users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_users
    ADD CONSTRAINT gdcs_users_pkey PRIMARY KEY (id);


--
-- Name: gdcs_users gdcs_users_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_users
    ADD CONSTRAINT gdcs_users_uuid_unique UNIQUE (uuid);


--
-- Name: gdcs_weekly_levels gdcs_weekly_levels_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdcs_weekly_levels
    ADD CONSTRAINT gdcs_weekly_levels_pkey PRIMARY KEY (id);


--
-- Name: gdproxy_level_song_replaces gdproxy_level_song_replaces_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gdproxy_level_song_replaces
    ADD CONSTRAINT gdproxy_level_song_replaces_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: ngproxy_songs ngproxy_songs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ngproxy_songs
    ADD CONSTRAINT ngproxy_songs_pkey PRIMARY KEY (id);


--
-- Name: ngproxy_songs ngproxy_songs_song_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ngproxy_songs
    ADD CONSTRAINT ngproxy_songs_song_id_unique UNIQUE (song_id);


--
-- Name: permissions permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);


--
-- Name: roles roles_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_name_unique UNIQUE (name, scope);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: abilities_scope_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX abilities_scope_index ON public.abilities USING btree (scope);


--
-- Name: assigned_roles_entity_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX assigned_roles_entity_index ON public.assigned_roles USING btree (entity_id, entity_type, scope);


--
-- Name: assigned_roles_role_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX assigned_roles_role_id_index ON public.assigned_roles USING btree (role_id);


--
-- Name: assigned_roles_scope_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX assigned_roles_scope_index ON public.assigned_roles USING btree (scope);


--
-- Name: gdcs_account_comments_account_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX gdcs_account_comments_account_id_index ON public.gdcs_account_comments USING btree (account_id);


--
-- Name: gdcs_level_comments_account_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX gdcs_level_comments_account_id_index ON public.gdcs_level_comments USING btree (account_id);


--
-- Name: gdcs_level_comments_level_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX gdcs_level_comments_level_id_index ON public.gdcs_level_comments USING btree (level_id);


--
-- Name: permissions_ability_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX permissions_ability_id_index ON public.permissions USING btree (ability_id);


--
-- Name: permissions_entity_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX permissions_entity_index ON public.permissions USING btree (entity_id, entity_type, scope);


--
-- Name: permissions_scope_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX permissions_scope_index ON public.permissions USING btree (scope);


--
-- Name: roles_scope_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX roles_scope_index ON public.roles USING btree (scope);


--
-- Name: assigned_roles assigned_roles_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.assigned_roles
    ADD CONSTRAINT assigned_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: permissions permissions_ability_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_ability_id_foreign FOREIGN KEY (ability_id) REFERENCES public.abilities(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

-- Dumped from database version 16.3
-- Dumped by pg_dump version 16.3

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	2014_10_12_000000_create_users_table	1
2	2014_10_12_100000_create_password_resets_table	1
3	2019_08_19_000000_create_failed_jobs_table	1
4	2019_12_14_000001_create_personal_access_tokens_table	1
5	2022_03_15_154201_create_gdcs_accounts_table	1
6	2022_03_15_155611_create_gdcs_users_table	1
7	2022_03_15_173820_create_gdcs_user_scores_table	1
8	2022_03_16_162725_create_gdcs_account_settings_table	1
9	2022_03_17_150505_create_gdcs_account_comments_table	1
10	2022_03_18_234643_create_gdcs_user_daily_chest_table	1
11	2022_03_19_221952_create_gdcs_challenges_table	1
12	2022_03_20_144624_create_gdcs_account_messages_table	1
13	2022_03_20_161952_create_gdcs_account_friend_requests_table	1
14	2022_03_20_162803_create_gdcs_account_friends_table	1
15	2022_03_21_143025_create_gdcs_levels_table	1
16	2022_03_21_184724_create_ngproxy_songs_table	1
17	2022_03_23_134434_create_gdcs_level_ratings_table	1
18	2022_03_23_181214_create_gdcs_level_comments_table	1
19	2022_03_23_191421_create_gdcs_account_blocks_table	1
20	2022_03_24_114809_create_gdcs_level_packs_table	1
21	2022_03_24_184250_create_gdcs_daily_levels_table	1
22	2022_03_24_184403_create_gdcs_weekly_levels_table	1
23	2022_03_24_191441_create_gdcs_level_download_records_table	1
24	2022_03_24_193546_create_gdcs_level_gauntlets_table	1
25	2022_03_25_100930_create_gdcs_like_records_table	1
26	2022_03_25_101833_create_gdcs_level_scores_table	1
27	2022_03_25_104134_create_gdcs_level_star_suggestions_table	1
28	2022_03_25_104313_create_gdcs_level_demon_difficulty_suggestions_table	1
29	2022_03_25_104433_create_gdcs_level_rating_suggestions_table	1
30	2022_03_26_105905_create_gdcs_temp_level_upload_accesses_table	1
31	2022_03_27_145324_create_gdcs_level_transfer_records_table	1
32	2022_03_27_214539_create_gdproxy_level_song_replaces_table	1
33	2022_03_27_222543_create_gdproxy_custom_songs_table	1
34	2022_03_29_101804_create_gdcs_account_links_table	1
35	2022_04_03_232258_create_gdcs_banned_users_table	1
36	2022_04_09_223925_create_gdcs_custom_songs_table	1
37	2022_04_13_152621_create_permission_tables	1
38	2022_05_11_191142_create_gdcs_contests_table	1
39	2022_05_21_214953_add_original_download_url_to_ngproxy_songs_table	1
40	2022_06_06_171210_create_gdcs_account_failed_logs_table	1
41	2022_06_07_220325_add_ip_to_gdcs_account_failed_logs_table	1
42	2022_07_04_094637_delete_permission_tables	1
43	2022_07_04_095035_create_bouncer_tables	1
44	2022_07_20_192600_change_level_info_in_gdcs_levels_table	1
45	2022_07_21_220116_change_comment_in_gdcs_account_friend_requests_table	1
46	2022_07_22_211626_drop_users_table	1
47	2022_07_22_211857_drop_password_resets_table	1
48	2022_07_22_211929_drop_personal_access_tokens_table	1
49	2022_08_28_234924_delete_gdproxy_custom_songs_table	1
50	2022_10_09_160454_delete_gdcs_level_trasnfer_records_table	1
51	2022_10_09_160516_create_gdcs_level_transfer_records_table	1
52	2023_01_05_205210_create_gdcs_contest_participants_table	1
53	2023_01_08_150322_add_deadline_at_to_gdcs_contests_table	1
\.


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.migrations_id_seq', 53, true);


--
-- PostgreSQL database dump complete
--

