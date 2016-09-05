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

// check to ensure valid session, else redirect
session_start();
if (!session_is_registered("SESSION_UID"))
{
    sendRedirect("error.php?ec=1");
    exit;
}

// includes
include("menu.inc");

?>
<html>
<head>
<basefont face="Verdana">
</head>
<body bgcolor="White">

<?
drawstatusbar("Report Events");
?>

<table align="center" border="1">
 <tr>
  <th>Date</th>
  <th>Event Type</th>
  <th>Description</th>
  <th>Number of Hours</th>
 </tr>

<?
// Set up MySQL connection
$connection = mysql_connect($hostname, $user, $pass) 
    or die ("Unable to connect!");

// Do the report query
$query = "SELECT e.event_date, t.name, e.description, e.num_hours " .
         "FROM event e, event_type t " .
         "WHERE e.userid = $SESSION_UID " .
         "AND e.event_type_id = t.id " .
         "ORDER BY e.event_date, t.name";
$result = mysql_db_query($database, $query, $connection) 
    or die ("Error in query: $query. " . mysql_error());
while(list($event_date, $name, $description, $hours) = mysql_fetch_row($result))
{
    print("<tr><td align=\"left\" valign=\"top\">$event_date</td>");
    print("<td align=\"left\" valign=\"top\">$name</td>");
    print("<td align=\"left\" valign=\"top\">$description</td>");
    print("<td align=\"left\" valign=\"top\">$hours</td></tr>");
}

print("</table>");

// clean up
mysql_free_result ($result);
mysql_close($connection);
?>
</table>
</center>         
</body>
</html>
