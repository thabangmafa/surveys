<script type="text/javascript">
    $(document).ready(function(){
        $("#surveyform").validationEngine();
    });
</script>

<?php

    echo heading('Edit survey section', '/images/surveys/sections.png');
    echo '
        <p>This section is used to edit the various sections of a survey. Here you can edit details such as the title and description of a survey section.</p>
        <div class="btn btn-default btn-left btn-helpbox">
            <ul>
                <li>Click <strong><em><a title="Back to the survey section listing" href="' . base_url() . 'index.php/surveys/manage_sections/' . $survey_id . '">here</a></em></strong> link to return to the survey sections list without making any changes.</li>
            </ul>
        </div>
    ';

    echo heading('Edit survey section', '/images/surveys/sections.png');
    echo rms_form_open_multipart('surveys/update_section/' . $section[0]['SURVEY_ID'] . '/' . $section[0]['ID'], array('id' => 'surveyform', 'name' => 'surveyform'));
    echo rms_form_input('TITLE', 'Title', 'This field is mandatory.', 'text', $section[0]['TITLE'], 'size="128" class="validate[required]"');
    echo rms_form_textarea('DESCRIPTION', 'Description', 'This field is mandatory.', 7, 110, $section[0]['DESCRIPTION'], 'id="DESCRIPTION" class="validate[required]"');
    echo rms_form_hidden('SURVEY_ID', $survey_id);
    echo rms_form_hidden('ID', $section[0]['ID']);
    echo '<div class="type-button"><input type="submit" name="submitdetails" value="Save details" id="submitdetails" /></div>';
    echo rms_form_close();

?>