<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 12/06/2017
 * Time: 14:50
 */

namespace TechPromux\Bundle\DynamicQueryBundle\Manager;


use TechPromux\Bundle\BaseBundle\Manager\BaseManager;
use TechPromux\Bundle\DynamicQueryBundle\Entity\DataModelCondition;
use TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataField;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Aggregation\MinFunction;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\BaseFieldFunction;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\BlankFunction;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Aggregation\AvgFunction;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Aggregation\CountFunction;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Aggregation\MaxFunction;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Aggregation\StddevFunction;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Aggregation\SumFunction;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Aggregation\VarianceFunction;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\DateOfFirstDayOfMonthFunction;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\DateOfFirstDayOfWeekFunction;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\DateOfFirstDayOfYearFunction;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\DayNameOfWeekFunction;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\DayNumberOfMonthFunction;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\DayNumberOfWeekFunction;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\DayNumberOfYearFunction;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\MonthNameOfYearFunction;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\MonthNumberOfYearFunction;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\QuantityOfDaysToCurrentDayFunction;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\QuantityOfMonthsToCurrentMonthFunction;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\QuantityOfWeeksToCurrentWeekFunction;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\QuantityOfYearsToCurrentYearFunction;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\WeekNumberOfMonthFunction;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\WeekNumberOfYearFunction;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\YearMonthDayNumbersFunction;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\YearMonthNumbersFunction;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\YearNumberFunction;


class DynamicQueryUtilManager extends BaseManager
{

    /**
     *
     * @return string
     */
    public function getBundleName()
    {
        return 'TechPromuxDynamicQueryBundle';
    }

    //----------------------------------------------------------------------------

    public function getDriverTypesChoices()
    {
        return array('pdo_mysql' => 'pdo_mysql', 'pdo_postgres' => 'pdo_postgres');
    }

    //----------------------------------------------------------------------------------------------

    public function getMetadataTableTypesChoices()
    {
        return array('table' => 'table', 'query' => 'query');
    }

    //----------------------------------------------------------------------------------------------

    protected $join_types_options = array();

    /**
     * @param BaseJoinType $relation_type
     * @return array
     */
    public function addTableJoinType($relation_type)
    {
        $this->join_types_options[$relation_type->getId()] = $relation_type;
        return $this->join_types_options;
    }

    /**
     *
     * @return array
     */
    public function getRegisteredRelationJoinTypes()
    {
        return $this->join_types_options;
    }

    /**
     *
     * @return array
     */
    public function getRelationJoinTypesChoices()
    {

        $join_types_choices = array();

        foreach ($this->join_types_options as $jto) {
            /* @var $jto BaseJoinType */
            $join_types_choices[$jto->getId()] = $jto->getId();
        }
        return $join_types_choices;
    }

    /**
     * @param $type
     * @return mixed
     */
    public function getRelationJoinTypeById($type)
    {
        return $this->join_types_options[$type];
    }

    //-------------------------------------------------------------------------------

    /**
     * @return array
     */
    public function getRegisteredFieldTypes()
    {
        $types_map = \Doctrine\DBAL\Types\Type::getTypesMap();
        return $types_map;
    }

    /**
     *
     * @return array
     */
    public function getFieldTypesChoices()
    {
        $types_map = $this->getRegisteredFieldTypes();
        $choices = array();
        foreach ($types_map as $tn => $tp) {
            $choices[$tn] = $tn;
        }
        return $choices;
    }

    public function getNumericAlphaDatetimeCategorization($type)
    {
        //TODO pedir a la function
        return in_array($type, array('integer', 'smallint', 'bigint', 'decimal', 'float', 'double')) ? 'numeric' : (in_array($type, array('date', 'time', 'datetime')) ? 'datetime' : 'alpha');
    }


    //-----------------------------------------------------------------------------------------

