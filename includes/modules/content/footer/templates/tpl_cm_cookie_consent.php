<div class="col-sm-<?php echo $content_width; ?>">
  <div class="footerbox cookie_consent">
    <h2><?php echo MODULE_CONTENT_FOOTER_COOKIE_CONSENT_MODAL_BLOCK_TITLE; ?></h2>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#cookieModal">
  <?php echo MODULE_CONTENT_FOOTER_COOKIE_CONSENT_MODAL_BUTTON_TITLE; ?>
</button>

<div id="cookieModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  <?php echo tep_draw_form('modal_cookies_config', tep_href_link($PHP_SELF, tep_get_all_get_params(array('action')) .'action=config_consent', 'SSL')); ?>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="gridSystemModalLabel"><?php echo MODULE_CONTENT_FOOTER_COOKIE_CONSENT_MODAL_TITLE; ?></h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <h5 class="modal-title"><?php echo MODULE_CONTENT_FOOTER_COOKIE_CONSENT_TITLE_STRICT; ?></h5>
            <div class="col-md-9"><?php echo MODULE_CONTENT_FOOTER_COOKIE_CONSENT_TITLE_OSCOMMERCE; ?></div>
            <div class="col-md-3">
              <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-success btn-sm active">
                  <input type="radio" name="options" id="oscommerce1" autocomplete="off" checked>
                  <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                </label>
                <label class="btn btn-disabled btn-sm" disabled>
                  <input type="radio" name="options" id="oscommerce2" autocomplete="off">
                  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                </label>
              </div>
            </div>
            <div class="col-md-9"><?php echo MODULE_CONTENT_FOOTER_COOKIE_CONSENT_TITLE_CONSENT_COOKIE; ?></div>
            <div class="col-md-3">
              <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-success btn-sm active">
                  <input type="radio" name="options" id="consent1" autocomplete="off" checked>
                  <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                </label>
                <label class="btn btn-disabled btn-sm" disabled>
                  <input type="radio" name="options" id="consent2" autocomplete="off">
                  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <h5 class="modal-title"><?php echo MODULE_CONTENT_FOOTER_COOKIE_CONSENT_TITLE_THIRD_PARTIES; ?></h5>
            <?php
            foreach ($oscTemplate->_cookie_modules as $class => $parameters) {
              if ($parameters['cookie_group'] == 'third') {
            ?>
            <div class="col-md-9"><?php echo $parameters['title']; ?></div>
            <div class="col-md-3">
              <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-success btn-sm<?php echo ($oscTemplate->getCookieModuleClass($class) == "True" ? ' active' : ''); ?>">
                  <input type="radio" name="options[<?php echo $class; ?>]" id="option_<?php echo $class; ?>_1" autocomplete="off" value="1" <?php echo ($oscTemplate->getCookieModuleClass($class) == "True" ? 'checked' : ''); ?>>
                  <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                </label>
                <label class="btn btn-success btn-sm<?php echo ($oscTemplate->getCookieModuleClass($class) == "False" ? ' active' : ''); ?>">
                  <input type="radio" name="options[<?php echo $class; ?>]" id="option_<?php echo $class; ?>_2" autocomplete="off" value="0" <?php echo ($oscTemplate->getCookieModuleClass($class) == "False" ? 'checked' : ''); ?>>
                  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                </label>
              </div>
            </div>
            <?php
              }
            }
            ?>
          </div>
          <div class="col-md-12">
            <h5 class="modal-title"><?php echo MODULE_CONTENT_FOOTER_COOKIE_CONSENT_TITLE_FUNCTIONAL; ?></h5>
            <?php
            foreach ($oscTemplate->_cookie_modules as $class => $parameters) {
              if ($parameters['cookie_group'] == 'functional') {
            ?>
            <div class="col-md-9"><?php echo $parameters['title']; ?></div>
            <div class="col-md-3">
              <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-success btn-sm<?php echo ($oscTemplate->getCookieModuleClass($class) == "True" ? ' active' : ''); ?>">
                  <input type="radio" name="options[<?php echo $class; ?>]" id="option_<?php echo $class; ?>_1" autocomplete="off" value="1" <?php echo ($oscTemplate->getCookieModuleClass($class) == "True" ? 'checked' : ''); ?>>
                  <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                </label>
                <label class="btn btn-success btn-sm<?php echo ($oscTemplate->getCookieModuleClass($class) == "False" ? ' active' : ''); ?>">
                  <input type="radio" name="options[<?php echo $class; ?>]" id="option_<?php echo $class; ?>_2" autocomplete="off" value="0" <?php echo ($oscTemplate->getCookieModuleClass($class) == "False" ? 'checked' : ''); ?>>
                  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                </label>
              </div>
            </div>
            <?php
              }
            }
            ?>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12"><?php echo MODULE_CONTENT_FOOTER_COOKIE_CONSENT_MODAL_CONTENT; ?></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo IMAGE_BUTTON_CONTINUE; ?></button>
        <?php echo tep_draw_button(IMAGE_BUTTON_UPDATE, 'glyphicon glyphicon-user', null, 'primary', null, 'btn btn-primary'); ?>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  </form>
</div><!-- /.modal -->
  </div>
</div>

