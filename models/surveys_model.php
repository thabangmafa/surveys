<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * RMS Surveys Model
 *
 * @package     RMS
 * @description Contains all model functions for survey management
 * @category	Model
 * @author		Thabang Mafa
 *
 */
class Surveys_model extends CI_Model
{

	/**
	 *
	 * Function get_questiontypes()
	 *
	 * @description Retrieves the available question types from the database
	 * @access	    public
	 * @param	    none
	 * @return	    The result of the database query.
	 *
	 */
    function get_questiontypes()
    {
        $sql = "
            SELECT *
            FROM BMSMASTER.RMS_SURVEY_QUESTIONTYPE
            ORDER BY ID
        ";
        $q = $this->db->query($sql);
        return $q->result_array();
    }

    function get_questiontype($qtid)
    {
        $sql = "
            SELECT DESCRIPTION
            FROM BMSMASTER.RMS_SURVEY_QUESTIONTYPE
            WHERE ID = ?
        ";
        $q = $this->db->query($sql, array($qtid));
        $temp = $q->result_array();
        return $temp[0]['DESCRIPTION'];
    }

    function get_staffname($ppno)
    {
        $sql = "
			SELECT PERSONNEL_NO, surname || ', ' || name || ' (' || title || ' ' || initials || ')' AS NAME
			FROM MIC.PERSONS
            WHERE PERSONNEL_NO = ?
			ORDER BY surname || ', ' || name || ' (' || title || ' ' || initials || ')'
		";
		$q = $this->db->query($sql, array($ppno));
		return $q->result_array();
    }

    function get_open_surveys($ppno)
    {
        $sql = "
            SELECT *
            FROM BMSMASTER.RMS_SURVEY
            WHERE OPENDATE < SYSDATE
            AND CLOSEDATE >= SYSDATE
            AND PERSONNEL_NO = ?
            AND (ISDELETED = 'No' OR ISDELETED IS NULL)
        ";
        $q = $this->db->query($sql, array($ppno));
        return $q->result_array();
    }

    function get_survey($survey_id)
    {
        $sql = "
            SELECT *
            FROM BMSMASTER.RMS_SURVEY
            WHERE ID = ?
        ";
        $q = $this->db->query($sql, array($survey_id));
        return $q->result_array();
    }

    function get_section($survey_id, $section_id)
    {
        $sql = "
            SELECT *
            FROM BMSMASTER.RMS_SURVEY_SECTION
            WHERE ID = ? AND SURVEY_ID = ?
        ";
        $q = $this->db->query($sql, array($section_id, $survey_id));
        return $q->result_array();
    }

    function get_question($survey_id, $section_id, $question_id)
    {
        $sql = "
            SELECT *
            FROM BMSMASTER.RMS_SURVEY_QUESTION
            WHERE ID = ? AND SURVEY_ID = ? AND SURVEY_SECTION_ID = ?
        ";
        $q = $this->db->query($sql, array($question_id, $survey_id, $section_id));
        return $q->result_array();
    }

    function get_options($survey_id, $section_id, $question_id)
    {
        $sql = "
            SELECT *
            FROM BMSMASTER.RMS_SURVEY_OPTION
            WHERE SURVEY_QUESTION_ID = ? AND SURVEY_ID = ? AND SURVEY_SECTION_ID = ?
            ORDER BY ITEMORDER ASC
        ";
        $q = $this->db->query($sql, array($question_id, $survey_id, $section_id));
        return $q->result_array();
    }

    function get_closed_surveys($ppno)
    {
        $sql = "
            SELECT *
            FROM BMSMASTER.RMS_SURVEY
            WHERE (OPENDATE >= SYSDATE OR CLOSEDATE <= SYSDATE)
            AND PERSONNEL_NO = ?
            AND (ISDELETED = 'No' OR ISDELETED IS NULL)
        ";
        $q = $this->db->query($sql, array($ppno));
        return $q->result_array();
    }

    function get_deleted_surveys($ppno)
    {
        $sql = "
            SELECT *
            FROM BMSMASTER.RMS_SURVEY
            WHERE PERSONNEL_NO = ?
            AND ISDELETED = 'Yes'
        ";
        $q = $this->db->query($sql, array($ppno));
        return $q->result_array();
    }

