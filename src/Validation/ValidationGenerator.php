<?php
/**
 * Created by PhpStorm.
 * User: Michael Behr
 * Date: 05.01.17
 * Time: 11:18
 */

namespace Bow\Tools\Validation;

class ValidationGenerator
{
    /**
     * @var array store rules
     */
    protected $rules = [];

    /**
     * @var string namespace
     */
    protected $namespace = '';

    /**
     * ValidationGenerator constructor.
     *
     * @param array  $rules
     * @param string $namespace
     */
    public function __construct($rules, $namespace = '')
    {
        $this->namespace = $namespace;
        $this->rules = $rules;
    }

    /**
     * get rules prepared for update actions, per default no require
     * @return array
     */
    public function getUpdateRules()
    {
        return $this->getRules();
    }

    /**
     * get rules for store action, per default require all
     *
     * @return array
     */
    public function getStoreRules()
    {
        return $this->required()->getRules();
    }

    /**
     * get rules
     *
     * @return array
     */
    public function getRules()
    {
        return $this->applyNamespace();
    }

    /**
     * exclude rules from selection
     *
     * @param array $exclude
     *
     * @return $this
     */
    public function exclude($exclude)
    {
        $this->rules = array_diff_key($this->rules, array_flip($exclude));
        return $this;
    }

    /**
     * take only rules specified in parameter array
     *
     * @param $only
     *
     * @return $this
     */
    public function only($only)
    {
        $this->rules = array_intersect_key($this->rules, array_flip($only));
        return $this;
    }

    /**
     * set given (param array) or all (param '*') rules as required
     *
     * @param string|array $require
     *
     * @return $this
     */
    public function required($require = '*')
    {
        $rulesRequired = [];

        if ($require == '*') {
            // all needed
            foreach ($this->rules as $index => $rule)
                $rulesRequired[$index] = $this->append($rule, 'required');

        } else {
            foreach ($this->rules as $index => $rule)
                $rulesRequired[$index] = (in_array($index, $require)) ? $this->append($rule, 'required') : $rule;
        }

        $this->rules = $rulesRequired;
        return $this;
    }

    /**
     * set given (param array) or all (param '*') rules as required
     *
     * @param string|array $fill
     *
     * @return $this
     */
    public function filled($fill = '*')
    {
        $rulesFilled = [];

        foreach ($this->rules as $index => $rule) {
            if (($fill == '*') || in_array($index, $fill)) {
                $rulesFilled[$index] = $this->append($rule, 'filled');
            } else {
                $rulesFilled[$index] = $rule;
            }
        }

        $this->rules = $rulesFilled;
        return $this;
    }

    public function nullable($nullable = '*')
    {
        $rulesNullable = [];

        foreach ($this->rules as $index => $rule) {
            if (($nullable == '*') || in_array($index, $nullable)) {
                $rulesNullable[$index] = $this->append($rule, 'nullable');
            } else {
                $rulesNullable[$index] = $rule;
            }
        }

        $this->rules = $rulesNullable;
        return $this;
    }

    /**
     * apply namespace to all remaining rules
     *
     * @return array
     */
    protected function applyNamespace()
    {
        if ($this->namespace != '') {
            $namespacedRules = [];

            foreach ($this->rules as $index => $rule) {
                $namespacedRules["$this->namespace.$index"] = $rule;
            }
            return $namespacedRules;
        }
        return $this->rules;
    }

    protected function append($rule, $validator)
    {
        if (is_array($rule))
            return $rule[] = $validator;
        else
            return "$rule|$validator";
    }
}