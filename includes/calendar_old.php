<?php

namespace choco;

class Calendar {

    var $_oneDate;
    var $_data;
    var $_counter, $_start, $_end;

    public function create($year = null, $month = null, $date = null, $firstDay = 0) {
        $this->_oneDate = 1 * 60 * 60 * 24;
        $this->_data = array();

        self::init($year, $month, $date, $firstDay);
    }

    /**
     * $firstDay: 0: sun, 1: monday, ...
     * $padding: true / false
     *   padding before and after date.
     */
    private function init($Year = null, $Month = null, $Date = null, $firstDay = 0) {

        $now = time(); // unix time

        if ($Year && $Month && $Date) {
            $specifyTime = mktime(0, 0, 0, $Month, $Date, $Year);   // unix time
        } else {
            $specifyTime = time(); // unix time
        }
        $specifyYear = date('Y', $specifyTime);   // 西暦を4桁で示す年
        $specifyMonth = date('n', $specifyTime);  // 先頭に 0 をつけない月（1 ～ 12）
        $specifyDate = date('j', $specifyTime);   // 先頭に 0 をつけない日付（1 ～ 31）

        $lastDay = $firstDay - 1;
        if ($lastDay < 0)
            $lastDay = 6;

        $start = mktime(0, 0, 0, $specifyMonth, 1, $specifyYear);
        $startYear = date('Y', $start);   // 西暦を4桁で示す年
        $startMonth = date('n', $start); // 先頭に 0 をつけない月（1 ～ 12）
        $startDate = date('j', $start);   // 先頭に 0 をつけない日付（1 ～ 31）
        $startDay = date('w', $start);  // 曜日を取得
        // padding before day
        if ($startDay != $firstDay) {
            if ($startDay > $firstDay) {
                $beforePadding = $startDay - $firstDay;
            } else {
                $beforePadding = 7 - $firstDay - $startDay;
            }
            $start -= $beforePadding * $this->_oneDate;
        }

        $end = mktime(0, 0, 0, $startMonth + 1, 1, $startYear) - $this->_oneDate;

        // padding after day
        $endDay = date('w', $end); // 曜日を取得
        if ($endDay != $lastDay) {
            if ($lastDay > $endDay) {
                $afterPadding = $lastDay - $endDay;
            } else {
                $afterPadding = 7 - $endDay - $lastDay;
            }
            $end += $afterPadding * $this->_oneDate;
        }

        // ** data **

        $this->_data['beforePadding'] = $beforePadding;
        $this->_data['afterPadding'] = $afterPadding;

        $this->_data['startDay'] = $startDay;
        $this->_data['firstDay'] = $firstDay;

        $this->_data['now']['year'] = date('Y', $now);   // 西暦を4桁で示す年
        $this->_data['now']['month'] = date('n', $now);  // 先頭に 0 をつけない月（1 ～ 12）
        $this->_data['now']['date'] = date('j', $now);   // 先頭に 0 をつけない日付（1 ～ 31）
        $this->_data['now']['day'] = date('w', $now);    // 曜日 0:日曜日

        $this->_data['specify']['year'] = $specifyYear;   // 西暦を4桁で示す年
        $this->_data['specify']['month'] = $specifyMonth; // 先頭に 0 をつけない月（1 ～ 12）
        $this->_data['specify']['date'] = $specifyDate;   // 先頭に 0 をつけない日付（1 ～ 31）
        $this->_data['specify']['day'] = date('w', $specifyTime);   // 曜日 0:日曜日
        //

        $this->_start = $start;
        $this->_end = $end;
        $this->_counter = $start;
    }

    public function getNow() {
        return $this->_data['now'];
    }

    public function getData() {
        return self::_data;
    }

    public function getNext() {
        if ($this->_counter > $this->_end) {
            return null;
        }

        $year = date('Y', $this->_counter);   // 西暦を4桁で示す年
        $month = date('n', $this->_counter);  // 先頭に 0 をつけない月（1 ～ 12）
        $date = date('j', $this->_counter);   // 先頭に 0 をつけない日付（1 ～ 31）
        $day = date('w', $this->_counter);    // 曜日 0:日曜日

        $this->_data['calendar'][$year][$month][$date]['day'] = $day;
        $this->_counter += $this->_oneDate;

        return array('year' => $year, 'month' => $month, 'date' => $date, 'day' => $day);
    }

}

