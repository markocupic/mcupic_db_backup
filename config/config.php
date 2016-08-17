<?php

/**
 * DbBackup
 *
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * @package my_db_backup
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


$GLOBALS['TL_HOOKS']['generatePage'][] = array('MCupic\DbBackup', 'doDbBackup');
