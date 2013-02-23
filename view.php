<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Prints a particular instance of newmodule
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod
 * @subpackage newmodule
 * @copyright  2011 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/// (Replace newmodule with the name of your module and remove this line)

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
$context = get_context_instance(CONTEXT_MODULE, $cm->id);

add_to_log($course->id, 'newmodule', 'view', "view.php?id={$cm->id}", $newmodule->name, $cm->id);

/// Print the page header

$PAGE->set_url('/mod/newmodule/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($newmodule->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

// other things you may want to set - remove if not needed
//$PAGE->set_cacheable(false);
//$PAGE->set_focuscontrol('some-html-id');
//$PAGE->add_body_class('newmodule-'.$somevar);

// Output starts here
echo $OUTPUT->header();

if ($newmodule->intro) { // Conditions to show the intro can change to look for own settings or whatever
    echo $OUTPUT->box(format_module_intro('newmodule', $newmodule, $cm->id), 'generalbox mod_introbox', 'newmoduleintro');
}

// Replace the following lines with you own code
echo $OUTPUT->heading('Yay! It works!');

global $USER;
echo 'hello '.$USER->firstname.' '.$USER->lastname.'<br />';
echo 'Welcome To The Test Activity Module <br /><br /><br />';

echo '
	<form action="#" method="POST">
		<label for="msgBody">Your Message To Submit</label>
		<input type="text" id="msgBody" style="width:60%; display:block" />
		<button id="msgSubmitBtn">Submit</button>
	</form>

	<div class="submitted_msgs" style="min-height:400px; padding:10px; width:60%; border:2px dotted #777; overflow:auto;margin-top:20px;">
		<ul id="msgList">
			';
echo newmodule_get_init_data('http://www.elearninghan.com/moodle_mod_test/moodle_test.php');
echo '
		</ul>
	</div>
';

echo '
<script type="text/javascript">
function dosubmit(){
	if(Y.one("#msgBody").get("value") == ""){
		alert("Empty Message You Bastard~");
		return false;
	}
	var cfg = {
	    method: "POST",
	    data: "msg="+Y.one("#msgBody").get("value"),
	    on: {
	        success: function (id, o, args) {
	            if ( o.responseText ) {
	                alert(o.responseText);
	                Y.one("#msgBody").set("value","");
	                Y.io("testget.php?id="+'.$id.', {
					    on:   {success: function(id,o,args){Y.one("#msgList").setHTML(o.responseText); }}
					});
	            }
	        },
	        failure: function (id, o) {
	            Y.log( "fail" );
	            alert("Submit Fail!");
	        }
	    }
	};
    var URI = "test.php?id="+'.$id.';
	Y.io(URI,cfg);

}
	Y.one("#msgSubmitBtn").on("click", function (e) {
		dosubmit();
		e.preventDefault();
	});
</script>
';

// Finish the page
echo $OUTPUT->footer();
