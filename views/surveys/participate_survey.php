<script type="text/javascript">
    $(document).ready(function(){
        $("#surveyform").validationEngine();
    });
</script>

<?php

    if (isset($final))
    {
        echo '<form method="POST" action="' . base_url() . 'index.php/surveys/update_survey_participation/' . $survey[0]['ID'] . '" name="surveyform" id="surveyform">';
        echo heading('Participate in <em>' . $final['title'] . '</em>', '/images/common/approve.png');
        echo '
            <div class="btn btn-default btn-left btn-helpbox">
                <p><strong>SURVEY DESCRIPTION</strong><br />' . $final['intro'] . '</p>
                <p><strong>AVAILABILITY</strong><br />' . $final['opendate'] . ' - ' . $final['closedate'] . '</p>
                <p><strong>SURVEY HOST</strong><br />' . $final['creator'] . '</p>
            </div>
        ';
        if (is_array($final) && count($final) > 0)
        {
            foreach ($final['sections'] as $section_key => $section_value)
            {
                $count_question = 0;
                echo heading($section_value['title'], '/images/surveys/sections.png');
                echo '<div class="btn btn-info btn-left btn-helpbox"><strong>DESCRIPTION</strong><br />' . $section_value['description'] . '</div>';
                foreach ($section_value['questions'] as $question_key => $question_value)
                {
                    $count_question++;
                    if ($question_value['required'] == 'Yes')
                    {
                        $marker = '<sup title="This question is a required question." style="color: #880000; font-size: 14px; font-weight: bold;">*</sup>';
                        $extra = ' class="validate[minCheckbox[1]]"';
                    }
                    else
                    {
                        $marker = '';
                        $extra = '';
                    }
                    echo '<p><strong>' . $count_question . '. ' . $question_value['question'] . '</strong> ' . $marker . '</p>';

                    // Radio buttons
                    if ($question_value['type'] == 1 || $question_value['type'] == 4)
                    {
                        echo '<div class="type-text">';

                        // For each of the options, draw a radio button.
                        foreach ($question_value['options'] as $option_key => $option_value)
                        {
                            $id = $final['id'] . '-' . $section_value['id'] . '-' . $question_value['id'] . '-' . $option_value['id'];
                            $name = $final['id'] . '-' . $section_value['id'] . '-' . $question_value['id'];
                            if ($question_value['required'] == 'Yes')
                            {
                                echo '<p style="text-indent: 10px;"><input' . $extra . ' type="radio" name="' . $name . '" id="' . $id . '" value="' . $option_value['id'] . '" />&nbsp;&nbsp;<label for="' . $id . '"><strong>' . $option_value['option'] . '</strong></label></p>';
                            }
                            else
                            {
                                echo '<p style="text-indent: 10px;><input' . $extra . ' class="validate[required]" type="radio" name="' . $name . '" id="' . $id . '" value="' . $option_value['id'] . '" />&nbsp;&nbsp;<label for="' . $id . '"><strong>' . $option_value['option'] . '</strong></label></p>';
                            }
                        }

                        // This question is of type 4 (single choice with motivation), therefore, add a text area below the options.
                        if ($question_value['type'] == 4)
                        {
                            if ($question_value['required'] == 'Yes')
                            {
                                echo rms_form_textarea($name . '-motivation', '', '', 7, 110, set_value($name . '-motivation'), 'id="' . $id . '-motivation" class="validate[required]"');
                            }
                            else
                            {
                                echo rms_form_textarea($name . '-motivation', '', '', 7, 110, set_value($name . '-motivation'), 'id="' . $id . '-motivation"');
                            }
                        }
                        echo '</div>';
                    }

                    // Checkboxes
                    elseif ($question_value['type'] == 2 || $question_value['type'] == 5)
                    {
                        echo '<div class="type-text">';

                        // For each of the options, draw a checkbox.
                        foreach ($question_value['options'] as $option_key => $option_value)
                        {
                            $id = $final['id'] . '-' . $section_value['id'] . '-' . $question_value['id'] . '-' . $option_value['id'];
                            $name = $final['id'] . '-' . $section_value['id'] . '-' . $question_value['id'];
                            if ($question_value['required'] == 'Yes')
                            {
                                echo '<p style="text-indent: 10px;"><input' . $extra . ' type="checkbox" name="' . $name . '[]" id="' . $id . '" value="' . $option_value['id'] . '" />&nbsp;&nbsp;<label for="' . $id . '"><strong>' . $option_value['option'] . '</strong></label></p>';
                            }
                            else
                            {
                                echo '<p style="text-indent: 10px;"><input' . $extra . ' class="validate[minCheckbox[1]]" type="checkbox" name="' . $name . '[]" id="' . $id . '" value="' . $option_value['id'] . '" />&nbsp;&nbsp;<label for="' . $id . '"><strong>' . $option_value['option'] . '</strong></label></p>';
                            }
                        }

                        // This question is of type 5 (multiple choice with motivation), therefore, add a text area below the options.
                        if ($question_value['type'] == 5)
                        {
                            if ($question_value['required'] == 'Yes')
                            {
                                echo rms_form_textarea($name . '-motivation', '', '', 7, 110, set_value($name . '-motivation'), 'id="' . $id . '-motivation" class="validate[required]"');
                            }
                            else
                            {
                                echo rms_form_textarea($name . '-motivation', '', '', 7, 110, set_value($name . '-motivation'), 'id="' . $id . '-motivation"');
                            }
                        }
                        echo '</div>';
                    }

                    // Open-ended - automatically append '-1' to signify that the option chosen is 1.
                    elseif ($question_value['type'] == 3)
                    {
                        $id = $final['id'] . '-' . $section_value['id'] . '-' . $question_value['id'] . '-1';
                        if ($question_value['required'] == 'Yes')
                        {
                            echo rms_form_textarea($id . '-textcomponent', '', '', 7, 110, set_value($id . '-textcomponent'), 'id="' . $id . '-textcomponent" class="validate[required]"');
                        }
                        else
                        {
                            echo rms_form_textarea($id . '-textcomponent', '', '', 7, 110, set_value($id . '-textcomponent'), 'id="' . $id . '-textcomponent"');
                        }
                    }
                    echo '<div style="clear: both; padding-bottom: 20px;"></div>';
                }
            }
        }
        echo rms_form_hidden('participant', $final['participant']);
        echo '<div class="yform"><div class="type-button"><input type="submit" name="submitdetails" value="Submit survey" id="submitdetails" /></div></div>';
        echo rms_form_close();
    }
    else
    {
        echo '
            <div class="btn btn-danger btn-left btn-helpbox">
                A problem occurred with the loading of the survey. Please contact <a href="' . base_url() . 'index/php/dashboard/contactus">IT support</a>.
            </div>
        ';
    }

?>