<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-option" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo (isset($text_send)) ? $text_send : ''; ?></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i><?php echo $text_edit_guestion; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-option" class="form-horizontal">
            <div class="tab-pane">
                <ul class="nav nav-tabs" id="language">
                    <?php foreach ($languages as $language) { ?>
                    <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                    <?php } ?>
                </ul>
                <div class="tab-content">
                    <?php foreach ($languages as $language) { ?>
                    <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                        <div class="form-group required">
                            <label class="col-sm-2 control-label"><?php echo $entry_question; ?>:</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                                    <input type="text" name="question_description[<?php echo $language['language_id']; ?>][question]" value="<?php echo isset($question_description[$language['language_id']]) ? $question_description[$language['language_id']]['question'] : ''; ?>" placeholder="<?php echo $entry_question; ?>" class="form-control" />
                                </div>
                                <?php if (isset($error_title[$language['language_id']])) { ?>
                                <div class="text-danger"><?php echo $error_title[$language['language_id']]; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label"><?php echo $entry_answer; ?>:</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                                    <textarea id="question_description-<?php echo $language['language_id']; ?>" name="question_description[<?php echo $language['language_id']; ?>][answer]" placeholder="<?php echo $entry_answer; ?>" class="form-control" ><?php echo isset($question_description[$language['language_id']]) ? $question_description[$language['language_id']]['answer'] : ''; ?></textarea>
                                </div>
                                <?php if (isset($error_answer[$language['language_id']])) { ?>
                                <div class="text-danger"><?php echo $error_answer[$language['language_id']]; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                <div class="col-sm-10">
                    <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                    <select name="publish_status" id="input-status" class="form-control">
                        <?php if ($publish_status == '1') { ?>
                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                        <option value="0"><?php echo $text_disabled; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_enabled; ?></option>
                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <?php if(isset($text_send)) { ?>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_answer_to_user; ?>:</label>
                <div class="col-sm-10">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                        <textarea id="answer-to-user" name="answer_to_user" placeholder="<?php echo $entry_answer; ?>" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $text_interested_email; ?></label>
                <div class="col-sm-10">
                    <br>
                    <?php echo $interested_email; ?>
                    <input type="hidden" name="answer" value="<?php echo $interested_email; ?>" id="input-answer" class="form-control" />
                </div>
            </div>
            <?php } ?>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    $('#language a:first').tab('show');
</script>
<?php echo $footer; ?>