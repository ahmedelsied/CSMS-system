<?php
namespace lib\vendor;
trait createdir
{
    public function create_anime_folder($dir)
    {
        !file_exists(UPLOADS_FOLDER.$dir) ?  mkdir(UPLOADS_FOLDER.$dir, 0777, true) : false;
    }
}