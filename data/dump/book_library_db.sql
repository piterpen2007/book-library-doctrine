--
-- PostgreSQL database dump
--

-- Dumped from database version 12.9 (Ubuntu 12.9-0ubuntu0.20.04.1)
-- Dumped by pg_dump version 12.9 (Ubuntu 12.9-0ubuntu0.20.04.1)

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
-- Name: authors; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.authors (
    id integer,
    name character varying(255),
    surname character varying(255),
    birthday date,
    country character varying(2)
);


ALTER TABLE public.authors OWNER TO postgres;

--
-- Name: purchase_price; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.purchase_price (
    text_document_id int,
    date timestamp without  time zone,
    price integer,
    currency character varying(3)
);


ALTER TABLE public.purchase_price OWNER TO postgres;

--
-- Name: text_documents; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.text_documents (
    id integer,
    title character varying(255),
    year date,
    author_id integer,
    status character varying(30),
    type character varying(30)
);


ALTER TABLE public.text_documents OWNER TO postgres;

--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id integer,
    login character varying(50),
    password character varying(60)
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Data for Name: authors; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.authors (id, name, surname, birthday, country) FROM stdin;
\.


--
-- Data for Name: purchase_price; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.purchase_price (date, price, currency) FROM stdin;
\.


--
-- Data for Name: text_documents; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.text_documents (id, title, year, author_id, status, type) FROM stdin;
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, login, password) FROM stdin;
\.


--
-- PostgreSQL database dump complete
--

