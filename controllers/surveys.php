<?php

/**
 *
 * RMS Surveys Controller
 *
 * @package     RMS
 * @description Contains all controller functions for survey management
 * @category	Controller
 * @author		Thabang Mafa
 *
 */
class Surveys extends CI_Controller
{

	/**
	 *
	 * Function __construct()
	 *
	 * @description Initializes the controller
	 * @access	    public
	 * @param	    none
	 * @return	    nothing
	 *
	 */
	function __construct()
	{
		parent::__construct();
        $this->load->model('surveys_model');
	}

	/**
	 *
	 * Function index()
	 *
	 * @description Lists the surveys that can be managed
	 * @access	    public
	 * @param	    none
	 * @return	    nothing
	 *
	 */
	function index()
	{
	    $personnel_no = $this->session->userdata('personnelno');
        $this->manage_surveys($personnel_no);
	}

	/**
	 *
	 * Function manage_surveys()
	 *
	 * @description Lists the surveys that can be managed
	 * @access	    public
	 * @param	    none
	 * @return	    nothing
	 *
	 */
	function manage_surveys($personnel_no)
	{
	    $data['open_surveys'] = $this->surveys_model->get_open_surveys($personnel_no);
	    $data['closed_surveys'] = $this->surveys_model->get_closed_surveys($personnel_no);
	    $data['deleted_surveys'] = $this->surveys_model->get_deleted_surveys($personnel_no);
	    $data['staffname']= $this->surveys_model->get_staffname($personnel_no);
	    $this->template->load('template', 'surveys/list_surveys', $data);
	}

	/**
	 *
	 * Function participate_surveys()
	 *
	 * @description Lists the surveys that can be participated in
	 * @access	    public
	 * @param	    none
	 * @return	    nothing
	 *
	 */
	function participate_surveys()
	{
	    $personnel_no = $this->session->userdata('personnelno');
	    $data['open_surveys'] = $this->surveys_model->get_open_surveys($personnel_no);
	    $data['staffname']= $this->surveys_model->get_staffname($personnel_no);
	    $this->template->load('template', 'surveys/participate_surveys', $data);
	}

	/**
	 *
	 * Function update_survey_participation()
	 *
	 * @description Updates the participation in a survey; adds new records if not participated yet.
	 * @access	    public
	 * @param	    none
	 * @return	    nothing
	 *
	 */
	function update_survey_participation()
	{
        foreach ($_POST as $k => $v)
        {
            $data[$k] = $this->input->post($k);
        }
        unset($data['submitdetails']);
        $temp = $data['participant'];
        unset($data['participant']);
        $counter = 0;
        foreach ($data as $key => $value)
        {
            $key_ids = explode('-', $key);
            $savedata[$counter]['survey_id'] = $key_ids[0];
            $savedata[$counter]['section_id'] = $key_ids[1];
            $savedata[$counter]['question_id'] = $key_ids[2];
            if (strpos($key, 'motivation') === false)
            {
                if (isset($key_ids[3]))
                {
                    $savedata[$counter]['option_id'] = $key_ids[3];
                }
                else
                {
                    $savedata[$counter]['option_id'] = $value;
                }
                $savedata[$counter]['motivation'] = '';
                if (strpos($key, 'textcomponent') !== false)
                {
                    $savedata[$counter]['textcomponent'] = $value;
                }
                else
                {
                    $savedata[$counter]['textcomponent'] = '';
                }
            }
            else
            {
                $savedata[$counter]['option_id'] = 1;
                $savedata[$counter]['motivation'] = $value;
                $savedata[$counter]['textcomponent'] = '';
            }
            $savedata[$counter]['participant'] = $temp;
            $savedata[$counter]['ipaddress'] = $_SERVER['REMOTE_ADDR'];
            $counter++;
        }
        foreach ($savedata as $key => $val)
        {
            if ($val['participant'] == 'Anonymous participant')
            {
                $has_entered = $this->surveys_model->get_option_entry_by_ip_address($val['participant'], $val['survey_id'], $val['section_id'], $val['question_id'], $val['option_id']);
            }
            else
            {
                $has_entered = $this->surveys_model->get_option_entry_by_personnel_no($val['participant'], $val['survey_id'], $val['section_id'], $val['question_id'], $val['option_id']);
            }
            if (trim($has_entered) !== '')
            {
                $savedata[$key]['entry_id'] = $has_entered;
                $this->surveys_model->update_participation($savedata[$key]);
            }
            else
            {
                $this->surveys_model->insert_participation($savedata[$key]);
            }
        }
        $this->template->load('template', 'surveys/participate_thanks');
	}

