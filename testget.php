<?php
	require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
	require_once(dirname(__FILE__).'/lib.php');
	require_once(dirname(__FILE__).'/locallib.php');

	$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
	$n  = optional_param('n', 0, PARAM_INT);  // newmodule instance ID - it should be named as the first character of the module

	if ($id) {
	    $cm         = get_coursemodule_from_id('newmodule', $id, 0, false, MUST_EXIST);
	    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
	    $newmodule  = $DB->get_record('newmodule', array('id' => $cm->instance), '*', MUST_EXIST);
	} elseif ($n) {
	    $newmodule  = $DB->get_record('newmodule', array('id' => $n), '*', MUST_EXIST);
	    $course     = $DB->get_record('course', array('id' => $newmodule->course), '*', MUST_EXIST);
	    $cm         = get_coursemodule_from_instance('newmodule', $newmodule->id, $course->id, false, MUST_EXIST);
	} else {
	    error('You must specify a course_module ID or an instance ID');
	}

	require_login($course, true, $cm);
	global $USER;
	echo newmodule_get_init_data('http://www.elearninghan.com/moodle_mod_test/moodle_test.php');

?>