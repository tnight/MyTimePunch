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

// config.php - Variables and Functions here

// database settings
// $database="db39492";
$database="mytimepunch";
$user = "mytimepunch";
$pass = "foobar";
$hostname = "localhost";

// Used in the browser window
$version = "1.1";
$sitename = "MyTimePunch";

// Begin FUNCTIONS

// Function to draw the status bar across the screen. Pass it the
// message as an argument.
function drawstatusbar($messages)
{
    if (!$messages) {
        $messages="Locations";
    }
    print('<td bgcolor="#0000A0" align="right"><b>');
    print('<font size="-1" face="Arial" color="White">');
    if ($message) {
        print("<i>Previous message: $message</i>");
    }
    print('</td></font></b>');
    print('<center>');
    print('<table width="100%" border="0" cellspacing="0" cellpadding="3">');
    print('<tr>');
    print('<td bgcolor="#0000A0">');
    print("<b><font face=\"Arial\" color=\"White\">$messages</font></b>");
    print('</td>');
    print('</tr>');
    print('</table>');
    print('</center>');
}

// function to format mySQL DATETIME values
function fixDate($val)
{
    //split it up into components
    $arr = explode(" ", $val);
    $timearr = explode(":", $arr[1]);
    $datearr = explode("-", $arr[0]);
    // create a timestamp with mktime(), format it with date()
    return date("D d-M-Y H:i", mktime($timearr[0], $timearr[1],
        $timearr[2], $datearr[1], $datearr[2], $datearr[0]));
}

function sendRedirect($uri) 
{
    header("Location: http://" . $_SERVER['HTTP_HOST']
	. dirname($_SERVER['PHP_SELF']) . "/" . $uri);
}

?>
