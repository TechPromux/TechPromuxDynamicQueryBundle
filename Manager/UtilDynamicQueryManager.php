<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 12/06/2017
 * Time: 14:50
 */

namespace TechPromux\DynamicQueryBundle\Manager;


use TechPromux\BaseBundle\Manager\BaseManager;
use TechPromux\DynamicQueryBundle\Entity\MetadataField;
use TechPromux\DynamicQueryBundle\Type\ConditionalOperator\BaseConditionalOperatorType;
use TechPromux\DynamicQueryBundle\Type\DynamicValue\BaseDynamicValueType;
use TechPromux\DynamicQueryBundle\Type\DynamicValue\Date\FirstDayOfCurrentMonthDynamicValueType;
use TechPromux\DynamicQueryBundle\Type\DynamicValue\Date\FirstDayOfCurrentWeekDynamicValueType;
use TechPromux\DynamicQueryBundle\Type\DynamicValue\Date\FirstDayOfCurrentYearDynamicValueType;
use TechPromux\DynamicQueryBundle\Type\FieldFunction\BaseFieldFunctionType;
use TechPromux\DynamicQueryBundle\Type\TableRelation\BaseTableRelationType;
use TechPromux\DynamicQueryBundle\Type\ValueFormat\BaseValueFormatType;


