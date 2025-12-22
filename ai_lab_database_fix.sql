--
-- PostgreSQL database dump
--

-- Dumped from database version 17.4
-- Dumped by pg_dump version 17.4

-- Started on 2025-12-22 11:45:10

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 6 (class 2615 OID 42189)
-- Name: public; Type: SCHEMA; Schema: -; Owner: postgres
--

-- *not* creating schema, since initdb creates it


ALTER SCHEMA public OWNER TO postgres;

--
-- TOC entry 5170 (class 0 OID 0)
-- Dependencies: 6
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: postgres
--

COMMENT ON SCHEMA public IS '';


--
-- TOC entry 2 (class 3079 OID 42581)
-- Name: pgcrypto; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS pgcrypto WITH SCHEMA public;


--
-- TOC entry 5172 (class 0 OID 0)
-- Dependencies: 2
-- Name: EXTENSION pgcrypto; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION pgcrypto IS 'cryptographic functions';


--
-- TOC entry 926 (class 1247 OID 42241)
-- Name: permit_status; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.permit_status AS ENUM (
    'pending',
    'accepted',
    'rejected'
);


ALTER TYPE public.permit_status OWNER TO postgres;

--
-- TOC entry 275 (class 1255 OID 42321)
-- Name: trg_check_permit_limit(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.trg_check_permit_limit() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    DECLARE
        cnt INTEGER;
    BEGIN
        SELECT COUNT(*) INTO cnt FROM lab_permit_requests WHERE email = NEW.email;

        IF TG_OP = 'INSERT' THEN
            IF cnt >= 3 THEN
                RAISE EXCEPTION 'Max 3 submissions allowed for %', NEW.email;
            END IF;
        END IF;

        RETURN NEW;
    END;
    $$;


ALTER FUNCTION public.trg_check_permit_limit() OWNER TO postgres;

--
-- TOC entry 276 (class 1255 OID 42323)
-- Name: trg_limit_admins(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.trg_limit_admins() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    DECLARE cnt INTEGER;
    BEGIN
        IF NEW.role = 'admin' THEN
            SELECT COUNT(*) INTO cnt FROM users WHERE role = 'admin';
            IF cnt >= 5 THEN
                RAISE EXCEPTION 'Max 5 admins allowed.';
            END IF;
        END IF;
        RETURN NEW;
    END;
    $$;


ALTER FUNCTION public.trg_limit_admins() OWNER TO postgres;

--
-- TOC entry 278 (class 1255 OID 42325)
-- Name: trg_set_updated_at(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.trg_set_updated_at() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
            BEGIN
                NEW.updated_at = now();
                RETURN NEW;
            END;
            $$;


ALTER FUNCTION public.trg_set_updated_at() OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 236 (class 1259 OID 42740)
-- Name: activities; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.activities (
    id bigint NOT NULL,
    title character varying(255) NOT NULL,
    label character varying(255),
    short_description text,
    full_description text,
    published_at date,
    thumbnail_image character varying(255),
    banner_image character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    status character varying(255) DEFAULT 'progress'::character varying NOT NULL,
    document_link character varying(255),
    CONSTRAINT activities_status_check CHECK (((status)::text = ANY ((ARRAY['published'::character varying, 'progress'::character varying, 'cancelled'::character varying])::text[])))
);


ALTER TABLE public.activities OWNER TO postgres;

--
-- TOC entry 235 (class 1259 OID 42739)
-- Name: activities_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.activities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.activities_id_seq OWNER TO postgres;

--
-- TOC entry 5173 (class 0 OID 0)
-- Dependencies: 235
-- Name: activities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.activities_id_seq OWNED BY public.activities.id;


--
-- TOC entry 232 (class 1259 OID 42711)
-- Name: admin_actions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.admin_actions (
    id bigint NOT NULL,
    admin_id bigint,
    action_type character varying(50) NOT NULL,
    target_table character varying(50) NOT NULL,
    target_id bigint NOT NULL,
    note text,
    created_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.admin_actions OWNER TO postgres;

--
-- TOC entry 231 (class 1259 OID 42710)
-- Name: admin_actions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.admin_actions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.admin_actions_id_seq OWNER TO postgres;

--
-- TOC entry 5174 (class 0 OID 0)
-- Dependencies: 231
-- Name: admin_actions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.admin_actions_id_seq OWNED BY public.admin_actions.id;


--
-- TOC entry 234 (class 1259 OID 42726)
-- Name: email_logs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.email_logs (
    id bigint NOT NULL,
    to_email character varying(200) NOT NULL,
    from_email character varying(200) NOT NULL,
    subject text,
    body text,
    related_table character varying(50),
    related_id bigint,
    status character varying(50) DEFAULT 'queued'::character varying NOT NULL,
    sent_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.email_logs OWNER TO postgres;

--
-- TOC entry 233 (class 1259 OID 42725)
-- Name: email_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.email_logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.email_logs_id_seq OWNER TO postgres;

--
-- TOC entry 5175 (class 0 OID 0)
-- Dependencies: 233
-- Name: email_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.email_logs_id_seq OWNED BY public.email_logs.id;


--
-- TOC entry 224 (class 1259 OID 42656)
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 42655)
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO postgres;

--
-- TOC entry 5176 (class 0 OID 0)
-- Dependencies: 223
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- TOC entry 228 (class 1259 OID 42680)
-- Name: lab_permit_requests; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.lab_permit_requests (
    id bigint NOT NULL,
    user_id bigint,
    full_name character varying(200) NOT NULL,
    study_program character varying(150),
    semester smallint,
    phone character varying(50),
    email character varying(200) NOT NULL,
    reason text NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    admin_id bigint,
    admin_notes text,
    submitted_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT lab_permit_requests_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'accepted'::character varying, 'rejected'::character varying])::text[])))
);


ALTER TABLE public.lab_permit_requests OWNER TO postgres;

--
-- TOC entry 227 (class 1259 OID 42679)
-- Name: lab_permit_requests_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.lab_permit_requests_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.lab_permit_requests_id_seq OWNER TO postgres;

--
-- TOC entry 5177 (class 0 OID 0)
-- Dependencies: 227
-- Name: lab_permit_requests_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.lab_permit_requests_id_seq OWNED BY public.lab_permit_requests.id;


