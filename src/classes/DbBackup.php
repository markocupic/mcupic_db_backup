<?php

namespace MCupic;


/**
 * MyDbBackup
 *
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * @package my_db_backup
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */
class DbBackup extends \System
{

    public function doDbBackup()
    {

        $host = $GLOBALS['TL_CONFIG']['dbHost'];
        $user = $GLOBALS['TL_CONFIG']['dbUser'];
        $pw = $GLOBALS['TL_CONFIG']['dbPass'];
        $db = $GLOBALS['TL_CONFIG']['dbDatabase'];
        $keepBackupFiles = intval($GLOBALS['TL_CONFIG']['dbBackup']['keepBackupFiles']) > 0 ? intval($GLOBALS['TL_CONFIG']['dbBackup']['keepBackupFiles']) * 60 * 90 * 24 : 60 * 90 * 24 * 60; //default 60 days

        $filename = 'contao_db_backup' . date("Y_m_d") . '.sql';
        $backupDir = $GLOBALS['TL_CONFIG']['uploadPath'] . '/contao_db_backup';
        $src = $backupDir . '/' . $filename;
        new \Folder($backupDir);

        //Wenn Backup schon existiert, dann weiter
        if (!file_exists(TL_ROOT . '/' . $src))
        {
            // Delete old files
            $arrFiles = scan(TL_ROOT . '/' . $backupDir);
            foreach ($arrFiles as $strFile)
            {
                if (strncmp('.', $strFile, 1) !== 0 && is_file(TL_ROOT . '/' . $backupDir . '/' . $strFile))
                {
                    $objFile = new \File($backupDir . '/' . $strFile);
                    if($objFile->mtime > 0)
                    {
                        if(time() - $objFile->mtime > $keepBackupFiles)
                        {
                            $objFile->delete();
                        }
                    }
                }
            }

            //SQL-Dump
            if (strlen($pw))
            {
                $sqlcommand = 'mysqldump -h ' . $host . ' -u ' . $user . ' -p' . $pw . ' ' . $db . ' > ' . TL_ROOT . '/' . $src;
                exec($sqlcommand);
                if (file_exists(TL_ROOT . '/' . $src))
                {
                    \Dbafs::addResource($src, true);
                }
            }


        }
    }
}
