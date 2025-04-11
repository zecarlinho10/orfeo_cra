
---
 CREATE TABLE "radicado_from_mail"
 (
        "radi_nume_radi" NUMERIC(14,0),
  	"mail_number" NUMERIC,
  	"mail_id" NUMERIC(20,0),
	"log_date" TIMESTAMP,
	"destinatario" VARCHAR(50)
 );



CREATE TABLE "radicado_mail"
 (
    "mail_id" NUMERIC(20,0),
  	"mail" VARCHAR(50),
  	"mail_pass" VARCHAR(20),
  	"mail_subject_filter" VARCHAR(100),
  	"mail_dest_alt" VARCHAR(100),
	"mail_host" VARCHAR(80)
 );

CREATE TABLE "mail_destinatarios"(
     "fk_usua_login" varchar("32"),
     "fk_mail_id" INTEGER

);
