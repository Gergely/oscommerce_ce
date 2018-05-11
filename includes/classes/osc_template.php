<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  class oscTemplate {
    var $_title;
    var $_blocks = array();
    var $_content = array();
    var $_grid_container_width = 12;
    var $_grid_content_width = BOOTSTRAP_CONTENT;
    var $_grid_column_width = 0; // deprecated
    var $_data = array();
    var $_cookie_modules = array();
    var $_cookie_expiration_time;
    var $_expiration = 365*24*60*60;
    var $_cookies = array();

    function __construct() {
      $this->_title = TITLE;
      $this->_cookie_expiration_time = time()+$this->_expiration;
      $this->consentRead();
    }

    function setGridContainerWidth($width) {
      $this->_grid_container_width = $width;
    }

    function getGridContainerWidth() {
      return $this->_grid_container_width;
    }

    function setGridContentWidth($width) {
      $this->_grid_content_width = $width;
    }

    function getGridContentWidth() {
      return $this->_grid_content_width;
    }

    function setGridColumnWidth($width) {
      $this->_grid_column_width = $width;
    }

    function getGridColumnWidth() {
      return (12 - BOOTSTRAP_CONTENT) / 2;
    }

    function setTitle($title) {
      $this->_title = $title;
    }

    function getTitle() {
      return $this->_title;
    }

    function addBlock($block, $group) {
      $this->_blocks[$group][] = $block;
    }

    function hasBlocks($group) {
      return (isset($this->_blocks[$group]) && !empty($this->_blocks[$group]));
    }

    function getBlocks($group) {
      if ($this->hasBlocks($group)) {
        return implode("\n", $this->_blocks[$group]);
      }
    }

    function buildBlocks() {
      global $language;

      if ( defined('TEMPLATE_BLOCK_GROUPS') && tep_not_null(TEMPLATE_BLOCK_GROUPS) ) {
        $tbgroups_array = explode(';', TEMPLATE_BLOCK_GROUPS);

        foreach ($tbgroups_array as $group) {
          $module_key = 'MODULE_' . strtoupper($group) . '_INSTALLED';

          if ( defined($module_key) && tep_not_null(constant($module_key)) ) {
            $modules_array = explode(';', constant($module_key));

            foreach ( $modules_array as $module ) {
              $class = basename($module, '.php');

              if ( !class_exists($class) ) {
                if ( file_exists('includes/languages/' . $language . '/modules/' . $group . '/' . $module) ) {
                  include('includes/languages/' . $language . '/modules/' . $group . '/' . $module);
                }

                if ( file_exists('includes/modules/' . $group . '/' . $module) ) {
                  include('includes/modules/' . $group . '/' . $module);
                }
              }

              if ( class_exists($class) ) {
                $mb = new $class();

                if ( $mb->isEnabled() ) {
                  $mb->execute();

                  if (method_exists($mb, 'hasCookie') && $mb->hasCookie()) {
                    $this->_cookie_modules[$class] = $mb->getCookies();
                  }
                }
              }
            }
          }
        }

        $this->cookiesBuild();
      }
    }

    function addContent($content, $group) {
      $this->_content[$group][] = $content;
    }

    function hasContent($group) {
      return (isset($this->_content[$group]) && !empty($this->_content[$group]));
    }

    function getContent($group) {
      global $language;

      if ( !class_exists('tp_' . $group) && file_exists('includes/modules/pages/tp_' . $group . '.php') ) {
        include('includes/modules/pages/tp_' . $group . '.php');
      }

      if ( class_exists('tp_' . $group) ) {
        $template_page_class = 'tp_' . $group;
        $template_page = new $template_page_class();
        $template_page->prepare();
      }

      foreach ( $this->getContentModules($group) as $module ) {
        if ( !class_exists($module) ) {
          if ( file_exists('includes/modules/content/' . $group . '/' . $module . '.php') ) {
            if ( file_exists('includes/languages/' . $language . '/modules/content/' . $group . '/' . $module . '.php') ) {
              include('includes/languages/' . $language . '/modules/content/' . $group . '/' . $module . '.php');
            }

            include('includes/modules/content/' . $group . '/' . $module . '.php');
          }
        }

        if ( class_exists($module) ) {
          $mb = new $module();

          if ( $mb->isEnabled() ) {
            $mb->execute();
          }
        }
      }

      if ( class_exists('tp_' . $group) ) {
        $template_page->build();
      }

      if ($this->hasContent($group)) {
        return implode("\n", $this->_content[$group]);
      }
    }

    function getContentModules($group) {
      $result = array();

      foreach ( explode(';', MODULE_CONTENT_INSTALLED) as $m ) {
        $module = explode('/', $m, 2);

        if ( $module[0] == $group ) {
          $result[] = $module[1];
        }
      }

      return $result;
    }

    function consentRead() {
      global $cookie_path, $cookie_domain;

      if (isset($_COOKIE['oscConsent'])) {
        // Retrieve the contents from the cookie
        $result = json_decode(stripslashes($_COOKIE['oscConsent']), true);

        $oscConsent = array();
        foreach ($result as $value) {
          $pieces = explode("|", $value);
          $this->cookies[$pieces[1]] = $pieces[0];
        }
      }
    }

    function cookiesBuild() {
      global $cookie_path, $cookie_domain, $PHP_SELF;

      if (isset($_GET['action'])) {
        switch ($_GET['action']) {
          case 'config_consent' : $this->deleteSensitiveCookie('oscConsent', $cookie_path, $cookie_domain);
                                  $this->saveConsent($_POST['options']);
                                  tep_redirect(tep_href_link($PHP_SELF, tep_get_all_get_params(array('action'))));
                                  break;
        }
      }

      $cookies_array = array();
      $rebuild = false;
      foreach ($this->_cookie_modules as $class => $parameters) {
        $cookies_array[$class] = '1';
        if (!isset($this->cookies[$class])) {
          $rebuild = true;
        }
      }

      if (!isset($_COOKIE['oscConsent']) || $rebuild) {
        if ($rebuild) {
          $this->deleteSensitiveCookie('oscConsent', $cookie_path, $cookie_domain);
        }

        $this->saveConsent($cookies_array);
      }
    }

    function saveConsent($parameters) {
      global $cookie_domain;

      $consents = $this->cookies;
      if (is_array($parameters)) {
        foreach ($parameters as $class => $value) {
          $consents[$class] = ($value ? 'True' : 'False');

          if ( !$value && isset($this->_cookie_modules[$class]['cookie_files']) && is_array($this->_cookie_modules[$class]['cookie_files']) ) {
            foreach ($this->_cookie_modules[$class]['cookie_files'] as $name => $path) {
              if ( isset($_COOKIE[$name]) ) {
                $this->deleteSensitiveCookie($name, $path, $cookie_domain);
              }
            }
          }
        }

      }

      $new_consents = array();
      foreach ($consents as $key => $value) {
        $new_consents[] = (string)$value . '|' . $key;
      }

      $parameters = json_encode($new_consents);
      setcookie("oscConsent", $parameters, $this->_cookie_expiration_time, $cookie_path, $cookie_domain);
    }

    function deleteSensitiveCookie($name, $path, $domain) {
      setcookie($name, '', time()-3600, $path, $domain);
    }

    function getCookieModuleClass($class) {
      return $this->cookies[$class];
    }
  }
?>
