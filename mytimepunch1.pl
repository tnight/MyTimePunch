#!/cygdrive/c/perl/bin/perl -w
#---------------------------------------------------------------------------
#
# $Id: mytimepunch1.pl,v 1.2 2003/04/17 16:41:11 terryn Exp $
#
# Program:  mytimepunch1.pl
# Language: Perl (Microsoft Windows or Unix-like environment)
#
# This program queries a MyTimePunch database and calculates hours
# spent against "expected" hours, roughly based on a forty (40) hour
# work week.
#
# $Log: mytimepunch1.pl,v $
# Revision 1.2  2003/04/17 16:41:11  terryn
# Structured the code into logical subroutines and made other minor improvements.
#
# Revision 1.1  2003/04/02 19:51:31  terryn
# Initial checkin of program to calculate "expected" and actual hours
# from the MyTimePunch database.  This revision can query the timein and
# timeout tables for a given user and, optionally, a given date range,
# dumping the data found and totalling the number of hours.
#
# The major functionality planned for future revisions is the actual
# calculation of expected hours.  To start with, this would be something
# really simple like using Date::Manip's business day functionality.
# Then I'd like to add support for querying the event table and taking
# holiday/sick/vacation events into account when calculating expected
# hours.
#
# Eventually, I'd like to enhance the event table to include the number
# of hours of duration for the events.  Then, I could use that value in
# the expected hours calculation and get even more accurate.
#
#===========================================================================

# Gain access to all the pragmas and modules we'll need.
use strict;
use DBI;
use Date::Manip;
use File::Basename;
use Getopt::Long;

# Forward-declare our subroutines.
sub main();
sub validateOptions($);
sub fetchTimeData($);
sub calcExpectedHours($);
sub calcActualHours($$$);

# Define constants we need.
$::PROGNAME = basename($0);
$::USAGE = <<END;
usage: $::PROGNAME [-h|--help] --user username [--start-date date]
  [--end-date date]

Display a report of work hours for a MyTimePunch user, optionally
starting from a specific date.


OPTIONS

--help: Display this usage message.

--user: The MyTimePunch user for which work hours should be displayed.

--start-date: The date from which the report will start.  Defaults to
              the first day of the year, i.e. January 1st.

--end-date: The date at which the report will end.  Defaults to
            tomorrow's date.


NOTES

Dates can be in any reasonably decipherable format.

END


# Call the main subroutine.
main();

sub main()
{
    # Needed by Date::Manip.
    $ENV{'TZ'} = 'PST8PDT';

    # Get and validate our command-line options.
    my $opts = validateOptions($::USAGE);

    # Get the time data from the database.
    my ($timein, $timeout) = fetchTimeData($opts);

    # Calculate the "expected" hours for the period in question.
    my $expectedHours = calcExpectedHours($opts);

    # Calculate the total hours represented, up to two decimal places.
    my $actualHours = calcActualHours($opts, $timein, $timeout);

    # Print our final totals.
    printf("\nTotal expected hours: %9.2f\n", $expectedHours);
    printf("\nTotal actual hours:   %9.2f\n\n", $actualHours);
}

sub validateOptions($)
{
    my $usage = shift;

    my $opts = {};
    my $optsOk = GetOptions($opts,
                            'end-date=s',
                            'help|h',
                            'start-date=s',
                            'user=s');
    die($usage) if (! $optsOk || ! exists($opts->{'user'}));

    # Set the default start date.
    if (! exists($opts->{'start-date'})) {
        $opts->{'start-date'} = 'jan 1';
    }

    # Set the default end date.
    if (! exists($opts->{'end-date'})) {
        $opts->{'end-date'} = 'tomorrow';
    }

    # Format the begin and end dates for the database.
    my $startDate = UnixDate(ParseDate($opts->{'start-date'}), '%Y-%m-%d');
    my $endDate = UnixDate(ParseDate($opts->{'end-date'}), '%Y-%m-%d');

    # Validate (har-har) our starting and ending dates.
    die("Error: Invalid start date '$opts->{'start-date'}'.  Stopped")
      if (! $startDate);
    die("Error: Invalid end date '$opts->{'end-date'}'.  Stopped")
      if (! $endDate);
    die('Error: Start date must not be after end date.  Stopped')
      if ($startDate gt $endDate);

    # Put the standardized dates into the options hash so we can
    # conveniently pass them around.
    $opts->{'start-date'} = $startDate;
    $opts->{'end-date'} = $endDate;

    return $opts;
}

