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

$connection = mysql_connect($hostname, $user, $pass) 
    or die ("Unable to connect!");
$query .= "SELECT id from users";
$result = mysql_db_query($database, $query, $connection)
    or die ("Error in query: $query. " . mysql_error());
?>
<html>
<head>
<basefont face="Verdana">
</head>
<body bgcolor="White">

<?

drawstatusbar("Report Time");

// query to show item
print("<table align=center border=1>");
print("<th>Status</th><th>In</th><th>Out</th>");
print("<tr>");
print("<td align=center valign=top>");
print("<table border=0>");
$query = "SELECT status.status FROM status WHERE status.userid=$SESSION_UID";
$result = mysql_db_query($database, $query, $connection) 
    or die ("Error in query: $query. " . mysql_error());
while(list($status) = mysql_fetch_row($result))
{
    print("<tr><td>$status</td></tr>");
}

print("</table>");
print("</td>");
print("<td align=center valign=top>");
print("<table border=0>");
$query = "SELECT time FROM timein WHERE userid=$SESSION_UID";
$result = mysql_db_query($database, $query, $connection) 
    or die ("Error in query: $query. " . mysql_error());
while(list($timein) = mysql_fetch_row($result))
{        
    $newtime=fixDate($timein);
    print("<tr><td><pre>$newtime</pre></td></tr>");
}
print("</table>");
print("</td>");
print("<td align=center valign=top>");
print("<table border=0>");
$query = "SELECT time FROM timeout WHERE userid=$SESSION_UID";
$result = mysql_db_query($database, $query, $connection)
    or die ("Error in query: $query. " . mysql_error());
while(list($timeout) = mysql_fetch_row($result))
{
    $newtime=fixDate($timeout);
    print("<tr><td><pre>$newtime</pre></td></tr>");
}
print("</table>");
print("</td>");
print("</tr>");
print("</table>");


// clean up
mysql_free_result ($result);
mysql_close($connection);
?>
</table>
</center>         
</body>
</html>
