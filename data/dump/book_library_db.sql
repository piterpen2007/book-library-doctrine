--
-- PostgreSQL database dump
--

-- Dumped from database version 14.2
-- Dumped by pg_dump version 14.2

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

ALTER TABLE ONLY public.users DROP CONSTRAINT user_id_pk;
ALTER TABLE ONLY public.text_documents DROP CONSTRAINT text_documents_id_pk;
ALTER TABLE ONLY public.purchase_price DROP CONSTRAINT purchase_price_pkey;
ALTER TABLE ONLY public.authors DROP CONSTRAINT authors_pk;
DROP TABLE public.users;
DROP TABLE public.text_documents;
DROP TABLE public.text_document_to_author;
DROP TABLE public.purchase_price;
DROP TABLE public.authors;
SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: authors; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.authors (
    id integer NOT NULL,
    name character varying(255),
    surname character varying(255),
    birthday date,
    country character varying(2)
);


ALTER TABLE public.authors OWNER TO postgres;

--
-- Name: authors_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.authors ALTER COLUMN id ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.authors_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: purchase_price; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.purchase_price (
    text_document_id integer,
    date timestamp without time zone,
    price integer,
    currency character varying(3),
    id integer NOT NULL
);


ALTER TABLE public.purchase_price OWNER TO postgres;

--
-- Name: purchase_price_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.purchase_price ALTER COLUMN id ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.purchase_price_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: text_document_to_author; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.text_document_to_author (
    text_document_id integer NOT NULL,
    author_id integer NOT NULL
);


ALTER TABLE public.text_document_to_author OWNER TO postgres;

--
-- Name: text_documents; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.text_documents (
    id integer NOT NULL,
    title character varying(255),
    year date,
    status character varying(30),
    type character varying(30),
    number integer
);


ALTER TABLE public.text_documents OWNER TO postgres;

--
-- Name: text_documents_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.text_documents ALTER COLUMN id ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.text_documents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id integer NOT NULL,
    login character varying(50),
    password character varying(60)
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.users ALTER COLUMN id ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: authors authors_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.authors
    ADD CONSTRAINT authors_pk PRIMARY KEY (id);


--
-- Name: purchase_price purchase_price_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.purchase_price
    ADD CONSTRAINT purchase_price_pkey PRIMARY KEY (id);


--
-- Name: text_documents text_documents_id_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.text_documents
    ADD CONSTRAINT text_documents_id_pk PRIMARY KEY (id);


--
-- Name: users user_id_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT user_id_pk PRIMARY KEY (id);


--
-- PostgreSQL database dump complete
--