sub fetchTimeData($)
{
    my $opts = shift;

    # Connect to the database.
    my $dbh = DBI->connect('dbi:mysql:database=mytimepunch;host=twcdwb01;',
                           'reports',
                           'crunch2it',
                           { PrintError => 0, RaiseError => 1 })
        or die($DBI::errstr);

    # Query the timein table.
    my $stmt = <<END;
SELECT t.time
FROM users u, timein t
WHERE u.username = '$opts->{'user'}'
  AND t.userid = u.id
  AND t.time between '$opts->{'start-date'}' and '$opts->{'end-date'}'
ORDER BY t.time
END

    my $timein = $dbh->selectall_arrayref($stmt);

    # Query the timeout table.
    $stmt = <<END;
SELECT t.time
FROM users u, timeout t
WHERE u.username = '$opts->{'user'}'
  AND t.userid = u.id
  AND t.time between '$opts->{'start-date'}' and '$opts->{'end-date'}'
ORDER BY t.time
END

    my $timeout = $dbh->selectall_arrayref($stmt);

    # Close the database connection since we no longer need it.
    $dbh->disconnect();

    return($timein, $timeout);
}

sub calcExpectedHours($)
{
    my $opts      = shift;

    # TRN: This is, as may be obvious, just a place holder for some
    # actual logic to determine how many business hours should be
    # expected to have been worked for a given period.
    #
    # This will start as something really simple, like using
    # Date::Manip's business day functionality to figure out how many
    # eight-hour days should have been worked.  Eventually, it should
    # encompass sick time, vacation time, and holidays.
    #
    # In the realm of the more-distant and more-fantastical future,
    # the MyTimePunch database will record not just whole sick or
    # vacation days, but the number of hours associated with a sick or
    # vacation event, so the actual number of hours can be used in the
    # calculations.
    #
    # It should also handle the number of hours spent on "other"
    # events such as company-sponsored parties, letting the team leave
    # the office early before a holiday, etc, but which should not be
    # considered actual time spent "working".

    my $startDate = $opts->{'start-date'};
    my $endDate   = $opts->{'end-date'};

    

    return 0;
}

sub calcActualHours($$$)
{
    my $opts    = shift;
    my $timein  = shift;
    my $timeout = shift;

    # For now, just do something useless with the data, like print it
    # out.  Eventually, we'll be able to do something worthwhile, like
    # calculate hours.
    my $error;
    my $totalDelta = 0;
    print "Timesheet dump for user $opts->{'user'}:\n\n";
    for (my $i = 0; $i < scalar(@$timein); $i++) {
        if (! defined $timeout->[$i][0]) {
            $timeout->[$i][0] = UnixDate('today', '%Y-%m-%d %H:%M:%S');
        }

        # Calculate the time interval.
        my $dateIn = ParseDate($timein->[$i][0]);
        my $dateOut = ParseDate($timeout->[$i][0]);
        my $delta = DateCalc($dateIn, $dateOut, \$error);
        die "Date Parsing Error: $error" if ($error);

        # Add the interval to our accumulator.
        $totalDelta = DateCalc($totalDelta, $delta, \$error);

        my $hours = Delta_Format($delta, 2, '%ht');

        printf("%s - %s: %-17s = %5.2f hours\n",
               $timein->[$i][0],
               $timeout->[$i][0],
               $delta,
               $hours);
    }

    # Calculate the total hours represented, up to two decimal places.
    my $totalHours = Delta_Format($totalDelta, 2, '%ht');

    return $totalHours;
}