class UtilDynamicQueryManager extends BaseManager
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

    /**
     * @var BaseSecurityManager
     */
    private $security_manager;

    /**
     * @return BaseSecurityManager
     */
    public function getSecurityManager()
    {
        return $this->security_manager;
    }

    /**
     * @param BaseSecurityManager $security_manager
     * @return DataSourceManager
     */
    public function setSecurityManager($security_manager)
    {
        $this->security_manager = $security_manager;
        return $this;
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

    protected $table_relation_types = array();

    /**
     * @param BaseTableRelationType $table_relation_type
     * @return $this
     */
    public function addTableRelationType($table_relation_type)
    {
        $this->table_relation_types[$table_relation_type->getId()] = $table_relation_type;
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getRegisteredTableRelationTypes()
    {
        return $this->table_relation_types;
    }

    /**
     *
     * @return array
     */
    public function getTableRelationTypesChoices()
    {

        $table_relation_types_choices = array();

        foreach ($this->table_relation_types as $jto) {
            /* @var $jto BaseJoinType */
            $table_relation_types_choices[$jto->getId()] = $jto->getId();
        }
        return $table_relation_types_choices;
    }

    /**
     * @param string $id
     * @return BaseTableRelationType
     */
    public function getTableRelationTypeById($id)
    {
        return $this->table_relation_types[$id];
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

    protected $field_function_types = array();

    /**
     * @param BaseFieldFunctionType $field_function_type
     * @return $this
     */
    public function addFieldFunctionType($field_function_type)
    {
        $this->field_function_types[$field_function_type->getId()] = $field_function_type;
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getRegisteredFieldFunctions()
    {
        return $this->field_function_types;
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
     * @param string $id
     * @return BaseFieldFunctionType
     */
    public function getFieldFunctionById($id)
    {
        $all_functions = $this->getRegisteredFieldFunctions();

        $function = $all_functions[$id != null ? $id : ''];

        return $function;
    }

    //-------------------------------------------------------------------------------------------------

    protected $value_format_types = array();

    /**
     * @param BaseValueFormatType $value_format_type
     * @return $this
     */
    public function addValueFormatType($value_format_type)
    {
        $this->value_format_types[$value_format_type->getId()] = $value_format_type;
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getRegisteredValueFormats()
    {
        return $this->value_format_types;
    }

    /**
     * @return array
     */
    public function getValueFormatsChoices()
    {
        $formats = $this->getRegisteredValueFormats();

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
     * @param string $id
     * @return BaseValueFormatType
     */
    public function getValueFormatById($id)
    {
        if ($id == null || $id == '')
            return null;

        $all_formats = $this->getRegisteredValueFormats();

        $format = $all_formats[$id];

        return $format;
    }

    public function formatValue($value, $pattern = '')
    {
        $format = $this->getValueFormatById($pattern);

        if ($format == null || $format == '')
            return $value;

        try {
            $formatted_value = $format->getFormattedValue($value);
            return $formatted_value;
        } catch (\Exception $ex) {
            return '-- ERROR!!! --';
        }
    }

    public function pushValuesToArray(array $array, $value)
    {
        $values = is_array($value) ? $value : array($value);
        $array = array_merge($array, $values);
        return $array;
    }

    public function summarizeValues($summarize_function, $values)
    {
        if (is_null($values)) {
            $values = array();
        } elseif (!is_array($values)) {
            $values = array($values);
        }
        switch ($summarize_function) {
            case 'SUM':
                $result = 0;
                foreach ($values as $i => $value) {
                    $result += !is_null($value) ? $value : 0;
                }
                return $result;
            case 'AVG':
                if (count($values) == 0) return null;
                $result = 0;
                //$cont = 0;
                foreach ($values as $i => $value) {
                    $result += !is_null($value) ? $value : 0;
                    //$cont++;
                }
                return $result / count($values);
            case 'COUNT':
                $result = 0;
                foreach ($values as $i => $value) {
                    if (!is_null($value)) {
                        $result++;
                    }
                }
                return $result;
            case 'MIN':
                if (count($values) == 0) return null;
                $result = PHP_INT_MAX;
                foreach ($values as $i => $value) {
                    $result = !is_null($value) && $value <= $result ? $value : $result;
                }
                return $result;
            case 'MAX':
                if (count($values) == 0) return null;
                $result = PHP_INT_MIN;
                foreach ($values as $i => $value) {
                    $result = !is_null($value) && $value >= $result ? $value : $result;
                }
                return $result;

        }
    }


    public function verifyLimitIndicator($value, $limit_type, $limit)
    {
        if (is_null($value))
            return false;

        switch ($limit_type) {
            case 'less_than':
                return ($value < $limit);
            case 'less_or_equal':
                return ($value <= $limit);
            case 'greater_than':
                return ($value > $limit);
            case 'greater_or_equal':
                return ($value >= $limit);
            case 'between':
                $limits = explode(',', $limit . ',');
                $min = trim($limits[0]);
                $max = trim($limits[1]);
                return ($value >= $min and $value <= $max);
            case 'not_between':
                $limits = explode(',', $limit . ',');
                $min = trim($limits[0]);
                $max = trim($limits[1]);
                return ($value < $min or $value > $max);
            default:
                return false;

        }

        return false;
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

            if ($function == null) {
                return $field_sql_name;
            }

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
            'FIELD' => 'FIELD',
            'DYNAMIC' => 'DYNAMIC',
        );
        return $choices;
    }

    //-----------------------------------------------------------------------------------------

    protected $conditional_operator_types = array();

    /**
     * @param BaseConditionalOperatorType $conditional_operator_type
     * @return $this
     */
    public function addConditionalOperatorType($conditional_operator_type)
    {
        $this->conditional_operator_types[$conditional_operator_type->getId()] = $conditional_operator_type;
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getRegisteredConditionalOperators()
    {
        return $this->conditional_operator_types;
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
     * @param string $id
     * @return BaseConditionalOperatorType
     */
    public function getConditionalOperatorById($id)
    {
        if ($id == null)
            return null;

        $all_operators = $this->getRegisteredConditionalOperators();

        $operator = $all_operators[$id];

        return $operator;
    }

    //-----------------------------------------------------------------------------------------

    protected $dynamic_value_types = array();

    /**
     * @param BaseDynamicValueType $dynamic_value_type
     * @return $this
     */
    public function addDynamicValueType($dynamic_value_type)
    {
        $this->dynamic_value_types[$dynamic_value_type->getId()] = $dynamic_value_type;
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getRegisteredDynamicValues()
    {
        return $this->dynamic_value_types;
    }

    /**
     * @return array
     */
    public function getDynamicValuesChoices()
    {

        $values = $this->getRegisteredDynamicValues();

        $values_choices = array();

        foreach ($values as $dv) {
            /* @var $dv BaseDynamicValueType */
            $group_title = $dv->getGroupName();
            $values_choices[$group_title][$dv->getId()] = $dv->getId();
        }

        return $values_choices;
    }

    /**
     * @param string $id
     * @return BaseDynamicValueType
     */
    public function getDynamicValueById($id)
    {
        if ($id == null)
            return null;

        $all_values = $this->getRegisteredDynamicValues();

        $value = $all_values[$id];

        return $value;
    }


    //---------------------------------------------------------------------------------------------


}