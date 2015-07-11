<?php
/**
 * Dc_Slider
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Dc
 * @package    Dc_Slider
 * @copyright  Copyright (c) 2009-2015 Damián Culotta. (http://www.damianculotta.com.ar/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;

$installer->startSetup();

$installer->run("

CREATE TABLE {$this->getTable('slider/slider')} (
  slider_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(255),
  filename VARCHAR(255) NOT NULL,
  url VARCHAR(255),
  from_date DATE,
  to_date DATE,
  position SMALLINT(6) NOT NULL DEFAULT '0',
  status SMALLINT(6) NOT NULL DEFAULT '1',
  PRIMARY KEY (slider_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE  {$this->getTable('slider/slider_store')} (
  slider_id INT(11) UNSIGNED NOT NULL,
  store_id SMALLINT(5) UNSIGNED NOT NULL,
  PRIMARY KEY (slider_id,store_id),
  KEY FK_SLIDER_STORE_STORE (store_id),
  CONSTRAINT FK_SLIDER_STORE_SLIDER FOREIGN KEY (slider_id) REFERENCES {$this->getTable('slider/slider')} (slider_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT FK_SLIDER_STORE_STORE FOREIGN KEY (store_id) REFERENCES {$this->getTable('core/store')} (store_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup();