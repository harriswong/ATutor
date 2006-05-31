<?php require('../common/body_header.inc.php'); ?>

<h2>Creating Tests &amp; Surveys</h2>
	<p>To begin creating a test, use the <em>Create Test/Survey</em> link. Filling out the information on the Create Test/Survey page will address all the administrative options for a test. Actual questions are added to the test in a separate step.</p>

	<p>Some properties that may require explanation when creating a <strong>Test/Quiz</strong>:</p>

	<dl>
		<dt>Link from My Courses</dt>
		<dd>Will display a link to the test on the My Courses page, in the course listing. Students will be made aware that the current test is available before they enter the course.</dd>
	
		<dt>Release Results</dt>
		<dd>Defines the availability of test results to students, either once the test has been submitted, once submitted and completely marked, or not at all. In the latter case, the Release Results property can be changed to <em>Once quiz has been submitted</em> to make results available to students once all submissions have been marked.</dd>

		<dt>Randomized Questions</dt>
		<dd>Will display the number of questions specified, chosen randomly from the pool of available questions for that test.</dd>
	
		<dt>Start &amp; End Dates</dt>
		<dd>Define the window of time in which the test will be available for taking.  It is possible to define the start date to be in the future, meaning the test will not be available until that date.</dd>
	
		<dt>Assign to Groups</dt>
		<dd>Specifies the groups (as defined in the <a href="enrollment.php">Enrollment Manager</a>) permitted to take this test. By default, tests are available to Everyone in the course.</dd>
	</dl>
	
	<p><strong>Surveys</strong> are the same as regular tests, with the exception that no marks be assigned to questions (and no results be released), and in some cases it might be preferable to treat submissions as <em>Anonymous</em>.  This can be done by choosing Yes from the <em>Anonymous</em> property setting.</p>

	<p>Once the initial properties have been saved, the test or survey will be listed in the Test/Survey Manager.  From here, one can <em>Edit</em> the test properties, <em>Preview</em> the test questions, add <em>Questions</em> to a test, view the <em>Submissions</em> received so far, view the test <em>Statistics</em>, or <em>Delete</em> the test.</p>

<?php require('../common/body_footer.inc.php'); ?>