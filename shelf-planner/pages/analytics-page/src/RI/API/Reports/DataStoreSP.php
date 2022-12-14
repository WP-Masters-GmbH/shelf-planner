<?php // -*- coding: utf-8 -*-
/**
 * Admin\API\Reports\DataStoreSPSP class file.
 */

namespace QuickAssortmentsSP\COG\RI\API\Reports;

if (! defined('ABSPATH')) {
    exit;
}

use QuickAssortmentsSP\COG\RI\TimeIntervalSP;

/**
 * Admin\API\Reports\DataStoreSPSP: Common parent for custom report data stores.
 */

/**
 * Class DataStoreSPSP.
 *
 * @author  Khan Mohammad R. <khan@quickassortments.com>
 *
 * @package QuickAssortmentsSP\COG\RISP\API\Reports
 *
 * @since   2.0.0
 */
class DataStoreSP
{
    /**
     * Table used as a data store for this report.
     *
     * @var string
     */
    const TABLE_NAME = '';
    /**
     * CacheSP group for the reports.
     *
     * @var string
     */
    protected $cache_group = 'reports';
    /**
     * Time out for the cache.
     *
     * @var int
     */
    protected $cache_timeout = 3600;
    /**
     * Mapping columns to data type to return correct response types.
     *
     * @var array
     */
    protected $column_types = [];

    /**
     * SQL columns to select in the db query.
     *
     * @var array
     */
    protected $report_columns = [];

    // @todo This does not really belong here, maybe factor out the comparison as separate class?
    /**
     * OrderSP by property, used in the cmp function.
     *
     * @var string
     */
    private $order_by = '';
    /**
     * OrderSP property, used in the cmp function.
     *
     * @var string
     */
    private $order = '';

    /**
     * Returns string to be used as cache key for the data.
     *
     * @param array $params QuerySP parameters.
     *
     * @return string
     */
    protected function get_cache_key($params)
    {
        return implode(
            '_',
            [
                'qa_cog_report',
                $this->cache_key,
                md5(wp_json_encode($params)),
            ]
        );
    }

    /**
     * Wrapper around CacheSP::get().
     *
     * @param string $cache_key CacheSP key.
     *
     * @return mixed
     */
    protected function get_cached_data($cache_key)
    {
        return CacheSP::get($cache_key);
    }

    /**
     * Wrapper around CacheSP::set().
     *
     * @param string $cache_key CacheSP key.
     * @param mixed  $value     New value.
     *
     * @return bool
     */
    protected function set_cached_data($cache_key, $value)
    {
        return CacheSP::set($cache_key, $value);
    }

    /**
     * Sorts intervals according to user's request.
     *
     * They are pre-sorted in SQL, but after adding gaps, they need to be sorted including the added ones.
     *
     * @param stdClass $data      Data object, must contain an array under $data->intervals.
     * @param string   $sort_by   Ordering property.
     * @param string   $direction DESC/ASC.
     */
    protected function sort_intervals(&$data, $sort_by, $direction)
    {
        $this->sort_array($data->intervals, $sort_by, $direction);
    }

    /**
     * Sorts array of arrays based on subarray key $sort_by.
     *
     * @param array  $arr       Array to sort.
     * @param string $sort_by   Ordering property.
     * @param string $direction DESC/ASC.
     */
    protected function sort_array(&$arr, $sort_by, $direction)
    {
        $this->order_by = $this->normalize_order_by($sort_by);
        $this->order    = $direction;
        usort($arr, [$this, 'interval_cmp']);
    }

    /**
     * Normalizes order_by clause to match to SQL query.
     *
     * @param string $order_by OrderSP by option requeste by user.
     *
     * @return string
     */
    protected function normalize_order_by($order_by)
    {
        if ('date' === $order_by) {
            return 'time_interval';
        }

        return $order_by;
    }

