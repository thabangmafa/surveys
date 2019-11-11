<script type="text/javascript">
    $(document).ready(function(){
        $("#surveyform").validationEngine();
        $("#OPENDATE").datepicker(
        {
            changeMonth: true,
            changeYear: true,
            minDate: '-2Y',
            dateFormat: 'd/M/y'
        }).unbind('blur');
        $("#CLOSEDATE").datepicker(
        {
            changeMonth: true,
            changeYear: true,
            minDate: '-2Y',
            dateFormat: 'd/M/y'
        }).unbind('blur');
    });
</script>

<?php

    $yesno_options = array();
    $yesno_options[''] = 'Please choose';
    $yesno_options['Yes'] = 'Yes';
    $yesno_options['No'] = 'No';

    echo heading('Edit survey', '/images/surveys/surveys.png');
    echo '
        <p>This section is used to edit the top-level details of a survey. Here you can edit details such as the title, description, open date and closing date.</p>
        <div class="btn btn-default btn-left btn-helpbox">
            <ul>
                <li>Click <strong><em><a title="Back to the survey listing" href="' . base_url() . 'index.php/surveys/index">here</a></em></strong> link to return to the survey list without making any changes.</li>
            </ul>
        </div>
    ';

    if (isset($survey))
    {
        echo heading('Edit survey', '/images/surveys/surveys.png');
        echo rms_form_open_multipart('surveys/update_survey/' . $survey[0]['ID'], array('id' => 'surveyform', 'name' => 'surveyform'));
        echo rms_form_input('TITLE', 'Title', 'This field is mandatory.', 'text', $survey[0]['TITLE'], 'size="128" class="validate[required]"');
        echo rms_form_textarea('DESCRIPTION', 'Description', 'This field is mandatory.', 7, 110, $survey[0]['DESCRIPTION'], 'id="DESCRIPTION" class="validate[required]"');
        echo rms_form_dropdown('RESTRICTED', 'Restricted?', 'This field is mandatory.', 'RESTRICTED', $yesno_options, $survey[0]['RESTRICTED'], 'id="RESTRICTED" class="validate[required]"');
        echo rms_form_dropdown('ALLOWRETAKE', 'Allow retake?', 'This field is mandatory.', 'ALLOWRETAKE', $yesno_options, $survey[0]['ALLOWRETAKE'], 'id="ALLOWRETAKE" class="validate[required]"');
        echo rms_form_dropdown('ISDELETED', 'Survey is marked as deleted?', 'This field is mandatory.', 'ISDELETED', $yesno_options, $survey[0]['ISDELETED'], 'id="ISDELETED" class="validate[required]"');
        echo rms_form_input('OPENDATE', 'Open date', 'This field is mandatory.', 'text', $survey[0]['OPENDATE'], 'id="OPENDATE" name="OPENDATE" readonly="readonly" size="50" class="validate[required]"');
        echo rms_form_input('CLOSEDATE', 'Close date', 'This field is mandatory.', 'text', $survey[0]['CLOSEDATE'], 'id="CLOSEDATE" name="CLOSEDATE" readonly="readonly" size="50" class="validate[required]"');
        echo rms_form_hidden('ID', $survey[0]['ID']);
        echo '<div class="type-button"><input type="submit" name="submitdetails" value="Save details" id="submitdetails" /></div>';
        echo rms_form_close();
    }

?>