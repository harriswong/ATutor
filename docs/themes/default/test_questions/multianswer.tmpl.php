<p><?php echo AT_print($this->row['question'], 'tests_questions.question'); ?></p>

<?php
	echo '<input type="hidden" name="answers['.$this->row['question_id'].'][]" value="-1" />';
	for ($i=0; $i < 10; $i++) {
		if ($this->row['choice_'.$i] != '') {
			if ($i > 0) {
				echo '<br/>';
			}

			echo '<input type="checkbox" name="answers['.$this->row['question_id'].'][]" value="'.$i.'" id="choice_'.$this->row['question_id'].'_'.$i.'" /><label for="choice_'.$this->row['question_id'].'_'.$i.'">'.AT_print($this->row['choice_'.$i], 'tests_questions.choice_'.$i).'</label>';
		}
	}
?>