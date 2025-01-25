--
-- PostgreSQL database dump
--

-- Dumped from database version 17.2 (Ubuntu 17.2-1.pgdg24.04+1)
-- Dumped by pg_dump version 17.2 (Ubuntu 17.2-1.pgdg24.04+1)

-- Started on 2025-01-25 17:11:06 MSK

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
-- TOC entry 6 (class 2615 OID 16567)
-- Name: buses; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA buses;


ALTER SCHEMA buses OWNER TO postgres;

--
-- TOC entry 852 (class 1247 OID 16569)
-- Name: direction; Type: TYPE; Schema: buses; Owner: max
--

CREATE TYPE buses.direction AS ENUM (
    'forward',
    'backward'
);


ALTER TYPE buses.direction OWNER TO max;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 218 (class 1259 OID 16573)
-- Name: buses; Type: TABLE; Schema: buses; Owner: postgres
--

CREATE TABLE buses.buses (
    bus_id integer NOT NULL,
    bus_name character varying(255) NOT NULL
);


ALTER TABLE buses.buses OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 16576)
-- Name: buses_bus_id_seq; Type: SEQUENCE; Schema: buses; Owner: postgres
--

CREATE SEQUENCE buses.buses_bus_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE buses.buses_bus_id_seq OWNER TO postgres;

--
-- TOC entry 3439 (class 0 OID 0)
-- Dependencies: 219
-- Name: buses_bus_id_seq; Type: SEQUENCE OWNED BY; Schema: buses; Owner: postgres
--

ALTER SEQUENCE buses.buses_bus_id_seq OWNED BY buses.buses.bus_id;


--
-- TOC entry 220 (class 1259 OID 16577)
-- Name: routes; Type: TABLE; Schema: buses; Owner: postgres
--

CREATE TABLE buses.routes (
    route_id integer NOT NULL,
    bus integer NOT NULL,
    stop integer NOT NULL,
    arrival character varying(255)[],
    dir buses.direction NOT NULL,
    stop_num integer
);


ALTER TABLE buses.routes OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 16582)
-- Name: routes_route_id_seq; Type: SEQUENCE; Schema: buses; Owner: postgres
--

CREATE SEQUENCE buses.routes_route_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE buses.routes_route_id_seq OWNER TO postgres;

--
-- TOC entry 3440 (class 0 OID 0)
-- Dependencies: 221
-- Name: routes_route_id_seq; Type: SEQUENCE OWNED BY; Schema: buses; Owner: postgres
--

ALTER SEQUENCE buses.routes_route_id_seq OWNED BY buses.routes.route_id;


--
-- TOC entry 222 (class 1259 OID 16583)
-- Name: stops; Type: TABLE; Schema: buses; Owner: postgres
--

CREATE TABLE buses.stops (
    stop_id integer NOT NULL,
    stop_name character varying(255) NOT NULL
);


ALTER TABLE buses.stops OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 16586)
-- Name: stops_stop_id_seq; Type: SEQUENCE; Schema: buses; Owner: postgres
--

CREATE SEQUENCE buses.stops_stop_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE buses.stops_stop_id_seq OWNER TO postgres;

--
-- TOC entry 3441 (class 0 OID 0)
-- Dependencies: 223
-- Name: stops_stop_id_seq; Type: SEQUENCE OWNED BY; Schema: buses; Owner: postgres
--

ALTER SEQUENCE buses.stops_stop_id_seq OWNED BY buses.stops.stop_id;


--
-- TOC entry 3272 (class 2604 OID 16587)
-- Name: buses bus_id; Type: DEFAULT; Schema: buses; Owner: postgres
--

ALTER TABLE ONLY buses.buses ALTER COLUMN bus_id SET DEFAULT nextval('buses.buses_bus_id_seq'::regclass);


--
-- TOC entry 3273 (class 2604 OID 16588)
-- Name: routes route_id; Type: DEFAULT; Schema: buses; Owner: postgres
--

ALTER TABLE ONLY buses.routes ALTER COLUMN route_id SET DEFAULT nextval('buses.routes_route_id_seq'::regclass);


--
-- TOC entry 3274 (class 2604 OID 16589)
-- Name: stops stop_id; Type: DEFAULT; Schema: buses; Owner: postgres
--

ALTER TABLE ONLY buses.stops ALTER COLUMN stop_id SET DEFAULT nextval('buses.stops_stop_id_seq'::regclass);


--
-- TOC entry 3428 (class 0 OID 16573)
-- Dependencies: 218
-- Data for Name: buses; Type: TABLE DATA; Schema: buses; Owner: postgres
--

COPY buses.buses (bus_id, bus_name) FROM stdin;
1	Автобус №11
2	Автобус №12
3	Автобус №13
\.


--
-- TOC entry 3430 (class 0 OID 16577)
-- Dependencies: 220
-- Data for Name: routes; Type: TABLE DATA; Schema: buses; Owner: postgres
--

