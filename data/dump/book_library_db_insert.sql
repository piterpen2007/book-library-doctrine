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
-- Data for Name: authors; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.authors (id, name, surname, birthday, country) VALUES (1, 'Чак', 'Паланик', '1962-02-21', 'us');
INSERT INTO public.authors (id, name, surname, birthday, country) VALUES (2, 'Илья', 'Кормильцев', '1959-09-26', 'ru');
INSERT INTO public.authors (id, name, surname, birthday, country) VALUES (3, 'Брет', 'Эллис', '1964-03-07', 'us');
INSERT INTO public.authors (id, name, surname, birthday, country) VALUES (4, 'Лев', 'Толстой', '1828-08-28', 'ru');
INSERT INTO public.authors (id, name, surname, birthday, country) VALUES (5, 'Филип', 'Дик', '1928-12-16', 'us');
INSERT INTO public.authors (id, name, surname, birthday, country) VALUES (6, 'Виктор', 'Пелевин', '1962-11-22', 'ru');
INSERT INTO public.authors (id, name, surname, birthday, country) VALUES (8, 'Нил', 'Гейман', '1960-11-10', 'uk');
INSERT INTO public.authors (id, name, surname, birthday, country) VALUES (9, 'Терри', 'Праччет', '1938-04-28', 'uk');
INSERT INTO public.authors (id, name, surname, birthday, country) VALUES (10, 'Нил', 'Гейман', '1960-11-10', 'uk');
INSERT INTO public.authors (id, name, surname, birthday, country) VALUES (11, 'Терри', 'Праччет', '1938-04-28', 'uk');


--
-- Data for Name: purchase_price; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.purchase_price (text_document_id, date, price, currency, id) VALUES (11, '2021-07-27 15:14:00', 2220, 'RUB', 1);
INSERT INTO public.purchase_price (text_document_id, date, price, currency, id) VALUES (14, '2021-07-27 15:14:00', 2220, 'RUB', 2);
INSERT INTO public.purchase_price (text_document_id, date, price, currency, id) VALUES (130, '2020-01-01 00:00:00', 100000, 'RUB', 3);
INSERT INTO public.purchase_price (text_document_id, date, price, currency, id) VALUES (130, '2021-01-01 00:00:00', 15000, 'RUB', 4);


--
-- Data for Name: text_document_to_author; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (1, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (2, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (3, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (4, 2);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (5, 2);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (6, 3);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (7, 3);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (8, 4);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (9, 4);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (10, 5);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (11, 6);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (12, 6);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (13, 6);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (20, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (22, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (24, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (26, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (28, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (30, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (32, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (34, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (36, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (38, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (40, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (42, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (44, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (46, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (48, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (50, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (52, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (54, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (56, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (58, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (60, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (62, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (64, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (66, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (68, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (70, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (72, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (74, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (76, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (78, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (80, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (82, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (84, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (86, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (88, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (90, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (92, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (94, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (96, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (98, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (100, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (102, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (104, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (106, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (108, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (110, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (112, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (114, 5);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (115, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (116, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (128, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (146, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (158, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (159, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (160, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (161, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (162, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (163, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (164, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (165, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (166, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (177, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (177, 2);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (177, 3);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (178, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (178, 2);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (178, 3);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (179, 1);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (179, 2);
INSERT INTO public.text_document_to_author (text_document_id, author_id) VALUES (179, 3);


--
-- Data for Name: text_documents; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (1, 'Бойцовский клуб', '1996-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (2, 'Снафф', '2008-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (3, 'Проклятые', '2011-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (4, 'Никто из ниоткуда. Сценарий, стихи, рассказы', '2006-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (5, 'Скованные одной цепью. Стихи', '1990-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (7, 'The Rules of Attraction', '1987-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (8, 'Крейцерова соната', '1891-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (9, 'Карма', '1894-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (10, 'Мечтают ли андроиды об электроовцах?', '1966-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (11, 'S.N.U.F.F.', '2011-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (12, 'iPhuck 10', '2017-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (13, 'Чапаев и Пустота', '1996-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (20, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (22, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (24, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (26, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (28, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (30, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (32, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (34, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (36, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (38, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (40, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (42, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (44, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (46, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (48, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (50, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (52, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (54, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (56, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (58, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (60, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (62, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (64, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (66, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (68, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (70, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (72, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (74, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (76, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (78, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (80, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (82, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (84, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (86, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (88, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (90, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (92, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (94, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (96, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (98, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (100, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (102, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (104, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (106, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (108, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (110, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (112, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (114, 'Привет ', '1999-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (14, 'Esquire', '2020-01-01', 'inStock', 'magazine', 1);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (15, 'Esquire', '2020-01-01', 'inStock', 'magazine', 2);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (16, 'GQ', '2020-01-01', 'inStock', 'magazine', 1);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (17, 'Логос', '2020-01-01', 'inStock', 'magazine', 1);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (18, 'National Geographic Magazine', '2020-01-01', 'inStock', 'magazine', 7);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (19, 'Rolling Stone', '2017-01-01', 'inStock', 'magazine', 4);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (21, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (23, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (25, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (27, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (29, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (31, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (33, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (35, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (37, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (39, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (41, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (43, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (45, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (47, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (49, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (51, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (53, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (55, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (57, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (59, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (61, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (63, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (65, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (67, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (69, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (71, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (73, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (75, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (77, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (79, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (81, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (83, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (85, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (87, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (89, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (91, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (93, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (95, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (97, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (99, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (101, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (103, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (105, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (107, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (109, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (111, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (113, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (115, 'Привет', '1999-01-01', 'inStock', 'magazine', 657567);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (116, 'Привет', '1999-01-01', 'inStock', 'magazine', 657567);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (128, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (129, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (6, 'Glamorama', '1998-01-01', 'archive', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (147, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (153, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (154, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (155, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (157, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (158, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (160, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (162, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (164, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (166, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (177, 'НЕсколько авторов', '2022-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (178, 'Текстовой документ', '2021-01-01', 'inStock', 'book', NULL);
INSERT INTO public.text_documents (id, title, year, status, type, number) VALUES (179, 'Новый журнал', '2021-01-01', 'inStock', 'magazine', 10);


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.users (id, login, password) VALUES (1, 'admin', '$2y$10$NT9RFJplr.bI8PKT4SZF7ulQmh.OxIkrusJSQ/2IA/lwqwWkAK6FK');


--
-- Name: authors_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.authors_id_seq', 11, true);


--
-- Name: purchase_price_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.purchase_price_id_seq', 4, true);


--
-- Name: text_documents_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.text_documents_id_seq', 179, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 2, true);


--
-- PostgreSQL database dump complete
--

