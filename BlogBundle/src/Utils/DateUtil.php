<?php


namespace Dhi\BlogBundle\Utils;


use App\Core\Exceptions\Alert;
use DateInterval;
use DatePeriod;
use DateTime;

class DateUtil
{
    public const PERIODS = [
        'DAY'=> 'DAY',
        'YESTERDAY'=> 'YESTERDAY',
        'MONTH'=> 'MONTH',
        'TOMORROW'=> 'TOMORROW',
        'WEEK'=> 'WEEK',
        'YEAR'=> 'YEAR',
    ];

    public const DAYS = [
        'MON'=> 'MONDAY',
        'TUE'=> 'TUESDAY',
        'WED'=> 'WEDNESDAY',
        'THU'=> 'THURSDAY',
        'FRI'=> 'FRIDAY',
        'SAT'=> 'SATURDAY',
        'SUN'=> 'SUNDAY',
    ];

    /**
     * @param DateTime $dateTime
     * @return DateTime|null
     */
    public static function getFirstDayOfTheMonthFromDate(DateTime $dateTime){

        try {
            return new DateTime($dateTime->format("01-m-Y"));
        } catch (\Exception $exception) {
            return null;
        }
    }
    /**
     * @param DateTime $dateTime
     * @return DateTime|null
     */
    public static function getFirstDayOfTheWeekFromDate(DateTime $dateTime){

        try {
            $rank = self::getWeekday($dateTime->format("Y-m-d"));
            $rank = $rank - 1;
            $date = new DateTime($dateTime->format("Y-m-d"));
            $date = $date->sub(new DateInterval('P'.$rank.'D'));

            return $date;

        } catch (\Exception $exception) {
            return null;
        }
    }
    /**
     * @param DateTime $dateTime
     * @return DateTime|null
     */
    public static function getFirstDayOfTheYearFromDate(DateTime $dateTime){

        try {
            return new DateTime($dateTime->format("01-01-Y"));

        } catch (\Exception $exception) {
            return null;
        }
    }

    public static function getWeekday($date) {
        return date('w', strtotime($date));
    }

    public static function getMonthday($date) {
        $date = new DateTime($date);
        $date = $date->format("d");

        return $date + 0;
    }

    public static function getYearDay() {

        $date = new DateTime("Y");
        $date = date($date->format("y-01-01"));
        $diff = new DateTime();
        try {
            $diff = (new DateTime())->diff(new DateTime($date))->days;
        } catch (\Exception $e) {}

        return $diff;
    }

    public static function getYear()
    {
        try{
            $date = new DateTime("Y");
            return $date->format("Y");
        }catch (\Exception $exception){
            return "1759";
        }
    }

    /**
     * @param \DateTimeInterface $dateTime
     * @return DateTime
     * @throws \Exception
     */
    public static function interfaceToDateTime(?\DateTimeInterface $dateTime = null)
    {
        if (!$dateTime)
            return null;

        return new DateTime($dateTime->format('Y-m-d H:i'));
    }

    public static function getDateDayDif(DateTime $dateTime1, DateTime $dateTime2) {
        $diff = date_diff($dateTime1, $dateTime2);
        $is_negative = ($diff->invert === 0)?-1:1;
        return $diff->days*$is_negative;
    }

    public static function getMinuteDateDif(DateTime $dateTime1, DateTime $dateTime2, $absolute = false) {
        $diff = $dateTime1->diff($dateTime2);
        $minutes = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
        if ($absolute) {
            $is_negative = ($diff->invert === 0)?-1:1;
            return $minutes*$is_negative;
        }
        return $minutes;
    }

    public static function getSecondsDateDif(DateTime $dateTime1, DateTime $dateTime2) {
        $diff = $dateTime1->diff($dateTime2);
        $seconds = ($diff->days * 24 * 60 * 60) + ($diff->h * 60 * 60) + $diff->i * 60 + $diff->s;
        $is_negative = ($diff->invert === 0)?-1:1;
        return $seconds*$is_negative;
    }

    /**
     * @param DateTime $dateTime
     * @return float|int
     * @throws \Exception
     */
    public static function getRemainingDay(DateTime $dateTime) {
        return self::getDateDayDif($dateTime, new DateTime());
    }

    public static function getIntervalOfToday() {
        return self::getInterval('DAY');
    }

