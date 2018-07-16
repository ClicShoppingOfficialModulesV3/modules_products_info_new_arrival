<?php
/**
 * pi_products_info_new_arrival.php 
 * @copyright Copyright 2008 - http://www.innov-concept.com
 * @Brand : ClicShopping(Tm) at Inpi all right Reserved
 * @license GPL 2 License & MIT Licence

 */

  use ClicShopping\OM\Registry;
  use ClicShopping\OM\CLICSHOPPING;

  class pi_products_info_new_arrival {
    public $code;
    public $group;
    public $title;
    public $description;
    public $sort_order;
    public $enabled = false;

    public function __construct() {
      $this->code = get_class($this);
      $this->group = basename(__DIR__);

      $this->title = CLICSHOPPING::getDef('module_products_info_new_arrival');
      $this->description = CLICSHOPPING::getDef('module_products_info_new_arrival_description');

      if (defined('MODULE_PRODUCTS_INFO_NEW_ARRIVAL_STATUS')) {
        $this->sort_order = MODULE_PRODUCTS_INFO_NEW_ARRIVAL_SORT_ORDER;
        $this->enabled = (MODULE_PRODUCTS_INFO_NEW_ARRIVAL_STATUS == 'True');
      }
    }

    public function execute() {

      if (isset($_GET['products_id']) && isset($_GET['Products']) ) {
        $content_width = (int)MODULE_PRODUCTS_INFO_NEW_ARRIVAL_CONTENT_WIDTH;
        $text_position = MODULE_PRODUCTS_INFO_NEW_ARRIVAL_POSITION;

        $CLICSHOPPING_ProductsCommon = Registry::get('ProductsCommon');
        $CLICSHOPPING_Template = Registry::get('Template');

        if (MODULE_PRODUCTS_INFO_NEW_ARRIVAL_STATUS == 'True') {

          $size_button = $CLICSHOPPING_ProductsCommon->getSizeButton(MODULE_PRODUCTS_INFO_NEW_ARRIVAL_SIZE);
          $new_arrival = $CLICSHOPPING_ProductsCommon->getProductsNewArrival();

          $products_new_arrival_content = '<!-- Start image_new_arrival -->' . "\n";

          ob_start();
          require($CLICSHOPPING_Template->getTemplateModules($this->group . '/content/products_info_new_arrival'));
          $products_new_arrival_content .= ob_get_clean();

          $products_new_arrival_content .= '<!-- end image_new_arrival -->' . "\n";

          $CLICSHOPPING_Template->addBlock($products_new_arrival_content, $this->group);
        }
      }
    } // public function execute

    public function isEnabled() {
      return $this->enabled;
    }

    public function check() {
      return defined('MODULE_PRODUCTS_INFO_NEW_ARRIVAL_STATUS');
    }

    public function install() {
      $CLICSHOPPING_Db = Registry::get('Db');

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Souhaitez-vous activer ce module ?',
          'configuration_key' => 'MODULE_PRODUCTS_INFO_NEW_ARRIVAL_STATUS',
          'configuration_value' => 'True',
          'configuration_description' => 'Souhaitez vous activer ce module à votre boutique ?',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Veuillez selectionner la largeur de l\'affichage?',
          'configuration_key' => 'MODULE_PRODUCTS_INFO_NEW_ARRIVAL_CONTENT_WIDTH',
          'configuration_value' => '12',
          'configuration_description' => 'Veuillez indiquer un nombre compris entre 1 et 12',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_content_module_width_pull_down',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'A quel endroit souhaitez-vous afficher le code barre ?',
          'configuration_key' => 'MODULE_PRODUCTS_INFO_NEW_ARRIVAL_POSITION',
          'configuration_value' => 'none',
          'configuration_description' => 'Affiche le code barre du produit à gauche ou à droite<br><br><i>(Valeur Left = Gauche <br>Valeur Right = Droite <br>Valeur None = Aucun)</i>',
          'configuration_group_id' => '6',
          'sort_order' => '2',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'float-md-right\', \'float-md-left\', \'float-md-none\'),',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Veuillez indiquer la taille du bouton ?',
          'configuration_key' => 'MODULE_PRODUCTS_INFO_NEW_ARRIVAL_SIZE',
          'configuration_value' => 'lg',
          'configuration_description' => 'Affiche une taille détermiée',
          'configuration_group_id' => '6',
          'sort_order' => '2',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'lg\', \'md\', \'sm\', \'xs\'),',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Ordre de tri d\'affichage',
          'configuration_key' => 'MODULE_PRODUCTS_INFO_NEW_ARRIVAL_SORT_ORDER',
          'configuration_value' => '100',
          'configuration_description' => 'Ordre de tri pour l\'affichage (Le plus petit nombre est montré en premier)',
          'configuration_group_id' => '6',
          'sort_order' => '3',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );

      return $CLICSHOPPING_Db->save('configuration', ['configuration_value' => '1'],
                                              ['configuration_key' => 'WEBSITE_MODULE_INSTALLED']
                            );
    }

    public function remove() {
      return Registry::get('Db')->exec('delete from :table_configuration where configuration_key in ("' . implode('", "', $this->keys()) . '")');
    }

    public function keys() {
      return array (
        'MODULE_PRODUCTS_INFO_NEW_ARRIVAL_STATUS',
        'MODULE_PRODUCTS_INFO_NEW_ARRIVAL_CONTENT_WIDTH',
        'MODULE_PRODUCTS_INFO_NEW_ARRIVAL_POSITION',
        'MODULE_PRODUCTS_INFO_NEW_ARRIVAL_SIZE',
        'MODULE_PRODUCTS_INFO_NEW_ARRIVAL_SORT_ORDER'
      );
    }
  }
