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

--
-- Data for Name: country; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.country (id, code2, code3, code, name) VALUES (1, 'ru', 'rus', '643', 'Россия');
INSERT INTO public.country (id, code2, code3, code, name) VALUES (2, 'us', 'usa', '840', 'США');


--
-- Data for Name: authors; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.authors (id, country_id, birthday, name, surname) VALUES (8, 1, '1883-12-10', 'Алексей', 'Толстой');
INSERT INTO public.authors (id, country_id, birthday, name, surname) VALUES (6, 1, '1962-11-22', 'Виктор', 'Пелевин');
INSERT INTO public.authors (id, country_id, birthday, name, surname) VALUES (4, 1, '1828-08-28', 'Лев', 'Толстой');
INSERT INTO public.authors (id, country_id, birthday, name, surname) VALUES (2, 1, '1959-09-26', 'Илья', 'Кормильцев');
INSERT INTO public.authors (id, country_id, birthday, name, surname) VALUES (5, 2, '1928-12-16', 'Филип', 'Дик');
INSERT INTO public.authors (id, country_id, birthday, name, surname) VALUES (3, 2, '1964-03-07', 'Брет', 'Эллис');
INSERT INTO public.authors (id, country_id, birthday, name, surname) VALUES (1, 2, '1962-02-21', 'Чак', 'Паланик');
INSERT INTO public.authors (id, country_id, birthday, name, surname) VALUES (14, 1, '1659-06-11', 'Цунетомо', 'Ямамото');


--
-- Data for Name: currency; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.currency (id, code, name, description) VALUES (1, '643', 'RUB', 'Рубль');


--
-- Data for Name: doctrine_migration_versions; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: text_document_status; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.text_document_status (id, name) VALUES (1, 'archive');
INSERT INTO public.text_document_status (id, name) VALUES (2, 'inStock');