    /**
     *
     * @return array
     */
    public function getRegisteredFieldFunctions()
    {
        // TODO: pasar a un compiler que lo cargue

        $functions = array(
            // null function

            '' => new BlankFunction(),

            // Aggregation functions

            'AGGREGATION.COUNT' => new CountFunction(),
            'AGGREGATION.SUM' => new SumFunction(),
            'AGGREGATION.AVG' => new AvgFunction(),
            'AGGREGATION.MAX' => new MaxFunction(),
            'AGGREGATION.MIN' => new MinFunction(),
            'AGGREGATION.STDDEV' => new StddevFunction(),
            'AGGREGATION.VARIANCE' => new VarianceFunction(),

            // Date functions

            'DATE.DAY_NAME_OF_WEEK' => new DayNameOfWeekFunction(),
            'DATE.MONTH_NAME_OF_WEEK' => new MonthNameOfYearFunction(),

            'DATE.DATE_OF_FIRST_DAY_OF_WEEK' => new DateOfFirstDayOfWeekFunction(),
            'DATE.DATE_OF_FIRST_DAY_OF_MONTH' => new DateOfFirstDayOfMonthFunction(),
            'DATE.DATE_OF_FIRST_DAY_OF_YEAR' => new DateOfFirstDayOfYearFunction(),

            'DATE.DAY_NUMBER_OF_WEEK' => new DayNumberOfWeekFunction(),
            'DATE.DAY_NUMBER_OF_MONTH' => new DayNumberOfMonthFunction(),
            'DATE.DAY_NUMBER_OF_YEAR' => new DayNumberOfYearFunction(),
            'DATE.WEEK_NUMBER_OF_MONTH' => new WeekNumberOfMonthFunction(),
            'DATE.WEEK_NUMBER_OF_YEAR' => new WeekNumberOfYearFunction(),
            'DATE.MONTH_NUMBER_OF_YEAR' => new MonthNumberOfYearFunction(),
            'DATE.YEAR_NUMBER' => new YearNumberFunction(),

            'DATE.YEAR_MONTH_NUMBERS' => new YearMonthNumbersFunction(),
            'DATE.YEAR_MONTH_DAY_NUMBERS' => new YearMonthDayNumbersFunction(),

            'DATE.QUANTITY_OF_DAYS_TO_CURRENT_DAY' => new QuantityOfDaysToCurrentDayFunction(),
            'DATE.QUANTITY_OF_WEEKS_TO_CURRENT_WEEK' => new QuantityOfWeeksToCurrentWeekFunction(),
            'DATE.QUANTITY_OF_MONTHS_TO_CURRENT_MONTH' => new QuantityOfMonthsToCurrentMonthFunction(),
            'DATE.QUANTITY_OF_YEARS_TO_CURRENT_YEAR' => new QuantityOfYearsToCurrentYearFunction(),

            // Time functions

            // User functions

        );

        return $functions;
    }

    /**
     * @return array
     */
    public function getFieldFunctionsChoices()
    {

        $functions = $this->getRegisteredFieldFunctions();

        $functions_choices = array();

        foreach ($functions as $f) {
            /* @var $f BaseFieldFunction */

            if ($f->getId() != '') {

                $group_name = $f->getGroupName() . ' FUNCTIONS';

                if (!isset($functions_choices[$group_name])) {
                    $functions_choices[$group_name] = array();
                }

                $functions_choices[$group_name][$f->getId()] = $f->getId();
            }
        }

        return $functions_choices;
    }

    /**
     * @param string $function
     * @return BaseFieldFunction
     */
    public function getFieldFunctionById($function)
    {
        $all_functions = $this->getRegisteredFieldFunctions();

        $function = $all_functions[$function != null ? $function : ''];

        return $function;
    }

    //-------------------------------------------------------------------------------------------------

