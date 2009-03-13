<?php

/**
 * Adds the needed resources to web response.
 */
function _add_ajax_load_resources()
{
  $sf_response = sfContext::getInstance()->getResponse();
  $sf_response->addJavascript("/sf/prototype/js/prototype");
  $sf_response->addStylesheet("/pmAjaxLoadPlugin/css/ajaxload.css");
}

/**
 * This function obtains a value for specified search string.
 *
 * @param array $options An array of options
 * @param string $key A search string
 *
 * @return string
 */
function _get_ajax_load_option_value($options, $key, $default_value = null)
{
  $value = $default_value;
  if (isset($options[$key]))
    $value = $options[$key];
  return $value;
}

/**
 * This function calculates if the given image is valid as the built-in images.
 *
 * @param string $image the image url (ex. "circle-ball.gif")
 *
 * @return boolean
 */
function _available_image($image)
{
  return file_exists(sfConfig::get("sf_web_dir")."/pmAjaxLoadPlugin/images/".$image);
}

/**
 * This function builds the markup for an ajax load. This ajax load is displayed while the page is loading.
 *
 * @params array $options an array of options
 *
 * @returns string
 */
function ajax_load($options = array())
{
  use_helper("Javascript");
  _add_ajax_load_resources();

  $title = _get_ajax_load_option_value($options, "title", "Loading");
  $image = _get_ajax_load_option_value($options, "image", "circle-ball.gif");
  $help = _get_ajax_load_option_value($options, "help", "Please wait while the page is loading.");
  $background_color = _get_ajax_load_option_value($options, "background-color", "#000");
  $opacity = _get_ajax_load_option_value($options, "opacity", "0.85");

  $html = content_tag("div", null, array("id" => "ajax-load-background", "style" => "background-color: $background_color; opacity: $opacity"));
  $html .= tag("div", array("id" => "ajax-load"), true);
  $html .= content_tag("div", __($title), array("id" => "ajax-load-title"));
  if (_available_image($image))
    $html .= image_tag("/pmAjaxLoadPlugin/images/$image");
  else
    $html .= image_tag($image);
  $html .= content_tag("div", __($help), array("id" => "ajax-load-help"));
  $html .= tag("/div", array(), true);

  $html .= javascript_tag("Event.observe(window, 'load', function() { $('ajax-load-background').hide(); $('ajax-load').hide(); });");

  return $html;
}

function ajax_unload($options = array())
{
  use_helper("Javascript");
  _add_ajax_load_resources();

  $title = _get_ajax_load_option_value($options, "title", "Loading");
  $image = _get_ajax_load_option_value($options, "image", "circle-ball.gif");
  $help = _get_ajax_load_option_value($options, "help", "Please wait while the page is loading.");
  $background_color = _get_ajax_load_option_value($options, "background-color", "#000");
  $opacity = _get_ajax_load_option_value($options, "opacity", "0.85");
  $submit_id = _get_ajax_load_option_value($options, "submit_id", null);

  $html = content_tag("div", null, array("id" => "ajax-load-background", "style" => "display: none; background-color: $background_color; opacity: $opacity"));
  $html .= tag("div", array("id" => "ajax-load", "style" => "display: none;"), true);
  $html .= content_tag("div", __($title), array("id" => "ajax-load-title"));
  if (_available_image($image))
    $html .= image_tag("/pmAjaxLoadPlugin/images/$image");
  else
    $html .= image_tag($image);
  $html .= content_tag("div", __($help), array("id" => "ajax-load-help"));
  $html .= tag("/div", array(), true);

  $html .= javascript_tag("$('$submit_id').observe('submit', function() { $('ajax-load-background').show(); $('ajax-load').show(); })");
  $html .= javascript_tag("Event.observe(window, 'unload', function() { $('ajax-load-background').hide(); $('ajax-load').hide(); });");

  return $html;
}
