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

// Include config and functions
include("config.php");

// check to ensure valid session, else redirect
session_start();
if (!session_is_registered("SESSION_UID"))
{
    sendRedirect("error.php?ec=1");
    exit;
}

// Include Main Menu
include("menu.inc");

// Get database connection
$connection = mysql_connect($hostname, $user, $pass) 
    or die ("Unable to connect!");

// Query to get status
$query = "SELECT users.admin, status.status " .
         "FROM users, status " .
         "WHERE users.id = status.userid " .
         "AND status.userid = $SESSION_UID";
$result = mysql_db_query($database, $query, $connection) 
    or die ("Error in query: $query. " . mysql_error());
list($isAdmin, $status) = mysql_fetch_row($result);

// Clean up
mysql_free_result($result);
mysql_close($connection);
?>

<table border="0" cellspacing="0" cellpadding="3" width="100%">
 <tr>
  <td bgcolor="0000a0">
   <b><font face="Arial" color="White">
    Status: <? print($status); ?>
   </font></b>
  </td>
  <!-- If last operation returned a status code, display here -->
  <td bgcolor="0000a0" align="right">
   <b><font size="-1" face="Arial" color="White">
    <? 
if ($message) { 
    print("<i>Last message: $message</i>"); 
} 
    ?>
   </font></b>
  </td>
 </tr>
</table>

<p></p>

<table align="center" border="0" width="80%">
 <tr>

  <td align="center" valign="top">

   <!-- BEGIN Clock Menu -->
   <table align="center" bgcolor="ccccff" border="1"
   cellpadding="3" cellspacing="3">
    <tr>
     <td align="center" bgcolor="ffffff"><b>Clock</b></td>
    </tr>
    <tr>
     <td align="center" valign="top">
      <br />
<?
if ($status == "In") {
?>
      <font color="gray">Clock In</font>
<?
}
else {
?>
      <a href="commitchange.php?in=In">Clock In</a>
<?
}
?>
     </td>
    </tr>
    <tr>
     <td align="center" valign="top">
      <br />
<?
if ($status == "In") {
?>
      <a href="commitchange.php?out=out">Clock Out</a>
<?
}
else {
?>
      <font color="gray">Clock Out</font>
<?
}
?>

     </td>
    </tr>
    <tr>
     <td align="center" valign="top">
      <br /><a href="report.php">Report Time</a> 
     </td>
    </tr>
   </table>
   <!-- END Clock Menu -->

  </td>

  <td align="center" valign="top">

   <!-- BEGIN Event Menu -->
   <table align="center" bgcolor="ccccff" border="1"
   cellpadding="3" cellspacing="3">
    <tr>
     <td align="center" bgcolor="ffffff"><b>Events</b></td>
    </tr>
    <tr>
     <td align="center" valign="top">
      <br /><a href="rcrdevnt.php">Record Event</a> 
     </td>
    </tr>
    <tr>
     <td align="center" valign="top">
      <br /><a href="rptevnts.php">Report Events</a>
     </td>
    </tr>
   </table>
   <!-- END Event Menu -->

  </td>

  <td align="center" valign="top">

   <!-- BEGIN Notes Menu -->
   <table align="center" bgcolor="ccccff" border="1"
   cellpadding="3" cellspacing="3">
    <tr>
     <td align="center" bgcolor="ffffff"><b>Notes</b></td>
    </tr>
    <tr>
     <td align="center" valign="top">
      <br /><a href="makenote.php">Make Note</a> 
     </td>
    </tr>
    <tr>
     <td align="center" valign="top">
      <br /><a href="rptnotes.php">Report Notes</a> 
     </td>
    </tr>
   </table>
   <!-- END Notes Menu -->

  </td>

<?
if ($isAdmin == 1) {
?>

  <td align="center" valign="top">

   <!-- BEGIN Admin Menu -->
   <table align="center" bgcolor="ccccff" border="1"
   cellpadding="3" cellspacing="3">
    <tr>
     <td align="center" bgcolor="ffffff"><b>Admin</b></td>
    </tr>
    <tr>
     <td align="center" valign="top">
      <br /><a href="adduser.php">Add User</a> 
     </td>
    </tr>
    <tr>
     <td align="center" valign="top">
      <br /><a href="deleteuser.php">Delete User</a> 
     </td>
    </tr>
   </table>
   <!-- END Admin Menu -->

  </td>

<?
}
?>

 </tr>
</table>

</body>
</html>
