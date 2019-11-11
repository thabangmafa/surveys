<script type="text/javascript">
    $(document).ready(function(){
        $('#openlist').tablesorter();
        $('#closedlist').tablesorter();
    });
</script>

<?php

    echo heading('Manage Surveys', '/images/surveys/surveys.png');
    echo '
        <p>This section is used to manage the top-level of surveys. From here you can edit details such as the title, description, open date and closing date. You can also close and re-open surveys with a quick link and proceed to manage a surveys sections.</p>
        <div class="btn btn-default btn-left btn-helpbox">
            <ul>
                <li>Click on the <strong><em>Participate</em></strong> link next to a survey to participate in it</li>
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
                        <th style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
        ';
        foreach ($open_surveys as $open_survey)
        {
            echo '
                <tr>
                    <td>' . $open_survey['TITLE'] . '</td>
                    <td>' . $open_survey['DESCRIPTION'] . '</td>
                    <td>' . $open_survey['OPENDATE'] . '</td>
                    <td>' . $open_survey['CLOSEDATE'] . '</td>
                    <td>' . $open_survey['RESTRICTED'] . '</td>
                    <td>' . $staffname[0]['NAME'] . '</td>
                    <td>
                        <a title="Participate in this survey" href="' . base_url() . 'index.php/surveys/participate_survey/' . $open_survey['ID'] . '"><img style="width: 14px; height: 14px; border: 0" src="' . base_url() . 'images/common/approve.png" alt="Participate in this survey" title="Participate in this survey" />&nbsp;Participate</a>&nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
            ';
        }
        echo '
                </tbody>
            </table>
        ';
    }
    else
    {
        echo '
            <div class="btn btn-info btn-left btn-helpbox">
                <ul>
                    <li>There are currently no open surveys to participate in. Please try again later.</li>
                </ul>
            </div>
        ';
    }

?>