    function get_survey_sections($survey_id)
    {
        $sql = "
            SELECT *
            FROM BMSMASTER.RMS_SURVEY_SECTION
            WHERE SURVEY_ID = ?
        ";
        $q = $this->db->query($sql, array($survey_id));
        return $q->result_array();
    }

    function get_survey_questions($survey_id, $section_id)
    {
        $sql = "
            SELECT *
            FROM BMSMASTER.RMS_SURVEY_QUESTION
            WHERE SURVEY_ID = ? AND SURVEY_SECTION_ID = ?
        ";
        $q = $this->db->query($sql, array($survey_id, $section_id));
        return $q->result_array();
    }

    function close_survey($survey_id)
    {
        $sql = "
            UPDATE BMSMASTER.RMS_SURVEY
            SET CLOSEDATE = ?
            WHERE ID = ?
        ";
        $newclose = date('j/M/y');
        $q = $this->db->query($sql, array($newclose, $survey_id));
    }

    function reopen_survey($survey_id)
    {
        $sql = "
            UPDATE BMSMASTER.RMS_SURVEY
            SET OPENDATE = ?, CLOSEDATE = ?
            WHERE ID = ?
        ";
        $newopen = date('j/M/y');
        $newclose = date('j/M/y', strtotime('+1 week'));
        $q = $this->db->query($sql, array($newopen, $newclose, $survey_id));
    }

    function update_survey($data)
    {
        $sql = "
            UPDATE BMSMASTER.RMS_SURVEY
            SET TITLE = ?, DESCRIPTION = ?, RESTRICTED = ?, OPENDATE = ?, CLOSEDATE = ?, ALLOWRETAKE = ?, ISDELETED = ?
            WHERE ID = ?
        ";
        $sql_array = array($data['TITLE'], $data['DESCRIPTION'], $data['RESTRICTED'], $data['OPENDATE'], $data['CLOSEDATE'], $data['ALLOWRETAKE'], $data['ISDELETED'], $data['ID']);
        $q = $this->db->query($sql, $sql_array);
    }

    function insert_survey($data)
    {
        $sql = "
            INSERT INTO BMSMASTER.RMS_SURVEY
            (ID, TITLE, DESCRIPTION, RESTRICTED, OPENDATE, CLOSEDATE, PERSONNEL_NO, ALLOWRETAKE, ISDELETED)
            VALUES (BMSMASTER.SEQ_RMS_SURVEY.NEXTVAL, ?, ?, ?, ?, ?, ?, ?, ?)
        ";
        $sql_array = array($data['TITLE'], $data['DESCRIPTION'], $data['RESTRICTED'], $data['OPENDATE'], $data['CLOSEDATE'], $data['PERSONNEL_NO'], $data['ALLOWRETAKE'], $data['ISDELETED']);
        $q = $this->db->query($sql, $sql_array);
    }

    function insert_section($data)
    {
        $sql = "
            INSERT INTO BMSMASTER.RMS_SURVEY_SECTION
            (ID, SURVEY_ID, TITLE, DESCRIPTION)
            VALUES (BMSMASTER.SEQ_RMS_SURVEY_SECTION.NEXTVAL, ?, ?, ?)
        ";
        $sql_array = array($data['SURVEY_ID'], $data['TITLE'], $data['DESCRIPTION']);
        $q = $this->db->query($sql, $sql_array);
    }

    function update_section($data)
    {
        $sql = "
            UPDATE BMSMASTER.RMS_SURVEY_SECTION
            SET TITLE = ?, SURVEY_ID = ?, DESCRIPTION = ?
            WHERE ID = ?
        ";
        $sql_array = array($data['TITLE'], $data['SURVEY_ID'], $data['DESCRIPTION'], $data['ID']);
        $q = $this->db->query($sql, $sql_array);
    }

    function insert_question($data)
    {
        $sql = "
            INSERT INTO BMSMASTER.RMS_SURVEY_QUESTION
            (ID, SURVEY_ID, SURVEY_SECTION_ID, QUESTION, OPTIONCOUNT, QUESTIONTYPE_ID, ISREQUIRED)
            VALUES (BMSMASTER.SEQ_RMS_SURVEY_QUESTION.NEXTVAL, ?, ?, ?, ?, ?, ?)
        ";
        $sql_array = array($data['SURVEY_ID'], $data['SECTION_ID'], $data['QUESTION'], $data['OPTIONCOUNT'], $data['QUESTIONTYPE_ID'], $data['ISREQUIRED']);
        $q = $this->db->query($sql, $sql_array);
    }

