<?php
class Lib_File_Access
{
    public function write ($filename, $data)
    {
        $f = fopen($filename, 'wb+');
        if (!$f) {
            throw new Exception('Cant write to file '.$filename);
        }
        if (!flock($f, LOCK_EX)) {
            throw new Exception('Cant lock file '.$filename);
        }

        fwrite($f, $data);

        flock($f, LOCK_UN);

        fclose($f);
    }

    public function read ($filename)
    {

    }
}