COPY buses.routes (route_id, bus, stop, arrival, dir, stop_num) FROM stdin;
6	1	3	{10:00,10:15,10:35,11:15,16:00,16:10,16:30,16:50}	backward	2
7	1	2	{10:05,10:20,10:40,11:20,16:00,16:10,16:30,16:50}	backward	3
8	1	1	{10:10,10:25,10:45,11:25,16:00,16:10,16:30,16:50}	backward	4
9	2	5	{09:30,09:45,10:00,10:45,16:00,16:10,16:30,16:50}	forward	1
10	2	6	{09:35,09:50,10:05,10:50,16:00,16:10,16:30,16:50}	forward	2
11	2	7	{09:40,09:55,10:15,10:55,16:00,16:10,16:30,16:50}	forward	3
13	2	8	{09:55,10:10,10:30,11:10,16:00,16:10,16:30,16:50}	backward	1
14	2	7	{10:00,10:15,10:35,11:15,16:00,16:10,16:30,16:50}	backward	2
15	2	4	{10:05,10:20,10:40,11:20,16:00,16:10,16:30,16:50}	backward	3
16	2	5	{10:10,10:25,10:45,11:25,16:00,16:10,16:30,16:50}	backward	4
17	3	1	{09:30,09:45,10:00,10:45,16:00,16:10,16:30,16:50}	forward	3
5	1	4	{09:55,10:10,10:30,11:10,16:00,16:10,16:30,16:50}	backward	1
18	3	2	{09:35,09:50,10:05,10:50,16:00,16:10,16:30,16:50}	forward	4
19	3	3	{09:40,09:55,10:15,10:55,16:00,16:10,16:30,16:50}	forward	1
20	3	4	{09:45,10:00,10:20,11:00,16:00,16:10,16:30,16:50}	forward	2
12	2	8	{09:45,10:00,10:20,11:00,16:00,16:10,16:30,16:50}	forward	4
1	1	1	{09:30,09:45,10:00,10:45,23:00,23:15,23:29,23:30}	forward	1
2	1	2	{09:35,09:50,10:05,10:50,16:00,16:10,16:30,16:50}	forward	2
3	1	3	{09:40,09:55,10:15,10:55,16:00,16:10,16:30,16:50}	forward	3
4	1	4	{09:45,10:00,10:20,11:00,16:00,16:10,16:30,16:50}	forward	4
\.


--
-- TOC entry 3432 (class 0 OID 16583)
-- Dependencies: 222
-- Data for Name: stops; Type: TABLE DATA; Schema: buses; Owner: postgres
--

COPY buses.stops (stop_id, stop_name) FROM stdin;
1	Попова
2	Ленина
3	Проверочная
4	Тестовая
5	Литейная
6	Кораблестроителей
7	Горная
8	Дорожная
9	Первая
\.


--
-- TOC entry 3442 (class 0 OID 0)
-- Dependencies: 219
-- Name: buses_bus_id_seq; Type: SEQUENCE SET; Schema: buses; Owner: postgres
--

SELECT pg_catalog.setval('buses.buses_bus_id_seq', 3, true);


--
-- TOC entry 3443 (class 0 OID 0)
-- Dependencies: 221
-- Name: routes_route_id_seq; Type: SEQUENCE SET; Schema: buses; Owner: postgres
--

SELECT pg_catalog.setval('buses.routes_route_id_seq', 38, true);


--
-- TOC entry 3444 (class 0 OID 0)
-- Dependencies: 223
-- Name: stops_stop_id_seq; Type: SEQUENCE SET; Schema: buses; Owner: postgres
--

SELECT pg_catalog.setval('buses.stops_stop_id_seq', 9, true);


--
-- TOC entry 3276 (class 2606 OID 16591)
-- Name: buses buses_pkey; Type: CONSTRAINT; Schema: buses; Owner: postgres
--

ALTER TABLE ONLY buses.buses
    ADD CONSTRAINT buses_pkey PRIMARY KEY (bus_id);


--
-- TOC entry 3278 (class 2606 OID 16593)
-- Name: routes routes_pkey; Type: CONSTRAINT; Schema: buses; Owner: postgres
--

ALTER TABLE ONLY buses.routes
    ADD CONSTRAINT routes_pkey PRIMARY KEY (route_id);


--
-- TOC entry 3280 (class 2606 OID 16595)
-- Name: stops stops_pkey; Type: CONSTRAINT; Schema: buses; Owner: postgres
--

ALTER TABLE ONLY buses.stops
    ADD CONSTRAINT stops_pkey PRIMARY KEY (stop_id);


--
-- TOC entry 3281 (class 2606 OID 16596)
-- Name: routes bus_fk; Type: FK CONSTRAINT; Schema: buses; Owner: postgres
--

ALTER TABLE ONLY buses.routes
    ADD CONSTRAINT bus_fk FOREIGN KEY (bus) REFERENCES buses.buses(bus_id);


--
-- TOC entry 3445 (class 0 OID 0)
-- Dependencies: 3281
-- Name: CONSTRAINT bus_fk ON routes; Type: COMMENT; Schema: buses; Owner: postgres
--

COMMENT ON CONSTRAINT bus_fk ON buses.routes IS 'Автобус';


--
-- TOC entry 3282 (class 2606 OID 16601)
-- Name: routes stop_fk; Type: FK CONSTRAINT; Schema: buses; Owner: postgres
--

ALTER TABLE ONLY buses.routes
    ADD CONSTRAINT stop_fk FOREIGN KEY (stop) REFERENCES buses.stops(stop_id);


--
-- TOC entry 3446 (class 0 OID 0)
-- Dependencies: 3282
-- Name: CONSTRAINT stop_fk ON routes; Type: COMMENT; Schema: buses; Owner: postgres
--

COMMENT ON CONSTRAINT stop_fk ON buses.routes IS 'Остановка';


-- Completed on 2025-01-25 17:11:06 MSK

--
-- PostgreSQL database dump complete
--

