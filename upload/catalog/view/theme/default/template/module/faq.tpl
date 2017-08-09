<h1><span><?php echo $heading_title; ?></span></h1>
<div class="row">
  <div class="col-md-9">
    <?php if (isset($questions) && $questions) { ?>
    <div class="container_comments panel-group" id="container_comments-ID" role="tablist" aria-multiselectable="false">
      <?php foreach ($questions as $number => $question) { ?>
      <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="panel-heading_<?php echo $question['id']; ?>">
          <div class="panel-title">
            <a role="button" data-toggle="collapse" data-parent="container_comments-ID" href="#body_<?php echo $question['id'];?>" aria-expanded="false" class="collapsed">
              <?php echo $question['question_text']; ?><span class="arrow"></span>
            </a>
          </div>
        </div>
        <div id="body_<?php echo $question['id'];?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="labelby_<?php echo $question['id']; ?>">
          <div class="panel-body"><?php echo $question['answer_text']; ?></div>
        </div>
      </div>
      <?php } ?>
    </div>
    <?php } else { ?>
    <!-- <noindex> -->
    <div class="mgn-b20"><?php echo $text_no_comments; ?></div>
    <!-- </noindex> -->
    <?php } ?>
  </div>
  <div class="col-md-3">
    <div class="orange-border">
      <h3 class="heading-thin upper"><?php echo $text_faq_heading; ?></h3>
      <p class="faq-descr text-gray"><?php echo $text_faq_description ?></p>
      <form id="question-form-ID" class="mgn-t10">
        <div class="form-group">
          <label for="questionUserName-ID"><?php echo $entry_name; ?></label>
          <input type="text" name="question_user_name" class="form-control" id="questionUserName-ID" value="<?php echo ($logged) ? $customer_firstname : '';?>" placeholder="">
        </div>
        <div class="form-group">
          <label for="questionEmail-ID"><?php echo $entry_email; ?></label>
          <input type="email" name="question_email" class="form-control" id="questionEmail-ID" value="<?php echo ($logged) ? $customer_email : '';?>" placeholder="">
        </div>
        <div class="form-group">
          <label for="questionText-ID"><?php echo $entry_question; ?></label>
          <textarea name="question_text" class="form-control" id="questionText-ID"></textarea>
        </div>
        <div class="text-center">
          <button type="submit" class="btn standart-black btn-transition"><?php echo $text_submit; ?></button>
        </div>
      </form>
    </div>
  </div>
</div>