--
-- TOC entry 250 (class 1259 OID 50892)
-- Name: member_activities; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.member_activities (
    id bigint NOT NULL,
    member_id bigint NOT NULL,
    title character varying(255) NOT NULL,
    year character varying(255),
    location character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.member_activities OWNER TO postgres;

--
-- TOC entry 249 (class 1259 OID 50891)
-- Name: member_activities_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.member_activities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.member_activities_id_seq OWNER TO postgres;

--
-- TOC entry 5178 (class 0 OID 0)
-- Dependencies: 249
-- Name: member_activities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.member_activities_id_seq OWNED BY public.member_activities.id;


--
-- TOC entry 240 (class 1259 OID 50822)
-- Name: member_backgrounds; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.member_backgrounds (
    id bigint NOT NULL,
    member_id bigint NOT NULL,
    institute character varying(255) NOT NULL,
    academic_title character varying(255) NOT NULL,
    year integer,
    degree character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.member_backgrounds OWNER TO postgres;

--
-- TOC entry 239 (class 1259 OID 50821)
-- Name: member_backgrounds_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.member_backgrounds_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.member_backgrounds_id_seq OWNER TO postgres;

--
-- TOC entry 5179 (class 0 OID 0)
-- Dependencies: 239
-- Name: member_backgrounds_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.member_backgrounds_id_seq OWNED BY public.member_backgrounds.id;


--
-- TOC entry 246 (class 1259 OID 50864)
-- Name: member_ips; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.member_ips (
    id bigint NOT NULL,
    member_id bigint NOT NULL,
    title character varying(255) NOT NULL,
    year character varying(255),
    registration_number character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.member_ips OWNER TO postgres;

--
-- TOC entry 245 (class 1259 OID 50863)
-- Name: member_ips_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.member_ips_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.member_ips_id_seq OWNER TO postgres;

--
-- TOC entry 5180 (class 0 OID 0)
-- Dependencies: 245
-- Name: member_ips_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.member_ips_id_seq OWNED BY public.member_ips.id;


--
-- TOC entry 248 (class 1259 OID 50878)
-- Name: member_ppm; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.member_ppm (
    id bigint NOT NULL,
    member_id bigint NOT NULL,
    title character varying(255) NOT NULL,
    year character varying(255),
    description character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.member_ppm OWNER TO postgres;

--
-- TOC entry 247 (class 1259 OID 50877)
-- Name: member_ppm_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.member_ppm_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.member_ppm_id_seq OWNER TO postgres;

--
-- TOC entry 5181 (class 0 OID 0)
-- Dependencies: 247
-- Name: member_ppm_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.member_ppm_id_seq OWNED BY public.member_ppm.id;


--
-- TOC entry 242 (class 1259 OID 50836)
-- Name: member_publications; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.member_publications (
    id bigint NOT NULL,
    member_id bigint NOT NULL,
    title character varying(255) NOT NULL,
    publisher character varying(255),
    year integer,
    link text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.member_publications OWNER TO postgres;

--
-- TOC entry 241 (class 1259 OID 50835)
-- Name: member_publications_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.member_publications_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.member_publications_id_seq OWNER TO postgres;

--
-- TOC entry 5182 (class 0 OID 0)
-- Dependencies: 241
-- Name: member_publications_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.member_publications_id_seq OWNED BY public.member_publications.id;


--
-- TOC entry 244 (class 1259 OID 50850)
-- Name: member_research; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.member_research (
    id bigint NOT NULL,
    member_id bigint NOT NULL,
    title character varying(255) NOT NULL,
    year character varying(255),
    description character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.member_research OWNER TO postgres;

--
-- TOC entry 243 (class 1259 OID 50849)
-- Name: member_research_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.member_research_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.member_research_id_seq OWNER TO postgres;

--
-- TOC entry 5183 (class 0 OID 0)
-- Dependencies: 243
-- Name: member_research_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.member_research_id_seq OWNED BY public.member_research.id;


--
-- TOC entry 238 (class 1259 OID 50812)
-- Name: members; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.members (
    id bigint NOT NULL,
    full_name character varying(255) NOT NULL,
    role character varying(255),
    photo character varying(255),
    expertise text,
    description text,
    linkedin character varying(255),
    scholar character varying(255),
    researchgate character varying(255),
    orcid character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    status character varying(255),
    user_id integer
);


ALTER TABLE public.members OWNER TO postgres;

--
-- TOC entry 237 (class 1259 OID 50811)
-- Name: members_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.members_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.members_id_seq OWNER TO postgres;

--
-- TOC entry 5184 (class 0 OID 0)
-- Dependencies: 237
-- Name: members_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.members_id_seq OWNED BY public.members.id;


--
-- TOC entry 219 (class 1259 OID 42631)
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- TOC entry 218 (class 1259 OID 42630)
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO postgres;

--
-- TOC entry 5185 (class 0 OID 0)
-- Dependencies: 218
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- TOC entry 256 (class 1259 OID 50924)
-- Name: news; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.news (
    id bigint NOT NULL,
    title character varying(255) NOT NULL,
    category character varying(255),
    date date,
    image_thumb text,
    image_detail text,
    excerpt text,
    content text,
    quote text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    status character varying(20) DEFAULT 'approved'::character varying
);


ALTER TABLE public.news OWNER TO postgres;

--
-- TOC entry 255 (class 1259 OID 50923)
-- Name: news_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.news_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.news_id_seq OWNER TO postgres;

--
-- TOC entry 5186 (class 0 OID 0)
-- Dependencies: 255
-- Name: news_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.news_id_seq OWNED BY public.news.id;


--
-- TOC entry 222 (class 1259 OID 42648)
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 42668)
-- Name: personal_access_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.personal_access_tokens OWNER TO postgres;

--
-- TOC entry 225 (class 1259 OID 42667)
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.personal_access_tokens_id_seq OWNER TO postgres;

--
-- TOC entry 5187 (class 0 OID 0)
-- Dependencies: 225
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- TOC entry 258 (class 1259 OID 59007)
-- Name: projects; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.projects (
    id bigint NOT NULL,
    code character varying(255),
    title character varying(255) NOT NULL,
    description text,
    status character varying(255) DEFAULT 'progress'::character varying NOT NULL,
    document_url character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT projects_status_check CHECK (((status)::text = ANY ((ARRAY['published'::character varying, 'progress'::character varying, 'cancelled'::character varying])::text[])))
);


ALTER TABLE public.projects OWNER TO postgres;

--
-- TOC entry 257 (class 1259 OID 59006)
-- Name: projects_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.projects_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.projects_id_seq OWNER TO postgres;

--
-- TOC entry 5188 (class 0 OID 0)
-- Dependencies: 257
-- Name: projects_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.projects_id_seq OWNED BY public.projects.id;


--
-- TOC entry 254 (class 1259 OID 50915)
-- Name: research_partners; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.research_partners (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    logo character varying(255),
    website character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.research_partners OWNER TO postgres;

--
-- TOC entry 253 (class 1259 OID 50914)
-- Name: research_partners_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.research_partners_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.research_partners_id_seq OWNER TO postgres;

--
-- TOC entry 5189 (class 0 OID 0)
-- Dependencies: 253
-- Name: research_partners_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.research_partners_id_seq OWNED BY public.research_partners.id;


--
-- TOC entry 252 (class 1259 OID 50906)
-- Name: research_products; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.research_products (
    id bigint NOT NULL,
    title character varying(255) NOT NULL,
    description text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    image character varying(255)
);


ALTER TABLE public.research_products OWNER TO postgres;

--
-- TOC entry 251 (class 1259 OID 50905)
-- Name: research_products_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.research_products_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.research_products_id_seq OWNER TO postgres;

--
-- TOC entry 5190 (class 0 OID 0)
-- Dependencies: 251
-- Name: research_products_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.research_products_id_seq OWNED BY public.research_products.id;


--
-- TOC entry 221 (class 1259 OID 42638)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    role character varying(32) DEFAULT 'user'::character varying NOT NULL
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 220 (class 1259 OID 42637)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO postgres;

--
-- TOC entry 5191 (class 0 OID 0)
-- Dependencies: 220
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 230 (class 1259 OID 42702)
-- Name: volunteer_registrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.volunteer_registrations (
    id bigint NOT NULL,
    full_name character varying(200) NOT NULL,
    nickname character varying(100),
    study_program character varying(150),
    semester smallint,
    email character varying(200),
    phone character varying(50),
    areas jsonb,
    skills text,
    motivation text,
    availability character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    status character varying(50) DEFAULT 'Pending'::character varying
);


ALTER TABLE public.volunteer_registrations OWNER TO postgres;

--
-- TOC entry 229 (class 1259 OID 42701)
-- Name: volunteer_registrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.volunteer_registrations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.volunteer_registrations_id_seq OWNER TO postgres;

--
-- TOC entry 5192 (class 0 OID 0)
-- Dependencies: 229
-- Name: volunteer_registrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.volunteer_registrations_id_seq OWNED BY public.volunteer_registrations.id;


--
-- TOC entry 4899 (class 2604 OID 42743)
-- Name: activities id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.activities ALTER COLUMN id SET DEFAULT nextval('public.activities_id_seq'::regclass);


--
-- TOC entry 4895 (class 2604 OID 42714)
-- Name: admin_actions id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.admin_actions ALTER COLUMN id SET DEFAULT nextval('public.admin_actions_id_seq'::regclass);


--
-- TOC entry 4897 (class 2604 OID 42729)
-- Name: email_logs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.email_logs ALTER COLUMN id SET DEFAULT nextval('public.email_logs_id_seq'::regclass);


--
-- TOC entry 4887 (class 2604 OID 42659)
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- TOC entry 4890 (class 2604 OID 42683)
-- Name: lab_permit_requests id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lab_permit_requests ALTER COLUMN id SET DEFAULT nextval('public.lab_permit_requests_id_seq'::regclass);


--
-- TOC entry 4907 (class 2604 OID 50895)
-- Name: member_activities id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.member_activities ALTER COLUMN id SET DEFAULT nextval('public.member_activities_id_seq'::regclass);


--
-- TOC entry 4902 (class 2604 OID 50825)
-- Name: member_backgrounds id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.member_backgrounds ALTER COLUMN id SET DEFAULT nextval('public.member_backgrounds_id_seq'::regclass);


--
-- TOC entry 4905 (class 2604 OID 50867)
-- Name: member_ips id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.member_ips ALTER COLUMN id SET DEFAULT nextval('public.member_ips_id_seq'::regclass);


--
-- TOC entry 4906 (class 2604 OID 50881)
-- Name: member_ppm id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.member_ppm ALTER COLUMN id SET DEFAULT nextval('public.member_ppm_id_seq'::regclass);


--
-- TOC entry 4903 (class 2604 OID 50839)
-- Name: member_publications id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.member_publications ALTER COLUMN id SET DEFAULT nextval('public.member_publications_id_seq'::regclass);


--
-- TOC entry 4904 (class 2604 OID 50853)
-- Name: member_research id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.member_research ALTER COLUMN id SET DEFAULT nextval('public.member_research_id_seq'::regclass);


--
-- TOC entry 4901 (class 2604 OID 50815)
-- Name: members id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.members ALTER COLUMN id SET DEFAULT nextval('public.members_id_seq'::regclass);


--
-- TOC entry 4884 (class 2604 OID 42634)
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- TOC entry 4910 (class 2604 OID 50927)
-- Name: news id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.news ALTER COLUMN id SET DEFAULT nextval('public.news_id_seq'::regclass);


--
-- TOC entry 4889 (class 2604 OID 42671)
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- TOC entry 4912 (class 2604 OID 59010)
-- Name: projects id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.projects ALTER COLUMN id SET DEFAULT nextval('public.projects_id_seq'::regclass);


--
-- TOC entry 4909 (class 2604 OID 50918)
-- Name: research_partners id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.research_partners ALTER COLUMN id SET DEFAULT nextval('public.research_partners_id_seq'::regclass);


--
-- TOC entry 4908 (class 2604 OID 50909)
-- Name: research_products id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.research_products ALTER COLUMN id SET DEFAULT nextval('public.research_products_id_seq'::regclass);


--
-- TOC entry 4885 (class 2604 OID 42641)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 4893 (class 2604 OID 42705)
-- Name: volunteer_registrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.volunteer_registrations ALTER COLUMN id SET DEFAULT nextval('public.volunteer_registrations_id_seq'::regclass);


--
-- TOC entry 5142 (class 0 OID 42740)
-- Dependencies: 236
-- Data for Name: activities; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.activities (id, title, label, short_description, full_description, published_at, thumbnail_image, banner_image, created_at, updated_at, status, document_link) FROM stdin;
4	AI for Education	published	Using machine learning to predict flood risks.	This research focuses on AI techniques...	2024-12-01	thumb_1764744095_708.png	activitymage.png	2025-11-20 13:00:55	2025-11-29 17:52:57	published	\N
6	Digitalization in this Era	cancelled	Using machine learning to predict flood risks.	This research focuses on AI techniques...	2024-12-01	activitymage.png	activitymage.png	2025-11-20 13:01:34	2025-12-03 13:39:57	cancelled	\N
12	A Sample Profile	published	This is a sample to test the program work or nah	A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE	2025-11-28	thumb_1764417787_186.png	\N	2025-11-29 19:03:08	2025-11-29 19:03:26	published	\N
13	Second Test Sample	published	A sample cz there's some error i do it again, and i wanna try the doc link	A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE A SAMPLE	2025-11-21	thumb_1764418608_275.png	\N	2025-11-29 19:16:49	2025-11-29 19:16:49	published	https://docs.google.com/document/d/1x8QZEoQtSGp03e9CkNQam1shAnDk3P3z1JO2Jd5qV8U/edit?usp=sharing
20	Trying	published	New Test	New Sample to test the integration	2025-12-11	thumb_1764744095_708.png	\N	2025-12-03 13:41:35	2025-12-03 13:41:35	published	
11	Combination of AR and VR	published	Using machine learning to predict flood risks.	This research focuses on AI techniques...	2024-12-01	thumb_1764744095_708.png	activitymage.png	2025-11-20 14:47:33	2025-11-29 17:53:25	published	\N
5	Cybersecurity for Government	published	Using machine learning to predict flood risks.	This research focuses on AI techniques...	2024-12-26	thumb_1764744095_708.png	activitymage.png	2025-11-20 13:01:10	2025-11-29 17:15:40	published	\N
2	AI Disaster Prediction	published	Using machine learning to predict flood risks.	This research focuses on AI techniques....	2024-12-01	thumb_1764744095_708.png	activitymage.png	2025-11-20 12:34:40	2025-12-06 13:41:18	published	\N
10	Virtual Reality	cancelled	Using machine learning to predict flood risks.	This research focuses on AI techniques...	2024-12-01	activitymage.png	activitymage.png	2025-11-20 14:47:17	2025-12-14 14:11:22	cancelled	\N
14	Third Sample	cancelled	A sample so it's a test	A TEST THIS IS A TEST OKAY PLS GOD LET ME SURVIVE THIS PROJECT I MEAN IT, LOVE WATER!	2025-11-28	thumb_1764426786_873.png	\N	2025-11-29 21:33:07	2025-11-30 22:46:55	cancelled	\N
3	Cybersecurity Workshop	published	Hands-on workshop on cybersecurity basics bla bla. Hands-on workshop on cybersecurity basics bla bla.	Full detail for cybersecurity workshop......blah. 2nd tetsing failed, 3rd testing go, bismillah.	2024-11-28	activitymage.png	\N	\N	2025-11-29 16:20:39	published	\N
8	Augmented Reality	published	Using machine learning to predict flood risks.	This research focuses on AI techniques...	2024-12-19	thumb_1764744095_708.png	activitymage.png	2025-11-20 14:46:41	2025-11-29 18:55:06	published	\N
9	Augmented Test	published	using dinosaur to survive	This research focuses on AI techniques...	2024-12-01	thumb_1764744095_708.png	activitymage.png	2025-11-20 14:47:08	2025-12-06 13:41:42	published	\N
7	Modern Era	published	Using machine learning to predict flood risks.	This research focuses on AI techniques...	2024-12-06	thumb_1764744095_708.png	activitymage.png	2025-11-20 14:29:30	2025-11-29 16:22:19	published	\N
\.


--
-- TOC entry 5138 (class 0 OID 42711)
-- Dependencies: 232
-- Data for Name: admin_actions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.admin_actions (id, admin_id, action_type, target_table, target_id, note, created_at) FROM stdin;
1	\N	approve_permit	lab_permit_requests	1	Silakan gunakan lab besok.	2025-11-19 22:31:42
2	\N	reject_permit	lab_permit_requests	1	Alasan anda tidak memenuhi syarat.	2025-11-19 22:32:38
\.


--
-- TOC entry 5140 (class 0 OID 42726)
-- Dependencies: 234
-- Data for Name: email_logs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.email_logs (id, to_email, from_email, subject, body, related_table, related_id, status, sent_at, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 5130 (class 0 OID 42656)
-- Dependencies: 224
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- TOC entry 5134 (class 0 OID 42680)
-- Dependencies: 228
-- Data for Name: lab_permit_requests; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.lab_permit_requests (id, user_id, full_name, study_program, semester, phone, email, reason, status, admin_id, admin_notes, submitted_at, created_at, updated_at) FROM stdin;
4	\N	Nasywaaaa	Information System	3	98765444457	nasywaaaa@gmail.com	third testing	pending	\N	\N	2025-12-07 17:40:14	\N	\N
5	\N	Last Test	Informatics Engineering	3	97766566879	testsss@gmail.com	last test	pending	\N	\N	2025-12-07 17:50:42	\N	\N
6	\N	Tiara	Informatics Engineering	3	088675645	tiara@gmail.com	test	pending	\N	\N	2025-12-09 15:29:30	\N	\N
1	\N	Nasywa	Teknik Informatika	3	08123456789	nasywa@gmail.com	Izin pakai lab	rejected	\N	Alasan anda tidak memenuhi syarat.	2025-11-19 22:29:05	2025-11-19 15:29:05	2025-11-19 15:32:37
7	\N	Tiara	Information System	3	097766566879	tiara@gmail.com	test	pending	\N	\N	2025-12-15 16:24:17	\N	\N
8	\N	Tiara	Information System	3	097766566879	tiara@gmail.com	test	pending	\N	\N	2025-12-15 16:24:52	\N	\N
10	\N	Nssywa	Information System	6	98764335788	nasywaqonitarh1010@gmail.com	abcd	pending	\N	\N	2025-12-22 11:00:55	\N	\N
\.


--
-- TOC entry 5156 (class 0 OID 50892)
-- Dependencies: 250
-- Data for Name: member_activities; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.member_activities (id, member_id, title, year, location, created_at, updated_at) FROM stdin;
1	1	AI Workshop at ITS	2022	Surabaya	2025-11-22 13:16:01	2025-11-22 13:16:01
2	2	Seminar IoT Nasional	2022	Jakarta	2025-11-22 14:32:59	2025-11-22 14:32:59
3	3	Workshop AI Polinema	2021	Malang	2025-11-22 14:32:59	2025-11-22 14:32:59
4	4	Android Dev Conference	2020	Surabaya	2025-11-22 14:32:59	2025-11-22 14:32:59
5	5	Machine Learning Day	2022	Malang	2025-11-22 14:32:59	2025-11-22 14:32:59
6	6	Industrial Expo	2023	Sidoarjo	2025-11-22 14:32:59	2025-11-22 14:32:59
7	7	Drone Expo 2022	2022	Jakarta	2025-11-22 14:32:59	2025-11-22 14:32:59
8	8	Image Processing Meetup	2021	Bandung	2025-11-22 14:32:59	2025-11-22 14:32:59
9	9	Robotics Festival	2020	Denpasar	2025-11-22 14:32:59	2025-11-22 14:32:59
10	10	Tech Innovation Forum	2023	Jakarta	2025-11-22 14:32:59	2025-11-22 14:32:59
11	11	STEM Education Expo	2022	Malang	2025-11-22 14:32:59	2025-11-22 14:32:59
12	15	Activities 1	2025	Malang CIty	\N	\N
\.


--
-- TOC entry 5146 (class 0 OID 50822)
-- Dependencies: 240
-- Data for Name: member_backgrounds; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.member_backgrounds (id, member_id, institute, academic_title, year, degree, created_at, updated_at) FROM stdin;
4	1	Okayama University	Doctor of Philosophy in Engineering	2021	S3	2025-11-22 09:32:32	2025-11-22 09:32:32
5	1	ITS Surabaya	Magister Manajemen Teknologi	2011	S2	2025-11-22 09:32:32	2025-11-22 09:32:32
6	1	ITB	Sarjana Teknik	2003	S1	2025-11-22 09:32:32	2025-11-22 09:32:32
7	2	ITS Surabaya	Magister Teknik	2015	S2	2025-11-22 14:20:42	2025-11-22 14:20:42
8	2	Polinema	Sarjana Teknik	2010	S1	2025-11-22 14:20:42	2025-11-22 14:20:42
11	3	Universitas Brawijaya	Magister Ilmu Komputer	2018	S2	2025-11-22 14:23:32	2025-11-22 14:23:32
12	3	Polinema	Sarjana Komputer	2014	S1	2025-11-22 14:23:32	2025-11-22 14:23:32
13	4	Polinema	Magister Manajemen Teknologi	2017	S2	2025-11-22 14:31:53	2025-11-22 14:31:53
14	4	Polinema	Sarjana Komputer	2013	S1	2025-11-22 14:31:53	2025-11-22 14:31:53
15	5	Universitas Brawijaya	Magister Ilmu Komputer	2020	S2	2025-11-22 14:31:53	2025-11-22 14:31:53
16	5	Polinema	Sarjana Komputer	2016	S1	2025-11-22 14:31:53	2025-11-22 14:31:53
17	6	ITS Surabaya	Magister Teknik Informatika	2019	S2	2025-11-22 14:31:53	2025-11-22 14:31:53
18	6	Polinema	Sarjana Komputer	2015	S1	2025-11-22 14:31:53	2025-11-22 14:31:53
19	7	Universitas Brawijaya	Magister Teknik Informatika	2019	S2	2025-11-22 14:31:53	2025-11-22 14:31:53
20	7	Polinema	Sarjana Komputer	2014	S1	2025-11-22 14:31:53	2025-11-22 14:31:53
21	8	National Taiwan University	Master of Engineering	2018	S2	2025-11-22 14:31:53	2025-11-22 14:31:53
22	8	Polinema	Sarjana Komputer	2013	S1	2025-11-22 14:31:53	2025-11-22 14:31:53
23	9	ITS Surabaya	Magister Teknik Informatika	2020	S2	2025-11-22 14:31:53	2025-11-22 14:31:53
24	9	Polinema	Sarjana Komputer	2016	S1	2025-11-22 14:31:53	2025-11-22 14:31:53
25	10	Universitas Brawijaya	Magister Terapan	2017	S2	2025-11-22 14:31:53	2025-11-22 14:31:53
26	10	Polinema	Sarjana Teknik	2012	S1	2025-11-22 14:31:53	2025-11-22 14:31:53
27	11	Universitas Negeri Malang	Magister Teknik	2016	S2	2025-11-22 14:31:53	2025-11-22 14:31:53
28	11	Universitas Negeri Malang	Sarjana Pendidikan Teknik	2012	S1	2025-11-22 14:31:53	2025-11-22 14:31:53
31	15	Polinema	Applied Bachelor	2025	D-IV	\N	\N
32	15	Testing	Tested	2025	S2	\N	\N
33	15	Sample	Sampled	2025	S3	\N	\N
34	15	Politeknik Negeri Malang	S.Tr, Kom	2025	Bachelor	\N	\N
\.


--
-- TOC entry 5152 (class 0 OID 50864)
-- Dependencies: 246
-- Data for Name: member_ips; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.member_ips (id, member_id, title, year, registration_number, created_at, updated_at) FROM stdin;
1	1	Smart Flood Detection System	2022	IDP00012345	2025-11-22 13:15:46	2025-11-22 13:15:46
2	2	Smart Hydroponic Controller	2022	IDP00020001	2025-11-22 14:32:48	2025-11-22 14:32:48
3	3	Online Quiz Generator	2021	IDP00020002	2025-11-22 14:32:48	2025-11-22 14:32:48
4	4	Intelligent Parking System	2020	IDP00020003	2025-11-22 14:32:48	2025-11-22 14:32:48
5	5	Digital Classroom Toolkit	2019	IDP00020004	2025-11-22 14:32:48	2025-11-22 14:32:48
6	6	Industrial IoT Gateway	2023	IDP00020005	2025-11-22 14:32:48	2025-11-22 14:32:48
7	7	Smart Drone Autopilot	2022	IDP00020006	2025-11-22 14:32:48	2025-11-22 14:32:48
8	8	Machine Vision Toolkit	2021	IDP00020007	2025-11-22 14:32:48	2025-11-22 14:32:48
9	9	Robotics Simulation Software	2020	IDP00020008	2025-11-22 14:32:48	2025-11-22 14:32:48
10	10	Factory Monitoring Dashboard	2023	IDP00020009	2025-11-22 14:32:48	2025-11-22 14:32:48
11	11	STEM Learning App	2021	IDP00020010	2025-11-22 14:32:48	2025-11-22 14:32:48
12	15	IP Test 1	2020	IDP00013425	\N	\N
\.


--
-- TOC entry 5154 (class 0 OID 50878)
-- Dependencies: 248
-- Data for Name: member_ppm; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.member_ppm (id, member_id, title, year, description, created_at, updated_at) FROM stdin;
1	1	Digital Transformation Training for SMEs	2023	Community service program for empowering small businesses.	2025-11-22 13:15:53	2025-11-22 13:15:53
2	2	Training: Basic IoT	2023	Workshop IoT untuk guru SMK.	2025-11-22 14:32:53	2025-11-22 14:32:53
3	3	Digital Literacy for Kids	2022	Pelatihan komputer untuk anak.	2025-11-22 14:32:53	2025-11-22 14:32:53
4	4	Android App Bootcamp	2021	Workshop pemrograman Android.	2025-11-22 14:32:53	2025-11-22 14:32:53
5	5	UMKM Website Building	2023	Pelatihan pembuatan website UMKM.	2025-11-22 14:32:53	2025-11-22 14:32:53
6	6	Industrial Safety Monitoring	2022	Implementasi sensor keamanan.	2025-11-22 14:32:53	2025-11-22 14:32:53
7	7	Drone Safety Training	2023	Pelatihan penggunaan drone.	2025-11-22 14:32:53	2025-11-22 14:32:53
8	8	AI for Beginners	2021	Pelatihan AI dasar.	2025-11-22 14:32:53	2025-11-22 14:32:53
9	9	Robotics for Students	2022	Pelatihan robotika dasar.	2025-11-22 14:32:53	2025-11-22 14:32:53
10	10	Factory Automation Seminar	2023	Seminar teknologi pabrik.	2025-11-22 14:32:53	2025-11-22 14:32:53
11	11	STEM Teaching Workshop	2021	Workshop metode STEM.	2025-11-22 14:32:53	2025-11-22 14:32:53
12	15	Pengabdian 1	2025	Short desc for a test	\N	\N
\.


--
-- TOC entry 5148 (class 0 OID 50836)
-- Dependencies: 242
-- Data for Name: member_publications; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.member_publications (id, member_id, title, publisher, year, link, created_at, updated_at) FROM stdin;
3	1	AI-based Android Learning System	IEEE	2023	https://example.com	2025-11-22 09:32:37	2025-11-22 09:32:37
4	1	Spatial Data Analysis Framework	Elsevier	2022	https://example.com	2025-11-22 09:32:37	2025-11-22 09:32:37
5	2	Optimization of IoT Sensor Networks	IEEE	2021	https://example.com	2025-11-22 14:39:46	2025-11-22 14:39:46
6	2	Energy Efficient Embedded Systems	Elsevier	2020	https://example.com	2025-11-22 14:39:46	2025-11-22 14:39:46
7	3	Deep Learning for Image Segmentation	Springer	2022	https://example.com	2025-11-22 14:39:50	2025-11-22 14:39:50
8	3	Neural Networks for Pattern Recognition	IEEE	2020	https://example.com	2025-11-22 14:39:50	2025-11-22 14:39:50
9	4	Mobile Application Performance Analysis	ACM	2021	https://example.com	2025-11-22 14:39:56	2025-11-22 14:39:56
10	4	Cross-Platform UI Optimization	Elsevier	2019	https://example.com	2025-11-22 14:39:56	2025-11-22 14:39:56
11	5	Natural Language Processing for Education	Taylor & Francis	2021	https://example.com	2025-11-22 14:40:00	2025-11-22 14:40:00
12	5	Text Classification using CNN	IEEE	2020	https://example.com	2025-11-22 14:40:00	2025-11-22 14:40:00
13	6	Cloud Computing Security Framework	Elsevier	2022	https://example.com	2025-11-22 14:40:05	2025-11-22 14:40:05
14	6	Blockchain for Secure Transactions	IEEE	2020	https://example.com	2025-11-22 14:40:05	2025-11-22 14:40:05
15	7	AI-Driven Decision Support System	Springer	2021	https://example.com	2025-11-22 14:40:09	2025-11-22 14:40:09
16	7	Predictive Maintenance using ML	IEEE	2020	https://example.com	2025-11-22 14:40:09	2025-11-22 14:40:09
17	8	IoT-Based Smart Agriculture System	Elsevier	2022	https://example.com	2025-11-22 14:40:13	2025-11-22 14:40:13
18	8	Edge Computing for Real-Time Monitoring	IEEE	2020	https://example.com	2025-11-22 14:40:13	2025-11-22 14:40:13
19	9	Computer Vision for Traffic Analysis	ACM	2021	https://example.com	2025-11-22 14:40:18	2025-11-22 14:40:18
20	9	Object Tracking using YOLO	IEEE	2020	https://example.com	2025-11-22 14:40:18	2025-11-22 14:40:18
21	10	Industrial Automation Using AI	Elsevier	2021	https://example.com	2025-11-22 14:40:23	2025-11-22 14:40:23
22	10	Robotics Control System Optimization	IEEE	2019	https://example.com	2025-11-22 14:40:23	2025-11-22 14:40:23
23	11	STEM Education Enhancement with Technology	Springer	2022	https://example.com	2025-11-22 14:40:27	2025-11-22 14:40:27
24	11	Digital Learning Model Effectiveness	Taylor & Francis	2020	https://example.com	2025-11-22 14:40:27	2025-11-22 14:40:27
25	15	Publication 1	Nasywa	2025	-	\N	\N
\.


--
-- TOC entry 5150 (class 0 OID 50850)
-- Dependencies: 244
-- Data for Name: member_research; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.member_research (id, member_id, title, year, description, created_at, updated_at) FROM stdin;
1	1	Machine Learning for Spatial Analysis	2020	Research on integrating ML models for geospatial data.	2025-11-22 13:15:36	2025-11-22 13:15:36
2	1	Cloud-based IoT Platform	2021	Development of IoT monitoring system using cloud infrastructure.	2025-11-22 13:15:36	2025-11-22 13:15:36
3	2	AI for Traffic Prediction	2021	Research on traffic flow forecasting using ML.	2025-11-22 14:32:41	2025-11-22 14:32:41
4	3	E-learning Recommendation Engine	2020	Adaptive recommendation for LMS.	2025-11-22 14:32:41	2025-11-22 14:32:41
5	4	IoT Air Quality Monitor	2019	Monitoring polusi udara via IoT sensors.	2025-11-22 14:32:41	2025-11-22 14:32:41
6	5	Sentiment Analysis for UMKM	2021	Analyzing customer reviews.	2025-11-22 14:32:41	2025-11-22 14:32:41
7	6	Fault Detection System	2020	Industrial sensor anomaly detection.	2025-11-22 14:32:41	2025-11-22 14:32:41
8	7	Drone Navigation System	2022	Smart auto-path drone nav.	2025-11-22 14:32:41	2025-11-22 14:32:41
9	8	3D Reconstruction	2018	Point-cloud object reconstruction.	2025-11-22 14:32:41	2025-11-22 14:32:41
10	9	Smart Robot Arm	2021	AI-controlled robotic arm.	2025-11-22 14:32:41	2025-11-22 14:32:41
11	10	Energy Consumption Predictor	2020	ML model for industrial energy.	2025-11-22 14:32:41	2025-11-22 14:32:41
12	11	STEM Digital Module	2019	Developing STEM-based learning tools.	2025-11-22 14:32:41	2025-11-22 14:32:41
13	15	Testing 1	2025	Tested	\N	\N
\.


--
-- TOC entry 5144 (class 0 OID 50812)
-- Dependencies: 238
-- Data for Name: members; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.members (id, full_name, role, photo, expertise, description, linkedin, scholar, researchgate, orcid, created_at, updated_at, status, user_id) FROM stdin;
2	Triana Fatmawati, S.T., M.T.	Researcher	triana.png	Machine Learning,Neural Networks,AI,Classification,Prediction,Data Mining,Pattern Classification,KNN,Clustering	Triana Fatmawati focuses on machine learning, neural networks, and modern data-driven modeling. Her research covers prediction systems, clustering, and classification across various digital platforms.	https://linkedin.com/	https://scholar.google.com/	https://researchgate.net/	https://orcid.org/	2025-11-21 16:05:44	2025-12-13 13:14:58	Active	11
15	Nasywa Qonita RH		memberavatar.png	Web Developer,IoT,Artificial Intelligence,Software Development,Laravel	I'm just a student doing a test for the program	https://linkedin.com	https://scholar.google.com		https://orcid.org	2025-12-12 09:42:31	2025-12-13 12:45:40	Active	15
3	M. Hasyim Ratsanjani, S.Kom., M.Kom.	Researcher	hasyim.png	Information Extraction, Text Mining, Social Media Analytics, Digital Image Processing	Hasyim specializes in text mining, social media analysis, and information extraction. His work focuses on extracting insights from unstructured digital data.	https://linkedin.com/	https://scholar.google.com/			2025-11-21 16:05:56	2025-11-21 16:05:56	Active	10
4	Pramana Yoga Saputra, S.Kom., M.MT.	Researcher	yoga.png	Information Extraction, Text Mining, Project Management, Social Media Analytics, Digital Image Processing	Yoga works in project management, information extraction, and digital imaging. He often collaborates in interdisciplinary research involving AI and industry applications.					2025-11-21 16:06:05	2025-11-21 16:06:05	Active	12
1	Ir. Yan Watequlis Syaifudin, S.T., M.MT., Ph.D	Head of Laboratory	yan.png	Software Engineering, Geographic Information System, Spatial Data, Educational Technology, Software Testing, Computer Networks, SaaS	I am a teacher at a University with an Associate Professor position. Currently, we are conducting a research project of Android Programming Learning Assistance System as a platform to provide self-learning for students to study Android programming with automatic assessment features. Besides, geographical information systems, technology enhanced learning, and intelligence information systems also become my research interest.	https://linkedin.com/	https://scholar.google.com/	https://researchgate.net/	https://orcid.org/	2025-11-21 16:05:37	2025-11-21 16:05:37	Active	\N
5	Mustika Mentari, S.Kom., M.Kom.	Researcher	mustika.png	Computer Vision, Image Processing, Segmentation, Machine Learning	Mustika focuses on image processing, segmentation techniques, and computer vision research with practical real-world applications.					2025-11-21 16:06:11	2025-11-21 16:06:11	Active	\N
6	Yuri Ariyanto, S.Kom., M.Kom.	Researcher	yuri.png	Network Security, Cloud Computing, Computer Networking, Information Systems, Recommendation Systems, Machine Learning	Yuri is active in cloud computing, cybersecurity, and modern recommendation systems.					2025-11-21 16:06:17	2025-11-21 16:06:17	Active	\N
7	Muhammad Afif Hendrawan, S.Kom., M.T.	Researcher	afif.png	Artificial Intelligence, Machine Learning, Signal Processing	Afif researches AI, signal processing, and automated modeling systems.					2025-11-21 16:06:23	2025-11-21 16:06:23	Active	\N
8	Noprianto, S.Kom., M.Eng.	Researcher	noprianto.png	Internet Of Things, Computer Vision, Software Engineer, Machine Learning	Noprianto focuses on IoT systems, machine learning, and software engineering for intelligent systems.					2025-11-21 16:06:29	2025-11-21 16:06:29	Active	\N
9	Kadek Suarjuna Batubulan, S.Kom.,MT	Researcher	kadek.png	Machine Learning, Pattern Recognition, Image Processing, Computer Vision, Pattern Classification, Feature Extraction, Feature Selection	Kadek specializes in ML, pattern recognition, and high-level visual recognition systems.					2025-11-21 16:06:34	2025-11-21 16:06:34	Active	\N
10	Chandrasena Setiadi, S.T., M.Tr.T	Researcher	candrasena.png		No detailed biography provided yet.					2025-11-21 16:06:40	2025-11-21 16:06:40	Active	\N
11	Retno Damayanti, S.Pd. M.T.	Researcher	retno.png		No detailed biography provided yet.					2025-11-21 16:06:46	2025-11-21 16:06:46	Active	\N
\.


--
-- TOC entry 5125 (class 0 OID 42631)
-- Dependencies: 219
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	2014_10_12_000000_create_users_table	1
2	2014_10_12_100000_create_password_reset_tokens_table	1
3	2019_08_19_000000_create_failed_jobs_table	1
4	2019_12_14_000001_create_personal_access_tokens_table	1
5	2025_11_18_135722_setup_ai_lab_tables	1
6	2025_11_18_143923_create_volunteer_registrations_table	1
7	2025_11_18_151416_add_role_to_users_table	1
8	2025_11_19_155837_create_activities_table	2
9	2025_11_21_084310_create_members_table	3
10	2025_11_21_111022_create_member_backgrounds_table	4
11	2025_11_21_111035_create_member_publications_table	4
12	2025_11_22_052656_create_member_research_table	5
13	2025_11_22_052733_create_member_ips_table	5
14	2025_11_22_052743_create_member_ppm_table	5
15	2025_11_22_052753_create_member_activities_table	5
16	2025_11_22_082825_create_research_products_table	6
17	2025_11_22_082918_create_research_partners_table	6
18	2025_11_22_093541_create_news_table	7
19	2025_11_22_101307_add_image_to_research_products_table	8
20	2025_11_27_025324_create_projects_table	9
21	2025_11_27_034206_add_status_to_activities_table	10
22	2025_11_27_053545_add_field_to_activities_table	11
\.


--
-- TOC entry 5162 (class 0 OID 50924)
-- Dependencies: 256
-- Data for Name: news; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.news (id, title, category, date, image_thumb, image_detail, excerpt, content, quote, created_at, updated_at, status) FROM stdin;
2	AMATI: Automated Cyber Security Maturity Assessment	innovation	2024-09-15	newstop.png	actdet1.png	AMATI helps organizations measure their cyber maturity automatically.	Full article content about AMATI, how it works, results, and deployment.	A faster route to understand organizational cyber hygiene.	2025-11-22 00:00:00	2025-12-06 12:52:23	main
6	International Collaboration: Okayama University	collaboration	2024-05-30	newstop.png	actdet1.png	Joint research initiative with Okayama University.	Research topics, partner teams, and next steps.	Cross-border research amplifies impact.	2025-11-22 00:00:00	2025-12-06 12:54:23	none
12	SmartFarming 1 - VocPro	award	\N	newstop.png	actdet1.png	Last Test i think yeah	hope this going out well in the web profile so yeah okay	hope all the hardwork we do is worth it	2025-12-10 00:00:00	2025-12-14 14:18:46	main
3	Crowdfunding Blockchain Pilot	collaboration	2024-08-20	newstop.png	actdet1.png	Pilot project for community crowdfunding using blockchain.	Details on the pilot, stakeholders, and early impact.	Blockchain can increase transparency in community funding.	2025-11-22 00:00:00	2025-12-03 15:55:29	main
4	SEALS Adaptive Learning Launch	research	2024-07-05	newstop.png	actdet1.png	Smart Adaptive Learning System (SEALS) launched at Polinema.	Details of SEALS, studies and outcomes.	Personalized learning improves engagement and outcomes.	2025-11-22 00:00:00	2025-12-03 15:56:00	none
5	Fintech Predictive Model released	research	2024-06-12	newstop.png	actdet1.png	Predictive models to forecast microloan default risk.	Model details, datasets, and evaluation.	Predictive analytics enable smarter lending decisions.	2025-11-22 00:00:00	2025-12-03 16:03:01	main
1	Smartfarming - Agrilink Vocpro	research	2024-10-10	newstop.png	actdet1.png	Agrilink Vocpro is a smart mobile and website-based application specifically designed to support modern agricultural activities in greenhouse environments.	Full article content about Agrilink Vocpro. Explain features, outcomes, and partners.	Improving the efficiency and accuracy of modern agriculture in greenhouse environments through an IoT-based automated monitoring system.	2025-11-22 00:00:00	2025-12-06 12:51:53	main
\.


--
-- TOC entry 5128 (class 0 OID 42648)
-- Dependencies: 222
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- TOC entry 5132 (class 0 OID 42668)
-- Dependencies: 226
-- Data for Name: personal_access_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.personal_access_tokens (id, tokenable_type, tokenable_id, name, token, abilities, last_used_at, expires_at, created_at, updated_at) FROM stdin;
1	App\\Models\\User	1	api-token	9e1cdfb1b51f51b54d51771be7792e78ea5d5f3922314aac3f85319b92fac96b	["*"]	2025-11-19 15:32:37	\N	2025-11-19 15:23:25	2025-11-19 15:32:37
\.


--
-- TOC entry 5164 (class 0 OID 59007)
-- Dependencies: 258
-- Data for Name: projects; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.projects (id, code, title, description, status, document_url, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 5160 (class 0 OID 50915)
-- Dependencies: 254
-- Data for Name: research_partners; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.research_partners (id, name, logo, website, created_at, updated_at) FROM stdin;
1	Adma Solusi	img/ads.png	https://admasolusi.com/	2025-11-22 16:15:01	2025-11-22 16:15:01
2	Arm Solusi	img/armsolusi.png	https://armsolusi.com/	2025-11-22 16:15:01	2025-11-22 16:15:01
3	Bumiaji	img/bumiaji.png		2025-11-22 16:15:01	2025-11-22 16:15:01
4	DSG	img/dsg.png	https://digitalsolusigrup.co.id/	2025-11-22 16:15:01	2025-11-22 16:15:01
5	Quantum Grid	img/quantum.png	https://www.quantumgrid.co.id/	2025-11-22 16:15:01	2025-11-22 16:15:01
6	Apisindo	img/apisindo.png	https://linkmediamalang.indonetwork.co.id/	2025-11-22 16:15:01	2025-11-22 16:15:01
7	Infonika	img/infonika.png	https://share.google/POFKUHfLXQGbPQYpF	2025-11-22 16:15:01	2025-11-22 16:15:01
8	Sekawan Media	img/sekawanmed.png	https://www.sekawanmedia.co.id/	2025-11-22 16:15:01	2025-11-22 16:15:01
9	Utero Indonesia	img/utero.png	https://uteroindonesia.com/	2025-11-22 16:15:01	2025-11-22 16:15:01
10	Lintas Jejak	img/lintasjajak.png	https://lintasjejak.com/	2025-11-22 16:15:01	2025-11-22 16:15:01
11	MCF	img/mcf.png	https://kabar.mcf.or.id/	2025-11-22 16:15:01	2025-11-22 16:15:01
12	Taiwan Tech	img/taiwan.png	https://www.ntust.edu.tw/	2025-11-22 16:15:01	2025-11-22 16:15:01
13	Okayama University	img/okayama.png	https://www.okayama-u.ac.jp/	2025-11-22 16:15:01	2025-11-22 16:15:01
14	Balitbang Jatim	img/dpubm.png	https://binamarga.jatimprov.go.id/	2025-11-22 16:15:01	2025-11-22 16:15:01
15	Batu Government	img/batu.png	https://share.google/GfnVOObnKw4vk0Q3x	2025-11-22 16:15:01	2025-11-22 16:15:01
16	BSSN	img/pbam.png	https://www.bssn.go.id/tentang-bssn/	2025-11-22 16:15:01	2025-11-22 16:15:01
17	Pasuruan Government	img/clrm.png	https://pasuruankota.go.id/	2025-11-22 16:15:01	2025-11-22 16:15:01
18	Kominfo	img/kominfo.png	https://www.komdigi.go.id/	2025-11-22 16:15:01	2025-11-22 16:15:01
19	Universitas Negeri Makassar	img/unm.png	https://siappg.unm.ac.id/	2025-11-22 16:15:01	2025-11-22 16:15:01
20	INSTIKI	img/instiki.png	https://instiki.ac.id/	2025-11-22 16:15:01	2025-11-22 16:15:01
21	MCC Malang	img/mcc.png	https://mcc.malangkota.go.id/	2025-11-22 16:15:01	2025-11-22 16:15:01
22	UNESA	img/unesa.png	https://unesa.ac.id/	2025-11-22 16:15:01	2025-11-22 16:15:01
23	SMAI Kepanjen	img/smiskepanjen.png	https://www.smaisaka.sch.id/	2025-11-22 16:15:01	2025-11-22 16:15:01
24	SMKN 6 Malang	img/smkn6mlg.png	https://smkn6malang.sch.id/	2025-11-22 16:15:01	2025-11-22 16:15:01
25	SMKN 13 Malang	img/smkn13mlg.png	https://smkn13malang.sch.id/	2025-11-22 16:15:01	2025-11-22 16:15:01
26	AstraTech	img/astratech.png	https://www.polytechnic.astra.ac.id/	2025-11-22 16:15:01	2025-11-22 16:15:01
\.


--
-- TOC entry 5158 (class 0 OID 50906)
-- Dependencies: 252
-- Data for Name: research_products; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.research_products (id, title, description, created_at, updated_at, image) FROM stdin;
1	Automated Cyber Security Maturity Assessment (AMATI)	\N	2025-11-22 16:14:43	2025-11-22 16:14:43	img/amati.png
2	Agrilink Vocpro	\N	2025-11-22 16:14:43	2025-11-22 16:14:43	img/agrilink.png
3	Smart Adaptive Learning System (SEALS)	\N	2025-11-22 16:14:43	2025-11-22 16:14:43	img/seals.png
5	Owncloud Server	\N	2025-11-22 16:14:43	2025-11-22 16:14:43	img/owncloud.png
6	Gitea	\N	2025-11-22 16:14:43	2025-11-22 16:14:43	img/gitea.png
4	Crowdfunding	\N	2025-11-22 16:14:43	2025-11-22 16:14:43	img/crowfunding.png
\.


--
-- TOC entry 5127 (class 0 OID 42638)
-- Dependencies: 221
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, role) FROM stdin;
2	aku	ailab@polinema.ac.id	\N	81dc9bdb52d04dc20036dbd8313ed055	\N	\N	\N	admin
11	Triana Fatmawati	triana@ailab.com	\N	827ccb0eea8a706c4c34a16891f84e7b	\N	\N	\N	member
15	Nasywa Qonita RH	nasywa@gmail.com	\N	65a54865de989d0a6a60a8ad5b07e071	\N	\N	\N	member
10	M.Hasyim Ratsanjani	hasyim@gmail.com	\N	e1d5be1c7f2f456670de3d53c7b54f4a	\N	\N	\N	member
17	Bruno Mars	bruno@polinema.ac.id	\N	827ccb0eea8a706c4c34a16891f84e7b	\N	\N	\N	member
5	Nasywaa	waanas@gmail.com	\N	f9be311e65d81a9ad8150a60844bb94c	\N	\N	\N	admin
\.


--
-- TOC entry 5136 (class 0 OID 42702)
-- Dependencies: 230
-- Data for Name: volunteer_registrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.volunteer_registrations (id, full_name, nickname, study_program, semester, email, phone, areas, skills, motivation, availability, created_at, updated_at, status) FROM stdin;
1	Nasywa	Nasy	TI	3	nasywa@gmail.com	08111111111	["AI", "Web Dev"]	HTML, Python	Ingin belajar AI	Weekends	2025-11-19 15:36:32	2025-12-04 18:35:55	Rejected
3	Herconary	Herco	Informatics Engineering	3	herco2@gmail.com	0987544588765	["Web", "Other"]	mySQL	Testing volunteer	Weekdays (Evening)	2025-12-07 14:26:07	2025-12-07 14:26:07	Pending
4	Tiara	Ti	Informatics Engineering	6	tiara@gmail.com	98764335788	["AI", "IoT"]	LARAVEL	test	Weekdays (Evening)	2025-12-09 15:26:20	2025-12-16 09:07:50	Approved
2	Testing	Test	Information Systems	3	test@gmail.com	97766566879	["AI", "IoT"]	laravel	test	Weekdays (Evening)	2025-12-07 14:24:37	2025-12-16 09:08:12	Rejected
5	Tiara	Ti	Informatics Engineering	6	tiara@gmail.com	98764335788	["AI", "IoT", "Net"]	LARAVEL	abcd	Weekdays (Morning)	2025-12-22 10:54:18	2025-12-22 10:55:00	Rejected
\.


--
-- TOC entry 5193 (class 0 OID 0)
-- Dependencies: 235
-- Name: activities_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.activities_id_seq', 20, true);


--
-- TOC entry 5194 (class 0 OID 0)
-- Dependencies: 231
-- Name: admin_actions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.admin_actions_id_seq', 2, true);


--
-- TOC entry 5195 (class 0 OID 0)
-- Dependencies: 233
-- Name: email_logs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.email_logs_id_seq', 1, false);


--
-- TOC entry 5196 (class 0 OID 0)
-- Dependencies: 223
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- TOC entry 5197 (class 0 OID 0)
-- Dependencies: 227
-- Name: lab_permit_requests_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.lab_permit_requests_id_seq', 10, true);


--
-- TOC entry 5198 (class 0 OID 0)
-- Dependencies: 249
-- Name: member_activities_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.member_activities_id_seq', 14, true);


--
-- TOC entry 5199 (class 0 OID 0)
-- Dependencies: 239
-- Name: member_backgrounds_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.member_backgrounds_id_seq', 37, true);


--
-- TOC entry 5200 (class 0 OID 0)
-- Dependencies: 245
-- Name: member_ips_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.member_ips_id_seq', 14, true);


--
-- TOC entry 5201 (class 0 OID 0)
-- Dependencies: 247
-- Name: member_ppm_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.member_ppm_id_seq', 13, true);


--
-- TOC entry 5202 (class 0 OID 0)
-- Dependencies: 241
-- Name: member_publications_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.member_publications_id_seq', 26, true);


--
-- TOC entry 5203 (class 0 OID 0)
-- Dependencies: 243
-- Name: member_research_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.member_research_id_seq', 15, true);


--
-- TOC entry 5204 (class 0 OID 0)
-- Dependencies: 237
-- Name: members_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.members_id_seq', 17, true);


--
-- TOC entry 5205 (class 0 OID 0)
-- Dependencies: 218
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 22, true);


--
-- TOC entry 5206 (class 0 OID 0)
-- Dependencies: 255
-- Name: news_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.news_id_seq', 12, true);


--
-- TOC entry 5207 (class 0 OID 0)
-- Dependencies: 225
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.personal_access_tokens_id_seq', 1, true);


--
-- TOC entry 5208 (class 0 OID 0)
-- Dependencies: 257
-- Name: projects_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.projects_id_seq', 1, false);


--
-- TOC entry 5209 (class 0 OID 0)
-- Dependencies: 253
-- Name: research_partners_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.research_partners_id_seq', 26, true);


--
-- TOC entry 5210 (class 0 OID 0)
-- Dependencies: 251
-- Name: research_products_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.research_products_id_seq', 1, false);


--
-- TOC entry 5211 (class 0 OID 0)
-- Dependencies: 220
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 1, true);


--
-- TOC entry 5212 (class 0 OID 0)
-- Dependencies: 229
-- Name: volunteer_registrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.volunteer_registrations_id_seq', 5, true);


--
-- TOC entry 4943 (class 2606 OID 42747)
-- Name: activities activities_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.activities
    ADD CONSTRAINT activities_pkey PRIMARY KEY (id);


--
-- TOC entry 4939 (class 2606 OID 42719)
-- Name: admin_actions admin_actions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.admin_actions
    ADD CONSTRAINT admin_actions_pkey PRIMARY KEY (id);


--
-- TOC entry 4941 (class 2606 OID 42734)
-- Name: email_logs email_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.email_logs
    ADD CONSTRAINT email_logs_pkey PRIMARY KEY (id);


--
-- TOC entry 4926 (class 2606 OID 42664)
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 4928 (class 2606 OID 42666)
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- TOC entry 4935 (class 2606 OID 42690)
-- Name: lab_permit_requests lab_permit_requests_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lab_permit_requests
    ADD CONSTRAINT lab_permit_requests_pkey PRIMARY KEY (id);


--
-- TOC entry 4959 (class 2606 OID 50899)
-- Name: member_activities member_activities_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.member_activities
    ADD CONSTRAINT member_activities_pkey PRIMARY KEY (id);


--
-- TOC entry 4949 (class 2606 OID 50829)
-- Name: member_backgrounds member_backgrounds_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.member_backgrounds
    ADD CONSTRAINT member_backgrounds_pkey PRIMARY KEY (id);


--
-- TOC entry 4955 (class 2606 OID 50871)
-- Name: member_ips member_ips_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.member_ips
    ADD CONSTRAINT member_ips_pkey PRIMARY KEY (id);


--
-- TOC entry 4957 (class 2606 OID 50885)
-- Name: member_ppm member_ppm_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.member_ppm
    ADD CONSTRAINT member_ppm_pkey PRIMARY KEY (id);


--
-- TOC entry 4951 (class 2606 OID 50843)
-- Name: member_publications member_publications_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.member_publications
    ADD CONSTRAINT member_publications_pkey PRIMARY KEY (id);


--
-- TOC entry 4953 (class 2606 OID 50857)
-- Name: member_research member_research_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.member_research
    ADD CONSTRAINT member_research_pkey PRIMARY KEY (id);


--
-- TOC entry 4945 (class 2606 OID 50819)
-- Name: members members_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.members
    ADD CONSTRAINT members_pkey PRIMARY KEY (id);


--
-- TOC entry 4947 (class 2606 OID 75389)
-- Name: members members_user_id_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.members
    ADD CONSTRAINT members_user_id_key UNIQUE (user_id);


--
-- TOC entry 4918 (class 2606 OID 42636)
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- TOC entry 4965 (class 2606 OID 50931)
-- Name: news news_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.news
    ADD CONSTRAINT news_pkey PRIMARY KEY (id);


--
-- TOC entry 4924 (class 2606 OID 42654)
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- TOC entry 4930 (class 2606 OID 42675)
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- TOC entry 4932 (class 2606 OID 42678)
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- TOC entry 4967 (class 2606 OID 59016)
-- Name: projects projects_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.projects
    ADD CONSTRAINT projects_pkey PRIMARY KEY (id);


--
-- TOC entry 4963 (class 2606 OID 50922)
-- Name: research_partners research_partners_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.research_partners
    ADD CONSTRAINT research_partners_pkey PRIMARY KEY (id);


--
-- TOC entry 4961 (class 2606 OID 50913)
-- Name: research_products research_products_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.research_products
    ADD CONSTRAINT research_products_pkey PRIMARY KEY (id);


--
-- TOC entry 4920 (class 2606 OID 42647)
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- TOC entry 4922 (class 2606 OID 42645)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 4937 (class 2606 OID 42709)
-- Name: volunteer_registrations volunteer_registrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.volunteer_registrations
    ADD CONSTRAINT volunteer_registrations_pkey PRIMARY KEY (id);


--
-- TOC entry 4933 (class 1259 OID 42676)
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- TOC entry 4978 (class 2620 OID 42735)
-- Name: lab_permit_requests trg_lab_permit_limit; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_lab_permit_limit BEFORE INSERT ON public.lab_permit_requests FOR EACH ROW EXECUTE FUNCTION public.trg_check_permit_limit();


--
-- TOC entry 4977 (class 2620 OID 42736)
-- Name: users trg_users_admin_limit; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_users_admin_limit BEFORE INSERT OR UPDATE ON public.users FOR EACH ROW EXECUTE FUNCTION public.trg_limit_admins();


--
-- TOC entry 4970 (class 2606 OID 42720)
-- Name: admin_actions admin_actions_admin_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.admin_actions
    ADD CONSTRAINT admin_actions_admin_id_foreign FOREIGN KEY (admin_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- TOC entry 4968 (class 2606 OID 42696)
-- Name: lab_permit_requests lab_permit_requests_admin_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lab_permit_requests
    ADD CONSTRAINT lab_permit_requests_admin_id_foreign FOREIGN KEY (admin_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- TOC entry 4969 (class 2606 OID 42691)
-- Name: lab_permit_requests lab_permit_requests_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lab_permit_requests
    ADD CONSTRAINT lab_permit_requests_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- TOC entry 4976 (class 2606 OID 50900)
-- Name: member_activities member_activities_member_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.member_activities
    ADD CONSTRAINT member_activities_member_id_foreign FOREIGN KEY (member_id) REFERENCES public.members(id) ON DELETE CASCADE;


--
-- TOC entry 4971 (class 2606 OID 50830)
-- Name: member_backgrounds member_backgrounds_member_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.member_backgrounds
    ADD CONSTRAINT member_backgrounds_member_id_foreign FOREIGN KEY (member_id) REFERENCES public.members(id) ON DELETE CASCADE;


--
-- TOC entry 4974 (class 2606 OID 50872)
-- Name: member_ips member_ips_member_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.member_ips
    ADD CONSTRAINT member_ips_member_id_foreign FOREIGN KEY (member_id) REFERENCES public.members(id) ON DELETE CASCADE;


--
-- TOC entry 4975 (class 2606 OID 50886)
-- Name: member_ppm member_ppm_member_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.member_ppm
    ADD CONSTRAINT member_ppm_member_id_foreign FOREIGN KEY (member_id) REFERENCES public.members(id) ON DELETE CASCADE;


--
-- TOC entry 4972 (class 2606 OID 50844)
-- Name: member_publications member_publications_member_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.member_publications
    ADD CONSTRAINT member_publications_member_id_foreign FOREIGN KEY (member_id) REFERENCES public.members(id) ON DELETE CASCADE;


--
-- TOC entry 4973 (class 2606 OID 50858)
-- Name: member_research member_research_member_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.member_research
    ADD CONSTRAINT member_research_member_id_foreign FOREIGN KEY (member_id) REFERENCES public.members(id) ON DELETE CASCADE;


--
-- TOC entry 5171 (class 0 OID 0)
-- Dependencies: 6
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE USAGE ON SCHEMA public FROM PUBLIC;


-- Completed on 2025-12-22 11:45:10

--
-- PostgreSQL database dump complete
--

