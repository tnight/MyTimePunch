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

// makenote.php  - Add a new note to the database

// Include our configuration and utility functions
include("config.php");

// Require a valid session
session_start();
if (!session_is_registered("SESSION_UID"))
{
    sendRedirect("error.php?ec=1");
    exit;
}

if(! $submit)
{
    // form has not been submitted yet so display the form

    include("menu.inc");
?>
 <div align="center">
  <table width="100%" border="0" cellspacing="0" cellpadding="3">
   <tr>
    <td bgcolor="#0000A0">
     <b><font face="Arial" color="White">Make Note</font></b>
    </td>
   </tr>
  </table>
  <table border="0" cellspacing="5" cellpadding="5">
   <form action="<? print($PHP_SELF); ?>" method="POST">
    <tr>
     <td align=center valign=top>
      Note:<br>
      <textarea name="note" cols="72" rows="25" wrap="virtual"></textarea>
     </td>
    </tr>
    <tr>
     <td align="center">
      <input type="submit" name="submit" value="Save">
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
    // Make sure note is not blank
    if ($note=='') {
        sendRedirect("error.php?ec=14");
	exit;
    }

    // Create connection to db
    $connection = mysql_connect($hostname, $user, $pass) 
        or die ("Unable to connect!");

    // INSERT into notes
    $query = "INSERT INTO notes (id, note, time, userid) VALUES ('', '$note', NOW(), $SESSION_UID)";
    $result = mysql_db_query($database, $query, $connection)
        or die ("Error in query: $query. " . mysql_error());

    $message = urlencode("Successfully Made Note");
    sendRedirect("main.php?message=$message");
    mysql_close($connection);
}
?>
