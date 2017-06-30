<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 12/06/2017
 * Time: 14:50
 */

namespace TechPromux\Bundle\DynamicQueryBundle\Manager;


use TechPromux\Bundle\BaseBundle\Manager\BaseManager;
use TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataField;
use TechPromux\Bundle\DynamicQueryBundle\Type\ConditionalOperator\BaseConditionalOperatorType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ConditionalOperator\Binary\BetweenConditionalOperatorType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ConditionalOperator\Binary\ContainsConditionalOperatorType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ConditionalOperator\Binary\EndsWithConditionalOperatorType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ConditionalOperator\Binary\EqualConditionalOperatorType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ConditionalOperator\Binary\GreaterOrEqualConditionalOperatorType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ConditionalOperator\Binary\GreaterThanConditionalOperatorType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ConditionalOperator\Binary\InConditionalOperatorType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ConditionalOperator\Binary\LessOrEqualConditionalOperatorType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ConditionalOperator\Binary\LessThanConditionalOperatorType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ConditionalOperator\Binary\NotBetweenConditionalOperatorType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ConditionalOperator\Binary\NotContainsConditionalOperatorType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ConditionalOperator\Binary\NotEndsWithConditionalOperatorType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ConditionalOperator\Binary\NotEqualConditionalOperatorType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ConditionalOperator\Binary\NotInConditionalOperatorType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ConditionalOperator\Binary\NotStartsWithConditionalOperatorType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ConditionalOperator\Binary\StartsWithConditionalOperatorType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ConditionalOperator\Unary\IsEmptyConditionalOperatorType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ConditionalOperator\Unary\IsFalseConditionalOperatorType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ConditionalOperator\Unary\IsNotEmptyConditionalOperatorType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ConditionalOperator\Unary\IsNotFalseConditionalOperatorType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ConditionalOperator\Unary\IsNotNullConditionalOperatorType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ConditionalOperator\Unary\IsNotTrueConditionalOperatorType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ConditionalOperator\Unary\IsNullConditionalOperatorType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ConditionalOperator\Unary\IsTrueConditionalOperatorType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Aggregation\MinFieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\BaseFieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\BlankFieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Aggregation\AvgFieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Aggregation\CountFieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Aggregation\MaxFieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Aggregation\StddevFieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Aggregation\SumFieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Aggregation\VarianceFieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\DateOfFirstDayOfMonthFieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\DateOfFirstDayOfWeekFieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\DateOfFirstDayOfYearFieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\DayNameOfWeekFieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\DayNumberOfMonthFieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\DayNumberOfWeekForMonday0ToSunday6FieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\DayNumberOfWeekForMonday1ToSunday7FieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\DayNumberOfWeekForSunday0ToSaturday6FieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\DayNumberOfYearFieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\MonthNameOfYearCommaYearFieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\MonthNameOfYearFieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\MonthNumberOfYearFieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\QuantityOfDaysToCurrentDayFieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\QuantityOfMonthsToCurrentMonthFieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\QuantityOfWeeksToCurrentWeekFieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\QuantityOfYearsToCurrentYearFieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\WeekNumberOfMonthFieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\WeekNumberOfYearFieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\YearMonthDayNumbersFieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\YearMonthNumbersFieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date\YearNumberFieldFunctionType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ValueFormat\BaseValueFormatType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ValueFormat\BlankValueFormatType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ValueFormat\Date\ToDatedmYValueFormatType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ValueFormat\Date\ToDateYmdValueFormatType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ValueFormat\Number\ToFloat_X_XX0pXXValueFormatType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ValueFormat\Number\ToFloat_XcXX0pXXValueFormatType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ValueFormat\Number\ToFloat_XXX0pXXValueFormatType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ValueFormat\Number\ToInteger_X_XX0ValueFormatType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ValueFormat\Number\ToInteger_XcXX0ValueFormatType;
use TechPromux\Bundle\DynamicQueryBundle\Type\ValueFormat\Number\ToInteger_XXX0ValueFormatType;


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

    public function getIsNumberDatetimeOrString($type)
    {
        switch ($type) {
            case null:
                return null;
            case 'integer':
            case 'smallint':
            case 'bigint':
            case 'decimal':
            case 'float':
            case 'double':
                return 'number';
            case 'date':
            case  'time':
            case  'datetime':
                return 'datetime';
            default:
                return 'string';
        }
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

            '' => new BlankFieldFunctionType(),

            // Aggregation functions

            'AGGREGATION.COUNT' => new CountFieldFunctionType(),
            'AGGREGATION.SUM' => new SumFieldFunctionType(),
            'AGGREGATION.AVG' => new AvgFieldFunctionType(),
            'AGGREGATION.MAX' => new MaxFieldFunctionType(),
            'AGGREGATION.MIN' => new MinFieldFunctionType(),
            'AGGREGATION.STDDEV' => new StddevFieldFunctionType(),
            'AGGREGATION.VARIANCE' => new VarianceFieldFunctionType(),

            // Date functions

            'DATE.DAY_NAME_OF_WEEK' => new DayNameOfWeekFieldFunctionType(),
            'DATE.MONTH_NAME_OF_YEAR' => new MonthNameOfYearFieldFunctionType(),
            'DATE.MONTH_NAME_OF_YEAR_COMMA_YEAR' => new MonthNameOfYearCommaYearFieldFunctionType(),

            'DATE.DATE_OF_FIRST_DAY_OF_WEEK' => new DateOfFirstDayOfWeekFieldFunctionType(),
            'DATE.DATE_OF_FIRST_DAY_OF_MONTH' => new DateOfFirstDayOfMonthFieldFunctionType(),
            'DATE.DATE_OF_FIRST_DAY_OF_YEAR' => new DateOfFirstDayOfYearFieldFunctionType(),

            'DATE.DAY_NUMBER_OF_WEEK_FOR_MONDAY_0_TO_SUNDAY_6' => new DayNumberOfWeekForMonday0ToSunday6FieldFunctionType(),
            'DATE.DAY_NUMBER_OF_WEEK_FOR_MONDAY_1_TO_SUNDAY_7' => new DayNumberOfWeekForMonday1ToSunday7FieldFunctionType(),
            'DATE.DAY_NUMBER_OF_WEEK_FOR_SUNDAY_0_TO_SATURDAY_6' => new DayNumberOfWeekForSunday0ToSaturday6FieldFunctionType(),
            'DATE.DAY_NUMBER_OF_MONTH' => new DayNumberOfMonthFieldFunctionType(),
            'DATE.DAY_NUMBER_OF_YEAR' => new DayNumberOfYearFieldFunctionType(),
            'DATE.WEEK_NUMBER_OF_MONTH' => new WeekNumberOfMonthFieldFunctionType(),
            'DATE.WEEK_NUMBER_OF_YEAR' => new WeekNumberOfYearFieldFunctionType(),
            'DATE.MONTH_NUMBER_OF_YEAR' => new MonthNumberOfYearFieldFunctionType(),
            'DATE.YEAR_NUMBER' => new YearNumberFieldFunctionType(),

            'DATE.YEAR_MONTH_NUMBERS' => new YearMonthNumbersFieldFunctionType(),
            'DATE.YEAR_MONTH_DAY_NUMBERS' => new YearMonthDayNumbersFieldFunctionType(),

            'DATE.QUANTITY_OF_DAYS_TO_CURRENT_DAY' => new QuantityOfDaysToCurrentDayFieldFunctionType(),
            'DATE.QUANTITY_OF_WEEKS_TO_CURRENT_WEEK' => new QuantityOfWeeksToCurrentWeekFieldFunctionType(),
            'DATE.QUANTITY_OF_MONTHS_TO_CURRENT_MONTH' => new QuantityOfMonthsToCurrentMonthFieldFunctionType(),
            'DATE.QUANTITY_OF_YEARS_TO_CURRENT_YEAR' => new QuantityOfYearsToCurrentYearFieldFunctionType(),

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
            /* @var $f BaseFieldFunctionType */

            if ($f->getId() != '') {

                $group_name = $f->getGroupName() . '_FUNCTIONS';

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
     * @return BaseFieldFunctionType
     */
    public function getFieldFunctionById($function)
    {
        $all_functions = $this->getRegisteredFieldFunctions();

        $function = $all_functions[$function != null ? $function : ''];

        return $function;
    }

    //-------------------------------------------------------------------------------------------------

    /**
     * @return array
     */
    public function getRegisteredValuesFormats()
    {
        $formats = array(
            '' => new BlankValueFormatType(),
            'NUMBER.TO_INTEGER_###0' => new ToInteger_XXX0ValueFormatType(),
            'NUMBER.TO_INTEGER_#_##0' => new ToInteger_X_XX0ValueFormatType(),
            'NUMBER.TO_INTEGER_#,##0' => new ToInteger_XcXX0ValueFormatType(),

            'NUMBER.TO_FLOAT_###0.##' => new ToFloat_XXX0pXXValueFormatType(),
            'NUMBER.TO_FLOAT_#_##0.##' => new ToFloat_X_XX0pXXValueFormatType(),
            'NUMBER.TO_FLOAT_#,##0.##' => new ToFloat_XcXX0pXXValueFormatType(),

            'DATE.TO_DATE_Y-m-d' => new ToDateYmdValueFormatType(),
            'DATE.TO_DATE_d/m/Y' => new ToDatedmYValueFormatType(),

        );

        return $formats;
    }

    /**
     * @return array
     */
    public function getValuesFormatsChoices()
    {
        $formats = $this->getRegisteredValuesFormats();

        $formats_choices = array();

        foreach ($formats as $f) {
            /* @var $f BaseValueFormatType */

            if ($f->getId() != '') {

                $group_name = $f->getGroupName() . '_FORMATS';

                if (!isset($formats_choices[$group_name])) {
                    $formats_choices[$group_name] = array();
                }

                $formats_choices[$group_name][$f->getId()] = $f->getId();
            }
        }

        return $formats_choices;
    }

    /**
     * @param string $function
     * @return BaseValueFormatType
     */
    public function getValueFormatById($format)
    {
        $all_formats = $this->getRegisteredValuesFormats();

        $format = $all_formats[$format != null ? $format : ''];

        return $format;
    }

    public function formatValue($value, $pattern = '')
    {
        $format = $this->getValueFormatById($pattern);

        try {
            $formatted_value = $format->getFormattedValue($value);
            return $formatted_value;
        } catch (\Exception $ex) {
            return '-- ERROR!!! --';
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
        $operators = array(
            'UNARY.IS_TRUE' => new IsTrueConditionalOperatorType(),
            'UNARY.IS_NOT_TRUE' => new IsNotTrueConditionalOperatorType(),

            'UNARY.IS_FALSE' => new IsFalseConditionalOperatorType(),
            'UNARY.IS_NOT_FALSE' => new IsNotFalseConditionalOperatorType(),

            'UNARY.IS_NULL' => new IsNullConditionalOperatorType(),
            'UNARY.IS_NOT_NULL' => new IsNotNullConditionalOperatorType(),

            'UNARY.IS_EMPTY' => new IsEmptyConditionalOperatorType(),
            'UNARY.IS_NOT_EMPTY' => new IsNotEmptyConditionalOperatorType(),

            'BINARY.EQUAL' => new EqualConditionalOperatorType(),
            'BINARY.NOT_EQUAL' => new NotEqualConditionalOperatorType(),

            'BINARY.LESS_THAN' => new LessThanConditionalOperatorType(),
            'BINARY.LESS_OR_EQUAL' => new LessOrEqualConditionalOperatorType(),

            'BINARY.GREATER_THAN' => new GreaterThanConditionalOperatorType(),
            'BINARY.GREATER_OR_EQUAL' => new GreaterOrEqualConditionalOperatorType(),

            'BINARY.CONTAINS' => new ContainsConditionalOperatorType(),
            'BINARY.NOT_CONTAINS' => new NotContainsConditionalOperatorType(),

            'BINARY.STARTS_WITH' => new StartsWithConditionalOperatorType(),
            'BINARY.NOT_STARTS_WITH' => new NotStartsWithConditionalOperatorType(),

            'BINARY.ENDS_WITH' => new EndsWithConditionalOperatorType(),
            'BINARY.NOT_ENDS_WITH' => new NotEndsWithConditionalOperatorType(),

            'BINARY.IN' => new InConditionalOperatorType(),
            'BINARY.NOT_IN' => new NotInConditionalOperatorType(),
            'BINARY.BETWEEN' => new BetweenConditionalOperatorType(),
            'BINARY.NOT_BETWEEN' => new NotBetweenConditionalOperatorType(),

        );

        return $operators;
    }

    /**
     * @param string $type
     * @return array
     */
    public function getConditionalOperatorsChoices($type = null)
    {

        $operators = $this->getRegisteredConditionalOperators();

        $operator_choices = array();

        foreach ($operators as $op) {
            /* @var $op BaseConditionalOperatorType */

            $type_category = $this->getIsNumberDatetimeOrString($type);

            if ($type == null || $op->getIsAllowedForValueType($type) || $op->getIsAllowedForValueType($type_category)) {

                $group_name = $op->getGroupName() . '_OPERATORS';

                if (!isset($operator_choices[$group_name])) {
                    $operator_choices[$group_name] = array();
                }

                $operator_choices[$group_name][$op->getId()] = $op->getId();

            }
        }

        return $operator_choices;
    }

    /**
     * @param string $operator
     * @return BaseConditionalOperatorType
     */
    public function getConditionalOperatorById($operator)
    {
        if ($operator == null)
            return null;

        $all_operators = $this->getRegisteredConditionalOperators();

        $operator = $all_operators[$operator];

        return $operator;
    }

    //-----------------------------------------------------------------------------------------

    public function getRegisteredComparablesDynamicValues()
    {

        $operators_options = array(
            'groups' => array(
                'DATE' => array(
                    'id' => 'DATE',
                    'title' => ('DATE')
                ),
                'USER' => array(
                    'id' => 'USER',
                    'title' => ('USER')
                )
            ),
            'values' => array(
                'DATE.CURRENT_DAY' => array(
                    'id' => 'DATE.CURRENT_DAY',
                    'title' => ('DATE (FROM CURRENT DAY)'),
                    'type' => 'DATE',
                    'return' => 'date',
                    'function' => function () {
                        $right_operand = date("Y-m-d");
                        return $right_operand;
                    }
                ),
                'DATE.CURRENT_WEEK_FIRST_DATE' => array(
                    'id' => 'DATE.CURRENT_WEEK_FIRST_DATE',
                    'title' => ('FIRST DATE (FROM CURRENT WEEK)'),
                    'type' => 'DATE',
                    'return' => 'date',
                    'function' => function () {
                        $right_operand = date("Y-m-d", strtotime(date("Y-m-d")) - (3600 * 24) * (date("w") - 1));
                        return $right_operand;
                    }
                ),
                'DATE.CURRENT_MONTH_FIRST_DATE' => array(
                    'id' => 'DATE.CURRENT_MONTH_FIRST_DATE',
                    'title' => ('FIRST DATE (FROM CURRENT MONTH)'),
                    'type' => 'DATE',
                    'return' => 'date',
                    'function' => function () {
                        $right_operand = date("Y-m-01");
                        return $right_operand;
                    }
                ),
                'DATE.CURRENT_YEAR_FIRST_DATE' => array(
                    'id' => 'DATE.CURRENT_YEAR_FIRST_DATE',
                    'title' => ('FIRST DATE (FROM CURRENT YEAR)'),
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