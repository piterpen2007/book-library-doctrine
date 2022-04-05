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

ALTER TABLE ONLY public.text_document_books DROP CONSTRAINT fk_e97cb0f7bf396750;
ALTER TABLE ONLY public.text_document_magazine_numbers DROP CONSTRAINT fk_ac00d7073eb84a1d;
ALTER TABLE ONLY public.authors DROP CONSTRAINT fk_8e0c2a51f92f3e70;
ALTER TABLE ONLY public.text_documents DROP CONSTRAINT fk_51bf414c6bf700bd;
ALTER TABLE ONLY public.purchase_prices DROP CONSTRAINT fk_3fa46f0cd379f864;
ALTER TABLE ONLY public.purchase_prices DROP CONSTRAINT fk_3fa46f0c38248176;
ALTER TABLE ONLY public.text_document_to_author DROP CONSTRAINT fk_262e170f675f31b;
ALTER TABLE ONLY public.text_document_to_author DROP CONSTRAINT fk_262e170d379f864;
ALTER TABLE ONLY public.text_document_magazines DROP CONSTRAINT fk_25415060bf396750;
DROP INDEX public.users_login_unq;
DROP INDEX public.text_documents_year_idx;
DROP INDEX public.text_documents_type_idx;
DROP INDEX public.text_documents_title_idx;
DROP INDEX public.text_document_status_name_unq;
DROP INDEX public.text_document_books_isbn_unq;
DROP INDEX public.magazine_number_idx;
DROP INDEX public.issn_uniq;
DROP INDEX public.idx_ac00d7073eb84a1d;
DROP INDEX public.idx_8e0c2a51f92f3e70;
DROP INDEX public.idx_51bf414c6bf700bd;
DROP INDEX public.idx_3fa46f0cd379f864;
DROP INDEX public.idx_3fa46f0c38248176;
DROP INDEX public.idx_262e170f675f31b;
DROP INDEX public.idx_262e170d379f864;
DROP INDEX public.currency_name_unq;
DROP INDEX public.currency_code_unq;
DROP INDEX public.country_code_unq;
DROP INDEX public.country_code3_unq;
DROP INDEX public.country_code2_unq;
DROP INDEX public.authors_surname_idx;
ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
ALTER TABLE ONLY public.text_documents DROP CONSTRAINT text_documents_pkey;
ALTER TABLE ONLY public.text_document_to_author DROP CONSTRAINT text_document_to_author_pkey;
ALTER TABLE ONLY public.text_document_status DROP CONSTRAINT text_document_status_pkey;
ALTER TABLE ONLY public.text_document_magazines DROP CONSTRAINT text_document_magazines_pkey;
ALTER TABLE ONLY public.text_document_magazine_numbers DROP CONSTRAINT text_document_magazine_numbers_pkey;
ALTER TABLE ONLY public.text_document_books DROP CONSTRAINT text_document_books_pkey;
ALTER TABLE ONLY public.purchase_prices DROP CONSTRAINT purchase_prices_pkey;
ALTER TABLE ONLY public.doctrine_migration_versions DROP CONSTRAINT doctrine_migration_versions_pkey;
ALTER TABLE ONLY public.currency DROP CONSTRAINT currency_pkey;
ALTER TABLE ONLY public.country DROP CONSTRAINT country_pkey;
ALTER TABLE ONLY public.authors DROP CONSTRAINT authors_pkey;
DROP SEQUENCE public.users_id_seq;
DROP TABLE public.users;
DROP SEQUENCE public.text_documents_id_seq;
DROP TABLE public.text_documents;
DROP TABLE public.text_document_to_author;
DROP SEQUENCE public.text_document_status_id_seq;
DROP TABLE public.text_document_status;
DROP TABLE public.text_document_magazines;
DROP SEQUENCE public.text_document_magazine_numbers_id_seq;
DROP TABLE public.text_document_magazine_numbers;
DROP TABLE public.text_document_books;
DROP SEQUENCE public.purchase_prices_id_seq;
DROP TABLE public.purchase_prices;
DROP TABLE public.doctrine_migration_versions;
DROP SEQUENCE public.currency_id_seq;
DROP TABLE public.currency;
DROP SEQUENCE public.country_id_seq;
DROP TABLE public.country;
DROP SEQUENCE public.authors_id_seq;
DROP TABLE public.authors;
SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: authors; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.authors (
    id integer NOT NULL,
    country_id integer,
    birthday date NOT NULL,
    name character varying(255) NOT NULL,
    surname character varying(255) NOT NULL
);


ALTER TABLE public.authors OWNER TO postgres;

--
-- Name: COLUMN authors.birthday; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.authors.birthday IS '(DC2Type:date_immutable)';


--
-- Name: authors_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.authors_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.authors_id_seq OWNER TO postgres;

