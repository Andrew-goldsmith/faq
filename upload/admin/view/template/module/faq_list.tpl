<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a onclick="location = '<?php echo $action_insert; ?>'" data-toggle="tooltip" title="<?php echo $text_insert; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <a onclick="$('#form').submit();" data-toggle="tooltip" title="<?php echo $text_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
        <a href="<?php echo $action_cancel; ?>" data-toggle="tooltip" title="<?php echo $text_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list-alt fa-fw"></i><?php echo $text_management_questions; ?></h3>
      </div>
      <div class="tab-pane" style="padding: 15px;">
        <ul class="nav nav-tabs" id="status-questions">
          <?php foreach ($questions_nav as $key=>$tab) { ?>
          <li><a href="#questions-<?php echo $key; ?>" data-toggle="tab"><?php echo $tab['name'] . " (" . $tab['total'] . ")"; if (isset($tab['info'])) { ?> <i class="fa fa-info-circle" style="color: #8fbb6c;" data-toggle="tooltip" title="<?php echo $tab['info']; ?>" aria-hidden="true"></i><?php } ?></a></li>
          <?php } ?>
        </ul>
        <form action="<?php echo $action_delete; ?>" method="post" enctype="multipart/form-data" id="form">
          <div class="tab-content">
            <?php foreach ($questions as $key => $question_list) { ?>
            <div class="tab-pane" id="questions-<?php echo $key; ?>">
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('#questions-<?php echo $key; ?> input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                      <td style="width: 60%;" class="text-left"><?php echo $column_question; ?></td>
                      <td class="text-right"><?php echo $column_sort_order; ?></td>
                      <td class="text-right"><?php echo $column_action; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($question_list) { ?>
                      <?php foreach ($question_list as $question) { ?>
                      <tr>
                        <td class="text-center"><?php if ($question['selected']) { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $question['question_id']; ?>" checked="checked" />
                          <?php } else { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $question['question_id']; ?>" />
                          <?php } ?>
                        </td>
                        <td style="width: 60%;" class="text-left"><?php echo $question['name']; ?></td>
                        <td class="text-right"><?php echo $question['sort_order']; ?></td>
                        <td class="text-right">
                          <?php foreach ($question['action'] as $action) { ?>
                          <a href="<?php echo $action['href']; ?>" data-toggle="tooltip" title="<?php echo $text_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                          <?php } ?>
                        </td>
                      </tr>
                      <?php } ?>
                    <?php } else { ?>
                      <tr>
                        <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
            <?php } ?>
          </div>
        </form>
      </div>
    </div>
    <!--settings-->
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list-alt fa-fw"></i><?php echo $text_management_settings; ?></h3>
      </div>
      <div class="tab-pane" id="settings-block" style="padding: 15px;">
        <ul class="nav nav-tabs">
          <li><a href="#settings" data-toggle="tab"><i class="fa fa-cogs" style="color: red;" data-toggle="tooltip" title="textset" aria-hidden="true"></i> Настройки</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane" id="settings">
            <form action="<?php echo $action_settings; ?>" method="post" enctype="multipart/form-data" id="settings-form">
              <div class="form-group row">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_module_status; ?></label>
                <div class="col-sm-10">
                  <select name="faq_status" id="input-status" class="faq-control">
                    <?php if ($faq_status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 control-label" for="input-notice-status-ID"><?php echo $entry_notice_status;?></label>
                <div class="col-sm-10">
                  <select name="faq_notice_status" id="input-notice-status-ID" class="form-control">
                    <?php if ($faq_notice_status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group row dependence <?php echo (!$faq_notice_status) ? 'lock' : ''; ?>">
                <label class="col-sm-2 control-label" for="input-default-email-ID"><?php echo $entry_default_email;?></label>
                <div class="col-sm-10">
                  <input type="checkbox" name="faq_default_email" id="input-default-email-ID" data-email-config="<?php echo $notice_email_config; ?>" data-email-custom="<?php echo $notice_email_custom; ?>" <?php echo ($faq_default_email) ? 'checked' : ''; ?>>
                </div>
                <?php if(!$faq_notice_status) { ?>
                <div class="lock-glass"></div>
                <?php } ?>
              </div>
              <div class="form-group row dependence <?php echo (!$faq_notice_status) ? 'lock' : ''; ?>">
                <label class="col-sm-2 control-label" for="input-notice-email-ID"><?php echo $entry_notice_email;?></label>
                <div class="col-sm-10">
                  <input type="text" name="faq_notice_email" id="input-notice-email-ID" value="<?php echo $faq_notice_email; ?>" style="width: 300px;" <?php echo ($faq_default_email) ? 'disabled' : ''; ?>>
                </div>
                <?php if(!$faq_notice_status) { ?>
                <div class="lock-glass"></div>
                <?php } ?>
              </div>
              <style type="text/css">
                .lock {
                  position: relative;
                }
                .lock-glass {
                  position: absolute;
                  top: 0;
                  left: 0;
                  width: 100%;
                  height: 100%;
                  background: rgba(255, 255, 255, .5);
                }
              </style>
              <script type="text/javascript">
                'use strict';
                (function () {
                  $('#input-notice-status-ID').change(function() {
                    if($(this).val() !== '1') {
                      $('.dependence').each(function() {
                        if(!$(this).hasClass('lock')) {
                          $(this).addClass('lock');
                          $(this).append('<div class="lock-glass"></div>');
                        }
                        $(this).find('input').prop("disabled", true);
                      });
                    } else {
                      $('.dependence').each(function() {
                        if($(this).hasClass('lock')) {
                          $(this).removeClass('lock');
                        }
                        $(this).find('.lock-glass').remove();
                        $(this).find('input').prop("disabled", false);
                      });
                    }
                  });
                  $('#input-default-email-ID').click(function() {
                    if(!$(this).is(':checked')) {
                      $('#input-notice-email-ID').prop("disabled", false).val($(this).attr('data-email-custom'));
                    } else {
                      $('#input-notice-email-ID').prop("disabled", true).val($(this).attr('data-email-config'));
                    }
                  });
                  //fix before save remove disabled on email input (for correct save)
                  $('#settings-form').submit(function() {
                    $('*:disabled').each(function() {
                      $(this).prop("disabled", false);
                    });
                  });
                }());
              </script>
              <button type="submit" class="btn btn-success" form="settings-form"><i class="fa fa-save"></i> <?php echo $text_save_settings; ?></button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $('#status-questions a:first').tab('show');
  $('#settings-block .nav-tabs a:first').tab('show');
</script>
<?php echo $footer; ?>