    /**
     * Fills in interval gaps from DBSP with 0-filled objects.
     *
     * @param array    $db_intervals   Array of all intervals present in the db.
     * @param DateTime $start_datetime Start date.
     * @param DateTime $end_datetime   End date.
     * @param string   $time_interval  Time interval, e.g. day, week, month.
     * @param stdClass $data           Data with SQL extracted intervals.
     *
     * @return stdClass
     */
    protected function fill_in_missing_intervals($db_intervals, $start_datetime, $end_datetime, $time_interval, &$data)
    {
        // @todo This is ugly and messy.
        $local_tz = new \DateTimeZone(wc_timezone_string());
        // At this point, we don't know when we can stop iterating, as the ordering can be based on any value.
        $time_ids     = array_flip(wp_list_pluck($data->intervals, 'time_interval'));
        $db_intervals = array_flip($db_intervals);
        // Totals object used to get all needed properties.
        $totals_arr = get_object_vars($data->totals);
        foreach ($totals_arr as $key => $val) {
            $totals_arr[$key] = 0;
        }
        // @todo Should 'products' be in intervals?
        unset($totals_arr['products']);
        while ($start_datetime <= $end_datetime) {
            $next_start = TimeIntervalSP::iterate($start_datetime, $time_interval);
            $time_id    = TimeIntervalSP::time_interval_id($time_interval, $start_datetime);
            // Either create fill-zero interval or use data from db.
            if ($next_start > $end_datetime) {
                $interval_end = $end_datetime->format('Y-m-d H:i:s');
            } else {
                $prev_end_timestamp = (int) $next_start->format('U') - 1;
                $prev_end           = new \DateTime();
                $prev_end->setTimestamp($prev_end_timestamp);
                $prev_end->setTimezone($local_tz);
                $interval_end = $prev_end->format('Y-m-d H:i:s');
            }
            if (array_key_exists($time_id, $time_ids)) {
                // For interval present in the db for this time frame, just fill in dates.
                $record               = &$data->intervals[$time_ids[$time_id]];
                $record['date_start'] = $start_datetime->format('Y-m-d H:i:s');
                $record['date_end']   = $interval_end;
            } elseif (! array_key_exists($time_id, $db_intervals)) {
                // For intervals present in the db outside of this time frame, do nothing.
                // For intervals not present in the db, fabricate it.
                $record_arr                  = [];
                $record_arr['time_interval'] = $time_id;
                $record_arr['date_start']    = $start_datetime->format('Y-m-d H:i:s');
                $record_arr['date_end']      = $interval_end;
                $data->intervals[]           = array_merge($record_arr, $totals_arr);
            }
            $start_datetime = $next_start;
        }

        return $data;
    }

    /**
     * Converts input datetime parameters to local timezone. If there are no inputs from the user in query_args,
     * uses default from $defaults.
     *
     * @param array $query_args Array of query arguments.
     * @param array $defaults   Array of default values.
     */
    protected function normalize_timezones(&$query_args, $defaults)
    {
        $local_tz = new \DateTimeZone(wc_timezone_string());
        foreach (['before', 'after'] as $query_arg_key) {
            if (isset($query_args[$query_arg_key]) && is_string($query_args[$query_arg_key])) {
                // Assume that unspecified timezone is a local timezone.
                $datetime = new \DateTime($query_args[$query_arg_key], $local_tz);
                // In case timezone was forced by using +HH:MM, convert to local timezone.
                $datetime->setTimezone($local_tz);
                $query_args[$query_arg_key] = $datetime;
            } elseif (isset($query_args[$query_arg_key]) && is_a($query_args[$query_arg_key], 'DateTime')) {
                // In case timezone is in other timezone, convert to local timezone.
                $query_args[$query_arg_key]->setTimezone($local_tz);
            } else {
                $query_args[$query_arg_key] = isset($defaults[$query_arg_key]) ? $defaults[$query_arg_key] : null;
            }
        }
    }

    /**
     * Removes extra records from intervals so that only requested number of records get returned.
     *
     * @param stdClass $data                    Data from whose intervals the records get removed.
     * @param int      $page_no                 Offset requested by the user.
     * @param int      $items_per_page          Number of records requested by the user.
     * @param int      $db_interval_count       Database interval count.
     * @param int      $expected_interval_count Expected interval count on the output.
     * @param string   $order_by                OrderSP by field.
     * @param string   $order                   ASC or DESC.
     */
    protected function remove_extra_records(&$data, $page_no, $items_per_page, $db_interval_count, $expected_interval_count, $order_by, $order)
    {
        if ('date' === strtolower($order_by)) {
            $offset = 0;
        } else {
            if ('asc' === strtolower($order)) {
                $offset = ($page_no - 1) * $items_per_page;
            } else {
                $offset = ($page_no - 1) * $items_per_page - $db_interval_count;
            }
            $offset = $offset < 0 ? 0 : $offset;
        }
        $count = $expected_interval_count - ($page_no - 1) * $items_per_page;
        if ($count < 0) {
            $count = 0;
        } elseif ($count > $items_per_page) {
            $count = $items_per_page;
        }
        $data->intervals = array_slice($data->intervals, $offset, $count);
    }

    /**
     * Returns true if there are any intervals that need to be filled in the response.
     *
     * @param int    $expected_interval_count Expected number of intervals in total.
     * @param int    $db_records              Total number of records for given period in the database.
     * @param int    $items_per_page          Number of items per page.
     * @param int    $page_no                 PageSP number.
     * @param string $order                   asc or desc.
     * @param string $order_by                Column by which the result will be sorted.
     * @param int    $intervals_count         Number of records for given (possibly shortened) time interval.
     *
     * @return bool
     */
    protected function intervals_missing($expected_interval_count, $db_records, $items_per_page, $page_no, $order, $order_by, $intervals_count)
    {
        if ($expected_interval_count <= $db_records) {
            return false;
        }
        if ('date' === $order_by) {
            $expected_intervals_on_page = $this->expected_intervals_on_page($expected_interval_count, $items_per_page, $page_no);

            return $intervals_count < $expected_intervals_on_page;
        }
        if ('desc' === $order) {
            return $page_no > floor($db_records / $items_per_page);
        }
        if ('asc' === $order) {
            return $page_no <= ceil(($expected_interval_count - $db_records) / $items_per_page);
        }

        // Invalid ordering.
        return false;
    }

