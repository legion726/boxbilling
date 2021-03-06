<?php
/**
 * BoxBilling
 *
 * @copyright BoxBilling, Inc (http://www.boxbilling.com)
 * @license   Apache-2.0
 *
 * Copyright BoxBilling, Inc
 * This source file is subject to the Apache-2.0 License that is bundled
 * with this source code in the file LICENSE
 */

namespace Box\Mod\Theme\Api;
class Admin extends \Api_Abstract
{

    /**
     * Get list of available client area themes
     * 
     * @return array 
     */
    public function get_list($data)
    {
        $themes = $this->getService()->getThemes();
        return array('list'=>$themes);
    }

    /**
     * Get theme by code
     * 
     * @param string $code - theme code
     * 
     * @return array 
     */
    public function get($data)
    {
        if(!isset($data['code'])) {
            throw new \Box_Exception('Theme code is missing');
        }

        return $this->getService()->loadTheme($data['code']);
    }

    /**
     * Set new theme as default
     * 
     * @param string $code - theme code
     * @return bool
     */
    public function select($data)
    {
        if(!isset($data['code'])) {
            throw new \Box_Exception('Theme code is missing');
        }
        $theme = $this->getService()->getTheme($data['code']);

        $systemService = $this->di['mod_service']('system');
        if($theme->isAdminAreaTheme()) {
            $systemService->setParamValue('admin_theme', $data['code']);
        } else {
            $systemService->setParamValue('theme', $data['code']);
        }

        $this->di['logger']->info('Changed default theme');
        return true;
    }

    /**
     * Delete theme preset
     *
     * @param string $code - theme code
     * @param string $preset - theme preset code
     *
     * @return bool
     */
    public function preset_delete($data)
    {
        if(!isset($data['code'])) {
            throw new \Box_Exception('Theme code is missing');
        }
        if(!isset($data['preset'])) {
            throw new \Box_Exception('Theme preset name is missing');
        }

        $service = $this->getService();

        $theme = $service->getTheme($data['code']);
        $service->deletePreset($theme, $data['preset']);

        return true;
    }

    /**
     * Select new theme preset
     *
     * @param string $code - theme code
     * @param string $preset - theme preset code
     *
     * @return bool
     */
    public function preset_select($data)
    {
        if(!isset($data['code'])) {
            throw new \Box_Exception('Theme code is missing');
        }
        if(!isset($data['preset'])) {
            throw new \Box_Exception('Theme preset name is missing');
        }

        $service = $this->getService();
        $theme = $service->getTheme($data['code']);
        $service->setCurrentThemePreset($theme, $data['preset']);

        return true;
    }
}