	/**
	 *
	 * Function participate_survey()
	 *
	 * @description Initializes survey participation.
	 * @access	    public
	 * @param	    $survey_id (int)
	 * @return	    nothing
	 *
	 */
	function participate_survey($survey_id)
	{
	    $data['survey'] = $this->surveys_model->get_survey($survey_id);
	    if (isset($data['survey']) && is_array($data['survey']) && count($data['survey']) > 0)
	    {
	        $data['staffname'] = $this->surveys_model->get_staffname($data['survey'][0]['PERSONNEL_NO']);
    	}
	    foreach ($data['survey'] as $survey_key => $survey_value)
	    {
	        $data['sections'] = $this->surveys_model->get_survey_sections($survey_id);
	        $data['final']['title'] = $survey_value['TITLE'];
	        $data['final']['intro'] = $survey_value['DESCRIPTION'];
	        $data['final']['opendate'] = $survey_value['OPENDATE'];
	        $data['final']['closedate'] = $survey_value['CLOSEDATE'];
	        $data['final']['restricted'] = $survey_value['RESTRICTED'];
	        $data['final']['creator'] = $data['staffname'][0]['NAME'];
	        $temp = $this->session->userdata('personnelno');
	        if (trim($temp) == "")
	        {
    	        $data['final']['participant'] = 'Anonymous participant';
	        }
	        else
	        {
    	        $data['final']['participant'] = $temp;
	        }
	        $data['final']['id'] = $survey_value['ID'];
	        $counter_section = 0;
	        foreach ($data['sections'] as $section_key => $section_value)
	        {
	            $data['questions'] = $this->surveys_model->get_survey_questions($survey_id, $section_value['ID']);
	            $data['final']['sections'][$counter_section]['id'] = $section_value['ID'];
	            $data['final']['sections'][$counter_section]['title'] = $section_value['TITLE'];
	            $data['final']['sections'][$counter_section]['description'] = $section_value['DESCRIPTION'];
	            $counter_question = 0;
	            foreach ($data['questions'] as $question_key => $question_value)
	            {
            	    $data['options'] = $this->surveys_model->get_options($survey_id, $section_value['ID'], $question_value['ID']);
            	    $data['final']['sections'][$counter_section]['questions'][$counter_question]['id'] = $question_value['ID'];
	                $data['final']['sections'][$counter_section]['questions'][$counter_question]['question'] = $question_value['QUESTION'];
	                $data['final']['sections'][$counter_section]['questions'][$counter_question]['type'] = $question_value['QUESTIONTYPE_ID'];
                    $data['final']['sections'][$counter_section]['questions'][$counter_question]['required'] = $question_value['ISREQUIRED'];
                    $counter_option = 0;
	                foreach ($data['options'] as $option_key => $option_value)
	                {
	                    $data['final']['sections'][$counter_section]['questions'][$counter_question]['options'][$counter_option]['id'] = $option_value['ID'];
    	                $data['final']['sections'][$counter_section]['questions'][$counter_question]['options'][$counter_option]['option'] = $option_value['DESCRIPTION'];
    	                $counter_option++;
	                }
	                $counter_question++;
	            }
	            $counter_section++;
	        }
	    }
	    $this->template->load('template', 'surveys/participate_survey', $data);
	}

	/**
	 *
	 * Function manage_sections()
	 *
	 * @description Initializes survey sections for management.
	 * @access	    public
	 * @param	    $survey_id (int)
	 * @return	    nothing
	 *
	 */
	function manage_sections($survey_id)
	{
	    $data['sections'] = $this->surveys_model->get_survey_sections($survey_id);
	    $data['survey'] = $this->surveys_model->get_survey($survey_id);
	    $data['survey_id'] = $survey_id;
	    $this->template->load('template', 'surveys/list_sections', $data);
	}

