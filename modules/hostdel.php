<?php

/*
 * LMS version 1.11-cvs
 *
 *  (C) Copyright 2001-2012 LMS Developers
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

$id = $_GET['id'];

if($id && $_GET['is_sure']=='1')
{
	if($DB->Execute('DELETE FROM hosts WHERE id = ?', array($id)))
	{
		if($instances = $DB->GetCol('SELECT id FROM daemoninstances WHERE hostid = ?', array($id)))
		{
			foreach($instances as $instance)
			{
				$DB->Execute('DELETE FROM daemoninstances WHERE id = ?', array($instance));
				$DB->Execute('DELETE FROM daemonconfig WHERE instanceid = ?', array($instance));
			}
		}
	}
}

header('Location: ?m=hostlist');

?>
