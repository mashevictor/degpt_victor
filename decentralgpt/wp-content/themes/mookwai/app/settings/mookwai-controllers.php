<?php

/**
 * MooKwai theme options - controllers (pending)
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\App\Settings;

defined('ABSPATH') || exit;

class MooKwai_Controllers
{

  // input
  function mk_input($args)
  {
    add_settings_field(
      $args['optionName'],
      $args['theTitle'],
      [$this, 'inputHTML'],
      $args['thePage'],
      $args['theSection'],
      $args
    );
    register_setting(
      $args['optionGroup'],
      $args['optionName'],
      array(
        'sanitize_callback' => 'sanitize_text_field',
        'default' => $args['defaultValue']
      )
    );
  }
  function inputHTML($args)
  { ?>
    <input type="text" name="<?php echo $args['optionName'] ?>" value="<?php echo esc_attr(get_option($args['optionName'])) ?>" class="<?php echo $args['className'] ?>">
    <?php if ($args['theDes']) { ?>
      <p class="description"><?php echo $args['theDes'] ?></p>
    <?php } ?>
  <?php }

  // textarea
  function mk_textarea($args)
  {
    add_settings_field(
      $args['optionName'],
      $args['theTitle'],
      [$this, 'textareaHTML'],
      $args['thePage'],
      $args['theSection'],
      $args
    );
    register_setting(
      $args['optionGroup'],
      $args['optionName'],
      array(
        'sanitize_callback' => 'sanitize_text_field',
        'default' => $args['defaultValue']
      )
    );
  }
  function textareaHTML($args)
  { ?>
    <textarea type="text" name="<?php echo $args['optionName'] ?>" row="5" class="<?php echo $args['className'] ?>"><?php echo get_option($args['optionName']) ?></textarea>
    <?php if ($args['theDes']) { ?>
      <p class="description"><?php echo $args['theDes'] ?></p>
    <?php } ?>
  <?php }

  // script input
  function mk_multiInput($args)
  {
    add_settings_field(
      $args['optionName'],
      $args['theTitle'],
      [$this, 'multiInputHTML'],
      $args['thePage'],
      $args['theSection'],
      $args
    );
    register_setting(
      $args['optionGroup'],
      $args['optionName'],
      array(
        'sanitize_callback' => 'sanitize_text_field',
        'default' => $args['defaultValue']
      )
    );
  }

  function multiInputHTML($args)
  { ?>
    <div class="<?php echo $args['groupClass'] ?>">
      <div class="mk-items-list"></div>
      <input type="hidden" class="mk-items-value-input" id="<?php echo $args['optionName'] ?>" name="<?php echo $args['optionName'] ?>" value="<?php echo esc_attr(get_option($args['optionName'])) ?>">
      <a class="mk-add-item-btn">添加文件</a>
      <?php if ($args['theDes']) { ?>
        <p class="description"><?php echo $args['theDes'] ?></p>
      <?php } ?>
    </div>
  <?php }

  // Checkbox
  function mk_checkbox($args)
  {
    add_settings_field(
      $args['optionName'],
      $args['theTitle'],
      [$this, 'checkboxHTML'],
      $args['thePage'],
      $args['theSection'],
      $args
    );
    register_setting(
      $args['optionGroup'],
      $args['optionName'],
      array(
        'sanitize_callback' => 'sanitize_text_field',
        'default' => $args['defaultValue'],
      )
    );
  }
  function checkboxHTML($args)
  { ?>
    <fieldset>
      <legend class="screen-reader-text"><span><?php echo $args['theTitle'] ?></span></legend>
      <label for="<?php echo $args['optionName'] ?>">
        <input name="<?php echo $args['optionName'] ?>" type="checkbox" id="<?php echo $args['optionName'] ?>" value="1" <?php checked(get_option($args['optionName']), '1') ?>>
        <?php echo $args['theLabel'] ?>
      </label>
    </fieldset>
  <?php }


  // Select
  function mk_select($args)
  {
    add_settings_field(
      $args['optionName'],
      $args['theTitle'],
      [$this, 'selectHTML'],
      $args['thePage'],
      $args['theSection'],
      $args
    );
    register_setting(
      $args['optionGroup'],
      $args['optionName'],
      array(
        'sanitize_callback' => 'sanitize_text_field',
        'default' => $args['defaultValue'],
      )
    );
  }
  function selectHTML($args)
  { ?>
    <select name="<?php echo $args['optionName'] ?>">
      <?php foreach ($args['optionsValue'] as $value => $label) { ?>
        <option value="<?php echo $value ?>" <?php selected(get_option($args['optionName']), $value); ?>><?php echo $label ?></option>
      <?php } ?>
    </select>
<?php }
}