    function update_question($data)
    {
        $sql = "
            UPDATE BMSMASTER.RMS_SURVEY_QUESTION
            SET QUESTION = ?, OPTIONCOUNT = ?, SURVEY_SECTION_ID = ?, SURVEY_ID = ?, QUESTIONTYPE_ID = ?, ISREQUIRED = ?
            WHERE ID = ?
        ";
        $sql_array = array($data['QUESTION'], $data['OPTIONCOUNT'], $data['SECTION_ID'], $data['SURVEY_ID'], $data['QUESTIONTYPE_ID'], $data['ISREQUIRED'], $data['ID']);
        $q = $this->db->query($sql, $sql_array);
    }

    function insert_option($data)
    {
        $sql = "
            INSERT INTO BMSMASTER.RMS_SURVEY_OPTION
            (ID, SURVEY_ID, SURVEY_SECTION_ID, SURVEY_QUESTION_ID, DESCRIPTION, ITEMORDER)
            VALUES
            (BMSMASTER.SEQ_RMS_SURVEY_OPTION.NEXTVAL, ?, ?, ?, ?, ?)
        ";
        $sql_array = array($data['SURVEY_ID'], $data['SURVEY_SECTION_ID'], $data['SURVEY_QUESTION_ID'], $data['DESCRIPTION'], $data['ITEMORDER']);
        $q = $this->db->query($sql, $sql_array);
    }

    function get_option_entry_by_ip_address($ip, $sid, $ssid, $qid, $oid)
    {
        $sql = "
            SELECT ID
            FROM BMSMASTER.RMS_SURVEY_RESPONSE
            WHERE IP_ADDRESS = ? AND SURVEY_ID = ? AND SURVEY_SECTION_ID = ? AND SURVEY_QUESTION_ID = ? AND SURVEY_OPTION_ID = ?
        ";
        $sql_array = array($ip, $sid, $ssid, $qid, $oid);
        $q =  $this->db->query($sql, $sql_array);
        $temp = $q->result_array();
        if (isset($temp[0]['ID']) && trim($temp[0]['ID']) !== '')
        {
            return $temp[0]['ID'];
        }
        return '';
    }

    function get_option_entry_by_personnel_no($pn, $sid, $ssid, $qid, $oid)
    {
        $sql = "
            SELECT ID
            FROM BMSMASTER.RMS_SURVEY_RESPONSE
            WHERE PARTICIPANT = ? AND SURVEY_ID = ? AND SURVEY_SECTION_ID = ? AND SURVEY_QUESTION_ID = ? AND SURVEY_OPTION_ID = ?
        ";
        $sql_array = array($pn, $sid, $ssid, $qid, $oid);
        $q =  $this->db->query($sql, $sql_array);
        $temp = $q->result_array();
        if (isset($temp[0]['ID']) && trim($temp[0]['ID']) !== '')
        {
            return $temp[0]['ID'];
        }
        return '';
    }

    function update_participation($data)
    {
        $sql = "
            UPDATE
            BMSMASTER.RMS_SURVEY_RESPONSE
            SET
                SURVEY_ID = ?, SURVEY_SECTION_ID = ?, SURVEY_QUESTION_ID = ?, SURVEY_OPTION_ID = ?, MOTIVATION = ?,
                PARTICIPANT = ?, RESPONSE_DATE = ?, IP_ADDRESS = ?, TEXTCOMPONENT = ?
            WHERE ID = ?
        ";
        $sql_array = array(
            $data['survey_id'], $data['section_id'], $data['question_id'], $data['option_id'], $data['motivation'],
            $data['participant'], date('Y-m-d H:i:s'), $data['ipaddress'], $data['textcomponent'], $data['entry_id']
        );
        $q = $this->db->query($sql, $sql_array);
    }

