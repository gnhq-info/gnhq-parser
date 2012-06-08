<?php
class Lib_Config_IniFile extends Lib_Config_Base
{
    /**
     * (non-PHPdoc)
     * @see Lib_Config_Base::_loadPropertiesFromSource()
     */
    protected function _loadPropertiesFromSource($params)
    {
        if (is_scalar($params)) {
            $fileName = $params;
        } elseif (is_array($params)) {
            if (count($params) === 1) {
                $fileName = current($params);
            } else {
                if (isset($params['iniFile'])) {
                    $fileName = $params['iniFile'];
                }
            }
        }
        if (!isset($fileName)) {
            throw new Exception('No config file!');
        }
        if (!file_exists($fileName)) {
            throw new Exception('File dont exist');
        }
        $data = parse_ini_file($fileName);
        if (!$data || !is_array($data)) {
            throw new Exception('Bad config file');
        }

        return $data;
    }
}