<div class="col-md-12">
  <p>Regular exercise is associated with many health benefits. Increasing physical activity is safe for most people. <br>However, some individuals should check with a physician before they become more physically active.<br><br></p>
  <table class="table table-bordered table-responsive quesnn">
    <tr>
      <th>No.</th>
      <th>Questionnaire</th>

    </tr>
    <tr>
		<td>1</td>
      <td>Have you ever had a stroke or heart condition or has a physician ever indicated you should restrict your physical activity due to these conditions?</td>
      <td class="yes"><?php echo in_array('ansYes0', $parq->questionnaire)?'Yes':'No'; ?></td>

    </tr>
    <tr>
      <td>2</td>
      <td>When at rest or partaking in physical activity do you experience chest pain?</td>
      <td class="yes"><?php echo in_array('ansYes1', $parq->questionnaire)?'Yes':'No'; ?></td>

    </tr>
    <tr>
      <td>3</td>
      <td>During physical activity do you ever feel faint, dizzy or lose your balance?</td>
      <td class="yes"><?php echo in_array('ansYes2', $parq->questionnaire)?'Yes':'No'; ?></td>

    </tr>
   <tr>
      <td>4</td>
      <td>Do you suffer from any breathing disorders or suffered a severe case of asthma that has medical attention in the last 12 months?</td>
      <td class="yes"><?php echo in_array('ansYes3', $parq->questionnaire)?'Yes':'No'; ?></td>

    </tr>
    <tr>
      <td>5</td>
      <td>Do you have insulin dependant diabetes or high blood sugar that has caused complication in the last three months?</td>
      <td class="yes"><?php echo in_array('ansYes4', $parq->questionnaire)?'Yes':'No'; ?></td>
</tr>
    <tr>
      <td>6</td>
      <td>Do you have an injury or orthopaedic condition (such as a back, hip or knee problem) that may worsen due to a change in your physical activity?*</td>
      <td class="yes"><?php echo in_array('ansYes5', $parq->questionnaire)?'Yes':'No'; ?></td>
</tr>
    <tr>
      <td>7</td>
      <td>Are you pregnant or have you given birth in the last 6 weeks?</td>
      <td class="yes"><?php echo in_array('ansYes6', $parq->questionnaire)?'Yes':'No'; ?></td>

    </tr>
    <tr>
      <td>8</td>
      <td>Are you over the age of 69?</td>
      <td class="yes"> <?php echo in_array('ansYes7', $parq->questionnaire)?'Yes':'No'; ?></td>

    </tr>
    <tr>
      <td>9</td>
      <td>Do you know of any other reason why you should not partake in, or increase your current physical activity?</td>
      <td class="yes"><?php echo in_array('ansYes8', $parq->questionnaire)?'Yes':'No'; ?></td>
</tr>


    
    <tr>
      <td colspan="4" class="yes">If you answered "yes" to any of the above questions, we suggest you seek approval/ guidance/support from your physician before commencing with any physical activity or drastic change in your nutrition.</td>
    </tr>
	<tr>
      <td colspan="4" class="no">If you answered "No" to all of the above questions, and there is no other concerns regarding your health you may commence with a structured physical activity regime that takes into account your ability and any restrictions that may be present.</td>
    </tr>
    <tr>
      <td colspan="4">If your health changes and you answer "Yes" to any of the above questions, seek guidance from a physician before commencing 
with activity.</td>
    </tr>
   </table>

    <div class="form-group">
      <label class="strong" for="parqNotes">Please provide any additional notes you think are relevant</label>
       </br>
         {{ $parq->parqNotes ? $parq->parqNotes : '--'}} 
     </div>
</div>