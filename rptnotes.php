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
drawstatusbar("Report Notes");
?>

<table align="center" border="1">
 <tr>
  <th>Date</th>
  <th>Note</th>
 </tr>

<?
// Set up MySQL connection
$connection = mysql_connect($hostname, $user, $pass) 
    or die ("Unable to connect!");

// Do the report query
$query = "SELECT notes.note, notes.time " .
         "FROM notes " .
         "WHERE notes.userid = $SESSION_UID " .
         "ORDER BY notes.time";
$result = mysql_db_query($database, $query, $connection) 
    or die ("Error in query: $query. " . mysql_error());
while(list($note,$time) = mysql_fetch_row($result))
{
    print("<tr><td align=\"right\" valign=\"top\">$time</td>");

    # Do regex here to turn all \n's into <br />'s
    $note = ereg_replace("\n", "<br />", $note);
    print("<td align=\"left\" valign=\"top\">$note</td></tr>");
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
