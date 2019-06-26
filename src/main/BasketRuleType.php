<?php
/**
 * Created by ADV/web-engineering co.
 */

namespace WebArch\BitrixIblockPropertyType;


use Bitrix\Main\ArgumentException;
use Bitrix\Sale\Internals\DiscountTable;
use WebArch\BitrixIblockPropertyType\Abstraction\IblockPropertyTypeBase;

/**
 * Class BasketRuleType
 *
 * @package WebArch\BitrixIblockPropertyType
 */
class BasketRuleType extends IblockPropertyTypeBase
{

    /**
     * @inheritdoc
     */
    public function getPropertyType()
    {
        return self::PROPERTY_TYPE_STRING;
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'Привязка к правилу работы с корзиной';
    }

    /**
     * @inheritdoc
     */
    public function getCallbacksMapping()
    {
        return [
            'GetPropertyFieldHtml' => [
                $this,
                'getPropertyFieldHtml',
            ],
        ];
    }

    /**
     * @inheritdoc
     * @throws ArgumentException
     */
    public function getPropertyFieldHtml(array $property, array $value, array $control)
    {
        return
            '<select name="' . $control['VALUE'] . ' id="' . $control['VALUE']. '"/>' . static::getBasketRuleOptionList($value['VALUE']). '</select>';
    }

    /**
     * @return string
     */
    public function getUserType()
    {
        return 'basket_rule';
    }

    /**
     * @param $currentValue
     *
     * @return string
     * @throws ArgumentException
     */
    private static function getBasketRuleOptionList($currentValue)
    {
        $html = '<option value="0" >(не выбран)</option>';

        foreach (static::getBasketRules() as $id => $name) {
            /** @noinspection HtmlUnknownAttribute */
            $html .= sprintf(
                '<option value="%d" %s >%s (%s)</option>',
                $id,
                $currentValue == $id ? ' selected="selected" ' : '',
                $name,
                $id
            );
        }

        return $html;
    }

    /**
     * @return array
     * @throws ArgumentException
     */
    private static function getBasketRules()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $db = DiscountTable::getList(['select' => ['*']]);

        $result = [];
        /** @noinspection PhpUndefinedMethodInspection */
        foreach ($db->fetchAll() as $row) {
            $result[$row['ID']] = $row['NAME'];
        }

        return $result;
    }
}