    public function getRegisteredFormatPatterns()
    {

        $patterns_options = array(
            'groups' => array(
                'NUMBER' => array(
                    'id' => 'NUMBER',
                    'title' => $this->trans('NUMBER')
                ),
                'DATE' => array(
                    'id' => 'DATE',
                    'title' => $this->trans('DATE')
                ),
                /*
                  'TIME' => array(
                  'id' => 'TIME',
                  'title' => $this->trans('TIME')
                  ),
                 */
            ),
            'patterns' => array(
                '' => array(
                    'id' => '',
                    'title' => $this->trans('NONE'),
                    'type' => '',
                    'return' => '',
                    'function' => function ($value, $prefix = '', $suffix = '', $forjs = false) {
                        return $prefix . $value . $suffix;
                    }
                ),
                'NUMBER_INTEGER_###0' => array(
                    'id' => 'NUMBER_INTEGER_###0',
                    'title' => $this->trans('###0'),
                    'type' => 'NUMBER',
                    'return' => 'integer',
                    'function' => function ($value, $prefix = '', $suffix = '', $forjs = false) {
                        $thousand_separator = '';
                        $decimal_pointer = '.';
                        $decimal_digist = 0;
                        $formatted_value = number_format((int)$value, $decimal_digist, $decimal_pointer, $thousand_separator);
                        return $prefix . $formatted_value . $suffix;
                    }
                ),
                'NUMBER_INTEGER_#_##0' => array(
                    'id' => 'NUMBER_INTEGER_#_##0',
                    'title' => $this->trans('# ##0'),
                    'type' => 'NUMBER',
                    'return' => 'integer',
                    'function' => function ($value, $prefix = '', $suffix = '', $forjs = false) {
                        $thousand_separator = $forjs ? '' : ' ';
                        $decimal_pointer = '.';
                        $decimal_digist = 0;
                        $formatted_value = number_format((int)$value, $decimal_digist, $decimal_pointer, $thousand_separator);
                        return $prefix . $formatted_value . $suffix;
                    }
                ),
                'NUMBER_INTEGER_#,##0' => array(
                    'id' => 'NUMBER_INTEGER_#,##0',
                    'title' => $this->trans('#,##0'),
                    'type' => 'NUMBER',
                    'return' => 'integer',
                    'function' => function ($value, $prefix = '', $suffix = '', $forjs = false) {
                        $thousand_separator = $forjs ? '' : ',';
                        $decimal_pointer = '.';
                        $decimal_digist = 0;
                        $formatted_value = number_format((int)$value, $decimal_digist, $decimal_pointer, $thousand_separator);
                        return $prefix . $formatted_value . $suffix;
                    }
                ),
                'NUMBER_FLOAT_###0.##' => array(
                    'id' => 'NUMBER_FLOAT_###0.##',
                    'title' => $this->trans('###0.##'),
                    'type' => 'NUMBER',
                    'return' => 'integer',
                    'function' => function ($value, $prefix = '', $suffix = '', $forjs = false) {
                        $thousand_separator = $forjs ? '' : '';
                        $decimal_pointer = '.';
                        $decimal_digist = 2;
                        $formatted_value = number_format((float)$value, $decimal_digist, $decimal_pointer, $thousand_separator);
                        return $prefix . $formatted_value . $suffix;
                    }
                ),
                'NUMBER_FLOAT_#_##0.##' => array(
                    'id' => 'NUMBER_FLOAT_#_##0.##',
                    'title' => $this->trans('# ##0.##'),
                    'type' => 'NUMBER',
                    'return' => 'integer',
                    'function' => function ($value, $prefix = '', $suffix = '', $forjs = false) {
                        $thousand_separator = $forjs ? '' : ' ';
                        $decimal_pointer = '.';
                        $decimal_digist = 2;
                        $formatted_value = number_format((float)$value, $decimal_digist, $decimal_pointer, $thousand_separator);
                        return $prefix . $formatted_value . $suffix;
                    }
                ),
                'NUMBER_FLOAT_#,##0.##' => array(
                    'id' => 'NUMBER_FLOAT_#,##0.##',
                    'title' => $this->trans('#,##0.##'),
                    'type' => 'NUMBER',
                    'return' => 'integer',
                    'function' => function ($value, $prefix = '', $suffix = '', $forjs = false) {
                        $thousand_separator = $forjs ? '' : ',';
                        $decimal_pointer = '.';
                        $decimal_digist = 2;
                        $formatted_value = number_format((float)$value, $decimal_digist, $decimal_pointer, $thousand_separator);
                        return $prefix . $formatted_value . $suffix;
                    }
                ),
                'DATE_Y-m-d' => array(
                    'id' => 'DATE_Y-m-d',
                    'title' => $this->trans('Y-m-d'),
                    'type' => 'DATE',
                    'return' => 'date',
                    'function' => function ($value, $prefix, $suffix) {
                        $formatted_value = (\DateTime::createFromFormat('Y-m-d', $value) ?
                            \DateTime::createFromFormat('Y-m-d', $value)
                            : \DateTime::createFromFormat('Y-m-d H:i:s', $value))
                            ->format('Y-m-d');
                        return $prefix . $formatted_value . $suffix;
                    }
                ),
                'DATE_d/m/Y' => array(
                    'id' => 'DATE_d/m/Y',
                    'title' => $this->trans('d/m/Y'),
                    'type' => 'DATE',
                    'return' => 'date',
                    'function' => function ($value, $prefix, $suffix) {
                        $formatted_value = (\DateTime::createFromFormat('Y-m-d', $value) ?
                            \DateTime::createFromFormat('Y-m-d', $value)
                            : \DateTime::createFromFormat('Y-m-d H:i:s', $value))
                            ->format('d/m/Y');
                        return $prefix . $formatted_value . $suffix;
                    }
                ),
                'DATE_week_day_name' => array(
                    'id' => 'DATE_week_day_name',
                    'title' => $this->trans('Week day name'),
                    'type' => 'DATE',
                    'return' => 'string',
                    'function' => function ($value, $prefix, $suffix) {

                        $days_names = [
                            'Sunday',
                            'Monday',
                            'Tuesday',
                            'Wednesday',
                            'Thursday',
                            'Friday',
                            'Saturday',
                        ];

                        $formatted_value = (\DateTime::createFromFormat('Y-m-d', $value) ?
                            \DateTime::createFromFormat('Y-m-d', $value)
                            : \DateTime::createFromFormat('Y-m-d H:i:s', $value))
                            ->format('w');
                        $formatted_value = $this->trans($days_names[$formatted_value]);
                        return $prefix . $formatted_value . $suffix;
                    }
                ),
                'DATE_month_name_comma_year' => array(
                    'id' => 'DATE_month_name_comma_year',
                    'title' => $this->trans('Month name, Year'),
                    'type' => 'DATE',
                    'return' => 'string',
                    'function' => function ($value, $prefix, $suffix) {
                        $month_value = (\DateTime::createFromFormat('Y-m-d', $value) ?
                            \DateTime::createFromFormat('Y-m-d', $value)
                            : \DateTime::createFromFormat('Y-m-d H:i:s', $value))
                            ->format('F');
                        $year_value = (\DateTime::createFromFormat('Y-m-d', $value) ?
                            \DateTime::createFromFormat('Y-m-d', $value)
                            : \DateTime::createFromFormat('Y-m-d H:i:s', $value))
                            ->format('Y');
                        $formatted_value = $this->trans($month_value) . ', ' . $year_value;
                        return $prefix . $formatted_value . $suffix;
                    }
                ),
            )
        );

        return $patterns_options;
    }