    /**
     * Returns expected number of items on the page in case of date ordering.
     *
     * @param int $expected_interval_count Expected number of intervals in total.
     * @param int $items_per_page          Number of items per page.
     * @param int $page_no                 PageSP number.
     *
     * @return float|int
     */
    protected function expected_intervals_on_page($expected_interval_count, $items_per_page, $page_no)
    {
        $total_pages = (int) ceil($expected_interval_count / $items_per_page);
        if ($page_no < $total_pages) {
            return $items_per_page;
        } elseif ($page_no === $total_pages) {
            return $expected_interval_count - ($page_no - 1) * $items_per_page;
        } else {
            return 0;
        }
    }

    /**
     * Updates the LIMIT query part for Intervals query of the report.
     *
     * If there are less records in the database than time intervals, then we need to remap offset in SQL query
     * to fetch correct records.
     *
     * @param array  $intervals_query         Array with clauses for the Intervals SQL query.
     * @param array  $query_args              QuerySP arguments.
     * @param int    $db_interval_count       Database interval count.
     * @param int    $expected_interval_count Expected interval count on the output.
     * @param string $table_name              Name of the db table relevant for the date constraint.
     */
    protected function update_intervals_sql_params(&$intervals_query, &$query_args, $db_interval_count, $expected_interval_count, $table_name)
    {
        if ($db_interval_count === $expected_interval_count) {
            return;
        }
        $local_tz = new \DateTimeZone(wc_timezone_string());
        if ('date' === strtolower($query_args['orderby'])) {
            // page X in request translates to slightly different dates in the db, in case some
            // records are missing from the db.
            $start_iteration = 0;
            $end_iteration   = 0;
            if ('asc' === strtolower($query_args['order'])) {
                // ORDER BY date ASC.
                $new_start_date    = $query_args['after'];
                $intervals_to_skip = ($query_args['page'] - 1) * $intervals_query['per_page'];
                $latest_end_date   = $query_args['before'];
                for ($i = 0; $i < $intervals_to_skip; $i++) {
                    if ($new_start_date > $latest_end_date) {
                        $new_start_date  = $latest_end_date;
                        $start_iteration = 0;
                        break;
                    }
                    $new_start_date = TimeIntervalSP::iterate($new_start_date, $query_args['interval']);
                    $start_iteration++;
                }

                $new_end_date = clone $new_start_date;
                for ($i = 0; $i < $intervals_query['per_page']; $i++) {
                    if ($new_end_date > $latest_end_date) {
                        break;
                    }
                    $new_end_date = TimeIntervalSP::iterate($new_end_date, $query_args['interval']);
                    $end_iteration++;
                }
                if ($new_end_date > $latest_end_date) {
                    $new_end_date  = $latest_end_date;
                    $end_iteration = 0;
                }
                if ($end_iteration) {
                    $new_end_date_timestamp = (int) $new_end_date->format('U') - 1;
                    $new_end_date->setTimestamp($new_end_date_timestamp);
                }
            } else {
                // ORDER BY date DESC.
                $new_end_date        = $query_args['before'];
                $intervals_to_skip   = ($query_args['page'] - 1) * $intervals_query['per_page'];
                $earliest_start_date = $query_args['after'];
                for ($i = 0; $i < $intervals_to_skip; $i++) {
                    if ($new_end_date < $earliest_start_date) {
                        $new_end_date  = $earliest_start_date;
                        $end_iteration = 0;
                        break;
                    }
                    $new_end_date = TimeIntervalSP::iterate($new_end_date, $query_args['interval'], true);
                    $end_iteration++;
                }

                $new_start_date = clone $new_end_date;
                for ($i = 0; $i < $intervals_query['per_page']; $i++) {
                    if ($new_start_date < $earliest_start_date) {
                        break;
                    }
                    $new_start_date = TimeIntervalSP::iterate($new_start_date, $query_args['interval'], true);
                    $start_iteration++;
                }
                if ($new_start_date < $earliest_start_date) {
                    $new_start_date  = $earliest_start_date;
                    $start_iteration = 0;
                }
                if ($start_iteration) {
                    // @todo Is this correct? should it only be added if iterate runs? other two iterate instances, too?
                    $new_start_date_timestamp = (int) $new_start_date->format('U') + 1;
                    $new_start_date->setTimestamp($new_start_date_timestamp);
                }
            }
            // @todo - Do this without modifying $query_args?
            $query_args['adj_after']              = $new_start_date;
            $query_args['adj_before']             = $new_end_date;
            $adj_after                            = $new_start_date->format(TimeIntervalSP::$sql_datetime_format);
            $adj_before                           = $new_end_date->format(TimeIntervalSP::$sql_datetime_format);
            $intervals_query['where_time_clause'] = '';
            $intervals_query['where_time_clause'] .= " AND {$table_name}.date_created <= '$adj_before'";
            $intervals_query['where_time_clause'] .= " AND {$table_name}.date_created >= '$adj_after'";
            $intervals_query['limit']             = 'LIMIT 0,' . $intervals_query['per_page'];
        } else {
            if ('asc' === $query_args['order']) {
                $offset = (($query_args['page'] - 1) * $intervals_query['per_page']) - ($expected_interval_count - $db_interval_count);
                $offset = $offset < 0 ? 0 : $offset;
                $count  = $query_args['page'] * $intervals_query['per_page'] - ($expected_interval_count - $db_interval_count);
                if ($count < 0) {
                    $count = 0;
                } elseif ($count > $intervals_query['per_page']) {
                    $count = $intervals_query['per_page'];
                }
                $intervals_query['limit'] = 'LIMIT ' . $offset . ',' . $count;
            }
            // Otherwise no change in limit clause.
            // @todo - Do this without modifying $query_args?
            $query_args['adj_after']  = $query_args['after'];
            $query_args['adj_before'] = $query_args['before'];
        }
    }