    function insert_participation($data)
    {
        $sql = "
            INSERT INTO
            BMSMASTER.RMS_SURVEY_RESPONSE
            (
                ID, SURVEY_ID, SURVEY_SECTION_ID, SURVEY_QUESTION_ID, SURVEY_OPTION_ID,
                MOTIVATION, PARTICIPANT, RESPONSE_DATE, IP_ADDRESS, TEXTCOMPONENT
            )
            VALUES (
                BMSMASTER.SEQ_RMS_SURVEY_RESPONSE.NEXTVAL, ?, ?, ?, ?,
                ?, ?, ?, ?, ?
            )
        ";
        $sql_array = array(
            $data['survey_id'], $data['section_id'], $data['question_id'], $data['option_id'],
            $data['motivation'], $data['participant'], date('Y-m-d H:i:s'), $data['ipaddress'], $data['textcomponent']
        );
        $q = $this->db->query($sql, $sql_array);
    }

    function delete_surveys_by_survey($sid)
    {
        $sql = "
            DELETE FROM
            BMSMASTER.RMS_SURVEY
            WHERE ID = ?
        ";
        $q = $this->db->query($sql, array($sid));
    }

    function delete_sections_by_survey($sid)
    {
        $sql = "
            DELETE FROM
            BMSMASTER.RMS_SURVEY_SECTION
            WHERE SURVEY_ID = ?
        ";
        $q = $this->db->query($sql, array($sid));
    }

    function delete_questions_by_survey($sid)
    {
        $sql = "
            DELETE FROM
            BMSMASTER.RMS_SURVEY_QUESTION
            WHERE SURVEY_ID = ?
        ";
        $q = $this->db->query($sql, array($sid));
    }

    function delete_options_by_survey($sid)
    {
        $sql = "
            DELETE FROM
            BMSMASTER.RMS_SURVEY_OPTION
            WHERE SURVEY_ID = ?
        ";
        $q = $this->db->query($sql, array($sid));
    }

    function delete_responses_by_survey($sid)
    {
        $sql = "
            DELETE FROM
            BMSMASTER.RMS_SURVEY_RESPONSE
            WHERE SURVEY_ID = ?
        ";
        $q = $this->db->query($sql, array($sid));
    }

    function delete_sections_by_survey_and_section($sid, $ssid)
    {
        $sql = "
            DELETE FROM
            BMSMASTER.RMS_SURVEY_SECTION
            WHERE SURVEY_ID = ? AND ID = ?
        ";
        $q = $this->db->query($sql, array($sid, $ssid));
    }

    function delete_questions_by_survey_and_section($sid, $ssid)
    {
        $sql = "
            DELETE FROM
            BMSMASTER.RMS_SURVEY_QUESTION
            WHERE SURVEY_ID = ? AND SURVEY_SECTION_ID = ?
        ";
        $q = $this->db->query($sql, array($sid, $ssid));
    }

    function delete_options_by_survey_and_section($sid, $ssid)
    {
        $sql = "
            DELETE FROM
            BMSMASTER.RMS_SURVEY_OPTION
            WHERE SURVEY_ID = ? AND SURVEY_SECTION_ID = ?
        ";
        $q = $this->db->query($sql, array($sid, $ssid));
    }

    function delete_responses_by_survey_and_section($sid, $ssid)
    {
        $sql = "
            DELETE FROM
            BMSMASTER.RMS_SURVEY_RESPONSE
            WHERE SURVEY_ID = ? AND SURVEY_SECTION_ID = ?
        ";
        $q = $this->db->query($sql, array($sid, $ssid));
    }

    function delete_questions_by_survey_and_section_and_question($sid, $ssid, $qid)
    {
        $sql = "
            DELETE FROM
            BMSMASTER.RMS_SURVEY_QUESTION
            WHERE SURVEY_ID = ? AND SURVEY_SECTION_ID = ? AND ID = ?
        ";
        $q = $this->db->query($sql, array($sid, $ssid, $qid));
    }

    function delete_options_by_survey_and_section_and_question($sid, $ssid, $qid)
    {
        $sql = "
            DELETE FROM
            BMSMASTER.RMS_SURVEY_OPTION
            WHERE SURVEY_ID = ? AND SURVEY_SECTION_ID = ? AND SURVEY_QUESTION_ID = ?
        ";
        $q = $this->db->query($sql, array($sid, $ssid, $qid));
    }

    function delete_responses_by_survey_and_section_and_question($sid, $ssid, $qid)
    {
        $sql = "
            DELETE FROM
            BMSMASTER.RMS_SURVEY_RESPONSE
            WHERE SURVEY_ID = ? AND SURVEY_SECTION_ID = ? AND SURVEY_QUESTION_ID = ?
        ";
        $q = $this->db->query($sql, array($sid, $ssid, $qid));
    }

