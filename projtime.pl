#!/usr/local/bin/perl -w
#
# Program:  projtime.pl
# Language: Perl (Microsoft Windows or Unix-like environment)
#
# This program imports data from an IronWorx project time file and
# tabulates the hours spent, suitable for copying and pasting into an
# invoice.
#
#
#===========================================================================
# Date       Programmer        Changes
# ------------------------------------------------
# 02/07/2000 Terry Nightingale Created
#
# 05/18/2000 Terry Nightingale Changed to properly handle multi-line
#                              descriptions for time spent.
#
#===========================================================================

use Date::Manip;
use strict;

{

    # Needed by Date::Manip.
    $ENV{'TZ'} = 'PST8PDT';

    my $timeFile = "h:/txt/projtime.txt";

    # Attempt to open our input data file.
    open(TDV, "$timeFile")
	or die "Could not open input file $timeFile for reading: $!\n";

    # Junk the two header lines.
    <TDV>;
    <TDV>;

    while (<TDV>) {
	# We start looking for data when we find '*' in column one.
	last if m/^\*/;
    }

    my @lines = ();
    my ($start, $stop, $desc);

    while (<TDV>) {

	chomp;

	# Skip blank lines.
	next if not $_;

	# We are done looking if we find '*' in column one.
	last if m/^\*/;

	if (m/^(\d+-\w+-\d+ \d+:\d+)\s+(\d+-\w+-\d+ \d+:\d+)\s+(.*)$/) {
	    # Save away the start & stop times, and the description.
	    ($start, $stop, $desc) = ($1, $2, $3);
	}
	else {
	    # Skip incomplete lines.
	    next if (m/^\d+-\w+-\d+ \d+:\d+\s+\d+-\w+-\d+\s+[^\s].*$/);

	    # If we didn't find a start or stop time, look for just a
	    # description on a line by itself.  The assumption is that
	    # it's a continuation of a description from the previous
	    # line.
	    m/^\s+([^\s].*)$/;
	    ($start, $stop, $desc) = ('', '', $1);
	}

	next if (! $desc);

	# Save our time data.
	push (@lines, [($start, $stop, $desc)]);
    }

    my $dateError = undef;

    my $hourlyRate   = 60;
    my $totalHours   = 0;
    my $totalDollars = 0;

    my $line;
    foreach $line (@lines) {
	($start, $stop, $desc) = @{$line};

	if ($start && $stop) {

	    my $delta = Date::Manip::DateCalc($start, $stop, \$dateError);
	    die "Date error $dateError.\n" if defined $dateError;

	    my $hours = Date::Manip::Delta_Format($delta, 2, '%hd');

	    $totalHours += $hours;

	    printf("%s   %5.2f   %s\n",
                Date::Manip::UnixDate($start, "%m/%d"), $hours, $desc);
	}
	else {
	    print ' 'x16, $desc, "\n";
	}
    }

    $totalDollars = $totalHours * $hourlyRate;

    print "\n", '-' x 70, "\n";
    printf("Total   %5.2f   @ \$%d/hr%s\$%8.2f\n",
        $totalHours, $hourlyRate, ' ' x 36, $totalDollars);
}