    /**
     * Returns a list of columns selected by the query_args formatted as a comma separated string.
     *
     * @param array $query_args User-supplied options.
     *
     * @return string
     */
    protected function selected_columns($query_args)
    {
        $selections = $this->report_columns;

        if (isset($query_args['fields']) && is_array($query_args['fields'])) {
            $keep = [];
            foreach ($query_args['fields'] as $field) {
                if (isset($selections[$field])) {
                    $keep[$field] = $selections[$field];
                }
            }
            $selections = implode(', ', $keep);
        } else {
            $selections = implode(', ', $selections);
        }

        return $selections;
    }

    /**
     * Updates start and end dates for intervals so that they represent intervals' borders, not times when data in db were recorded.
     *
     * E.g. if there are db records for only Tuesday and Thursday this week, the actual week interval is [Mon, Sun], not [Tue, Thu].
     *
     * @param DateTime $start_datetime Start date.
     * @param DateTime $end_datetime   End date.
     * @param string   $time_interval  Time interval, e.g. day, week, month.
     * @param array    $intervals      Array of intervals extracted from SQL db.
     */
    protected function update_interval_boundary_dates($start_datetime, $end_datetime, $time_interval, &$intervals)
    {
        $local_tz = new \DateTimeZone(wc_timezone_string());
        foreach ($intervals as $key => $interval) {
            $datetime = new \DateTime($interval['datetime_anchor'], $local_tz);

            $prev_start = TimeIntervalSP::iterate($datetime, $time_interval, true);
            // @todo Not sure if the +1/-1 here are correct, especially as they are applied before the ?: below.
            $prev_start_timestamp = (int) $prev_start->format('U') + 1;
            $prev_start->setTimestamp($prev_start_timestamp);
            if ($start_datetime) {
                $date_start                      = $prev_start < $start_datetime ? $start_datetime : $prev_start;
                $intervals[$key]['date_start']   = $date_start->format('Y-m-d H:i:s');
            } else {
                $intervals[$key]['date_start'] = $prev_start->format('Y-m-d H:i:s');
            }

            $next_end           = TimeIntervalSP::iterate($datetime, $time_interval);
            $next_end_timestamp = (int) $next_end->format('U') - 1;
            $next_end->setTimestamp($next_end_timestamp);
            if ($end_datetime) {
                $date_end                      = $next_end > $end_datetime ? $end_datetime : $next_end;
                $intervals[$key]['date_end']   = $date_end->format('Y-m-d H:i:s');
            } else {
                $intervals[$key]['date_end'] = $next_end->format('Y-m-d H:i:s');
            }

            $intervals[$key]['interval'] = $time_interval;
        }
    }