    public static function lastSeenHelp($date) {

        $mydate= date("Y-m-d H:i:s");
        $theDiff="";
        //echo $mydate;//2014-06-06 21:35:55
        $datetime1 = date_create($date);
        $datetime2 = date_create($mydate);
        $interval = date_diff($datetime1, $datetime2);
        //echo $interval->format('%s Seconds %i Minutes %h Hours %d days %m Months %y Year    Ago')."<br>";
        $min=$interval->format('%i');
        $sec=$interval->format('%s');
        $hour=$interval->format('%h');
        $mon=$interval->format('%m');
        $day=$interval->format('%d');
        $year=$interval->format('%y');
        if($interval->format('%i%h%d%m%y')=="00000") {
            //echo $interval->format('%i%h%d%m%y')."<br>";
            return $sec." " . "Secondes";
        } else if($interval->format('%h%d%m%y')=="0000"){
            return $min." " . "Minutes";
        } else if($interval->format('%d%m%y')=="000"){
            return $hour." " . "Hours";
        } else if($interval->format('%m%y')=="00"){
            return $day." " . "Days";
        } else if($interval->format('%y')=="0"){
            return $mon." " . "Month";
        } else{
            return $year." " . "Year";
        }
    }

    /**
     * @param $period
     * @return DateTime[]|[]
     */
    public static function getInterval($period){
        try {
            $date = new DateTime();
            $aujoudhui = $date->format("Y-m-d");

            $aujoudhui = new DateTime($aujoudhui);

            if ($period == "DAY") {
                $start_date = $aujoudhui;
                $limit = (new DateTime($start_date->format("Y-m-d")))->add(new DateInterval("P1D"));
            } elseif ($period == "YESTERDAY") {
                $limit = $aujoudhui;
                $start_date = (new DateTime($limit->format("Y-m-d")))->sub(new DateInterval("P1D"));
            }elseif ($period == "MONTH") {
                $start_date = DateUtil::getFirstDayOfTheMonthFromDate($aujoudhui);
                $add = DateUtil::getMonthday((new DateTime())->format("Y-m-d"));
                $add = 30 - ($add + 0);
                $limit = $aujoudhui->add(new DateInterval("P".$add."D"));
            }  elseif ($period == "TOMORROW") {
                $start_date = (new DateTime($aujoudhui->format("Y-m-d")))->add(new DateInterval("P1D"));
                $limit = $limit = (new DateTime($start_date->format("Y-m-d")))->add(new DateInterval("P1D"));
            } elseif ($period == "WEEK") {
                $start_date = DateUtil::getFirstDayOfTheWeekFromDate($aujoudhui);
                $add = DateUtil::getWeekday((new DateTime())->format("Y-m-d"));
                $add = 7 - ($add + 0);
                $limit = $aujoudhui->add(new DateInterval("P".$add."D"));
            } else {//default YEAR
                $start_date = DateUtil::getFirstDayOfTheYearFromDate($aujoudhui);
                $add = DateUtil::getYearDay();
                $add = 365 - ($add + 1);
                $limit = $aujoudhui->add(new DateInterval("P".$add."D"));
            }
        }catch (\Exception $exception){
            return [
                "start" => null,
                "end" => null,
            ];
        }

        return [
            "start" => $start_date,
            "end" => $limit,
        ];
    }

    /**
     * @param string $date
     * @return DateTime
     * @throws \Exception
     */
    public static function getDate($date = "now"){

        try{
            return new DateTime($date);
        }catch (\Throwable $t){
            throw new Alert("Impossible de construire la date : ".$date);
        }
    }

    /**
     * Get first date of date day
     * @param string $date
     * @return DateTime
     * @throws \Exception
     */
    public static function getDateFromZero($date = "now"){
        return new DateTime((new DateTime($date))->format('Y-m-d').' 00:00');
    }

    /**
     * @param string $date
     * @return DateTime
     * @throws \Exception
     */
    public static function getDateAtEnd($date = "now"){
        return self::addHoursToDate(new DateTime((new DateTime($date))->format('Y-m-d').' 00:00'), 24);
    }

    public static function getIntervalFromDates($date_interval = "15-02-2020/17-14-2020"){

        try{

            if(self::isValidDate($date_interval)){
                $stored = $date_interval;

                $date = (new DateTime(self::getDate($date_interval)->format("Y-m-d")))->add(new DateInterval("P1D"));

                $date_interval = $stored. "/" .$date->format('d-m-Y 00:00');
            }

            $dates = explode('-', $date_interval);

            if (count($dates) == 2){
                return [
                    "start" => new DateTime(str_replace('/','-',$dates[0])),
                    "end" => new DateTime(str_replace('/','-',$dates[1])),
                ];
            }else{
                $dates = explode('/', $date_interval);
                if (count($dates) == 2){

                    return [
                        "start" => new DateTime($dates[0]),
                        "end" => new DateTime($dates[1]),
                    ];
                }
            }

        }catch (\Exception $exception){

            return [
                "start" => null,
                "end" => null,
            ];
        }


        return [
            "start" => null,
            "end" => null,
        ];
    }

