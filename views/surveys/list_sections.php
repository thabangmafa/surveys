<script type="text/javascript">
    $(document).ready(function(){
        $('#openlist').tablesorter();
    });
</script>

<?php

    echo heading('Manage Survey Sections', '/images/surveys/surveys.png');
    echo '
        <p>This section is used to manage the sections of a survey. From here you can edit details such as the title and description of a survey\'s sections.</p>
        <div class="btn btn-default btn-left btn-helpbox">
            <ul>
                <li>Click <strong><em><a title="Add a new survey section" href="' . base_url() . 'index.php/surveys/add_section/' . $survey_id . '">here</a></em></strong> to create a new survey section</li>
                <li>Click <strong><em><a title="Return to surveys list" href="' . base_url() . 'index.php/surveys/index">here</a></em></strong> to return to the surveys list without modifying anything</li>
                <li>Click on the <strong><em>Questions</em></strong> link next to a survey section to drill deeper into the question management of the survey</li>
                <li>Click on the <strong><em>Edit</em></strong> link next to a survey section to edit the description and title of a survey section.</li>
            </ul>
        </div>
    ';

    if (isset($sections) && is_array($sections) && count($sections) > 0)
    {
        echo heading('Survey Sections', '/images/surveys/sections.png');
        echo '
            <div class="btn btn-info btn-left btn-helpbox" style="padding: 15px; margin: 15px 0;">
                <strong>&raquo;&nbsp;&nbsp;You are here:&nbsp;&nbsp;</strong><a style="color: #fff; text-decoration: underline;" title="Return to surveys" href="' . base_url() . 'index.php/surveys/index">' . $survey[0]['TITLE'] . '</a>
                <strong>&nbsp;&nbsp;&raquo;&nbsp;&nbsp;</strong>Sections
            </div>
        ';
        echo '
            <table border="0" class="tablesorter" id="openlist" style="width: 100%">
                <thead>
                    <tr>
                        <th style="width: 150px;">Title</th>
                        <th>Description</th>
                        <th style="width: 125px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
        ';
        foreach ($sections as $section)
        {
            echo '
                <tr>
                    <td>' . $section['TITLE'] . '</td>
                    <td>' . $section['DESCRIPTION'] . '</td>
                    <td>
                        <a title="Manage section questions" href="' . base_url() . 'index.php/surveys/manage_questions/' . $section['SURVEY_ID'] . '/' . $section['ID'] . '"><img style="width: 14px; height: 14px; border: 0" src="' . base_url() . 'images/surveys/sections.png" alt="Manage survey sections" title="Manage section questions" />&nbsp;Questions</a>&nbsp;&nbsp;&nbsp;
                        <a title="Edit survey section" href="' . base_url() . 'index.php/surveys/edit_section/' . $section['SURVEY_ID'] . '/' . $section['ID'] . '"><img style="width: 14px; height: 14px; border: 0" src="' . base_url() . 'images/common/edit.png" alt="Edit section" title="Edit section" />&nbsp;Edit</a>&nbsp;&nbsp;&nbsp;
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