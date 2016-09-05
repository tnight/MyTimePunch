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

// adduser.php  - Add a new user to the database

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

    // Check to see if the user is an admin
    $query = "SELECT admin FROM users WHERE id = '$SESSION_UID' and admin = '1'";
    $result = mysql_db_query($database, $query, $connection) 
        or die ("Error in query: $query. " . mysql_error());
    if(mysql_num_rows($result) <= 0)        
    {
        sendRedirect("error.php?ec=4");
	exit;
    }

    include("menu.inc");
	
?>
 <center>
  <table width="100%" border="0" cellspacing="0" cellpadding="3">
   <tr>
    <td bgcolor="#0000A0">
     <b><font face="Arial" color="White">Add New User</font></b>
    </td>
   </tr>
  </table>
  <table border="0" cellspacing="5" cellpadding="5">
   <form action="<? print($PHP_SELF); ?>" method="POST">
    <tr>
     <td><b>Username</b></td>
	 <td colspan=3><input name="username" type="text"></td>
    </tr>
    <tr>
	 <td><b>Password</b></td>
	 <td colspan=3><input name="password" type="password">
    </tr>
    <tr>
     <td><b>Admin?</b></td>
	 <td colspan=3><input name="admin" type="checkbox" value="1">
    </tr>
    <tr>
     <td colspan="4" align="center"><input type="Submit" name="submit" value="Add User"></td>
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
    // form was submitted

    // DEBUG: FOO TEST
    $message = urlEncode("Foo Test");
    sendRedirect("main.php?message=$message");
    mysql_close($connection);
    exit;

    // make sure username is not blank
    if ($username==''){
        sendRedirect("error.php?ec=13");
	exit;
    }
    // Check to make sure user does not already exist
    $query = "SELECT username FROM users WHERE username = '$username'";
    $result = mysql_db_query($database, $query, $connection) 
        or die ("Error in query: $query. " . mysql_error());
	
    // If the above statement returns more than 0 rows, the user exists, so display error
    if(mysql_num_rows($result) > 0)
    {
        sendRedirect("error.php?ec=12");
	exit;
    }
    else
    {
        // INSERT into users
        $query = "INSERT INTO users (id,username, password, admin) VALUES('','$username', password('$password'), '$admin')";
	$result = mysql_db_query($database, $query, $connection) 
	    or die ("Error in query: $query. " . mysql_error());
		
	$newuserid = mysql_insert_id($connection);
		
	// INSERT into status
	$query = "INSERT INTO status (userid, status) VALUES($newuserid, 'Out')";
	$result = mysql_db_query($database, $query, $connection) 
	    or die ("Error in query: $query. " . mysql_error());
		
	// back to main page
	$message = urlencode("User successfully added");
	sendRedirect("main.php?message=$message");
	mysql_close($connection);
    }
}
?>
