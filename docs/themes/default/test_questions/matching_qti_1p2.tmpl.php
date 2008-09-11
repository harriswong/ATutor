<!-- matching question with partial marks -->
		<item title="Mathcing question">
			<itemmetadata>
				<qmd_itemtype>Matching</qmd_itemtype>
				<qtimetadata>
					<qtimetadatafield>
						<fieldlabel>qmd_itemtype</fieldlabel>
						<fieldentry>Logical Groups</fieldentry>
					</qtimetadatafield>
				</qtimetadata>
			</itemmetadata>
			<presentation>
				<material>
					<mattext texttype="text/html"><?php echo $this->row['question']; ?></mattext>
				</material>
				<flow>
					<?php for ($i=0; $i < $this->num_choices; $i++): ?>		
					<response_grp ident="RESPONSE<?php echo $i; ?>" rcardinality="Multiple">
						<material>
							<mattext texttype="text/html"><?php echo $this->row['choice_'.$i]; ?></mattext>
						</material>
						<render_choice shuffle="No">
						<?php for ($j=0; $j < $this->num_options; $j++): ?>
							<response_label ident="Option<?php echo $j; ?>">	
								<flow_mat>
									<material>
										<mattext texttype="text/html"><?php echo $this->row['option_'.$j]; ?></mattext>
									</material>
								</flow_mat>
							</response_label>
						<?php endfor; ?>
						</render_choice>
					</response_grp>
					<?php endfor; ?>
				</flow>
			</presentation>


			<resprocessing>
				<outcomes>
					<decvar/>
				</outcomes>
			<?php for ($i=0; $i < $this->num_choices; $i++): ?>
				<?php if ($this->row['answer_'.$i] > -1): ?>
				<respcondition title="CorrectResponse">
					<conditionvar>						
						<varequal respident="RESPONSE<?php echo $i; ?>">Option<?php echo $this->row['answer_'.$i]; ?></varequal>
					</conditionvar>
					<setvar varname="Respondus_Correct" action="Add"><?php echo (isset($this->row['weight']))?$this->row['weight']:1; ?></setvar>
				</respcondition>
				<?php endif; ?>
			<?php endfor; ?>
			</resprocessing>
		<?php if ($this->row['feedback']): ?>
			<itemfeedback ident="FEEDBACK" view="All">
				<material>
					<mattext texttype="text/html"><?php echo $this->row['feedback']; ?></mattext>
				</material>
			</itemfeedback>
		<?php endif; ?>
		</item>
