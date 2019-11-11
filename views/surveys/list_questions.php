<script type="text/javascript">
    $(document).ready(function(){
        $('#openlist').tablesorter();
    });
</script>

<?php

    echo heading('Manage Questions', '/images/surveys/surveys.png');
    echo '
        <p>This section is used to manage the sections of a survey. From here you can edit details such as the title and description of a survey\'s sections.</p>
        <div class="btn btn-default btn-left btn-helpbox">
            <ul>
                <li>Click <strong><em><a title="Add a new survey question" href="' . base_url() . 'index.php/surveys/add_question/' . $survey_id . '/' . $section_id . '">here</a></em></strong> to create a new question</li>
                <li>Click <strong><em><a title="Return to surveys list" href="' . base_url() . 'index.php/surveys/index">here</a></em></strong> to return to the surveys list without modifying anything</li>
                <li>Click <strong><em><a title="Return to sections list" href="' . base_url() . 'index.php/surveys/manage_sections/' . $survey_id . '">here</a></em></strong> to return to this survey\'s section list without modifying anything</li>
                <li>Click on the <strong><em>Options</em></strong> link next to a question to drill deeper into the option management of the question</li>
                <li>Click on the <strong><em>Edit</em></strong> link next to a question to edit the question details</li>
            </ul>
        </div>
    ';

    if (isset($questions) && is_array($questions) && count($questions) > 0)
    {
        echo heading('Questions', '/images/surveys/questions.png');
        echo '
            <div class="btn btn-info btn-left btn-helpbox" style="padding: 15px; margin: 15px 0;">
                <strong>&raquo;&nbsp;&nbsp;You are here:&nbsp;&nbsp;</strong><a style="color: #fff; text-decoration: underline;" title="Return to surveys" href="' . base_url() . 'index.php/surveys/index">' . $survey[0]['TITLE'] . '</a>
                <strong>&nbsp;&nbsp;&raquo;&nbsp;&nbsp;</strong><a style="color: #fff; text-decoration: underline;" title="Return to sections" href="' . base_url() . 'index.php/surveys/manage_sections/' . $survey[0]['ID'] . '/' . $section[0]['ID'] . '">' . $section[0]['TITLE'] . '</a>
                <strong>&nbsp;&nbsp;&raquo;&nbsp;&nbsp;</strong>Questions
            </div>
        ';
        echo '
            <table border="0" class="tablesorter" id="openlist" style="width: 100%">
                <thead>
                    <tr>
                        <th>Question</th>
                        <th style="width: 100px;">Option Count</th>
                        <th style="width: 275px;">Question Type</th>
                        <th style="width: 125px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
        ';
        foreach ($questions as $question)
        {
            echo '
                <tr>
                    <td>' . $question['QUESTION'] . '</td>
                    <td>' . $question['OPTIONCOUNT'] . '</td>
                    <td>' . $this->surveys_model->get_questiontype($question['QUESTIONTYPE_ID']) . '</td>
                    <td>
                        <a title="Manage question options" href="' . base_url() . 'index.php/surveys/edit_options/' . $question['SURVEY_ID'] . '/' . $question['SURVEY_SECTION_ID'] . '/' . $question['ID'] . '"><img style="width: 14px; height: 14px; border: 0" src="' . base_url() . 'images/surveys/sections.png" alt="Manage question options" title="Manage question options" />&nbsp;Options</a>&nbsp;&nbsp;&nbsp;
                        <a title="Edit question" href="' . base_url() . 'index.php/surveys/edit_question/' . $question['SURVEY_ID'] . '/' . $question['SURVEY_SECTION_ID'] . '/' . $question['ID'] . '"><img style="width: 14px; height: 14px; border: 0" src="' . base_url() . 'images/common/edit.png" alt="Edit question" title="Edit question" />&nbsp;Edit</a>&nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
            ';
        }
        echo '
                </tbody>
            </table>
        ';
    }

?>