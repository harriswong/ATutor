<p><?php echo AT_print($this->row['question'], 'tests_questions.question'); ?></p>

<ul style="padding: 0px; margin: 0px; list-style-type: none">
	<?php for ($i=0; $i < 10; $i++): ?>
		<?php if ($this->row['choice_'.$i] != ''): ?>
			<li style="padding: 4px; display: inline">
				<input type="radio" name="answers[<?php echo $this->row['question_id']; ?>]" value="<?php echo $i; ?>" id="choice_<?php echo $this->row['question_id'].'_'.$i; ?>" /><label for="choice_<?php echo $this->row['question_id'].'_'.$i; ?>"><?php echo AT_print($this->row['choice_'.$i], 'tests_answers.answer'); ?></label>
			</li>
		<?php endif; ?>
	<?php endfor; ?>
	<li style="padding: 4px; display: inline">
		<input type="radio" name="answers[<?php echo $this->row['question_id']; ?>]" value="-1" id="choice_<?php echo $this->row['question_id']; ?>_x" checked="checked" /><label for="choice_<?php echo $this->row['question_id']; ?>_x"><em><?php echo _AT('leave_blank'); ?></em></label>
	</li>
</ul>