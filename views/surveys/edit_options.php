<script type="text/javascript">
    $(document).ready(function(){
        $("#surveyform").validationEngine();
    });
</script>

<?php

    echo heading('Manage Surveys', '/images/surveys/surveys.png');
    echo '
        <p>This section is used to create the options for a question. Here you can add or edit options up to the count of options specified at the question level.</p>
        <div class="btn btn-default btn-left btn-helpbox">
            <ul>
                <li>Click <strong><em><a title="Back to the question listing" href="' . base_url() . 'index.php/surveys/manage_questions/' . $survey_id . '/' . $section_id . '">here</a></em></strong> link to return to the questions list without making any changes.</li>
            </ul>
        </div>
    ';

    echo heading('Manage Options', '/images/surveys/options.png');
    echo '
        <div class="btn btn-info btn-left btn-helpbox" style="padding: 15px; margin: 15px 0;">
            <strong>&raquo;&nbsp;&nbsp;You are here:&nbsp;&nbsp;</strong><a style="color: #fff; text-decoration: underline;" title="Return to surveys" href="' . base_url() . 'index.php/surveys/index">' . $survey[0]['TITLE'] . '</a>
            <strong>&nbsp;&nbsp;&raquo;&nbsp;&nbsp;</strong><a style="color: #fff; text-decoration: underline;" title="Return to sections" href="' . base_url() . 'index.php/surveys/manage_sections/' . $survey[0]['ID'] . '/' . $section[0]['ID'] . '">' . $section[0]['TITLE'] . '</a>
            <strong>&nbsp;&nbsp;&raquo;&nbsp;&nbsp;</strong><a style="color: #fff; text-decoration: underline;" title="Return to questions" href="' . base_url() . 'index.php/surveys/manage_questions/' . $survey[0]['ID'] . '/' . $section[0]['ID'] . '/' . $question[0]['ID'] . '">' . $question[0]['QUESTION'] . '</a>
            <strong>&nbsp;&nbsp;&raquo;&nbsp;&nbsp;</strong>Options
        </div>
    ';
    echo rms_form_open_multipart('surveys/update_options/' . $survey_id . '/' . $section_id . '/' . $question_id, array('id' => 'surveyform', 'name' => 'surveyform'));
    $qt = $question[0]['QUESTIONTYPE_ID'];
    $correct = array();
    if ($qt == '1' || $qt == '2' || $qt == '4' || $qt == '5')
    {
        if (isset($question[0]['OPTIONCOUNT']) && $question[0]['OPTIONCOUNT'] >= 2)
        {
            for ($i = 0; $i < $question[0]['OPTIONCOUNT']; $i++)
            {
                if (isset($options[$i]) && $options[$i] !== '')
                {
                    echo rms_form_input('OPTION-' . ($i+1), 'Option ' . ($i+1), 'This field is mandatory.', 'text', $options[$i]['DESCRIPTION'], 'size="128" class="validate[required]"');
                }
                else
                {
                    echo rms_form_input('OPTION-' . ($i+1), 'Option ' . ($i+1), 'This field is mandatory.', 'text', '', 'size="128" class="validate[required]"');
                }
                $correct[] = 'Option - ' . ($i+1);
            }
        }
    }
    elseif ($qt == '3')
    {
        echo '<div class="btn btn-info btn-left btn-helpbox">There are no configurable options for an open-type question. The text fields will be added automatically during run time.</div>';
    }
    echo rms_form_hidden('SURVEY_ID', $survey_id);
    echo rms_form_hidden('SURVEY_SECTION_ID', $section_id);
    echo rms_form_hidden('SURVEY_QUESTION_ID', $question[0]['ID']);
    echo '<div class="type-button"><input type="submit" name="submitdetails" value="Save details" id="submitdetails" /></div>';
    echo rms_form_close();

?>