--
-- Data for Name: text_documents; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (1, 2, 'Бойцовский клуб', '1996-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (2, 2, 'Снафф', '2008-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (3, 2, 'Проклятые', '2011-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (4, 2, 'Никто из ниоткуда. Сценарий, стихи, рассказы', '2006-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (5, 2, 'Скованные одной цепью. Стихи', '1990-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (8, 2, 'Крейцерова соната', '1891-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (10, 2, 'Мечтают ли андроиды об электроовцах?', '1966-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (11, 2, 'S.N.U.F.F.', '2011-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (12, 2, 'iPhuck 10', '2017-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (13, 2, 'Чапаев и Пустота', '1996-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (16, 2, 'GQ', '2020-01-01', 'magazine');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (17, 2, 'Логос', '2020-01-01', 'magazine');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (18, 2, 'National Geographic Magazine', '2020-01-01', 'magazine');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (19, 2, 'Rolling Stone', '2017-01-01', 'magazine');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (20, 2, 'Текстовой документ', '2021-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (21, 2, 'Новый журнал', '2021-01-01', 'magazine');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (22, 2, 'Текстовой документ', '2021-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (24, 2, 'Текстовой документ', '2021-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (26, 2, 'Текстовой документ', '2021-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (9, 2, 'Карма', '1894-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (14, 2, 'Esquire', '2020-01-01', 'magazine');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (28, 2, 'Текстовой документ', '2021-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (30, 2, 'Текстовой документ', '2021-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (32, 2, 'Текстовой документ', '2021-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (34, 2, 'Текстовой документ', '2021-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (36, 2, 'Текстовой документ', '2021-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (38, 2, 'Текстовой документ', '2021-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (40, 2, 'Текстовой документ', '2021-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (72, 2, 'Текстовой документ', '2021-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (73, 2, 'Текстовой документ', '2021-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (51, 2, 'Текстовой документ', '2021-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (53, 2, 'Текстовой документ', '2021-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (55, 2, 'Текстовой документ', '2021-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (57, 2, 'Текстовой документ', '2021-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (42, 2, 'Текстовой документ', '2021-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (59, 2, 'Текстовой документ', '2021-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (44, 2, 'Текстовой документ', '2021-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (56, 2, 'Текстовой документ', '2021-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (46, 2, 'Текстовой документ', '2021-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (74, 2, 'Текстовой документ', '2021-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (75, 2, 'Текстовой документ', '2021-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (77, 2, 'Текстовой документ', '2021-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (79, 2, 'Текстовой документ', '2021-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (81, 2, 'Тестовая книга', '1991-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (82, 2, 'Тестовый журнал', '1900-01-01', 'magazine');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (83, 2, 'Тестовая книга с 2 авторами', '1900-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (84, 2, 'Текстовой документ', '2021-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (87, 2, 'Текстовой документ', '2021-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (95, 2, 'Текстовой документ', '2021-03-22', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (93, 2, 'Текстовой документ', '2021-03-22', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (97, 2, 'Текстовой документ', '2021-03-22', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (99, 2, 'Текстовой документ', '2021-03-22', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (105, 2, 'Текстовой документ', '2021-03-30', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (107, 2, 'Текстовой документ', '2021-03-30', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (7, 1, 'The Rules of Attraction', '1987-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (111, 2, 'Текстовой документ', '2021-04-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (114, 2, 'Текстовой документ', '2021-04-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (118, 2, 'Текстовой документ', '2021-04-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (122, 2, 'Текстовой документ', '2021-04-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (6, 1, 'Glamorama', '1998-01-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (126, 2, 'Текстовой документ', '2021-04-01', 'book');
INSERT INTO public.text_documents (id, status_id, title, year, type) VALUES (128, 2, 'тестовый журнал', '2000-04-01', 'magazine');


--
-- Data for Name: purchase_prices; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.purchase_prices (id, currency_id, text_document_id, date, price) VALUES (2, 1, 11, '2021-07-26 00:00:00', 18600);
INSERT INTO public.purchase_prices (id, currency_id, text_document_id, date, price) VALUES (1, 1, 1, '2021-07-27 15:24:00', 22200);


--
-- Data for Name: text_document_books; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.text_document_books (id, isbn) VALUES (1, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (2, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (3, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (4, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (5, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (7, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (8, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (10, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (11, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (12, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (13, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (20, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (22, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (24, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (26, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (9, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (28, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (30, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (32, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (34, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (36, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (38, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (40, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (72, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (73, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (51, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (53, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (55, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (57, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (42, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (59, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (44, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (56, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (46, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (74, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (75, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (77, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (79, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (81, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (83, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (84, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (87, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (93, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (95, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (97, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (99, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (6, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (105, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (107, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (111, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (114, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (118, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (122, NULL);
INSERT INTO public.text_document_books (id, isbn) VALUES (126, NULL);


--
-- Data for Name: text_document_magazines; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.text_document_magazines (id, issn) VALUES (16, NULL);
INSERT INTO public.text_document_magazines (id, issn) VALUES (17, NULL);
INSERT INTO public.text_document_magazines (id, issn) VALUES (18, NULL);
INSERT INTO public.text_document_magazines (id, issn) VALUES (19, NULL);
INSERT INTO public.text_document_magazines (id, issn) VALUES (21, NULL);
INSERT INTO public.text_document_magazines (id, issn) VALUES (14, NULL);
INSERT INTO public.text_document_magazines (id, issn) VALUES (82, NULL);
INSERT INTO public.text_document_magazines (id, issn) VALUES (128, NULL);


--
-- Data for Name: text_document_magazine_numbers; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.text_document_magazine_numbers (id, magazine_id, number) VALUES (1, 14, 2);
INSERT INTO public.text_document_magazine_numbers (id, magazine_id, number) VALUES (2, 16, 1);
INSERT INTO public.text_document_magazine_numbers (id, magazine_id, number) VALUES (3, 17, 1);
INSERT INTO public.text_document_magazine_numbers (id, magazine_id, number) VALUES (4, 18, 7);
INSERT INTO public.text_document_magazine_numbers (id, magazine_id, number) VALUES (5, 19, 4);
INSERT INTO public.text_document_magazine_numbers (id, magazine_id, number) VALUES (6, 21, 10);
INSERT INTO public.text_document_magazine_numbers (id, magazine_id, number) VALUES (7, 14, 1);
INSERT INTO public.text_document_magazine_numbers (id, magazine_id, number) VALUES (8, 82, 13);
INSERT INTO public.text_document_magazine_numbers (id, magazine_id, number) VALUES (9, 82, 133);
INSERT INTO public.text_document_magazine_numbers (id, magazine_id, number) VALUES (15, 128, 139);


--
-- Data for Name: text_document_to_author; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (1, 1);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (1, 2);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (1, 3);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (2, 4);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (2, 5);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (3, 7);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (4, 8);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (5, 10);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (6, 11);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (6, 12);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (6, 13);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (1, 20);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (1, 22);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (1, 24);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (1, 26);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (4, 9);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (1, 28);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (1, 30);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (1, 32);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (1, 34);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (1, 36);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (1, 38);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (1, 40);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (1, 42);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (1, 44);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (1, 46);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (1, 51);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (1, 53);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (1, 55);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (4, 56);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (4, 57);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (4, 59);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (4, 72);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (4, 73);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (4, 74);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (4, 75);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (4, 77);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (4, 79);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (2, 81);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (2, 83);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (3, 83);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (4, 84);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (4, 87);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (4, 93);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (4, 95);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (4, 97);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (4, 99);
INSERT INTO public.text_document_to_author (author_id, text_document_id) VALUES (3, 6);


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.users (id, login, password) VALUES (1, 'admin', '$2y$10$CN3ohVq.AWk/1TM6VC9Ume9JsI/WK0WrrhZc3WNndSyA87Y8PDDmO');


--
-- Name: authors_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.authors_id_seq', 20, true);


--
-- Name: country_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.country_id_seq', 2, true);


--
-- Name: currency_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.currency_id_seq', 1, true);


--
-- Name: purchase_prices_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.purchase_prices_id_seq', 2, true);


--
-- Name: text_document_magazine_numbers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.text_document_magazine_numbers_id_seq', 15, true);


--
-- Name: text_document_status_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.text_document_status_id_seq', 24, true);


--
-- Name: text_documents_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.text_documents_id_seq', 128, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 2, true);


--
-- PostgreSQL database dump complete
--