	/**
	 *
	 * Function manage_questions()
	 *
	 * @description Initializes survey questions per section for management.
	 * @access	    public
	 * @param	    $survey_id (int), $section_id (int)
	 * @return	    nothing
	 *
	 */
	function manage_questions($survey_id, $section_id)
	{
	    $data['questions'] = $this->surveys_model->get_survey_questions($survey_id, $section_id);
        $data['survey'] = $this->surveys_model->get_survey($survey_id);
        $data['section'] = $this->surveys_model->get_section($survey_id, $section_id);
	    $data['survey_id'] = $survey_id;
	    $data['section_id'] = $section_id;
	    $this->template->load('template', 'surveys/list_questions', $data);
	}

	/**
	 *
	 * Function manage_options()
	 *
	 * @description Initializes question options for management.
	 * @access	    public
	 * @param	    $survey_id (int), $section_id (int), $question_id (int)
	 * @return	    nothing
	 *
	 */
	function manage_options($survey_id, $section_id, $question_id)
	{
	    $data['options'] = $this->surveys_model->get_options($survey_id, $section_id, $question_id);
	    $this->template->load('template', 'surveys/list_options', $data);
	}

	/**
	 *
	 * Function close_survey()
	 *
	 * @description Closes a survey to prevent further participation
	 * @access	    public
	 * @param	    $survey_id (int)
	 * @return	    nothing
	 *
	 */
	function close_survey($survey_id)
	{
	    $this->surveys_model->close_survey($survey_id);
	    redirect('surveys/index');
	}

	/**
	 *
	 * Function reopen_survey()
	 *
	 * @description Re-opens a closed survey for further participation
	 * @access	    public
	 * @param	    $survey_id (int)
	 * @return	    nothing
	 *
	 */
	function reopen_survey($survey_id)
	{
	    $this->surveys_model->reopen_survey($survey_id);
	    redirect('surveys/index');
	}

	/**
	 *
	 * Function add_survey()
	 *
	 * @description Allows addition of a new survey - survey-level
	 * @access	    public
	 * @param	    none
	 * @return	    nothing
	 *
	 */
    function add_survey()
    {
	    $this->template->load('template', 'surveys/add_survey');
    }

	/**
	 *
	 * Function edit_survey()
	 *
	 * @description Allows editing of survey
	 * @access	    public
	 * @param	    $survey_id (int)
	 * @return	    nothing
	 *
	 */
	function edit_survey($survey_id)
	{
	    $data['survey'] = $this->surveys_model->get_survey($survey_id);
	    $this->template->load('template', 'surveys/edit_survey', $data);
	}

	/**
	 *
	 * Function insert_survey()
	 *
	 * @description Inserts a new survey into the database.
	 * @access	    public
	 * @param	    none
	 * @return	    nothing
	 *
	 */
	function insert_survey()
	{
        foreach ($_POST as $k => $v)
        {
            $data[$k] = $this->input->post($k);
        }
        unset($data['submitdetails']);
        $personnel_no = $this->session->userdata('personnelno');
        $data['PERSONNEL_NO'] = $personnel_no;
        $this->surveys_model->insert_survey($data);
        redirect('surveys/manage_surveys/' . $personnel_no);
	}

	/**
	 *
	 * Function update_survey()
	 *
	 * @description Updates a survey that was edited.
	 * @access	    public
	 * @param	    none
	 * @return	    nothing
	 *
	 */
	function update_survey()
	{
        foreach ($_POST as $k => $v)
        {
            $data[$k] = $this->input->post($k);
        }
        unset($data['submitdetails']);
        $this->surveys_model->update_survey($data);
        $personnel_no = $this->session->userdata('personnelno');
        redirect('surveys/manage_surveys/' . $personnel_no);
	}

	/**
	 *
	 * Function delete_survey()
	 *
	 * @description Deletes an entire survey. Filters through to sections, questions and options to prevent orphans.
	 * @access	    public
	 * @param	    $survey_id (int)
	 * @return	    nothing
	 *
	 */
	function delete_survey($survey_id)
	{
	    $this->surveys_model->delete_responses_by_survey($survey_id);
	    $this->surveys_model->delete_options_by_survey($survey_id);
	    $this->surveys_model->delete_questions_by_survey($survey_id);
	    $this->surveys_model->delete_sections_by_survey($survey_id);
	    $this->surveys_model->delete_surveys_by_survey($survey_id);
	    redirect('surveys/index');
	}

	/**
	 *
	 * Function restore_survey()
	 *
	 * @description Recovers a previously recycled survey.
	 * @access	    public
	 * @param	    $survey_id (int)
	 * @return	    nothing
	 *
	 */
	function restore_survey($survey_id)
	{
	    $this->surveys_model->restore_survey($survey_id);
	    redirect('surveys/index');
	}

	/**
	 *
	 * Function recycle_survey()
	 *
	 * @description Recycles a survey (soft delete).
	 * @access	    public
	 * @param	    $survey_id (int)
	 * @return	    nothing
	 *
	 */
	function recycle_survey($survey_id)
	{
	    $this->surveys_model->recycle_survey($survey_id);
	    redirect('surveys/index');
	}

	/**
	 *
	 * Function add_section()
	 *
	 * @description Allows addition of a new survey - section-level
	 * @access	    public
	 * @param	    $survey_id (int)
	 * @return	    nothing
	 *
	 */
	function add_section($survey_id)
	{
	    $data['survey_id'] = $survey_id;
        $this->template->load('template', 'surveys/add_section', $data);
	}

	/**
	 *
	 * Function edit_section()
	 *
	 * @description Allows editing of section
	 * @access	    public
	 * @param	    $survey_id (int), $section_id (int)
	 * @return	    nothing
	 *
	 */
	function edit_section($survey_id, $section_id)
	{
	    $data['section'] = $this->surveys_model->get_section($survey_id, $section_id);
	    $data['survey_id'] = $survey_id;
	    $data['id'] = $section_id;
	    $this->template->load('template', 'surveys/edit_section', $data);
	}

	/**
	 *
	 * Function insert_section()
	 *
	 * @description Inserts a new section into the database.
	 * @access	    public
	 * @param	    none
	 * @return	    nothing
	 *
	 */
	function insert_section()
	{
        foreach ($_POST as $k => $v)
        {
            $data[$k] = $this->input->post($k);
        }
        unset($data['submitdetails']);
        $this->surveys_model->insert_section($data);
        redirect('surveys/manage_sections/' . $data['SURVEY_ID']);
	}

	/**
	 *
	 * Function update_section()
	 *
	 * @description Updates a section that was edited.
	 * @access	    public
	 * @param	    none
	 * @return	    nothing
	 *
	 */
	function update_section()
	{
        foreach ($_POST as $k => $v)
        {
            $data[$k] = $this->input->post($k);
        }
        unset($data['submitdetails']);
        $this->surveys_model->update_section($data);
        redirect('surveys/manage_sections/' . $data['SURVEY_ID']);
	}

	/**
	 *
	 * Function delete_section()
	 *
	 * @description Allows for deleting sections from the survey. Filters through to options and questions to prevent orphan options and questions.
	 * @access	    public
	 * @param	    $survey_id (int), $section_id (int)
	 * @return	    nothing
	 *
	 */
	function delete_section($survey_id, $section_id)
	{
        $this->surveys_model->delete_responses_by_survey_and_section($survey_id, $section_id);
	    $this->surveys_model->delete_options_by_survey_and_section($survey_id, $section_id);
	    $this->surveys_model->delete_questions_by_survey_and_section($survey_id, $section_id);
	    $this->surveys_model->delete_sections_by_survey_and_section($survey_id, $section_id);
	    redirect('surveys/manage_sections/' . $survey_id);
	}

	/**
	 *
	 * Function add_question()
	 *
	 * @description Allows addition of a new survey - question-level
	 * @access	    public
	 * @param	    $survey_id (int), $section_id (int)
	 * @return	    nothing
	 *
	 */
	function add_question($survey_id, $section_id)
	{
	    $data['survey_id'] = $survey_id;
	    $data['section_id'] = $section_id;
	    $data['qtypes'] = $this->surveys_model->get_questiontypes();
        $this->template->load('template', 'surveys/add_question', $data);
	}

	/**
	 *
	 * Function edit_question()
	 *
	 * @description Allows editing of question
	 * @access	    public
	 * @param	    $survey_id (int), $section_id (int), $question_id (int)
	 * @return	    nothing
	 *
	 */
	function edit_question($survey_id, $section_id, $question_id)
	{
	    $data['question'] = $this->surveys_model->get_question($survey_id, $section_id, $question_id);
	    $data['survey_id'] = $survey_id;
	    $data['section_id'] = $section_id;
	    $data['id'] = $question_id;
	    $data['qtypes'] = $this->surveys_model->get_questiontypes();
	    $this->template->load('template', 'surveys/edit_question', $data);
	}

