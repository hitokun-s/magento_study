<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Api2
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Webservice API2 renderer of JSON type model
 *
 * @category   Mage
 * @package    Mage_Api2
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Mage_Api2_Model_Renderer_Json implements Mage_Api2_Model_Renderer_Interface
{
    /**
     * Adapter mime type
     */
    const MIME_TYPE = 'application/json';

    /**
     * Convert Array to JSON
     *
     * @param array|object $data
     * @return string
     */
    public function render($data)
    {
        return Zend_Json::encode($data);
    }

    /**
     * Get MIME type generated by renderer
     *
     * @return string
     */
    public function getMimeType()
    {
        return self::MIME_TYPE;
    }
}