    /**
     * @param \DateTimeInterface $dateTime
     * @param int $hours
     * @return DateTime|null
     * @throws \Exception
     */
    public static function addHoursToDate(\DateTimeInterface $dateTime, int $hours = 1){

        return self::addMinutesToDate($dateTime, self::convertHoursToMinutes($hours));
    }

    /**
     * @param \DateTimeInterface $dateTime
     * @param \DateTimeInterface $time
     * @return DateTime
     * @throws \Exception
     */
    public static function addTimeToDate(?\DateTimeInterface $dateTime, ?\DateTimeInterface $time){

        if (!$time && $dateTime)
            return self::dateFromInterface($dateTime);

        if ($time && !$dateTime)
            return null;

        $t = $time->format('H:i');

        $t = explode(':', $t);

        if (count($t) !== 2)
            return self::dateFromInterface($dateTime);

        $d = self::addMinutesToDate($dateTime, self::convertHoursToMinutes($t[0]) + $t[1]);

        return $d;
    }

    /**
     * @param \DateTimeInterface $dateTime
     * @return DateTime
     * @throws \Exception
     */
    public static function dateFromInterface(\DateTimeInterface $dateTime)
    {
        return new DateTime($dateTime->format('Y-m-d H:i:s'));
    }

    public static function convertDaysToMinutes($days = 1)
    {
        return $days*24*60;
    }

    public static function convertHoursToMinutes($days = 1)
    {
        return $days*60;
    }

    public static function convertMinutesToHours($minutes = 1)
    {
        if ($minutes < 1) {
            return 0;
        }

        $hours = floor($minutes / 60);

        return $hours;
    }

    public static function addDaysToDate(\DateTimeInterface $dateTime, $days = 1)
    {
        return self::addMinutesToDate($dateTime, self::convertDaysToMinutes(intval($days)));
    }

    /**
     * @param \DateTimeInterface $dateTime
     * @param int $minutes
     * @return DateTime|null
     * @throws \Exception
     */
    public static function addMinutesToDate(\DateTimeInterface $dateTime, $minutes = 15){

        if (!$minutes || $minutes === 0)
            $minutes = 15;

        $hours = intdiv($minutes, 60);

        $minutes = $minutes % 60;

        $days = intdiv($hours, 24);

        $hours = $hours % 24;

        $dateTime = new DateTime($dateTime->format('Y-m-d H:i'));

        try{
            return $dateTime->add(new DateInterval('P0Y0M'.$days.'DT'.$hours.'H'.$minutes.'M0S'));
        }catch (\Exception $exception){
            return (new DateTime())->add(new DateInterval('P0Y0M'.$days.'DT'.$hours.'H'.$minutes.'M0S'));
        }
    }

    /**
     * @param DateTime $date
     * @param int $minutes
     * @return DateTime
     * @throws \Exception
     */
    public static function subtractMinutesToDate(DateTime $date, $minutes = 1)
    {
        $date = $date->format("Y-m-d H:i:s");
        $time = strtotime($date);
        $time = $time - ($minutes * 60);
        $date = date("Y-m-d H:i:s", $time);
        return new DateTime($date);
    }

    public static function isValidDate($date){
        try{
            new DateTime($date);
            return $date && true;
        }catch (\Exception $exception){
            return false;
        }
    }

    // Function to get all the dates in given range

    /**
     * @param $start
     * @param $end
     * @param string $format
     * @return array
     * @throws \Exception
     */
    public static function getDatesFromRange(string $start, string $end, $format = 'd-m-Y') {

        // Declare an empty array
        $array = array();

        // Variable that store the date interval
        // of period 1 day
        $interval = new DateInterval('P1D');

        $realEnd = new DateTime($end);

        $realEnd->add($interval);

        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

        // Use loop to store date into array
        foreach($period as $date) {
            $array[] = [
                "date" => $date->format($format),
                "day" => self::parseDay($date->format("D")),
            ];
        }

        // Return the array elements
        return $array;
    }

    public static function parseDay($dayShortEn)
    {

        $dayShortEn = strtoupper($dayShortEn);

        if (isset(self::DAYS[$dayShortEn]))
            return self::DAYS[$dayShortEn];

        return null;
    }

    /**
     * @param DateTime $dateTime
     * @param string $time
     * @return DateTime
     * @throws \Exception
     */
    public static function accordDateToTime(DateTime $dateTime, string $time)
    {
        $f = $dateTime->format('d-m-Y').' '.$time;

        return new DateTime($f);
    }

    public static function isRegularTime(string $time)
    {
        $t = explode(':', $time);

        return (!(intval($t[0]) >= 24) && !(intval($t[1]) >= 60)) && preg_match("#^[0-9]{2}:[0-9]{2}#", $time);
    }

}