    /**
     * Change structure of intervals to form a correct response.
     *
     * Also converts local datetimes to GMT and adds them to the intervals.
     *
     * @param array $intervals Time interval, e.g. day, week, month.
     */
    protected function create_interval_subtotals(&$intervals)
    {
        foreach ($intervals as $key => $interval) {
            $start_gmt = TimeIntervalSP::convert_local_datetime_to_gmt($interval['date_start']);
            $end_gmt   = TimeIntervalSP::convert_local_datetime_to_gmt($interval['date_end']);
            // Move intervals result to subtotals object.
            $intervals[$key] = [
                'interval'       => $interval['time_interval'],
                'date_start'     => $interval['date_start'],
                'date_start_gmt' => $start_gmt->format(TimeIntervalSP::$sql_datetime_format),
                'date_end'       => $interval['date_end'],
                'date_end_gmt'   => $end_gmt->format(TimeIntervalSP::$sql_datetime_format),
            ];

            unset($interval['interval'] , $interval['date_start'] , $interval['date_end'] , $interval['datetime_anchor'] , $interval['time_interval']);

            $intervals[$key]['subtotals'] = (object) $this->cast_numbers($interval);
        }
    }

    /**
     * Casts strings returned from the database to appropriate data types for output.
     *
     * @param array $array Associative array of values extracted from the database.
     *
     * @return array|WP_Error
     */
    protected function cast_numbers($array)
    {
        $retyped_array = [];
        $column_types  = apply_filters('qa_cog_rest_reports_column_types', $this->column_types, $array);
        foreach ($array as $column_name => $value) {
            if (is_array($value)) {
                $value = $this->cast_numbers($value);
            }

            if (isset($column_types[$column_name])) {
                $retyped_array[$column_name] = $column_types[$column_name]($value);
            } else {
                $retyped_array[$column_name] = $value;
            }
        }

        return $retyped_array;
    }

    /**
     * Generates a virtual table given a list of IDs.
     *
     * @param array $ids          Array of IDs.
     * @param array $id_field     Name of the ID field.
     * @param array $other_values Other values that must be contained in the virtual table.
     *
     * @return array
     */
    protected function get_ids_table($ids, $id_field, $other_values = [])
    {
        $selects = [];
        foreach ($ids as $id) {
            $new_select = "SELECT {$id} AS {$id_field}";
            foreach ($other_values as $key => $value) {
                $new_select .= ", {$value} AS {$key}";
            }
            array_push($selects, $new_select);
        }

        return join(' UNION ', $selects);
    }

    /**
     * Returns a comma separated list of the fields in the `query_args`, if there aren't, returns `report_columns` keys.
     *
     * @param array $query_args Parameters supplied by the user.
     *
     * @return array
     */
    protected function get_fields($query_args)
    {
        if (isset($query_args['fields']) && is_array($query_args['fields'])) {
            return $query_args['fields'];
        }

        return array_keys($this->report_columns);
    }

    /**
     * Returns a comma separated list of the field names prepared to be used for a selection after a join with `default_results`.
     *
     * @param array $fields                 Array of fields name.
     * @param array $default_results_fields FieldsSP to load from `default_results` table.
     * @param array $outer_selections       Array of fields that are not selected in the inner query.
     *
     * @return string
     */
    protected function format_join_selections($fields, $default_results_fields, $outer_selections = [])
    {
        foreach ($fields as $i => $field) {
            foreach ($default_results_fields as $default_results_field) {
                if ($field === $default_results_field) {
                    $fields[$i] = "default_results.{$field} AS {$field}";
                }
            }
            if (in_array($field, $outer_selections, true) && array_key_exists($field, $this->report_columns)) {
                $fields[$i] = $this->report_columns[$field];
            }
        }

        return implode(', ', $fields);
    }

    /**
     * Fills FROM and WHERE clauses of SQL request for 'Intervals' section of data response based on user supplied parameters.
     *
     * @param array  $query_args Parameters supplied by the user.
     * @param string $table_name Name of the db table relevant for the date constraint.
     *
     * @return array
     */
    protected function get_intervals_sql_params($query_args, $table_name)
    {
        $intervals_query = [
            'from_clause'       => '',
            'where_time_clause' => '',
            'where_clause'      => '',
        ];

        $intervals_query = array_merge($intervals_query, $this->get_time_period_sql_params($query_args, $table_name));

        if (isset($query_args['interval']) && '' !== $query_args['interval']) {
            $interval                         = $query_args['interval'];
            $intervals_query['select_clause'] = TimeIntervalSP::db_datetime_format($interval, $table_name);
        }

        $intervals_query = array_merge($intervals_query, $this->get_limit_sql_params($query_args));

        $intervals_query = array_merge($intervals_query, $this->get_order_by_sql_params($query_args));

        return $intervals_query;
    }

