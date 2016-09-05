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

// rcrdevnt.php  - Record an event in the database

// Include our configuration and utility functions
include("config.php");

// Require a valid session
session_start();
if (!session_is_registered("SESSION_UID"))
{
    sendRedirect("error.php?ec=1");
    exit;
}

// Create connection to db
$connection = mysql_connect($hostname, $user, $pass) 
    or die ("Unable to connect!");

if(! $submit)
{
    // form has not been submitted yet so display the form

    include("menu.inc");
?>
  <div align="center">
  <table border="0" cellspacing="0" cellpadding="3" width="100%">
   <tr>
    <td bgcolor="#0000A0">
     <b><font face="Arial" color="White">Record Event</font></b>
    </td>
   </tr>
  </table>
  <table border="0" cellspacing="5" cellpadding="5">
   <form action="<? print($PHP_SELF); ?>" method="POST">
    <tr>
     <td align="right" valign="top">Event Type:</td>
     <td align="left" valign="top">
      <select name="event_type">
       <option value="">__ Please Select __</option>
<?

$query = "SELECT t.id, t.name from event_type t order by t.name";
$result = mysql_db_query($database, $query, $connection)
    or die ("Error in query: $query. " . mysql_error());

while(list($id, $name) = mysql_fetch_row($result))
{
    print("<option value=\"$id\">$name</option>\n");
}

?>
      </select>
     </td>
    </tr>
    <tr>
     <td align="right">Event Date:</td>
     <td align="left" valign="top">
<?
    // Calculate today's date for inclusion below.
    $defDate = date("m/d/Y");
?> 
      <input name="event_date" type="text" value="<? print($defDate); ?>">
     </td>
    <tr>
     <td align="right" valign="top">Description:</td>
     <td align="left">
      <input maxlength="200" name="description" type="text">
     </td>
    </tr>
    <tr>
     <td align="right" valign="top">Number of Hours:</td>
     <td align="left">
      <input maxlength="5" name="hours" type="text">
     </td>
    </tr>
    <tr>
     <td align="right" valign="top"></td>
     <td align="left">
      <input name="submit" type="submit" value="Save">
     </td>
    </tr>
   </form>
  </table>
 </div>
	
</body>
</html>
<?
}
else
{
    // Make sure event type and event date are not blank
    if ($event_type == '' || $event_date == '') {
        sendRedirect("error.php?ec=15");
	exit;
    }

    // Attempt to parse the event date.
    if (($timestamp = strtotime($event_date)) === -1) {
      sendRedirect("error.php?ec=16");
      exit;
    } else {
      $event_date = date('Y-m-d H:i:s', $timestamp);
    }

    // If no hours were specified, make sure the insert will still work.
    if ($hours == '') {
        $hours = 'null';
    }

    // INSERT into event
    $query =
        "INSERT INTO event " .
        "  (id, event_type_id, description, event_date, userid, num_hours) " .
        "  VALUES " .
        "  ('', '$event_type', '$description', '$event_date', $SESSION_UID, " .
        "   $hours)";
    $result = mysql_db_query($database, $query, $connection)
        or die ("Error in query: $query. " . mysql_error());

    $message = urlencode("Successfully Recorded Event.");
    sendRedirect("main.php?message=$message");
    mysql_close($connection);
}
?>
