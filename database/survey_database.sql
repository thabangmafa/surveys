/*
 *
 * @package     Oracle 11g
 * @description Surveys Management Module database tables
 * @category	Database Tables
 * @author		Thabang Mafa
 *
 */


  /**** @table RMS_SURVEY *****/
  
  CREATE TABLE "RMS_SURVEY" 
   (	"ID" NUMBER NOT NULL ENABLE, 
	"TITLE" VARCHAR2(100 BYTE) NOT NULL ENABLE, 
	"DESCRIPTION" VARCHAR2(4000 BYTE) NOT NULL ENABLE, 
	"RESTRICTED" VARCHAR2(20 BYTE) NOT NULL ENABLE, 
	"OPENDATE" DATE NOT NULL ENABLE, 
	"CLOSEDATE" DATE, 
	"PERSONNEL_NO" VARCHAR2(20 BYTE) NOT NULL ENABLE, 
	"ALLOWRETAKE" VARCHAR2(4 CHAR), 
	"ISDELETED" VARCHAR2(4 CHAR), 
	 CONSTRAINT "RMS_SURVEY_PK" PRIMARY KEY ("ID")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "PROJECT"  ENABLE
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "PROJECT" ;
  
  /**** @table RMS_SURVEY_OPTION *****/
  
    CREATE TABLE "RMS_SURVEY_OPTION" 
   (	"ID" NUMBER NOT NULL ENABLE, 
	"SURVEY_ID" NUMBER NOT NULL ENABLE, 
	"SURVEY_SECTION_ID" NUMBER NOT NULL ENABLE, 
	"SURVEY_QUESTION_ID" NUMBER NOT NULL ENABLE, 
	"DESCRIPTION" VARCHAR2(255 CHAR) NOT NULL ENABLE, 
	"ITEMORDER" NUMBER NOT NULL ENABLE, 
	"ISCORRECT" VARCHAR2(4 CHAR), 
	 CONSTRAINT "RMS_SURVEY_OPTION_PK" PRIMARY KEY ("ID")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "PROJECT"  ENABLE
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "PROJECT" ;
  
  /**** @table RMS_SURVEY_QUESTION *****/
  
  CREATE TABLE "RMS_SURVEY_QUESTION" 
   (	"ID" NUMBER NOT NULL ENABLE, 
	"SURVEY_ID" NUMBER NOT NULL ENABLE, 
	"SURVEY_SECTION_ID" NUMBER NOT NULL ENABLE, 
	"QUESTION" VARCHAR2(4000 BYTE) NOT NULL ENABLE, 
	"OPTIONCOUNT" NUMBER NOT NULL ENABLE, 
	"QUESTIONTYPE_ID" NUMBER NOT NULL ENABLE, 
	"ISREQUIRED" VARCHAR2(4 CHAR), 
	 CONSTRAINT "RMS_SURVEY_QUESTION_PK" PRIMARY KEY ("ID")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "PROJECT"  ENABLE
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "PROJECT" ;
  
  /**** @table RMS_SURVEY_QUESTIONTYPE *****/
  
  CREATE TABLE "RMS_SURVEY_QUESTIONTYPE" 
   (	"ID" NUMBER NOT NULL ENABLE, 
	"DESCRIPTION" VARCHAR2(100 BYTE) NOT NULL ENABLE, 
	 CONSTRAINT "RMS_SURVEY_QUESTIONTYPES_PK" PRIMARY KEY ("ID")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "PROJECT"  ENABLE
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "PROJECT" ;
  
  /**** @table RMS_SURVEY_RESPONSE *****/
  
  CREATE TABLE "RMS_SURVEY_RESPONSE" 
   (	"ID" NUMBER NOT NULL ENABLE, 
	"SURVEY_ID" NUMBER NOT NULL ENABLE, 
	"SURVEY_SECTION_ID" NUMBER NOT NULL ENABLE, 
	"SURVEY_QUESTION_ID" NUMBER NOT NULL ENABLE, 
	"SURVEY_OPTION_ID" NUMBER NOT NULL ENABLE, 
	"MOTIVATION" VARCHAR2(4000 CHAR), 
	"PARTICIPANT" VARCHAR2(25 CHAR) NOT NULL ENABLE, 
	"RESPONSE_DATE" VARCHAR2(20 BYTE) NOT NULL ENABLE, 
	"IP_ADDRESS" VARCHAR2(32 BYTE) NOT NULL ENABLE, 
	"TEXTCOMPONENT" VARCHAR2(4000 CHAR)
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "PROJECT" ;
  
  /**** @table RMS_SURVEY_SECTION *****/
  
  CREATE TABLE "RMS_SURVEY_SECTION" 
   (	"ID" NUMBER NOT NULL ENABLE, 
	"SURVEY_ID" NUMBER NOT NULL ENABLE, 
	"TITLE" VARCHAR2(100 BYTE), 
	"DESCRIPTION" VARCHAR2(4000 BYTE), 
	 CONSTRAINT "RMS_SURVEY_SECTION_PK" PRIMARY KEY ("ID")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "PROJECT"  ENABLE
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "PROJECT" ;
  
  /*** END ***/