	/**
	 *
	 * Function insert_question()
	 *
	 * @description Inserts a new question into the database.
	 * @access	    public
	 * @param	    none
	 * @return	    nothing
	 *
	 */
	function insert_question()
	{
        foreach ($_POST as $k => $v)
        {
            $data[$k] = $this->input->post($k);
        }
        unset($data['submitdetails']);
        $this->surveys_model->insert_question($data);
        redirect('surveys/manage_questions/' . $data['SURVEY_ID'] . '/' . $data['SECTION_ID']);
	}

	/**
	 *
	 * Function update_question()
	 *
	 * @description Updates a question that was edited.
	 * @access	    public
	 * @param	    none
	 * @return	    nothing
	 *
	 */
	function update_question()
	{
        foreach ($_POST as $k => $v)
        {
            $data[$k] = $this->input->post($k);
        }
        unset($data['submitdetails']);
        $this->surveys_model->update_question($data);
        redirect('surveys/manage_questions/' . $data['SURVEY_ID'] . '/' . $data['SECTION_ID']);
	}

	/**
	 *
	 * Function delete_question()
	 *
	 * @description Allows for deleting questions from the survey. Filters through to options to prevent orphan options.
	 * @access	    public
	 * @param	    $survey_id (int), $section_id (int), $question_id (int)
	 * @return	    nothing
	 *
	 */
	function delete_question($survey_id, $section_id, $question_id)
	{
	    $this->surveys_model->delete_options_by_survey_and_section_and_question($survey_id, $section_id, $question_id);
	    $this->surveys_model->delete_questions_by_survey_and_section_and_question($survey_id, $section_id, $question_id);
        redirect('surveys/manage_questions/' . $survey_id . '/' . $section_id);
	}

	/**
	 *
	 * Function edit_options()
	 *
	 * @description Allows for editing the options.
	 * @access	    public
	 * @param	    $survey_id (int), $section_id (int), $question_id (int)
	 * @return	    nothing
	 *
	 */
	function edit_options($survey_id, $section_id, $question_id)
	{

	    // Gets the options to edit, and sets variables, then load the view.
	    $data['options'] = $this->surveys_model->get_options($survey_id, $section_id, $question_id);
	    $data['question'] = $this->surveys_model->get_question($survey_id, $section_id, $question_id);
	    $data['question_id'] = $question_id;
	    $data['section_id'] = $section_id;
	    $data['survey_id'] = $survey_id;
        $data['survey'] = $this->surveys_model->get_survey($survey_id);
        $data['section'] = $this->surveys_model->get_section($survey_id, $section_id);
        $data['question'] = $this->surveys_model->get_question($survey_id, $section_id, $question_id);
	    $this->template->load('template', 'surveys/edit_options', $data);
	}

	/**
	 *
	 * Function update_options()
	 *
	 * @description Updates the options for a question.
	 * @access	    public
	 * @param	    none
	 * @return	    nothing
	 *
	 */
	function update_options()
	{

	    // Processes each post value and assign it to a variable.
        foreach ($_POST as $k => $v)
        {
            $data[$k] = $this->input->post($k);
        }
        unset($data['submitdetails']);

        // Delete all options for the question, so that they can be re-inserted.
        $this->surveys_model->delete_options_by_survey_and_section_and_question($data['SURVEY_ID'], $data['SURVEY_SECTION_ID'], $data['SURVEY_QUESTION_ID']);

        // Processes each option, and saves to database.
        foreach ($data as $key => $val)
        {
            $keys = explode('-', $key);
            if (count($keys) > 1)
            {
                $entry['DESCRIPTION'] = $val;
                $entry['SURVEY_ID'] = $data['SURVEY_ID'];
                $entry['SURVEY_SECTION_ID'] = $data['SURVEY_SECTION_ID'];
                $entry['SURVEY_QUESTION_ID'] = $data['SURVEY_QUESTION_ID'];
                $entry['ITEMORDER'] = $keys[1];
                $this->surveys_model->insert_option($entry);
            }
        }
        redirect('surveys/manage_questions/' . $data['SURVEY_ID'] . '/' . $data['SURVEY_SECTION_ID']);
	}

