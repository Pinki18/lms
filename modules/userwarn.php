<?php

/*
 * LMS version 1.6-cvs
 *
 *  (C) Copyright 2001-2005 LMS Developers
 *
 *  Please, see the doc/AUTHORS for more information about authors!
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License Version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307,
 *  USA.
 *
 *  $Id$
 */

$setwarnings = isset($_POST['setwarnings']) ? $_POST['setwarnings'] : array();

if(isset($setwarnings['muserid']))
{
	$warnon = isset($setwarnings['warnon']) ? $setwarnings['warnon'] : FALSE;
	$warnoff = isset($setwarnings['warnoff']) ? $setwarnings['warnoff'] : FALSE;
	$message = isset($setwarnings['message']) ? $setwarnings['message'] : '';
	
	foreach($setwarnings['muserid'] as $uid)
	{
		if($warnon)
			$LMS->NodeSetWarnU($uid, TRUE);
		if($warnoff) 
			$LMS->NodeSetWarnU($uid, FALSE);
		
		if($message)
		{
			$LMS->SetTS('users');
			$LMS->DB->Execute('UPDATE users SET message=? WHERE id=?', array($message, $uid));
		}
	}

	$SESSION->save('warnmessage', $message);
	$SESSION->save('warnon', $warnon);
	$SESSION->save('warnoff', $warnoff);
	
	$SESSION->redirect('?'.$SESSION->get('backto'));
}

$SESSION->save('backto', $_SERVER['QUERY_STRING']);

$layout['pagetitle'] = trans('Warnings');

$userlist = $LMS->DB->GetAll(
		    'SELECT users.id AS id, MAX(warning) AS warning, '.
		    $LMS->DB->Concat('UPPER(lastname)',"' '",'users.name').' AS username 
		    FROM users LEFT JOIN nodes ON users.id = ownerid WHERE deleted = 0 
		    GROUP BY users.id, lastname, users.name ORDER BY username ASC');

$SMARTY->assign('warnmessage', $SESSION->get('warnmessage'));
$SMARTY->assign('warnon', $SESSION->get('warnon'));
$SMARTY->assign('warnoff', $SESSION->get('warnoff'));
$SMARTY->assign('userlist',$userlist);
$SMARTY->display('userwarnings.html');

?>