    public function getFormatPatternsChoices()
    {

        $patterns_options = $this->getRegisteredFormatPatterns();

        $patterns_choices = array();

        foreach ($patterns_options['groups'] as $g) {
            $patterns_choices[$g['title']] = array();
        }
        foreach ($patterns_options['patterns'] as $op) {
            if ($op['type'] != '') {
                $group_title = $patterns_options['groups'][$op['type']]['id'];
                $patterns_choices[$group_title][$op['id']] = $op['id'];
            }
        }

        return $patterns_choices;
    }

    public function formatValue($value, $pattern = '', $prefix = '', $suffix = '', $forjs = false)
    {
        $all_patterns_options = $this->getRegisteredFormatPatterns();
        $pattern_option = $all_patterns_options['patterns'][$pattern];
        try {
            $formatted_value = call_user_func($pattern_option['function'], $value, $prefix, $suffix, $forjs);
            return $formatted_value;
        } catch (\Exception $ex) {
            return '-ERROR!-';
        }
    }

    //----------------------------------------------------------------------------------------


    public function getOrderTypesChoices()
    {
        $choices = array(
            'ASC' => 'ASC',
            'DESC' => 'DESC'
        );
        return $choices;
    }

    //----------------------------------------------------------------------------

    /**
     * @param MetadataField $metadata_field
     * @param string $function_name
     * @return null|string
     */
    public function getFieldFunctionSQLName(MetadataField $metadata_field = null, $function_name)
    {
        if ($metadata_field != null) {

            $field_sql_name = $metadata_field->getSQLName();

            $function = $this->getFieldFunctionById($function_name);

            $detail_sql_name = $function->getAppliedFunctionToField($field_sql_name);

            return $detail_sql_name;
        }
        return null;
    }