	/**
	 *
	 * Function clone_survey()
	 *
	 * @description Allows copy of a survey - all sections
	 * @access	    public
	 * @param	    $survey_id (int)
	 * @return	    nothing
	 *
	 */
	function clone_survey($survey_id)
	{

	    // Gets the survey-level data.
	    $temp['survey'] = $this->surveys_model->get_survey($survey_id);
	    if (isset($temp['survey']) && is_array($temp['survey']) && count($temp['survey']) > 0)
	    {
	        foreach ($temp['survey'] as $survey_key => $survey_value)
    	    {

    	        // Gets the section-level data.
                $temp['sections'] = $this->surveys_model->get_survey_sections($survey_id);

                // Processes the survey-level data.
	            $data['survey'][$survey_value['ID']]['TITLE'] = $survey_value['TITLE'] . ' - Copy';
    	        $data['survey'][$survey_value['ID']]['DESCRIPTION'] = $survey_value['DESCRIPTION'];
	            $data['survey'][$survey_value['ID']]['OPENDATE'] = $survey_value['OPENDATE'];
	            $data['survey'][$survey_value['ID']]['CLOSEDATE'] = $survey_value['CLOSEDATE'];
	            $data['survey'][$survey_value['ID']]['RESTRICTED'] = $survey_value['RESTRICTED'];
    	        $data['survey'][$survey_value['ID']]['ALLOWRETAKE'] = $survey_value['ALLOWRETAKE'];
	            $data['survey'][$survey_value['ID']]['ISDELETED'] = $survey_value['ISDELETED'];
	            $data['survey'][$survey_value['ID']]['PERSONNEL_NO'] = $survey_value['PERSONNEL_NO'];

	            // Processes the section-level data.
        	    if (isset($temp['sections']) && is_array($temp['sections']) && count($temp['sections']) > 0)
	            {
	                foreach ($temp['sections'] as $section_key => $section_value)
            	    {

            	        // Gets the question-level data.
	                    $temptemp = $this->surveys_model->get_survey_questions($section_value['SURVEY_ID'], $section_value['ID']);
	                    $temp['questions'] = array();
        	            foreach ($temptemp as $tt)
    	                {
                            $temp['questions'][] = $tt;
                        }

                        // Processes the section-level data.
    	                $data['survey'][$survey_value['ID']]['sections'][$section_value['ID']]['SURVEY_ID'] = $section_value['SURVEY_ID'];
	                    $data['survey'][$survey_value['ID']]['sections'][$section_value['ID']]['TITLE'] = $section_value['TITLE'];
        	            $data['survey'][$survey_value['ID']]['sections'][$section_value['ID']]['DESCRIPTION'] = $section_value['DESCRIPTION'];

                	    // Processes the question-level data.
                	    if (isset($temp['questions']) && is_array($temp['questions']) && count($temp['questions']) > 0)
                	    {
                	        foreach ($temp['questions'] as $question_key => $question_value)
                    	    {

                    	        // Gets the option-level data.
                                $temptemp = $this->surveys_model->get_options($question_value['SURVEY_ID'], $question_value['SURVEY_SECTION_ID'], $question_value['ID']);
                                $temp['options'] = array();
                                foreach ($temptemp as $tt)
                                {
                                    $temp['options'][] = $tt;
                                }

                                // Processes the question-level data.
                                $data['survey'][$survey_value['ID']]['sections'][$section_value['ID']]['questions'][$question_value['ID']]['SURVEY_ID'] = $question_value['SURVEY_ID'];
                                $data['survey'][$survey_value['ID']]['sections'][$section_value['ID']]['questions'][$question_value['ID']]['SURVEY_SECTION_ID'] = $question_value['SURVEY_SECTION_ID'];
	                            $data['survey'][$survey_value['ID']]['sections'][$section_value['ID']]['questions'][$question_value['ID']]['QUESTION'] = $question_value['QUESTION'];
                	            $data['survey'][$survey_value['ID']]['sections'][$section_value['ID']]['questions'][$question_value['ID']]['OPTIONCOUNT'] = $question_value['OPTIONCOUNT'];
    	                        $data['survey'][$survey_value['ID']]['sections'][$section_value['ID']]['questions'][$question_value['ID']]['QUESTIONTYPE_ID'] = $question_value['QUESTIONTYPE_ID'];
                                $data['survey'][$survey_value['ID']]['sections'][$section_value['ID']]['questions'][$question_value['ID']]['ISREQUIRED'] = $question_value['ISREQUIRED'];

                                // Processes the option-level data.
                        	    if (isset($temp['options']) && is_array($temp['options']) && count($temp['options']) > 0)
                        	    {
                        	        foreach ($temp['options'] as $option_key => $option_value)
                        	        {
                                	    $data['survey'][$survey_value['ID']]['sections'][$section_value['ID']]['questions'][$question_value['ID']]['options'][$option_value['ID']]['DESCRIPTION'] = $option_value['DESCRIPTION'];
                        	            $data['survey'][$survey_value['ID']]['sections'][$section_value['ID']]['questions'][$question_value['ID']]['options'][$option_value['ID']]['SURVEY_ID'] = $option_value['SURVEY_ID'];
                        	            $data['survey'][$survey_value['ID']]['sections'][$section_value['ID']]['questions'][$question_value['ID']]['options'][$option_value['ID']]['SURVEY_SECTION_ID'] = $option_value['SURVEY_SECTION_ID'];
                        	            $data['survey'][$survey_value['ID']]['sections'][$section_value['ID']]['questions'][$question_value['ID']]['options'][$option_value['ID']]['SURVEY_QUESTION_ID'] = $option_value['SURVEY_QUESTION_ID'];
                                	    $data['survey'][$survey_value['ID']]['sections'][$section_value['ID']]['questions'][$question_value['ID']]['options'][$option_value['ID']]['ITEMORDER'] = $option_value['ITEMORDER'];
                            	        $data['survey'][$survey_value['ID']]['sections'][$section_value['ID']]['questions'][$question_value['ID']]['options'][$option_value['ID']]['ISCORRECT'] = $option_value['ISCORRECT'];
                        	        }
                            	}
                            }
                        }
    	            }
            	}
	        }
    	}

    	// Process the ID changes for surveys, sections, questions and options, and clone each group.
    	foreach ($data['survey'] as $survey_key => $survey_value)
    	{

    	    // For the survey, gets the next available survey ID, adds the survey, and returns the ID
    	    $nextid1 = $this->surveys_model->clone_add_survey($data['survey'][$survey_key]);
    	    foreach ($survey_value['sections'] as $section_key => $section_value)
    	    {
    	        $data['survey'][$survey_key]['sections'][$section_key]['SURVEY_ID'] = $nextid1;

    	        // For each section, gets the next available section ID, adds the section, and returns the ID
    	        $nextid2 = $this->surveys_model->clone_add_section($data['survey'][$survey_key]['sections'][$section_key]);
    	        foreach ($section_value['questions'] as $question_key => $question_value)
    	        {
    	            $data['survey'][$survey_key]['sections'][$section_key]['questions'][$question_key]['SURVEY_ID'] = $nextid1;
    	            $data['survey'][$survey_key]['sections'][$section_key]['questions'][$question_key]['SURVEY_SECTION_ID'] = $nextid2;

    	            // For each question, gets the next available question ID, adds the question, and returns the ID
    	            $nextid3 = $this->surveys_model->clone_add_question($data['survey'][$survey_key]['sections'][$section_key]['questions'][$question_key]);
    	            foreach ($question_value['options'] as $option_key => $option_value)
    	            {
    	                $data['survey'][$survey_key]['sections'][$section_key]['questions'][$question_key]['options'][$option_key]['SURVEY_ID'] = $nextid1;
    	                $data['survey'][$survey_key]['sections'][$section_key]['questions'][$question_key]['options'][$option_key]['SURVEY_SECTION_ID'] = $nextid2;
                        $data['survey'][$survey_key]['sections'][$section_key]['questions'][$question_key]['options'][$option_key]['SURVEY_QUESTION_ID'] = $nextid3;

                        // For each option, gets the next available option ID, adds the option, and returns the ID
                        $nextid4 = $this->surveys_model->clone_add_option($data['survey'][$survey_key]['sections'][$section_key]['questions'][$question_key]['options'][$option_key]);
    	            }
    	        }
    	    }
    	}
        redirect('surveys/index');
	}

}