--
-- Name: country; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.country (
    id integer NOT NULL,
    code2 character varying(2) NOT NULL,
    code3 character varying(3) NOT NULL,
    code character varying(3) NOT NULL,
    name character varying(100) NOT NULL
);


ALTER TABLE public.country OWNER TO postgres;

--
-- Name: country_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.country_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.country_id_seq OWNER TO postgres;

--
-- Name: currency; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.currency (
    id integer NOT NULL,
    code character varying(3) NOT NULL,
    name character varying(3) NOT NULL,
    description character varying(255) NOT NULL
);


ALTER TABLE public.currency OWNER TO postgres;

--
-- Name: currency_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.currency_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.currency_id_seq OWNER TO postgres;

--
-- Name: doctrine_migration_versions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.doctrine_migration_versions (
    version character varying(1024) NOT NULL,
    executed_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    execution_time integer
);


ALTER TABLE public.doctrine_migration_versions OWNER TO postgres;

--
-- Name: purchase_prices; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.purchase_prices (
    id integer NOT NULL,
    currency_id integer,
    text_document_id integer,
    date timestamp(0) without time zone NOT NULL,
    price integer NOT NULL
);


ALTER TABLE public.purchase_prices OWNER TO postgres;

--
-- Name: COLUMN purchase_prices.date; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.purchase_prices.date IS '(DC2Type:datetime_immutable)';


--
-- Name: purchase_prices_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.purchase_prices_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.purchase_prices_id_seq OWNER TO postgres;

--
-- Name: text_document_books; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.text_document_books (
    id integer NOT NULL,
    isbn character varying(13) DEFAULT NULL::character varying
);


ALTER TABLE public.text_document_books OWNER TO postgres;

--
-- Name: text_document_magazine_numbers; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.text_document_magazine_numbers (
    id integer NOT NULL,
    magazine_id integer,
    number integer NOT NULL
);


ALTER TABLE public.text_document_magazine_numbers OWNER TO postgres;

--
-- Name: text_document_magazine_numbers_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.text_document_magazine_numbers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.text_document_magazine_numbers_id_seq OWNER TO postgres;

--
-- Name: text_document_magazines; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.text_document_magazines (
    id integer NOT NULL,
    issn character varying(8) DEFAULT NULL::character varying
);


ALTER TABLE public.text_document_magazines OWNER TO postgres;

--
-- Name: text_document_status; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.text_document_status (
    id integer NOT NULL,
    name character varying(50) NOT NULL
);


ALTER TABLE public.text_document_status OWNER TO postgres;

--
-- Name: text_document_status_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.text_document_status_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.text_document_status_id_seq OWNER TO postgres;

--
-- Name: text_document_to_author; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.text_document_to_author (
    author_id integer NOT NULL,
    text_document_id integer NOT NULL
);


ALTER TABLE public.text_document_to_author OWNER TO postgres;

--
-- Name: text_documents; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.text_documents (
    id integer NOT NULL,
    status_id integer,
    title character varying(255) NOT NULL,
    year date NOT NULL,
    type character varying(30) NOT NULL
);


ALTER TABLE public.text_documents OWNER TO postgres;

--
-- Name: COLUMN text_documents.year; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.text_documents.year IS '(DC2Type:date_immutable)';


--
-- Name: text_documents_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.text_documents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.text_documents_id_seq OWNER TO postgres;

--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id integer NOT NULL,
    login character varying(50) NOT NULL,
    password character varying(60) NOT NULL
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO postgres;

--
-- Name: authors authors_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.authors
    ADD CONSTRAINT authors_pkey PRIMARY KEY (id);


--
-- Name: country country_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.country
    ADD CONSTRAINT country_pkey PRIMARY KEY (id);


--
-- Name: currency currency_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.currency
    ADD CONSTRAINT currency_pkey PRIMARY KEY (id);


--
-- Name: doctrine_migration_versions doctrine_migration_versions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.doctrine_migration_versions
    ADD CONSTRAINT doctrine_migration_versions_pkey PRIMARY KEY (version);


--
-- Name: purchase_prices purchase_prices_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.purchase_prices
    ADD CONSTRAINT purchase_prices_pkey PRIMARY KEY (id);


--
-- Name: text_document_books text_document_books_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.text_document_books
    ADD CONSTRAINT text_document_books_pkey PRIMARY KEY (id);


--
-- Name: text_document_magazine_numbers text_document_magazine_numbers_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.text_document_magazine_numbers
    ADD CONSTRAINT text_document_magazine_numbers_pkey PRIMARY KEY (id);


--
-- Name: text_document_magazines text_document_magazines_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.text_document_magazines
    ADD CONSTRAINT text_document_magazines_pkey PRIMARY KEY (id);


--
-- Name: text_document_status text_document_status_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.text_document_status
    ADD CONSTRAINT text_document_status_pkey PRIMARY KEY (id);