    /**
     * @param MetadataField $metadata_field
     * @param string $function_name
     *
     * @return string
     */
    public function getFieldFunctionSQLType(MetadataField $metadata_field = null, $function_name)
    {
        $function = $this->getFieldFunctionById($function_name);

        $function_type = $function->getReturnedValueType();

        return ($function_type != '' && $function_type != null) ? $function_type : $metadata_field->getType();
    }

    //----------------------------------------------------------------------------------------

    public function getConditionalCompareToTypeChoices()
    {
        $choices = array(
            'FIXED' => 'FIXED',
            'DYNAMIC' => 'DYNAMIC',
            'FIELD' => 'FIELD',
        );
        return $choices;
    }

    //-----------------------------------------------------------------------------------------

    public function getRegisteredConditionalOperators()
    {
        $operators_options = array(
            'groups' => array(
                'BINARY' => array(
                    'id' => 'BINARY',
                    'title' => $this->trans('BINARY OPERATORS')
                ),
                'UNARY' => array(
                    'id' => 'UNARY',
                    'title' => $this->trans('UNARY OPERATORS')
                ),
            ),
            'operators' => array(
                'UNARY.IS_TRUE' => array(
                    'id' => 'UNARY.IS_TRUE',
                    'title' => $this->trans('IS TRUE'),
                    'type' => 'UNARY',
                    'allow' => array('boolean'),
                    'function' => function ($left_operand, $right_operand = null) {
                        $str_condition = $left_operand . ' IS TRUE';
                        return $str_condition;
                    }
                ),
                'UNARY.IS_NOT_TRUE' => array(
                    'id' => 'UNARY.IS_NOT_TRUE',
                    'title' => $this->trans('IS NOT TRUE'),
                    'type' => 'UNARY',
                    'allow' => array('boolean'),
                    'function' => function ($left_operand, $right_operand = null) {
                        $str_condition = $left_operand . ' IS NOT TRUE';
                        return $str_condition;
                    }
                ),
                'UNARY.IS_FALSE' => array(
                    'id' => 'UNARY.IS_FALSE',
                    'title' => $this->trans('IS FALSE'),
                    'type' => 'UNARY',
                    'allow' => array('boolean'),
                    'function' => function ($left_operand, $right_operand = null) {
                        $str_condition = $left_operand . ' IS FALSE';
                        return $str_condition;
                    }
                ),
                'UNARY.IS_NOT_FALSE' => array(
                    'id' => 'UNARY.IS_NOT_FALSE',
                    'title' => $this->trans('IS NOT FALSE'),
                    'type' => 'UNARY',
                    'allow' => array('boolean'),
                    'function' => function ($left_operand, $right_operand = null) {
                        $str_condition = $left_operand . ' IS NOT FALSE';
                        return $str_condition;
                    }
                ),
                'UNARY.IS_NULL' => array(
                    'id' => 'UNARY.IS_NULL',
                    'title' => $this->trans('IS NULL'),
                    'type' => 'UNARY',
                    'allow' => array('all', 'numeric', 'alpha', 'datetime'),
                    'function' => function ($left_operand, $right_operand = null) {
                        $str_condition = $left_operand . ' IS NULL';
                        return $str_condition;
                    }
                ),
                'UNARY.IS_NOT_NULL' => array(
                    'id' => 'UNARY.IS_NOT_NULL',
                    'title' => $this->trans('IS NOT NULL'),
                    'type' => 'UNARY',
                    'allow' => array('all', 'numeric', 'alpha', 'datetime'),
                    'function' => function ($left_operand, $right_operand = null) {
                        $str_condition = $left_operand . ' IS NOT NULL';
                        return $str_condition;
                    }
                ),
                'UNARY.IS_EMPTY' => array(
                    'id' => 'UNARY.IS_EMPTY',
                    'title' => $this->trans('IS EMPTY'),
                    'type' => 'UNARY',
                    'allow' => array('string', 'text', 'guid', 'alpha'),
                    'function' => function ($left_operand, $right_operand = null) {
                        $str_condition = $left_operand . ' = ' . "''";
                        return $str_condition;
                    }
                ),
                'UNARY.IS_NOT_EMPTY' => array(
                    'id' => 'UNARY.IS_NOT_EMPTY',
                    'title' => $this->trans('IS NOT EMPTY'),
                    'type' => 'UNARY',
                    'allow' => array('string', 'text', 'guid', 'alpha'),
                    'function' => function ($left_operand, $right_operand = null) {
                        $str_condition = $left_operand . ' <> ' . "''";
                        return $str_condition;
                    }
                ),
                'BINARY.EQUAL' => array(
                    'id' => 'BINARY.EQUAL',
                    'title' => $this->trans('EQUAL'),
                    'type' => 'BINARY',
                    'allow' => array('all', 'alpha', 'numeric', 'datetime'),
                    'function' => function ($left_operand, $right_operand) {
                        $str_condition = $left_operand . ' = ' . $right_operand;
                        return $str_condition;
                    }
                ),
                'BINARY.NOT_EQUAL' => array(
                    'id' => 'BINARY.NOT_EQUAL',
                    'title' => $this->trans('NOT EQUAL'),
                    'type' => 'BINARY',
                    'allow' => array('all', 'alpha', 'numeric', 'datetime'),
                    'function' => function ($left_operand, $right_operand) {
                        $str_condition = $left_operand . ' <> ' . $right_operand;
                        return $str_condition;
                    }
                ),
                'BINARY.GREATER_THAN' => array(
                    'id' => 'BINARY.GREATER_THAN',
                    'title' => $this->trans('GREATER THAN'),
                    'type' => 'BINARY',
                    'allow' => array('all', 'alpha', 'numeric', 'datetime'),
                    'function' => function ($left_operand, $right_operand) {
                        $str_condition = $left_operand . ' > ' . $right_operand;
                        return $str_condition;
                    }
                ),
                'BINARY.GREATER_OR_EQUAL' => array(
                    'id' => 'BINARY.GREATER_OR_EQUAL',
                    'title' => $this->trans('GREATER OR EQUAL'),
                    'type' => 'BINARY',
                    'allow' => array('all', 'alpha', 'numeric', 'datetime'),
                    'function' => function ($left_operand, $right_operand) {
                        $str_condition = $left_operand . ' >= ' . $right_operand;
                        return $str_condition;
                    }
                ),
                'BINARY.LESS_THAN' => array(
                    'id' => 'BINARY.LESS_THAN',
                    'title' => $this->trans('LESS THAN'),
                    'type' => 'BINARY',
                    'allow' => array('all', 'alpha', 'numeric', 'datetime'),
                    'function' => function ($left_operand, $right_operand) {
                        $str_condition = $left_operand . ' < ' . $right_operand;
                        return $str_condition;
                    }
                ),
                'BINARY.LESS_OR_EQUAL' => array(
                    'id' => 'BINARY.LESS_OR_EQUAL',
                    'title' => $this->trans('LESS OR EQUAL'),
                    'type' => 'BINARY',
                    'allow' => array('all', 'alpha', 'numeric', 'datetime'),
                    'function' => function ($left_operand, $right_operand) {
                        $str_condition = $left_operand . ' <= ' . $right_operand;
                        return $str_condition;
                    }
                ),
                'BINARY.LIKE' => array(
                    'id' => 'BINARY.LIKE',
                    'title' => $this->trans('LIKE'),
                    'type' => 'BINARY',
                    'allow' => array('guid', 'string', 'text', 'alpha'),
                    'function' => function ($left_operand, $right_operand) {
                        $str_condition = $left_operand . ' LIKE \'%' . $right_operand . '%\'';
                        return $str_condition;
                    }
                ),
                'BINARY.NOT_LIKE' => array(
                    'id' => 'BINARY.NOT_LIKE',
                    'title' => $this->trans('NOT LIKE'),
                    'type' => 'BINARY',
                    'allow' => array('guid', 'string', 'text', 'alpha'),
                    'function' => function ($left_operand, $right_operand) {
                        $str_condition = $left_operand . ' NOT LIKE \'%' . $right_operand . '%\'';
                        return $str_condition;
                    }
                ),
                'BINARY.BEGIN' => array(
                    'id' => 'BINARY.BEGIN',
                    'title' => $this->trans('BEGIN WITH'),
                    'type' => 'BINARY',
                    'allow' => array('guid', 'string', 'text', 'alpha'),
                    'function' => function ($left_operand, $right_operand) {
                        $str_condition = $left_operand . ' LIKE \'' . $right_operand . '%\'';
                        return $str_condition;
                    }
                ),
                'BINARY.NOT_BEGIN' => array(
                    'id' => 'BINARY.NOT_BEGIN',
                    'title' => $this->trans('NOT BEGIN WITH'),
                    'type' => 'BINARY',
                    'allow' => array('guid', 'string', 'text', 'alpha'),
                    'function' => function ($left_operand, $right_operand) {
                        $str_condition = $left_operand . ' NOT LIKE \'' . $right_operand . '%\'';
                        return $str_condition;
                    }
                ),
                'BINARY.END' => array(
                    'id' => 'BINARY.END',
                    'title' => $this->trans('END WITH'),
                    'type' => 'BINARY',
                    'allow' => array('guid', 'string', 'text', 'alpha'),
                    'function' => function ($left_operand, $right_operand) {
                        $str_condition = $left_operand . ' LIKE \'%' . $right_operand . '\'';
                        return $str_condition;
                    }
                ),
                'BINARY.NOT_END' => array(
                    'id' => 'BINARY.NOT_END',
                    'title' => $this->trans('NOT END WITH'),
                    'type' => 'BINARY',
                    'allow' => array('guid', 'string', 'text', 'alpha'),
                    'function' => function ($left_operand, $right_operand) {
                        $str_condition = $left_operand . ' NOT LIKE \'%' . $right_operand . '\'';
                        return $str_condition;
                    }
                ),
                'BINARY.IN' => array(
                    'id' => 'BINARY.IN',
                    'title' => $this->trans('IN'),
                    'type' => 'BINARY',
                    'allow' => array('all', 'alpha', 'numeric', 'datetime'),
                    'function' => function ($left_operand, $right_operand) {
                        $str_condition = $left_operand . ' IN (' . implode(',', $right_operand) . ')';
                        return $str_condition;
                    }
                ),
                'BINARY.NOT_IN' => array(
                    'id' => 'BINARY.NOT_IN',
                    'title' => $this->trans('NOT IN'),
                    'type' => 'BINARY',
                    'allow' => array('all', 'alpha', 'numeric', 'datetime'),
                    'function' => function ($left_operand, $right_operand) {
                        $str_condition = $left_operand . ' NOT IN (' . implode(',', $right_operand) . ')';
                        return $str_condition;
                    }
                ),
                'BINARY.BETWEEN' => array(
                    'id' => 'BINARY.BETWEEN',
                    'title' => $this->trans('BETWEEN'),
                    'type' => 'BINARY',
                    'allow' => array('integer', 'smallint', 'bigint', 'decimal', 'float', 'numeric'),
                    'function' => function ($left_operand, $right_operand) {
                        $str_condition = $left_operand . ' BETWEEN (' . implode(',', $right_operand) . ')';
                        return $str_condition;
                    }
                ),
                'BINARY.NOT_BETWEEN' => array(
                    'id' => 'BINARY.NOT_BETWEEN',
                    'title' => $this->trans('NOT BETWEEN'),
                    'type' => 'BINARY',
                    'allow' => array('integer', 'smallint', 'bigint', 'decimal', 'float', 'numeric'),
                    'function' => function ($left_operand, $right_operand) {
                        $str_condition = $left_operand . ' NOT BETWEEN (' . implode(',', $right_operand) . ')';
                        return $str_condition;
                    }
                ),
            )
        );

        return $operators_options;
    }

