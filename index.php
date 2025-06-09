<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir.'/formslib.php');
require_once(__DIR__.'/form.php');

$PAGE->set_url(new moodle_url('/local/practice/index.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title('Practice Form');
$PAGE->set_heading('Practice Form');

require_login();

$mform = new practice_form();

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/practice/index.php'));
} else if ($data = $mform->get_data()) {
    global $DB;
    $record = new stdClass();
    $record->firstname = $data->firstname;
    $record->lastname = $data->lastname;
    $record->email = $data->email;
    $record->timecreated = time();
    $record->timemodified = time();
    $DB->insert_record('practice', $record);
    redirect(new moodle_url('/local/practice/index.php'), 'Record added!', 2);
}

echo $OUTPUT->header();
$mform->display();

echo html_writer::start_tag('table', array('class' => 'generaltable'));
echo html_writer::start_tag('tr');
echo html_writer::tag('th', 'First Name');
echo html_writer::tag('th', 'Last Name');
echo html_writer::tag('th', 'Email');
echo html_writer::tag('th', 'Time Created');
echo html_writer::end_tag('tr');

$records = $DB->get_records('practice');
foreach ($records as $record) {
    echo html_writer::start_tag('tr');
    echo html_writer::tag('td', $record->firstname);
    echo html_writer::tag('td', $record->lastname);
    echo html_writer::tag('td', $record->email);
    echo html_writer::tag('td', userdate($record->timecreated));
    echo html_writer::end_tag('tr');
}
echo html_writer::end_tag('table');

echo $OUTPUT->footer();