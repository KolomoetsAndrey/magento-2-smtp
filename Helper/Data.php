<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_Smtp
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\Smtp\Helper;

use Magento\Store\Model\ScopeInterface;
use Mageplaza\Core\Helper\AbstractData;

/**
 * Class Data
 * @package Mageplaza\Smtp\Helper
 */
class Data extends AbstractData
{
    const CONFIG_MODULE_PATH = 'smtp';
    const CONFIG_GROUP_SMTP  = 'configuration_option';
    const DEVELOP_GROUP_SMTP = 'developer';
    const GENERAL_GROUP_SMTP = 'general';

    /**
     * @param string $code
     * @param null $storeId
     * @return mixed
     */
    public function getSmtpConfig($code = '', $storeId = null)
    {
        $code = ($code !== '') ? '/' . $code : '';

        return $this->getModuleConfig(self::CONFIG_GROUP_SMTP . $code, $storeId);
    }

    /**
     * @param string $code
     * @param null $storeId
     * @return mixed
     */
    public function getDeveloperConfig($code = '', $storeId = null)
    {
        $code = ($code !== '') ? '/' . $code : '';

        return $this->getModuleConfig(self::DEVELOP_GROUP_SMTP . $code, $storeId);
    }

    /**
     * @param string $code
     * @param null $storeId
     * @return mixed
     */
    public function getGeneralConfig($code = '', $storeId = null){
        $code = ($code !== '') ? '/' . $code : '';

        return $this->getModuleConfig(self::GENERAL_GROUP_SMTP . $code, $storeId);
    }

    /**
     * @param bool $decrypt
     * @return array|mixed
     */
    public function getTestPassword($decrypt = false)
    {
        if ($storeCode = $this->_request->getParam('store')) {
            $password = $this->getSmtpConfig('password', $storeCode);
        } else if ($websiteCode = $this->_request->getParam('website')) {
            $passwordPath = self::CONFIG_MODULE_PATH . '/' . self::CONFIG_GROUP_SMTP . '/password';
            $password     = $this->getConfigValue($passwordPath, $websiteCode, ScopeInterface::SCOPE_WEBSITE);
        } else {
            $password = $this->getSmtpConfig('password');
        }

        if($decrypt){
            /** @var \Magento\Framework\Encryption\EncryptorInterface $encryptor */
            $encryptor = $this->getObject(\Magento\Framework\Encryption\EncryptorInterface::class);

            return $encryptor->decrypt($password);
        }

        return $password;
    }
}
