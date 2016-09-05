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

if ($submit) {

    // Check login and password

    // Connect and execute query
    $connection = mysql_connect($hostname, $user, $pass)
        or die ("Unable to connect!");

    $query = "SELECT id, username, password " .
             "FROM users " .
             "WHERE username = '$frmuser' " .
             "AND password = PASSWORD('$frmpass')";
    $result = mysql_db_query($database, $query, $connection)
        or die ("Error in query: $query. " . mysql_error());

    // If row exists, login/pass is correct
    if (mysql_num_rows($result) == 1) {
        // Initiate a session
        session_start();

        // Register the user's ID
        session_register("SESSION_UID");
        list($id, $username, $password) = mysql_fetch_row($result);
        $SESSION_UID = $id;
        $_SESSION['username'] = $username;

        // Redirect to main page
        sendRedirect("main.php");
    }
    else {
        // Login/pass check failed, so redirect to error page
        sendRedirect("error.php?ec=0");
    }

    // Clean up and close connection
    mysql_free_result ($result);	
    mysql_close($connection);
    exit;
}
else {
    include("menu.inc");
?>
  <div align="center">

   <table width="100%" border="0" cellspacing="0" cellpadding="3">
    <tr>
     <td bgcolor="#0000A0">
      <b><font face="Arial" color="White">Login</font></b>
     </td>
    </tr>
   </table>

   <form action="<? print($_SERVER['PHP_SELF']); ?>" method="POST">
    <table border="0" cellspacing="5" cellpadding="5">
     <tr>
      <td align="center" colspan="2">
       <p>Welcome to the MyTimePunch time clock program.<br />
       Please log in to begin using the system's powerful features.</p>
      </td>
     </tr>
     <tr>
      <td align="right" valign="top">Username</td>
      <td align="left" valign="top"><input type="Text" name="frmuser" size="15"></td>
     </tr>
     <tr>
      <td align="right" valign="top">Password</td>
      <td align="left" valign="top">
       <input type="password" name="frmpass" size="15">
      </td>
     </tr>
     <tr>
      <td align="right" valign="top"></td>
      <td align="left" valign="top">
       <input type="submit" name="submit" value="Login">
      </td>
     </tr>
    </table>
   </form>
  </div>
 </body>
</html>

<?
}
?>
