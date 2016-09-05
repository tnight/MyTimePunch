<!-- main menu -->
<!--	Copyright (C) 2002  Stephen Lawrence Jr.

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
-->

<html>
 <head>
  <basefont face="Verdana">
  <title><? print($sitename . " " . $version); ?></title>
 </head>
 <body bgcolor="White">
  <h2><? print($sitename); ?></h2>
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
   <tr>
    <td align="left"><a href="main.php">Home</a></td>
<?
if (session_is_registered("SESSION_UID")) {
?>
    <td align="center">
     <? print("Logged in as: <b>" . $_SESSION['username'] . "</b>"); ?>
    </td>
    <td align="right"><a href="logout.php">Logout</a></td>
<?
}
else {
?>
    <td align="center"></td>
    <td align="right">Logout</td>
<?
}
?>
    </td>
   </tr>
  </table>