    /**
     * Fills WHERE clause of SQL request with date-related constraints.
     *
     * @param array  $query_args Parameters supplied by the user.
     * @param string $table_name Name of the db table relevant for the date constraint.
     *
     * @return array
     */
    protected function get_time_period_sql_params($query_args, $table_name)
    {
        $sql_query = [
            'from_clause'       => '',
            'where_time_clause' => '',
            'where_clause'      => '',
        ];

        if (isset($query_args['before']) && '' !== $query_args['before']) {
            if (is_a($query_args['before'], 'WC_DateTime')) {
                $datetime_str = $query_args['before']->date(TimeIntervalSP::$sql_datetime_format);
            } else {
                $datetime_str = $query_args['before']->format(TimeIntervalSP::$sql_datetime_format);
            }
            $sql_query['where_time_clause'] .= " AND {$table_name}.date_created <= '$datetime_str'";
        }

        if (isset($query_args['after']) && '' !== $query_args['after']) {
            if (is_a($query_args['after'], 'WC_DateTime')) {
                $datetime_str = $query_args['after']->date(TimeIntervalSP::$sql_datetime_format);
            } else {
                $datetime_str = $query_args['after']->format(TimeIntervalSP::$sql_datetime_format);
            }
            $sql_query['where_time_clause'] .= " AND {$table_name}.date_created >= '$datetime_str'";
        }

        return $sql_query;
    }

    /**
     * Fills LIMIT clause of SQL request based on user supplied parameters.
     *
     * @param array $query_args Parameters supplied by the user.
     *
     * @return array
     */
    protected function get_limit_sql_params($query_args)
    {
        $sql_query['per_page'] = get_option('posts_per_page');
        if (isset($query_args['per_page']) && is_numeric($query_args['per_page'])) {
            $sql_query['per_page'] = (int) $query_args['per_page'];
        }

        $sql_query['offset'] = 0;
        if (isset($query_args['page'])) {
            $sql_query['offset'] = ((int) $query_args['page'] - 1) * $sql_query['per_page'];
        }

        $sql_query['limit'] = "LIMIT {$sql_query['offset']}, {$sql_query['per_page']}";

        return $sql_query;
    }

    /**
     * Fills ORDER BY clause of SQL request based on user supplied parameters.
     *
     * @param array $query_args Parameters supplied by the user.
     *
     * @return array
     */
    protected function get_order_by_sql_params($query_args)
    {
        $sql_query['order_by_clause'] = '';
        if (isset($query_args['orderby'])) {
            $sql_query['order_by_clause'] = $this->normalize_order_by($query_args['orderby']);
        }

        if (isset($query_args['order'])) {
            $sql_query['order_by_clause'] .= ' ' . $query_args['order'];
        } else {
            $sql_query['order_by_clause'] .= ' DESC';
        }

        return $sql_query;
    }

    /**
     * Get join and where clauses for refunds based on user supplied parameters.
     *
     * @param array $query_args Parameters supplied by the user.
     *
     * @return array
     */
    protected function get_refund_subquery($query_args)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sp_cog_order_stats';
        $sql_query  = [
            'where_clause' => '',
            'from_clause'  => '',
        ];

        if (! isset($query_args['refunds'])) {
            return $sql_query;
        }

        if ('all' === $query_args['refunds']) {
            $sql_query['where_clause'] .= 'parent_id != 0';
        }

        if ('none' === $query_args['refunds']) {
            $sql_query['where_clause'] .= 'parent_id = 0';
        }

        if ('full' === $query_args['refunds'] || 'partial' === $query_args['refunds']) {
            $operator                  = 'full' === $query_args['refunds'] ? '=' : '!=';
            $sql_query['from_clause'] .= " JOIN {$table_name} parent_order_stats ON {$table_name}.parent_id = parent_order_stats.order_id";
            $sql_query['where_clause'] .= "parent_order_stats.status {$operator} '{$this->normalize_order_status('refunded')}'";
        }