    public function getConditionalOperatorsChoices($type = null)
    {

        $operators_options = $this->getRegisteredConditionalOperators();

        $operator_choices = array();

        foreach ($operators_options['groups'] as $g) {
            $operator_choices[$g['title']] = array();
        }
        foreach ($operators_options['operators'] as $op) {
            $types_allowed = $op['allow'];
            // esto es para los filtros, para que se puedan cargar los operadores permitidos solo segun el tipo de cada campo en el filtro
            // es mejor que los operadores tengan un metodo que se le pase el tipo y ellos digan si lo soportan o no
            if ($type == null || $type == '' ||
                in_array('all', $types_allowed)
                || in_array($type, $types_allowed)
                || in_array($this->getNumericAlphaDatetimeCategorization($type), $types_allowed)
            ) {
                $group_title = $operators_options['groups'][$op['type']]['id'];
                $operator_choices[$group_title][$op['id']] = $op['id'];
            }
        }

        return $operator_choices;
    }

    //-----------------------------------------------------------------------------------------

    public function getRegisteredComparablesDynamicValues()
    {

        $operators_options = array(
            'groups' => array(
                'DATE' => array(
                    'id' => 'DATE',
                    'title' => $this->trans('DATE')
                ),
                'USER' => array(
                    'id' => 'USER',
                    'title' => $this->trans('USER')
                )
            ),
            'values' => array(
                'DATE.CURRENT_DAY' => array(
                    'id' => 'DATE.CURRENT_DAY',
                    'title' => $this->trans('DATE (FROM CURRENT DAY)'),
                    'type' => 'DATE',
                    'return' => 'date',
                    'function' => function () {
                        $right_operand = date("Y-m-d");
                        return $right_operand;
                    }
                ),
                'DATE.CURRENT_WEEK_FIRST_DATE' => array(
                    'id' => 'DATE.CURRENT_WEEK_FIRST_DATE',
                    'title' => $this->trans('FIRST DATE (FROM CURRENT WEEK)'),
                    'type' => 'DATE',
                    'return' => 'date',
                    'function' => function () {
                        $right_operand = date("Y-m-d", strtotime(date("Y-m-d")) - (3600 * 24) * (date("w") - 1));
                        return $right_operand;
                    }
                ),
                'DATE.CURRENT_MONTH_FIRST_DATE' => array(
                    'id' => 'DATE.CURRENT_MONTH_FIRST_DATE',
                    'title' => $this->trans('FIRST DATE (FROM CURRENT MONTH)'),
                    'type' => 'DATE',
                    'return' => 'date',
                    'function' => function () {
                        $right_operand = date("Y-m-01");
                        return $right_operand;
                    }
                ),
                'DATE.CURRENT_YEAR_FIRST_DATE' => array(
                    'id' => 'DATE.CURRENT_YEAR_FIRST_DATE',
                    'title' => $this->trans('FIRST DATE (FROM CURRENT YEAR)'),
                    'type' => 'DATE',
                    'return' => 'date',
                    'function' => function () {
                        $right_operand = date("Y-01-01");
                        return $right_operand;
                    }
                ),
            )
        );

        return $operators_options;
    }

    public function getComparablesDynamicValuesChoices()
    {

        $values_options = $this->getRegisteredComparablesDynamicValues();

        $values_choices = array();

        foreach ($values_options['groups'] as $g) {
            $values_choices[$g['title']] = array();
        }
        foreach ($values_options['values'] as $op) {
            $group_title = $values_options['groups'][$op['type']]['id'];
            $values_choices[$group_title][$op['id']] = $op['id'];
        }

        return $values_choices;
    }

    //---------------------------------------------------------------------------------------------


}