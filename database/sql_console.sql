-- QUERY TO RUN TO SETUP THE LBAW2311 SCHEMA AND CHANGE TO THAT SCHEMA
CREATE SCHEMA IF NOT EXISTS lbaw2311;
SET search_path TO lbaw2311;
SHOW search_path;

--QUERY TO RUN TO SEE THE TABLES IN THE LBAW2311 SCHEMA
SELECT * FROM information_schema.tables WHERE table_schema = 'lbaw2311';
