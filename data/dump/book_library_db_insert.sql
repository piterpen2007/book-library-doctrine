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

--
-- Data for Name: authors; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.authors (id, name, surname, birthday, country) VALUES (1, 'Чак', 'Паланик', '1962-02-21', 'us');
INSERT INTO public.authors (id, name, surname, birthday, country) VALUES (2, 'Илья', 'Кормильцев', '1959-09-26', 'ru');
INSERT INTO public.authors (id, name, surname, birthday, country) VALUES (3, 'Брет', 'Эллис', '1964-03-07', 'us');
INSERT INTO public.authors (id, name, surname, birthday, country) VALUES (4, 'Лев', 'Толстой', '1828-08-28', 'ru');
INSERT INTO public.authors (id, name, surname, birthday, country) VALUES (5, 'Филип', 'Дик', '1928-12-16', 'us');
INSERT INTO public.authors (id, name, surname, birthday, country) VALUES (6, 'Виктор', 'Пелевин', '1962-11-22', 'ru');


--
-- Data for Name: purchase_price; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.purchase_price (date, price, currency, text_document_id) VALUES ('2021-07-27 12:14:00', 2220, 'RUB', 11);
INSERT INTO public.purchase_price (date, price, currency, text_document_id) VALUES ('2021-07-27 12:14:00', 2220, 'RUB', 14);


--
-- Data for Name: text_documents; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.text_documents (id, title, year, author_id, status, type, number) VALUES (1, 'Бойцовский клуб', '1996-01-01', 1, 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, author_id, status, type, number) VALUES (2, 'Снафф', '2008-01-01', 1, 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, author_id, status, type, number) VALUES (3, 'Проклятые', '2011-01-01', 1, 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, author_id, status, type, number) VALUES (4, 'Никто из ниоткуда. Сценарий, стихи, рассказы', '2006-01-01', 2, 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, author_id, status, type, number) VALUES (5, 'Скованные одной цепью. Стихи', '1990-01-01', 2, 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, author_id, status, type, number) VALUES (7, 'The Rules of Attraction', '1987-01-01', 3, 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, author_id, status, type, number) VALUES (8, 'Крейцерова соната', '1891-01-01', 4, 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, author_id, status, type, number) VALUES (9, 'Карма', '1894-01-01', 4, 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, author_id, status, type, number) VALUES (10, 'Мечтают ли андроиды об электроовцах?', '1966-01-01', 5, 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, author_id, status, type, number) VALUES (11, 'S.N.U.F.F.', '2011-01-01', 6, 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, author_id, status, type, number) VALUES (12, 'iPhuck 10', '2017-01-01', 6, 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, author_id, status, type, number) VALUES (13, 'Чапаев и Пустота', '1996-01-01', 6, 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, author_id, status, type, number) VALUES (14, 'Esquire', '2020-01-01', NULL, 'inStock', 'magazine', 1);
INSERT INTO public.text_documents (id, title, year, author_id, status, type, number) VALUES (15, 'Esquire', '2020-01-01', NULL, 'inStock', 'magazine', 2);
INSERT INTO public.text_documents (id, title, year, author_id, status, type, number) VALUES (16, 'GQ', '2020-01-01', NULL, 'inStock', 'magazine', 1);
INSERT INTO public.text_documents (id, title, year, author_id, status, type, number) VALUES (17, 'Логос', '2020-01-01', NULL, 'inStock', 'magazine', 1);
INSERT INTO public.text_documents (id, title, year, author_id, status, type, number) VALUES (18, 'National Geographic Magazine', '2020-01-01', NULL, 'inStock', 'magazine', 7);
INSERT INTO public.text_documents (id, title, year, author_id, status, type, number) VALUES (19, 'Rolling Stone', '2017-01-01', NULL, 'inStock', 'magazine', 4);


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.users (id, login, password) VALUES (1, 'admin', '$2y$10$ap5kuiAJfbFluRthHwErd.a3yQ05JRuN7DiazsyBrzFhQT8FF4ZFO');


--
-- Name: authors_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.authors_id_seq', 7, true);


--
-- Name: text_documents_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.text_documents_id_seq', 20, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 2, true);


--
-- PostgreSQL database dump complete
--