        return $sql_query;
    }

    /**
     * Maps order status provided by the user to the one used in the database.
     *
     * @param string $status OrderSP status.
     *
     * @return string
     */
    protected static function normalize_order_status($status)
    {
        $status = trim($status);

        return 'wc-' . $status;
    }

    /**
     * Returns comma separated ids of allowed products, based on query arguments from the user.
     *
     * @param array $query_args Parameters supplied by the user.
     *
     * @return string
     */
    protected function get_included_products($query_args)
    {
        $included_products = $this->get_included_products_array($query_args);

        return implode(',', $included_products);
    }

    /**
     * Returns an array of ids of allowed products, based on query arguments from the user.
     *
     * @param array $query_args Parameters supplied by the user.
     *
     * @return array
     */
    protected function get_included_products_array($query_args)
    {
        $included_products = [];
        $operator          = $this->get_match_operator($query_args);

        if (isset($query_args['categories']) && is_array($query_args['categories']) && count($query_args['categories']) > 0) {
            $included_products = $this->get_products_by_cat_ids($query_args['categories']);
            $included_products = empty($included_products) ? ['-1'] : wc_list_pluck($included_products, 'get_id');
        }

        if (isset($query_args['product_includes']) && is_array($query_args['product_includes']) && count($query_args['product_includes']) > 0) {
            if (count($included_products) > 0) {
                if ('AND' === $operator) {
                    $included_products = array_intersect($included_products, $query_args['product_includes']);
                } elseif ('OR' === $operator) {
                    // Union of products from selected categories and manually included products.
                    $included_products = array_unique(array_merge($included_products, $query_args['product_includes']));
                }
            } else {
                $included_products = $query_args['product_includes'];
            }
        }

        return $included_products;
    }

    /**
     * Returns logic operator for WHERE subclause based on 'match' query argument.
     *
     * @param array $query_args Parameters supplied by the user.
     *
     * @return string
     */
    protected function get_match_operator($query_args)
    {
        $operator = 'AND';

        if (! isset($query_args['match'])) {
            return $operator;
        }

        if ('all' === strtolower($query_args['match'])) {
            $operator = 'AND';
        } elseif ('any' === strtolower($query_args['match'])) {
            $operator = 'OR';
        }

        return $operator;
    }

    /**
     * Returns an array of products belonging to given categories.
     *
     * @param array $categories List of categories IDs.
     *
     * @return array|stdClass
     */
    protected function get_products_by_cat_ids($categories)
    {
        $product_categories = get_categories(
            [
                'hide_empty' => 0,
                'taxonomy'   => 'product_cat',
            ]
        );
        $cat_slugs          = [];
        $categories         = array_flip($categories);
        foreach ($product_categories as $product_cat) {
            if (key_exists($product_cat->cat_ID, $categories)) {
                $cat_slugs[] = $product_cat->slug;
            }
        }

        if (empty($cat_slugs)) {
            return [];
        }

        $args = [
            'category' => $cat_slugs,
            'limit'    => -1,
        ];

        return wc_get_products($args);
    }

    /**
     * Returns comma separated ids of allowed variations, based on query arguments from the user.
     *
     * @param array $query_args Parameters supplied by the user.
     *
     * @return string
     */
    protected function get_included_variations($query_args)
    {
        $included_variations = [];
        $operator            = $this->get_match_operator($query_args);

        if (isset($query_args['variations']) && is_array($query_args['variations']) && count($query_args['variations']) > 0) {
            $included_variations = array_filter(array_map('intval', $query_args['variations']));
        }

        $included_variations_str = implode(',', $included_variations);

        return $included_variations_str;
    }

    /**
     * Returns comma separated ids of excluded products, based on query arguments from the user.
     *
     * @param array $query_args Parameters supplied by the user.
     *
     * @return string
     */
    protected function get_excluded_products($query_args)
    {
        $excluded_products_str = '';

        if (isset($query_args['product_excludes']) && is_array($query_args['product_excludes']) && count($query_args['product_excludes']) > 0) {
            $excluded_products_str = implode(',', $query_args['product_excludes']);
        }

        return $excluded_products_str;
    }

    /**
     * Returns comma separated ids of included coupons, based on query arguments from the user.
     *
     * @param array $query_args Parameters supplied by the user.
     *
     * @return string
     */
    protected function get_included_coupons($query_args)
    {
        $included_coupons_str = '';

        if (isset($query_args['coupon_includes']) && is_array($query_args['coupon_includes']) && count($query_args['coupon_includes']) > 0) {
            $included_coupons_str = implode(',', $query_args['coupon_includes']);
        }

        return $included_coupons_str;
    }

    /**
     * Returns comma separated ids of excluded coupons, based on query arguments from the user.
     *
     * @param array $query_args Parameters supplied by the user.
     *
     * @return string
     */
    protected function get_excluded_coupons($query_args)
    {
        $excluded_coupons_str = '';

        if (isset($query_args['coupon_excludes']) && is_array($query_args['coupon_excludes']) && count($query_args['coupon_excludes']) > 0) {
            $excluded_coupons_str = implode(',', $query_args['coupon_excludes']);
        }

        return $excluded_coupons_str;
    }

    /**
     * Returns comma separated ids of included orders, based on query arguments from the user.
     *
     * @param array $query_args Parameters supplied by the user.
     *
     * @return string
     */
    protected function get_included_orders($query_args)
    {
        $included_orders_str = '';

        if (isset($query_args['order_includes']) && is_array($query_args['order_includes']) && count($query_args['order_includes']) > 0) {
            $included_orders_str = implode(',', $query_args['order_includes']);
        }

        return $included_orders_str;
    }

    /**
     * Returns comma separated ids of excluded orders, based on query arguments from the user.
     *
     * @param array $query_args Parameters supplied by the user.
     *
     * @return string
     */
    protected function get_excluded_orders($query_args)
    {
        $excluded_orders_str = '';

        if (isset($query_args['order_excludes']) && is_array($query_args['order_excludes']) && count($query_args['order_excludes']) > 0) {
            $excluded_orders_str = implode(',', $query_args['order_excludes']);
        }

        return $excluded_orders_str;
    }

    /**
     * Returns comma separated ids of included users, based on query arguments from the user.
     *
     * @param array $query_args Parameters supplied by the user.
     *
     * @return string
     */
    protected function get_included_users($query_args)
    {
        $included_users_str = '';

        if (isset($query_args['user_includes']) && is_array($query_args['user_includes']) && count($query_args['user_includes']) > 0) {
            $included_users_str = implode(',', $query_args['user_includes']);
        }

        return $included_users_str;
    }

    /**
     * Returns comma separated ids of excluded users, based on query arguments from the user.
     *
     * @param array $query_args Parameters supplied by the user.
     *
     * @return string
     */
    protected function get_excluded_users($query_args)
    {
        $excluded_users_str = '';

        if (isset($query_args['user_excludes']) && is_array($query_args['user_excludes']) && count($query_args['user_excludes']) > 0) {
            $excluded_users_str = implode(',', $query_args['user_excludes']);
        }

        return $excluded_users_str;
    }

    /**
     * Returns order status subquery to be used in WHERE SQL query, based on query arguments from the user.
     *
     * @param array  $query_args Parameters supplied by the user.
     * @param string $operator   AND or OR, based on match query argument.
     *
     * @return string
     */
    protected function get_status_subquery($query_args, $operator = 'AND')
    {
        global $wpdb;

        $subqueries        = [];
        $excluded_statuses = [];
        if (isset($query_args['status_is']) && is_array($query_args['status_is']) && count($query_args['status_is']) > 0) {
            $allowed_statuses = array_map([$this, 'normalize_order_status'], $query_args['status_is']);
            if ($allowed_statuses) {
                $subqueries[] = "{$wpdb->prefix}sp_cog_order_stats.status IN ( '" . implode("','", $allowed_statuses) . "' )";
            }
        }

        if (isset($query_args['status_is_not']) && is_array($query_args['status_is_not']) && count($query_args['status_is_not']) > 0) {
            $excluded_statuses = array_map([$this, 'normalize_order_status'], $query_args['status_is_not']);
        }

        if ((! isset($query_args['status_is']) || empty($query_args['status_is']))
             && (! isset($query_args['status_is_not']) || empty($query_args['status_is_not']))
        ) {
            $excluded_statuses = array_map([
                $this,
                'normalize_order_status',
            ], $this->get_excluded_report_order_statuses());
        }

        if ($excluded_statuses) {
            $subqueries[] = "{$wpdb->prefix}sp_cog_order_stats.status NOT IN ( '" . implode("','", $excluded_statuses) . "' )";
        }

        return implode(" $operator ", $subqueries);
    }

    /**
     * Get the excluded order statuses used when calculating reports.
     *
     * @return array
     */
    protected static function get_excluded_report_order_statuses()
    {
        $excluded_statuses = self::get_order_statuses(get_option('qa_cog_ri_settings_excluded_order_status'));

        if (empty($excluded_statuses) || ! is_array($excluded_statuses)) {
            $excluded_statuses = [
                'pending',
                'failed',
                'cancelled',
            ];
        }

        $excluded_statuses = array_merge(['trash'], $excluded_statuses);

        return apply_filters('qa_cog_reports_excluded_order_statuses', $excluded_statuses);
    }

    /**
     * Format order statuses by removing a leading 'wc-' if present.
     *
     * @param array $statuses OrderSP statuses.
     *
     * @return array formatted statuses.
     */
    public static function get_order_statuses($statuses)
    {
        $formatted_statuses = [];
        if(is_array($statuses)) {
            foreach (wc_get_order_statuses() as $key => $value) {
                if (!in_array($key, $statuses, true)) {
                    continue;
                }

                $formatted_key = preg_replace('/^wc-/', '', $key);
                $formatted_statuses[] = $formatted_key;
            }
        }

        return $formatted_statuses;
    }

    /**
     * Compares two report data objects by pre-defined object property and ASC/DESC ordering.
     *
     * @param stdClass $a Object a.
     * @param stdClass $b Object b.
     *
     * @return string
     */
    private function interval_cmp($a, $b)
    {
        if ('' === $this->order_by || '' === $this->order) {
            return 0;
            // @todo Should return WP_Error here perhaps?
        }
        if ($a[$this->order_by] === $b[$this->order_by]) {
            // As relative order is undefined in case of equality in usort, second-level sorting by date needs to be enforced
            // so that paging is stable.
            if ($a['time_interval'] === $b['time_interval']) {
                return 0; // This should never happen.
            } elseif ($a['time_interval'] > $b['time_interval']) {
                return 1;
            } elseif ($a['time_interval'] < $b['time_interval']) {
                return -1;
            }
        } elseif ($a[$this->order_by] > $b[$this->order_by]) {
            return strtolower($this->order) === 'desc' ? -1 : 1;
        } elseif ($a[$this->order_by] < $b[$this->order_by]) {
            return strtolower($this->order) === 'desc' ? 1 : -1;
        }
    }
}
