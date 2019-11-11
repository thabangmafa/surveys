<script type="text/javascript">
    $(document).ready(function(){
        $("#surveyform").validationEngine();
    });
</script>

<?php

    $yesno_options = array();
    $yesno_options[''] = 'Please choose';
    $yesno_options['Yes'] = 'Yes';
    $yesno_options['No'] = 'No';

    $count_options = array();
    $count_options[''] = 'Please choose';
    for ($i = 2; $i <= 10; $i++)
    {
        $count_options[$i] = $i;
    }

    $type_options = array();
    if (isset($qtypes) && is_array($qtypes))
    {
        $type_options[''] = 'Please choose';
        foreach ($qtypes as $qtype)
        {
            $type_options[$qtype['ID']] = $qtype['DESCRIPTION'];
        }
    }

    echo heading('Manage Surveys', '/images/surveys/surveys.png');
    echo '
        <p>This section is used to create the questions for a section. Here you can add details such as the question and number of options a question has.</p>
        <div class="btn btn-default btn-left btn-helpbox">
            <ul>
                <li>Click <strong><em><a title="Back to the question listing" href="' . base_url() . 'index.php/surveys/manage_questions/' . $survey_id . '/' . $section_id . '">here</a></em></strong> link to return to the questions list without making any changes.</li>
            </ul>
        </div>
    ';

    echo heading('Add question', '/images/surveys/questions.png');
    echo rms_form_open_multipart('surveys/insert_question/' . $survey_id . '/' . $section_id, array('id' => 'surveyform', 'name' => 'surveyform'));
    echo rms_form_input('QUESTION', 'Question', 'This field is mandatory.', 'text', '', 'size="128" class="validate[required]"');
    echo rms_form_dropdown('OPTIONCOUNT', 'Option count', 'This field is mandatory.', 'OPTIONCOUNT', $count_options, '', 'id="OPTIONCOUNT" class="validate[required]"');
    echo rms_form_dropdown('QUESTIONTYPE_ID', 'Question type', 'This field is mandatory.', 'QUESTIONTYPE_ID', $type_options, '', 'id="QUESTIONTYPE_ID" class="validate[required]"');
    echo rms_form_dropdown('ISREQUIRED', 'Is this question required?', 'This field is mandatory.', 'ISREQUIRED', $yesno_options, '', 'id="ISREQUIRED" class="validate[required]"');
    echo rms_form_hidden('SURVEY_ID', $survey_id);
    echo rms_form_hidden('SECTION_ID', $section_id);
    echo '<div class="type-button"><input type="submit" name="submitdetails" value="Save details" id="submitdetails" /></div>';
    echo rms_form_close();

?>
