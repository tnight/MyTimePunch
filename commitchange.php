<?
/* Copyright (C) 2002  Stephen Lawrence Jr.

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Include our configuration and utility functions
include("config.php");

// check for valid session
session_start();
if (!session_is_registered("SESSION_UID"))
{
    sendRedirect("error.php?ec=1");
    exit;
}

$connection = mysql_connect($hostname, $user, $pass)
    or die ("Unable to connect!");

// Clock-in pressed 
if($in){
    // Check to make sure not already in 
    $query = "SELECT status from status where userid=$SESSION_UID";
    $result = mysql_db_query($database, $query, $connection) 
        or die ("Error in query: $query. " . mysql_error());
    while(list($status) = mysql_fetch_row($result))
    {
        if ($status =="In"){
	    $message = urlencode("Already Clocked In");
	    mysql_close($connection);
	    sendRedirect("main.php?message=$message");
	}
    }

    // INSERT into timein 
    $query = "INSERT INTO timein (id,time, userid) VALUES ('',NOW(), $SESSION_UID)";
    $result = mysql_db_query($database, $query, $connection) 
        or die ("Error in query: $query. " . mysql_error());
        
    // INSERT into status
    $query = "UPDATE status set status='In' WHERE userid='$SESSION_UID'";
    $result = mysql_db_query($database, $query, $connection) 
        or die ("Error in query: $query. " . mysql_error());

    $itemid = mysql_insert_id($connection);
    $message = urlencode("Successfully Clocked In");
    mysql_close($connection);
    sendRedirect("main.php?message=$message");
}
// If clock-out was pressed
elseif($out){
    // Check to make sure not already out
    $query = "SELECT status from status where userid=$SESSION_UID";
    $result = mysql_db_query($database, $query, $connection) 
        or die ("Error in query: $query. " . mysql_error());
    while(list($status) = mysql_fetch_row($result))
    {
        if ($status =="Out"){
	    $message = urlencode("Already Clocked Out");
	    mysql_close($connection);
	    sendRedirect("main.php?message=$message");
	}
    }
    // INSERT into timeout
    $query = "INSERT INTO timeout (id,time, userid) VALUES ('',NOW(), $SESSION_UID)";
    $result = mysql_db_query($database, $query, $connection) 
        or die ("Error in query: $query. " . mysql_error());

    // INSERT into status
    $query = "UPDATE status set status='Out' WHERE userid=$SESSION_UID";
    $result = mysql_db_query($database, $query, $connection) 
        or die ("Error in query: $query. " . mysql_error());
        
    mysql_close($connection);
    $message = urlencode("Successfully Clocked Out");
    sendRedirect("main.php?message=$message");
}
else{
    mysql_close($connection);
    $message = urlencode("Nothing To Do");
    sendRedirect("main.php?message=$message");
    exit;
}
?>

