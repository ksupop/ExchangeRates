--
-- PostgreSQL database dump
--

-- Dumped from database version 10.23
-- Dumped by pg_dump version 10.23

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
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: valute; Type: TABLE; Schema: public; Owner: val_adm
--

CREATE TABLE public.valute (
    key integer NOT NULL,
    id text NOT NULL,
    numcode integer,
    charcode text,
    nominal integer,
    name text,
    value real,
    vunitrate real,
    valdate date
);


ALTER TABLE public.valute OWNER TO val_adm;

--
-- Name: valute_key_seq; Type: SEQUENCE; Schema: public; Owner: val_adm
--

CREATE SEQUENCE public.valute_key_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.valute_key_seq OWNER TO val_adm;

--
-- Name: valute_key_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: val_adm
--

ALTER SEQUENCE public.valute_key_seq OWNED BY public.valute.key;


--
-- Name: valute key; Type: DEFAULT; Schema: public; Owner: val_adm
--

ALTER TABLE ONLY public.valute ALTER COLUMN key SET DEFAULT nextval('public.valute_key_seq'::regclass);


--
-- Data for Name: valute; Type: TABLE DATA; Schema: public; Owner: val_adm
--

COPY public.valute (key, id, numcode, charcode, nominal, name, value, vunitrate, valdate) FROM stdin;
\.


--
-- Name: valute_key_seq; Type: SEQUENCE SET; Schema: public; Owner: val_adm
--

SELECT pg_catalog.setval('public.valute_key_seq', 21278, true);


--
-- Name: valute valute_pkey; Type: CONSTRAINT; Schema: public; Owner: val_adm
--

ALTER TABLE ONLY public.valute
    ADD CONSTRAINT valute_pkey PRIMARY KEY (key);


--
-- PostgreSQL database dump complete
--

