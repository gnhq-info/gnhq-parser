<?php
class ParserIKData_Cache_Web extends Lib_Cache_Basic
{
    protected function _getFrontendOptions()
    {
        return array('lifetime' => 86400, 'automatic_serialization' => true);
    }

    protected function _getBackendOptions()
    {
        return array(
            	'hashed_directory_level'  => 2,
            	'automatic_serialization' => true,
            	'hashed_directory_umask'  => 0700,
            	'cache_dir'               => APPLICATION_DIR_ROOT . 'SrcCache' . DIRECTORY_SEPARATOR . 'Web'
        );
    }
}