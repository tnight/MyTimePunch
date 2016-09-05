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

// error.php - assign error messages from $ec

// includes
include("config.php");

switch ($ec)
{
// login failure
case 0:
$message = "There was an error logging you in. <a href=\"index.php\">Please try again.</a>";
break;

// session problem
case 1:
$message = "Please <a href=\"index.php\">log in</a> again.";
break;

// malformed variable/failed query
case 2:
$message = "There was an error performing the requested action. Please <a href=\"index.php\">log in</a> again.";
break;

// No rights
case 11:
$message = "You are not authorized to do that.";
break;

// rights not assigned
case 12:
$message = "User already exists. <a href=\"adduser.php\">Try Again</a>";
break;

// blank username
case 13:
$message = "Must enter a username.";
break;

// blank note
case 14:
$message = "Must enter a note.";
break;

// blank event type or date
case 15:
$message = "Must select an event type and enter an event date.";
break;

// invalid event date
case 16:
$message = "Cannot parse event date.";
break;

default:
$message = "There was an error performing the requested action. Please <a href=index.php>log in</a> again.";
break;

}


?>
<html>
<head>
<basefont face="Verdana">
</head>

<body bgcolor="White">

<!-- main menu -->
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
<? include ("menu.inc"); ?>
</tr>
</table>


<table width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
<td bgcolor="#0000A0">
<b><font face="Arial" color="White">Error</font></b>
</td>
</tr>
</table>

<p>
<? print($message); ?>
</body>
</html>
