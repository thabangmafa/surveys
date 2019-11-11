<script type="text/javascript">
    $(document).ready(function(){
        $('#openlist').tablesorter();
        $('#closedlist').tablesorter();
    });
    function confirmation(msg, url) {
    	var answer = confirm(msg)
	    if (answer){
		    window.location = url;
	    }
    	else{
		    return false;
	    }
    }
</script>

<?php

    echo heading('Manage Surveys', '/images/surveys/surveys.png');
    echo '
        <p>This section is used to manage the top-level of surveys. From here you can edit details such as the title, description, open date and closing date. You can also close and re-open surveys with a quick link and proceed to manage a surveys sections.</p>
        <div class="btn btn-default btn-left btn-helpbox">
            <ul>
                <li>Click <strong><em><a title="Add a new survey" href="' . base_url() . 'index.php/surveys/add_survey">here</a></em></strong> to create a new survey</li>
                <li>Click on the <strong><em>Sections</em></strong> link next to a survey to drill deeper into the survey management</li>
                <li>Click on the <strong><em>Edit</em></strong> link next to a survey to edit the top-level detail of a survey.</li>
                <li>Click on the <strong><em>Re-open</em></strong> link next to a closed survey to quickly re-open the survey on the current date, and set the closing date seven days from now.</li>
                <li>Click on the <strong><em>Close</em></strong> link next to an open survey to immediately close the survey for further participation from respondents.</li>
            </ul>
        </div>
    ';

    if (isset($open_surveys) && is_array($open_surveys) && count($open_surveys) > 0)
    {
        echo heading('Open surveys', '/images/surveys/open.png');
        echo '
            <table border="0" class="tablesorter" id="openlist" style="width: 100%">
                <thead>
                    <tr>
                        <th style="width: 100px;">Title</th>
                        <th>Description</th>
                        <th style="width: 90px;">Open date</th>
                        <th style="width: 90px;">Closed date</th>
                        <th style="width: 90px;">Restricted?</th>
                        <th style="width: 125px;">Creator</th>
                        <th style="width: 300px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
        ';
        foreach ($open_surveys as $open_survey)
        {
            echo '
                <tr>
                    <td>' . $open_survey['TITLE'] . '</td>
                    <td>' . shorten($open_survey['DESCRIPTION'], 60) . '</td>
                    <td>' . $open_survey['OPENDATE'] . '</td>
                    <td>' . $open_survey['CLOSEDATE'] . '</td>
                    <td>' . $open_survey['RESTRICTED'] . '</td>
                    <td>' . $staffname[0]['NAME'] . '</td>
                    <td>
                        <a title="Manage survey sections" href="' . base_url() . 'index.php/surveys/manage_sections/' . $open_survey['ID'] . '"><img style="width: 14px; height: 14px; border: 0" src="' . base_url() . 'images/surveys/sections.png" alt="Manage survey sections" title="Manage survey sections" />&nbsp;Sections</a>&nbsp;&nbsp;&nbsp;
                        <a title="Edit survey" href="' . base_url() . 'index.php/surveys/edit_survey/' . $open_survey['ID'] . '"><img style="width: 14px; height: 14px; border: 0" src="' . base_url() . 'images/common/edit.png" alt="Edit survey" title="Edit survey" />&nbsp;Edit</a>&nbsp;&nbsp;&nbsp;
                        <a title="Close survey" href="javascript:confirmation(\'Are you sure you want to close this survey? It will become unavailable for participation immediately.\', \'' . base_url() . 'index.php/surveys/close_survey/' . $open_survey['ID'] . '\')"><img style="width: 14px; height: 14px; border: 0" src="' . base_url() . 'images/surveys/close.png" alt="Close survey" title="Close survey" />&nbsp;Close</a>&nbsp;&nbsp;&nbsp;
                        <a title="Recycle survey" href="javascript:confirmation(\'Are you sure you want to recycle this survey? You will be able to restore it later.\', \'' . base_url() . 'index.php/surveys/recycle_survey/' . $open_survey['ID'] . '\')"><img style="width: 14px; height: 14px; border: 0" src="' . base_url() . 'images/surveys/recycle.png" alt="Recycle survey" title="Recycle survey" />&nbsp;Recycle</a>&nbsp;&nbsp;&nbsp;
                        <a title="Clone survey" href="javascript:confirmation(\'Are you sure you want to make a copy of this survey?\', \'' . base_url() . 'index.php/surveys/clone_survey/' . $open_survey['ID'] . '\')"><img style="width: 14px; height: 14px; border: 0" src="' . base_url() . 'images/surveys/clone.png" alt="Clone survey" title="Clone survey" />&nbsp;Clone</a>&nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
            ';
        }
        echo '
                </tbody>
            </table>
            <div style="padding-bottom: 15px; margin-bottom: 15px; clear: both;"></div>
        ';
    }

    if (isset($closed_surveys) && is_array($closed_surveys) && count($closed_surveys) > 0)
    {
        echo heading('Closed surveys', '/images/surveys/closed.png');
        echo '
            <table border="0" class="tablesorter" id="closedlist" style="width: 100%">
                <thead>
                    <tr>
                        <th style="width: 100px;">Title</th>
                        <th>Description</th>
                        <th style="width: 90px;">Open date</th>
                        <th style="width: 90px;">Closed date</th>
                        <th style="width: 90px;">Restricted?</th>
                        <th style="width: 125px;">Creator</th>
                        <th style="width: 310px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
        ';
        foreach ($closed_surveys as $closed_survey)
        {
            echo '
                <tr>
                    <td>' . $closed_survey['TITLE'] . '</td>
                    <td>' . shorten($closed_survey['DESCRIPTION'], 60) . '</td>
                    <td>' . $closed_survey['OPENDATE'] . '</td>
                    <td>' . $closed_survey['CLOSEDATE'] . '</td>
                    <td>' . $closed_survey['RESTRICTED'] . '</td>
                    <td>' . $staffname[0]['NAME'] . '</td>
                    <td>
                        <a title="Manage survey sections" href="' . base_url() . 'index.php/surveys/manage_sections/' . $closed_survey['ID'] . '"><img style="width: 14px; height: 14px; border: 0" src="' . base_url() . 'images/surveys/sections.png" alt="Manage survey sections" title="Manage survey sections" />&nbsp;Sections</a>&nbsp;&nbsp;&nbsp;
                        <a title="Edit survey" href="' . base_url() . 'index.php/surveys/edit_survey/' . $closed_survey['ID'] . '"><img style="width: 14px; height: 14px; border: 0" src="' . base_url() . 'images/common/edit.png" alt="Edit survey" title="Edit survey" />&nbsp;Edit</a>&nbsp;&nbsp;&nbsp;
                        <a title="Re-open survey" href="javascript:confirmation(\'Are you sure you want to re-open this survey? It will become available for participation immediately.\', \'' . base_url() . 'index.php/surveys/reopen_survey/' . $closed_survey['ID'] . '\')"><img style="width: 14px; height: 14px; border: 0" src="' . base_url() . 'images/surveys/open.png" alt="Re-open survey" title="Re-open survey" />&nbsp;Re-open</a>&nbsp;&nbsp;&nbsp;
                        <a title="Recycle survey" href="javascript:confirmation(\'Are you sure you want to recycle this survey? You will be able to restore it later.\', \'' . base_url() . 'index.php/surveys/recycle_survey/' . $closed_survey['ID'] . '\')"><img style="width: 14px; height: 14px; border: 0" src="' . base_url() . 'images/surveys/recycle.png" alt="Recycle survey" title="Recycle survey" />&nbsp;Recycle</a>&nbsp;&nbsp;&nbsp;
                        <a title="Clone survey" href="javascript:confirmation(\'Are you sure you want to make a copy of this survey?\', \'' . base_url() . 'index.php/surveys/clone_survey/' . $closed_survey['ID'] . '\')"><img style="width: 14px; height: 14px; border: 0" src="' . base_url() . 'images/surveys/clone.png" alt="Clone survey" title="Clone survey" />&nbsp;Clone</a>&nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
            ';
        }
        echo '
                </tbody>
            </table>
            <div style="padding-bottom: 15px; margin-bottom: 15px; clear: both;"></div>
        ';
    }

    if (isset($deleted_surveys) && is_array($deleted_surveys) && count($deleted_surveys) > 0)
    {
        echo heading('Deleted surveys', '/images/common/delete.png');
        echo '
            <table border="0" class="tablesorter" id="closedlist" style="width: 100%">
                <thead>
                    <tr>
                        <th style="width: 100px;">Title</th>
                        <th>Description</th>
                        <th style="width: 90px;">Open date</th>
                        <th style="width: 90px;">Closed date</th>
                        <th style="width: 90px;">Restricted?</th>
                        <th style="width: 125px;">Creator</th>
                        <th style="width: 130px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
        ';
        foreach ($deleted_surveys as $deleted_survey)
        {
            echo '
                <tr>
                    <td>' . $deleted_survey['TITLE'] . '</td>
                    <td>' . shorten($deleted_survey['DESCRIPTION'], 60) . '</td>
                    <td>' . $deleted_survey['OPENDATE'] . '</td>
                    <td>' . $deleted_survey['CLOSEDATE'] . '</td>
                    <td>' . $deleted_survey['RESTRICTED'] . '</td>
                    <td>' . $staffname[0]['NAME'] . '</td>
                    <td>
                        <a title="Restore survey" href="javascript:confirmation(\'Are you sure you want to restore this survey?\', \'' . base_url() . 'index.php/surveys/restore_survey/' . $deleted_survey['ID'] . '\')"><img style="width: 14px; height: 14px; border: 0" src="' . base_url() . 'images/common/approve.png" alt="Restore survey" title="Restore survey" />&nbsp;Restore</a>&nbsp;&nbsp;&nbsp;
                        <a title="Destroy survey - THIS CAN NOT BE UNDONE!" href="javascript:confirmation(\'Are you sure you want to delete this survey permanently? This can NOT be undone!\', \'' . base_url() . 'index.php/surveys/delete_survey/' . $deleted_survey['ID'] . '\')"><img style="width: 14px; height: 14px; border: 0" src="' . base_url() . 'images/common/delete.png" alt="Destroy survey - THIS CAN NOT BE UNDONE!" title="Destroy survey - THIS CAN NOT BE UNDONE!" />&nbsp;Destroy</a>&nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
            ';
        }
        echo '
                </tbody>
            </table>
            <div style="padding-bottom: 15px; margin-bottom: 15px; clear: both;"></div>
        ';
    }

?>