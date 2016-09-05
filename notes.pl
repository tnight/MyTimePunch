#!/usr/bin/perl -w
#
# Program:  notes.pl
# Language: Perl (Microsoft Windows or Unix-like environment)
#
# This program parses hours spent from a project notes file and tabulates
# the hours spent.
#
#
#===========================================================================
# Date       Programmer        Changes
# ------------------------------------------------
# 12/23/2002 Terry Nightingale Created (Branched from projtime.pl)
#
# 99/99/9999 ...               ...
#
#===========================================================================

### use Date::Manip;
use FileHandle;
use strict;

main();

sub main {

    # Needed by Date::Manip.
    $ENV{'TZ'} = 'PST8PDT';

    my $notesFileName = "notes.txt";

    # Attempt to open our input data file.
    my $notesFile = FileHandle->new("$notesFileName")
    	or die("Could not open input file $notesFileName: $!\n");

    my @lines = ();
    my ($start, $stop, $desc);

    while (<$notesFile>) {
        # Skip lines that do not begin with ">>".
        next if not (m/^>>/);
        
        # Attempt to find start and stop times.
        
        # Print matching lines.
        print;
	}

}