    function recycle_survey($sid)
    {
        $sql = "
            UPDATE BMSMASTER.RMS_SURVEY
            SET ISDELETED = 'Yes'
            WHERE ID = ?
        ";
        $q = $this->db->query($sql, array($sid));
    }

    function restore_survey($sid)
    {
        $sql = "
            UPDATE BMSMASTER.RMS_SURVEY
            SET ISDELETED = 'No'
            WHERE ID = ?
        ";
        $q = $this->db->query($sql, array($sid));
    }

    function clone_add_survey($data)
    {
        $sql = "SELECT BMSMASTER.SEQ_RMS_SURVEY.NEXTVAL AS NEXTID1 FROM DUAL";
        $q = $this->db->query($sql);
        $temp = $q->result_array();
        $sql2 = "
            INSERT INTO BMSMASTER.RMS_SURVEY
            (ID, TITLE, DESCRIPTION, OPENDATE, CLOSEDATE, RESTRICTED, ALLOWRETAKE, ISDELETED, PERSONNEL_NO)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";
        $sql_array = array(
            $temp[0]['NEXTID1'], $data['TITLE'], $data['DESCRIPTION'], $data['OPENDATE'], $data['CLOSEDATE'],
            $data['RESTRICTED'], $data['ALLOWRETAKE'], $data['ISDELETED'], $data['PERSONNEL_NO']
        );
        $q2 = $this->db->query($sql2, $sql_array);
        return $temp[0]['NEXTID1'];
    }

    function clone_add_section($data)
    {
        $sql = "SELECT BMSMASTER.SEQ_RMS_SURVEY_SECTION.NEXTVAL AS NEXTID2 FROM DUAL";
        $q = $this->db->query($sql);
        $temp = $q->result_array();
        $sql2 = "
            INSERT INTO BMSMASTER.RMS_SURVEY_SECTION
            (ID, SURVEY_ID, TITLE, DESCRIPTION)
            VALUES (?, ?, ?, ?)
        ";
        $sql_array = array($temp[0]['NEXTID2'], $data['SURVEY_ID'], $data['TITLE'], $data['DESCRIPTION']);
        $q2 = $this->db->query($sql2, $sql_array);
        return $temp[0]['NEXTID2'];
    }

    function clone_add_question($data)
    {
        $sql = "SELECT BMSMASTER.SEQ_RMS_SURVEY_QUESTION.NEXTVAL AS NEXTID3 FROM DUAL";
        $q = $this->db->query($sql);
        $temp = $q->result_array();
        $sql2 = "
            INSERT INTO BMSMASTER.RMS_SURVEY_QUESTION
            (ID, SURVEY_ID, SURVEY_SECTION_ID, QUESTION, OPTIONCOUNT, QUESTIONTYPE_ID, ISREQUIRED)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";
        $sql_array = array(
            $temp[0]['NEXTID3'], $data['SURVEY_ID'], $data['SURVEY_SECTION_ID'], $data['QUESTION'],
            $data['OPTIONCOUNT'], $data['QUESTIONTYPE_ID'], $data['ISREQUIRED']
        );
        $q2 = $this->db->query($sql2, $sql_array);
        return $temp[0]['NEXTID3'];
    }

    function clone_add_option($data)
    {
        $sql = "SELECT BMSMASTER.SEQ_RMS_SURVEY_OPTION.NEXTVAL AS NEXTID4 FROM DUAL";
        $q = $this->db->query($sql);
        $temp = $q->result_array();
        $sql2 = "
            INSERT INTO BMSMASTER.RMS_SURVEY_OPTION
            (ID, SURVEY_ID, SURVEY_SECTION_ID, SURVEY_QUESTION_ID, DESCRIPTION, ITEMORDER, ISCORRECT)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";
        $sql_array = array(
            $temp[0]['NEXTID4'], $data['SURVEY_ID'], $data['SURVEY_SECTION_ID'], $data['SURVEY_QUESTION_ID'],
            $data['DESCRIPTION'], $data['ITEMORDER'], $data['ISCORRECT']
        );
        $q2 = $this->db->query($sql2, $sql_array);
        return $temp[0]['NEXTID4'];
    }

}
