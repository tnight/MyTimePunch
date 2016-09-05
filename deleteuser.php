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


// deleteuser.php 

// Include our configuration and utility functions
include("config.php");

// check for valid session
session_start();
if (!session_is_registered("SESSION_UID"))
{
    sendRedirect("error.php?ec=1");
    exit;
}

// open a connection to the database
$connection = mysql_connect($hostname, $user, $pass) or die ("Unable to connect!");

if(!$submit)
{
// form has not been submitted yet -> display form

	// Check to see if user is admin
	$query = "SELECT admin FROM users WHERE id = '$SESSION_UID' and admin = '1'";
        $result = mysql_db_query($database, $query, $connection) or die ("Error in query: $query. " . mysql_error());
	if(mysql_num_rows($result) <= 0)        
		{
			sendRedirect("error.php?ec=11");
			exit;
		}
	include("menu.inc");
?>
	
	<center>
	<table width="100%" border="0" cellspacing="0" cellpadding="3">
	<tr>
	<td bgcolor="#0000A0">
	<b><font face="Arial" color="White">Delete User</font></b>
	</td>
	</tr>
	</table>
	
	<table border="0" cellspacing="5" cellpadding="5">
	<form action="<? print($PHP_SELF); ?>" method="POST">

        <tr>
        <td><b>User</b></td>
        <td colspan=3><select name="item">

<?
        // query to get a list of users
        $query = "SELECT id, username FROM users ORDER BY username";
        $result = mysql_db_query($database, $query, $connection) or die ("Error in query: $query. " . mysql_error());

                while(list($myid, $myusername) = mysql_fetch_row($result))
                {
                    print("<option value=\"$myid\">$myusername</option>");
                }

        mysql_free_result ($result);
?>

	</select>
	<tr>
	<td colspan="4" align="center"><input type="Submit" name="submit" value="Delete User"></td>
	</tr>
	
	</form>
	</table>
	</center>
	
	</body>
	</html>
<?
}
else
{
// form has been submitted -> process data

	// Delete from users
	$query = "DELETE FROM users where id='$item'";
	$result = mysql_db_query($database, $query, $connection) or die ("Error in query: $query. " . mysql_error());
	
		
	// DELETE from status
	$query = "DELETE FROM status where userid='$item'";
	$result = mysql_db_query($database, $query, $connection) or die ("Error in query: $query. " . mysql_error());

	// DELETE from timein
	$query = "DELETE FROM timein where userid='$item'";
	$result = mysql_db_query($database, $query, $connection) or die ("Error in query: $query. " . mysql_error());
	
	// DELETE from timeout
	$query = "DELETE FROM timeout where userid='$item'";
	$result = mysql_db_query($database, $query, $connection) or die ("Error in query: $query. " . mysql_error());

	// DELETE from event
	$query = "DELETE FROM event where userid='$item'";
	$result = mysql_db_query($database, $query, $connection) or die ("Error in query: $query. " . mysql_error());

	// back to main page
	$message = urlencode("User successfully deleted");
	sendRedirect("main.php?message=$message");
	mysql_close($connection);

}
?>