--
-- Name: text_document_to_author text_document_to_author_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.text_document_to_author
    ADD CONSTRAINT text_document_to_author_pkey PRIMARY KEY (author_id, text_document_id);


--
-- Name: text_documents text_documents_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.text_documents
    ADD CONSTRAINT text_documents_pkey PRIMARY KEY (id);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: authors_surname_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX authors_surname_idx ON public.authors USING btree (surname);


--
-- Name: country_code2_unq; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX country_code2_unq ON public.country USING btree (code2);


--
-- Name: country_code3_unq; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX country_code3_unq ON public.country USING btree (code3);


--
-- Name: country_code_unq; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX country_code_unq ON public.country USING btree (code);


--
-- Name: currency_code_unq; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX currency_code_unq ON public.currency USING btree (code);


--
-- Name: currency_name_unq; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX currency_name_unq ON public.currency USING btree (name);


--
-- Name: idx_262e170d379f864; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_262e170d379f864 ON public.text_document_to_author USING btree (text_document_id);


--
-- Name: idx_262e170f675f31b; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_262e170f675f31b ON public.text_document_to_author USING btree (author_id);


--
-- Name: idx_3fa46f0c38248176; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_3fa46f0c38248176 ON public.purchase_prices USING btree (currency_id);


--
-- Name: idx_3fa46f0cd379f864; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_3fa46f0cd379f864 ON public.purchase_prices USING btree (text_document_id);


--
-- Name: idx_51bf414c6bf700bd; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_51bf414c6bf700bd ON public.text_documents USING btree (status_id);


--
-- Name: idx_8e0c2a51f92f3e70; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8e0c2a51f92f3e70 ON public.authors USING btree (country_id);


--
-- Name: idx_ac00d7073eb84a1d; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_ac00d7073eb84a1d ON public.text_document_magazine_numbers USING btree (magazine_id);


--
-- Name: issn_uniq; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX issn_uniq ON public.text_document_magazines USING btree (issn);


--
-- Name: magazine_number_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX magazine_number_idx ON public.text_document_magazine_numbers USING btree (number);


--
-- Name: text_document_books_isbn_unq; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX text_document_books_isbn_unq ON public.text_document_books USING btree (isbn);


--
-- Name: text_document_status_name_unq; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX text_document_status_name_unq ON public.text_document_status USING btree (name);


--
-- Name: text_documents_title_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX text_documents_title_idx ON public.text_documents USING btree (title);


--
-- Name: text_documents_type_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX text_documents_type_idx ON public.text_documents USING btree (type);


--
-- Name: text_documents_year_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX text_documents_year_idx ON public.text_documents USING btree (year);


--
-- Name: users_login_unq; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX users_login_unq ON public.users USING btree (login);


--
-- Name: text_document_magazines fk_25415060bf396750; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.text_document_magazines
    ADD CONSTRAINT fk_25415060bf396750 FOREIGN KEY (id) REFERENCES public.text_documents(id) ON DELETE CASCADE;


--
-- Name: text_document_to_author fk_262e170d379f864; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.text_document_to_author
    ADD CONSTRAINT fk_262e170d379f864 FOREIGN KEY (text_document_id) REFERENCES public.text_documents(id) ON DELETE CASCADE;


--
-- Name: text_document_to_author fk_262e170f675f31b; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.text_document_to_author
    ADD CONSTRAINT fk_262e170f675f31b FOREIGN KEY (author_id) REFERENCES public.authors(id);


--
-- Name: purchase_prices fk_3fa46f0c38248176; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.purchase_prices
    ADD CONSTRAINT fk_3fa46f0c38248176 FOREIGN KEY (currency_id) REFERENCES public.currency(id);


--
-- Name: purchase_prices fk_3fa46f0cd379f864; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.purchase_prices
    ADD CONSTRAINT fk_3fa46f0cd379f864 FOREIGN KEY (text_document_id) REFERENCES public.text_documents(id);


--
-- Name: text_documents fk_51bf414c6bf700bd; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.text_documents
    ADD CONSTRAINT fk_51bf414c6bf700bd FOREIGN KEY (status_id) REFERENCES public.text_document_status(id);


--
-- Name: authors fk_8e0c2a51f92f3e70; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.authors
    ADD CONSTRAINT fk_8e0c2a51f92f3e70 FOREIGN KEY (country_id) REFERENCES public.country(id);


--
-- Name: text_document_magazine_numbers fk_ac00d7073eb84a1d; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.text_document_magazine_numbers
    ADD CONSTRAINT fk_ac00d7073eb84a1d FOREIGN KEY (magazine_id) REFERENCES public.text_document_magazines(id);


--
-- Name: text_document_books fk_e97cb0f7bf396750; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.text_document_books
    ADD CONSTRAINT fk_e97cb0f7bf396750 FOREIGN KEY (id) REFERENCES public.text_documents(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

